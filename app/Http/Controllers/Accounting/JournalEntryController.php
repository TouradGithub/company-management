<?php

namespace App\Http\Controllers\Accounting;

use App\Helpers\AccountTransactionHelper;
use App\Http\Controllers\Controller;
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
use Mockery\Exception;

class JournalEntryController extends Controller
{
    public function index(){
        $branches = Branch::where('company_id', Auth::user()->model_id)->get();
        return view('financialaccounting.journalEntries.index' , compact('branches'));
    }

    public function create()
    {
        $accounts = Account::where('company_id', Auth::user()->model_id)
            ->whereDoesntHave('children')
            ->where('parent_id'  ,'!=' , 0)
            ->get();
        $costcenters = CostCenter::where('company_id', Auth::user()->model_id)->get();
        $branches = Branch::where('company_id', Auth::user()->model_id)->get();
        $journals = Journal::where('company_id' , Auth::user()->model_id)->get();
        $entry_number = JournalEntry::generateEntryNumber('JR0' ,Auth::user()->model_id);
        return view('financialaccounting.journalEntries.create'  , compact('accounts' ,'branches', 'costcenters' , 'journals' , 'entry_number'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'branch' => 'required',
            'journal_id' => 'required',
            'date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.account_id' => 'required',
            'entries.*.debit' => 'numeric|required_without:entries.*.credit',
            'entries.*.credit' => 'numeric|required_without:entries.*.debit',
            'entries.*.cost_center' => 'required',
            'entries.*.notes' => 'nullable|string',
        ],
            [
            'entries.*.cost_center.required' => 'يجب اختيار مركز التكلفة.',
            'branch.required' => 'يجب اختيار الفرع.',
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
                'entry_number' =>JournalEntry::generateEntryNumber( $journalCode,Auth::user()->model_id),
                'entry_date' => $request->date,
                'branch_id' => $request->branch,
                'company_id' => Auth::user()->model_id,
                'created_by'=>Auth::user()->name,
                'session_year' => date('Y'),
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

    public function fetchEntries(Request $request)
    {
        $query = JournalEntry::with([
                'details' => function ($query) {
                    $query->select('id', 'journal_entry_id', 'account_id', 'debit', 'credit', 'cost_center_id', 'comment');
                },
                'details.account:id,name,account_number',
                'branch:id,name',
                'company:id,name',
                'details.costcenter',
            ])
            ->select('id', 'entry_number', 'entry_date', 'branch_id', 'company_id', 'session_year');

        // Apply branch filter if provided
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Apply date range filter if provided
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        } elseif ($request->filled('from_date')) {
            $query->where('entry_date', '>=', $request->from_date);
        } elseif ($request->filled('to_date')) {
            $query->where('entry_date', '<=', $request->to_date);
        }

        $entries = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $entries
        ]);
    }
    public function destroy($id)
    {
        $entry = JournalEntry::findOrFail($id);
        if($entry){
            $entry->details()->delete();
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
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();
        $costcenters = CostCenter::where('company_id', Auth::user()->model_id)->get();
        $branches = Branch::where('company_id', Auth::user()->model_id)->get();
        $entry = JournalEntry::findOrFail($id);

        return view('financialaccounting.journalEntries.edit' , compact('entry','accounts' ,'branches', 'costcenters'));
    }
    public  function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch' => 'required',
            'entry_id' => 'required',
            'date' => 'required|date',
            'entries' => 'required|array|min:1',
            'entries.*.account_id' => 'required',
            'entries.*.debit' => 'nullable|numeric|required_without:entries.*.credit',
            'entries.*.credit' => 'nullable|numeric|required_without:entries.*.debit',
            'entries.*.cost_center' => 'required',
            'entries.*.notes' => 'nullable|string',
        ], [
            'entries.*.cost_center.required' => 'يجب اختيار مركز التكلفة.',
            'branch.required' => 'يجب اختيار الفرع.',
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

            $journalEntry = JournalEntry::where('entry_number', $request->entry_id)->first();
            $journalEntry->branch_id = $request->branch;
            $journalEntry->entry_date = $request->date;
            $journalEntry->entry_number = $request->entry_id;
            $journalEntry->created_by =Auth::user()->name;
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
        }catch (Exception $e){
            return response()->json([
                'status' => 'error',
                'errors' => $e->getMessage()
            ], 500);
        }

    }


}
