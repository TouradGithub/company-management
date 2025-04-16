<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\AccountStatementExport;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Branch;
use App\Models\Invoice;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;
use Mpdf\Mpdf;
class AccountStatementController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();
        $branches = Branch::where('company_id', Auth::user()->model_id)->get();
        return view('financialaccounting.account-statement.index' , compact('accounts' , 'branches'));
    }

    public function getStatement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // 🔹 حساب الرصيد السابق (المدين والدائن) قبل `from_date`
        $previousEntries = JournalEntryDetail::where('account_id', $request->account_id)
            ->whereHas('journalEntry', function ($query) use ($request) {
                $query->where('entry_date', '<', $request->from_date);

                if (!is_null($request->branch_id)) {
                    $query->where('branch_id', $request->branch_id);
                }
            })
            ->get();

        $previousDebit = $previousEntries->sum('debit');
        $previousCredit = $previousEntries->sum('credit');
        $previousBalance = $previousDebit - $previousCredit;

        // 🔹 جلب تفاصيل القيود خلال الفترة المحددة
        $data = JournalEntryDetail::where('account_id', $request->account_id)
            ->whereHas('journalEntry', function ($query) use ($request) {
                $query->whereBetween('entry_date', [$request->from_date, $request->to_date]);

                if (!is_null($request->branch_id)) {
                    $query->where('branch_id', $request->branch_id);
                }
            })
            ->with('journalEntry')
            ->get()
            ->map(function ($entry) {
                return [
                    'entry_number' => $entry->journalEntry->entry_number,
                    'date' => $entry->journalEntry->entry_date,
                    'createdby' => $entry->journalEntry->created_by ?? ' - ',
                    'comment' => $entry->comment ?? ' - ',
                    'debit' => $entry->debit,
                    'credit' => $entry->credit,
                    'balance' => $entry->debit - $entry->credit,
                ];
            });

        //  إضافة الرصيد السابق كأول سجل في البيانات
        $data->prepend([
            'entry_number' => '---',
            'date' => $request->from_date,
            'createdby' => ' - ',
            'comment' => ' الرصيد قبل',
            'debit' => $previousDebit,
            'credit' => $previousCredit,
            'balance' => $previousBalance,
        ]);

        return response()->json($data);
    }

    public function exportExcel(Request $request)
    {

        $data = $this->getStatementData($request);

        return Excel::download(new AccountStatementExport($data), 'account_statement.xlsx');
    }
    public function exportPDF(Request $request)
    {


        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'cairo',
            'orientation' => 'P',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
        $data = $this->getStatementData($request);
//dd($data);
        $html = view('financialaccounting.account-statement.exports.pdf', compact('data'))->render();

        $mpdf->WriteHTML($html);

        $mpdf->Output('account-statement.pdf', 'I');
    }
    public function getStatementData(Request $request)
    {
        $transactions = AccountTransaction::where('company_id', Auth::user()->model_id)
            ->where('account_id', $request->account_id)
            ->whereBetween('transaction_date', [$request->from_date, $request->to_date]);

        if (!is_null($request->branch_id)) {
            $transactions = $transactions->where('branch_id', $request->branch_id);
        }

        $transactions = $transactions->with('branch', 'account')->get();

        // الرصيد الافتتاحي
        $openingBalance = Account::find($request->account_id)->opening_balance ?? 0;

        // إجماليات
        $totalDebit = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');

        // الرصيد الختامي = الرصيد الافتتاحي + مجموع المدين - مجموع الدائن
        $closingBalance = $openingBalance + $totalDebit - $totalCredit;

        // إضافة الرصيد بعد كل عملية (اختياري إذا كنت تعرضه في الجدول)
        $runningBalance = $openingBalance;
        $transactions = $transactions->map(function ($transaction) use (&$runningBalance) {
            $runningBalance += $transaction->debit - $transaction->credit;
            $transaction->balance = number_format($runningBalance, 2);
            return $transaction;
        });

        return [
            'branch' => Branch::find($request->branch_id),
            'account' => Account::find($request->account_id),
            'to' => $request->to_date,
            'from' => $request->from_date,
            'data' => $transactions,
            'opening_balance' => number_format($openingBalance, 2),
            'total_debit' => number_format($totalDebit, 2),
            'total_credit' => number_format($totalCredit, 2),
            'closing_balance' => number_format($closingBalance, 2),
        ];
    }


}
