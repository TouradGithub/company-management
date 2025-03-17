<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class AccountStatementController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();
        return view('financialaccounting.account-statement.index' , compact('accounts'));
    }

    public function getStatement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:accounts,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        // 🔹 حساب الرصيد السابق (المدين والدائن) قبل `from_date`
        $previousEntries = JournalEntryDetail::where('account_id', $request->account_id)
            ->whereHas('journalEntry', function ($query) use ($request) {
                $query->where('entry_date', '<', $request->from_date);
            })
            ->get();

        $previousDebit = $previousEntries->sum('debit');
        $previousCredit = $previousEntries->sum('credit');
        $previousBalance = $previousDebit - $previousCredit;

        // 🔹 جلب تفاصيل القيود خلال الفترة المحددة
        $data = JournalEntryDetail::where('account_id', $request->account_id)
            ->whereHas('journalEntry', function ($query) use ($request) {
                $query->whereBetween('entry_date', [$request->from_date, $request->to_date]);
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

        // 🔹 إضافة الرصيد السابق كأول سجل في البيانات
        $data->prepend([
            'entry_number' => '---',
            'date' => $request->from_date,
            'createdby' => ' - ',
            'comment' => ' الرصيد قبل',
            'debit' => $previousDebit, // الرصيد المدين السابق
            'credit' => $previousCredit, // الرصيد الدائن السابق
            'balance' => $previousBalance, // صافي الرصيد السابق
        ]);

        return response()->json($data);
    }

}
