<?php

namespace App\Http\Controllers\AccountingBranch;

use App\Exports\JournalEntriesExport;
use App\Helpers\AccountTransactionHelper;
use App\Http\Controllers\Controller;
use App\Imports\JournalEntriesImport;
use App\Imports\ProductsImport;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\Branch;
use App\Models\CostCenter;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;
use Mpdf\Mpdf;

class JournalEntryController extends Controller
{
    public function index(){
        return view('financialaccountingbranch.journalEntries.index' );
    }
    public function create()
    {
        $accounts = Account::where('company_id', getCompanyId())
            ->where('islast'  , 1)
            ->get();
        $costcenters = CostCenter::where('company_id', getCompanyId())->get();
        $branches = Branch::where('company_id', getCompanyId())->get();
        $journals = Journal::where('company_id' , getCompanyId())->get();
        $entry_number = JournalEntry::generateEntryNumber('JR0' ,getCompanyId());
        return view('financialaccountingbranch.journalEntries.create'  , compact('accounts' ,'branches', 'costcenters' , 'journals' , 'entry_number'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'journal_id' => 'required',
            'date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.account_id' => 'required',
            'entries.*.debit' => 'nullable|numeric|required_without:entries.*.credit',
            'entries.*.credit' => 'nullable|numeric|required_without:entries.*.debit',
            'entries.*.cost_center' => 'required',
            'entries.*.notes' => 'nullable|string',
        ],
            [
            'entries.*.cost_center.required' => 'يجب اختيار مركز التكلفة.',
            'journal_id.required' => 'يجب اختيار الدفتر.',
            'date.required' => 'يجب تحديد التاريخ.',
            'date.date' => 'يجب إدخال تاريخ صحيح.',
            'entries.required' => 'يجب إدخال تفاصيل القيد.',
            'entries.array' => 'يجب أن يكون تفاصيل القيد على شكل قائمة.',
            'entries.min' => 'يجب إدخال قيد واحد على الأقل.',
            'entries.*.account_id.required' => 'يجب اختيار الحساب لكل قيد.',
            'entries.*.debit.numeric' => 'يجب أن يكون المبلغ المدين رقمًا صحيحًا.',
            'entries.*.credit.numeric' => 'يجب أن يكون المبلغ الدائن رقمًا صحيحًا.',
            'entries.*.debit.required_without' => 'يرجى ملء حقل الخصم أو الدائن.',
            'entries.*.credit.required_without' => 'يرجى ملء حقل الدائن أو الخصم.',
            'entries.*.notes.string' => 'يجب أن تكون الملاحظات نصية فقط.',
        ]);
        $validator->after(function ($validator) use ($request) {
            $entries = $request->input('entries', []);
            foreach ($entries as $index => $entry) {
                $debit = floatval($entry['debit'] ?? 0);
                $credit = floatval($entry['credit'] ?? 0);

                if ($debit <= 0 && $credit <= 0) {
                    $validator->errors()->add("entries.$index.debit", 'يجب أن يكون أحد الحقول دائن أو مدين بقيمة أكبر من الصفر.');
                }
            }
        });
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            DB::beginTransaction();
            $journal = \App\Models\Journal::find($request->journal_id);
            $journalCode = $journal->code;
            $journalEntry = JournalEntry::create([
                'entry_number' =>JournalEntry::generateEntryNumber( $journalCode,getCompanyId()),
                'entry_date' => $request->date,
                'branch_id' => getBranch()->id,
                'company_id' => getCompanyId(),
                'created_by'=>Auth::user()->name,
                'session_year' => getCurrentYear(),
            ]);
            foreach ($request->entries as $entry) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $entry['account_id'],
                    'cost_center_id' => $entry['cost_center'],
                    'debit' => $entry['debit'] ?? 0,
                    'credit' => $entry['credit'] ?? 0,
                    'comment' => $entry['notes'] ?? '',
                ]);
            }
            AccountTransactionHelper::updateAccountTransactions($journalEntry);
            DB::commit();
            return response()->json(['message' => 'تم الحفظ بنجاح ✅'], 200);
        }catch (Exception $e){
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file',
        ]);
        if (!$request->hasFile('import_file')) {
            return back()->with('error', 'No file uploaded.');
        }
        try {
            $file = $request->file('import_file');
            $destinationPath = storage_path('app/imports');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);
            $fullPath = $destinationPath . '/' . $fileName;
            if (!file_exists($fullPath)) {
                return response()->json(['success' => false, 'msg' => 'File could not be found.']);
            }
            Excel::import(new JournalEntriesImport(),  $fullPath);
            return back()->with('success', 'تم الاستيراد بنجاح ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }
    public function fetchEntries(Request $request)
    {
        $query = JournalEntry::where('company_id' , getCompanyId())->with([
                'details' => function ($query) {
                    $query->select('id', 'journal_entry_id', 'account_id', 'debit', 'credit', 'cost_center_id', 'comment');
                },
                'details.account:id,name,account_number',
                'branch:id,name',
                'company:id,name',
                'details.costcenter',
            ])
            ->select('id', 'entry_number', 'entry_date', 'branch_id', 'company_id', 'session_year');
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }
        if ($request->filled('entry_number')) {
            $query->where('entry_number', 'like', '%' . $request->entry_number . '%');
        }
        $entries = $query->get();
        return response()->json([
            'status' => 'success',
            'data' => $entries
        ]);
    }
    public function destroy($id)
    {
        $entry = JournalEntry::find($id);
        if($entry){

            $entry->details()->delete();
            AccountTransactionHelper::deleteAccountTransactions($entry);
            $entry->delete();

            return response()->json(['status' => 'success', 'message' => 'تم الحذف بنجاح']);
        }

        return response()->json(['status' => 'error', 'message' => 'هناك مشكله حاول مرة أخرى  ']);
    }
    public function destroyEntryDetails($id)
    {
        $entry = JournalEntryDetail::findOrFail($id);
        if($entry){
            $entry->delete();
            return response()->json(['status' => 'success', 'message' => 'تم الحذف بنجاح']);
        }
        return response()->json(['status' => 'error', 'message' => 'هناك مشكله حاول مرة أخرى  ']);
    }
    public function edit($id)
    {
        $accounts = Account::where('company_id', getCompanyId())->get();
        $costcenters = CostCenter::where('company_id', getCompanyId())->get();
        $journals = Journal::where('company_id' , getCompanyId())->get();

        $entry = JournalEntry::findOrFail($id);
        return view('financialaccountingbranch.journalEntries.edit' , compact('entry','accounts' , 'costcenters' , 'journals'));
    }
    public function clone($id)
    {
        $accounts = Account::where('company_id', getCompanyId())->get();
        $costcenters = CostCenter::where('company_id', getCompanyId())->get();
        $journals = Journal::where('company_id' , getCompanyId())->get();

        $entry = JournalEntry::findOrFail($id);
        return view('financialaccountingbranch.journalEntries.clone' , compact('entry','accounts' , 'costcenters' , 'journals'));
    }
    public  function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.account_id' => 'required',
            'entries.*.debit' => 'nullable|numeric|required_without:entries.*.credit',
            'entries.*.credit' => 'nullable|numeric|required_without:entries.*.debit',
            'entries.*.cost_center' => 'required',
            'entries.*.notes' => 'nullable|string',
        ],
            [
                'entries.*.cost_center.required' => 'يجب اختيار مركز التكلفة.',
                'date.required' => 'يجب تحديد التاريخ.',
                'date.date' => 'يجب إدخال تاريخ صحيح.',
                'entry_id.required' => '   رقم القيد غير موجود.',
                'entries.required' => 'يجب إدخال تفاصيل القيد.',
                'entries.array' => 'يجب أن يكون تفاصيل القيد على شكل قائمة.',
                'entries.min' => 'يجب إدخال قيد واحد على الأقل.',
                'entries.*.account_id.required' => 'يجب اختيار الحساب لكل قيد.',
                'entries.*.debit.numeric' => 'يجب أن يكون المبلغ المدين رقمًا صحيحًا.',
                'entries.*.credit.numeric' => 'يجب أن يكون المبلغ الدائن رقمًا صحيحًا.',
                'entries.*.debit.required_without' => 'يرجى ملء حقل الخصم أو الدائن.',
                'entries.*.credit.required_without' => 'يرجى ملء حقل الدائن أو الخصم.',
                'entries.*.notes.string' => 'يجب أن تكون الملاحظات نصية فقط.',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $journalEntry = JournalEntry::where('branch_id' ,getBranch()->id)->where('entry_number', $request->entry_id)->first();
            $journalEntry->branch_id = getBranch()->id;
            $journalEntry->entry_date = $request->date;
            $journalEntry->entry_number = $request->entry_id;
            $journalEntry->session_year = getCurrentYear();
            $journalEntry->created_by = Auth::user()->name;
            $journalEntry->save();
            $journalEntry->details()->delete();

            foreach ($request->entries as $entry) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => $entry['account_id'],
                    'cost_center_id' => $entry['cost_center'],
                    'debit' => $entry['debit'] ?? 0,
                    'credit' => $entry['credit'] ?? 0,
                    'comment' => $entry['notes'] ?? '',
                ]);
            }
            AccountTransactionHelper::updateAccountTransactions($journalEntry);

            DB::commit();

            return response()->json(['message' => 'تم تعديل القيد بنجاح ✅'], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function exportPdf(Request $request)
    {
        $entries = $this->getFilteredEntries($request);
        $html = view('financialaccountingbranch.journalEntries.exports.pdf', compact('entries'))->render();
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        return $mpdf->Output('journal_entries.pdf', 'D');
    }
    public function singleexportPdf($id)
    {
        $journalEntry = JournalEntry::with([
            'details.account:id,name,account_number',
            'details.costcenter',
        ])->findOrFail($id);
        $html = view('financialaccountingbranch.journalEntries.exports.single-pdf', compact('journalEntry'))->render();
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        return $mpdf->Output('single_journal_entries.pdf', 'D');
    }
    public function singlePrint($id)
    {
        $journalEntry = JournalEntry::with([
            'details.account:id,name,account_number',
            'details.costcenter',
        ])->findOrFail($id);
        $html = view('financialaccountingbranch.journalEntries.exports.single-pdf', compact('journalEntry'))->render();
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $mpdf->WriteHTML($html);
        return $mpdf->Output('single_journal_entries.pdf', 'I');
    }
    private function getFilteredEntries(Request $request)
    {
        $query = JournalEntry::with([
            'details.account:id,name,account_number',
            'details.costcenter',
        ]);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }
        if ($request->filled('entry_number')) {
            $query->where('entry_number', 'like', '%' . $request->entry_number . '%');
        }
        $query= $query->where('session_year',getCurrentYear());
        return $query->get();
    }
    public function exportExcel(Request $request)
    {
        return Excel::download(new JournalEntriesExport($request->all()), 'journal_entries.xlsx');
    }
}
