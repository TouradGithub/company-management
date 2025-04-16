<?php

namespace App\Http\Controllers\Accounting;

use App\Exports\AccountsExport;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use Mpdf\Mpdf;

class AccountsTreeController extends Controller
{
    public function index(){
        $accounttypes =  AccountType::all();
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();
        $addAccounts = Account::where('company_id', Auth::user()->model_id)
            ->where('islast','!=' ,1)->get();
        $accountsTree = $this->buildTree($accounts);
        return view('financialaccounting.accountsTree.index', compact('addAccounts' ,'accounttypes', 'accountsTree','accounts'));
    }
    public function accountTable(){
        $accounttypes =  AccountType::all();
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();

        $accountsTree = $this->buildTree($accounts);
        return view('financialaccounting.accountsTree.account-table', compact('accounttypes', 'accountsTree','accounts'));
    }

    public  function edit($id)
    {
        $account = Account::where('company_id',Auth::user()->model_id)->where('id' ,$id)->first();
        return response()->json($account,200);
    }

    public  function update(Request $request)
    {

        $validated = $request->validate([
            'account_number' => 'required',
            'id' => 'required',
            'name' => 'required|string|max:255',
            'account_type_id' => 'required',
            'parent_id' => 'required',
            'opening_balance' => 'nullable|numeric',
            'closing_list_type' => 'required|in:1,2',

        ],
            [
            'account_number.required' => 'رقم الحساب مطلوب.',
            'account_number.unique' => 'رقم الحساب مستخدم بالفعل.',
            'name.required' => 'اسم الحساب مطلوب.',
            'parent_id.required' => 'الحساب الرئيسي  مطلوب.',
            'name.max' => 'اسم الحساب يجب ألا يتجاوز 255 حرفًا.',
            'account_type_id.required' => 'نوع الحساب مطلوب.',
            'account_type_id.exists' => 'نوع الحساب غير صحيح.',
            'parent_id.exists' => 'الحساب الرئيسي المحدد غير موجود.',
            'opening_balance.numeric' => 'الرصيد الافتتاحي يجب أن يكون رقمًا.',
            'closing_list_type.in' => 'نوع القائمة الختامية يجب أن يكون إما "قائمة الدخل" أو "الميزانيه العموميه".',
            'closing_list_type.required' => ' القائمة الختامية  مطلوبه".',
        ]);

        $account = Account::where('company_id',Auth::user()->model_id)->where('id' ,$request->id)->first();

        if(!$account){
            return response()->json([
                'message'=>'هذا الحساب غير موجود'
            ],201);
        }

        if($account && $request->parent_id != $account->parent_id && $account->children()->count() > 0){
            return response()->json([
                'message'=>'هذا الحساب يحتوي على حسابات فرعيه'
            ],201);
        }


        $account->account_number = $validated['account_number'];
        $account->name = $validated['name'];
        $account->account_type_id = $validated['account_type_id'];
        $account->parent_id = $validated['parent_id'] ?? null;
        $account->company_id = auth()->user()->model_id;
        $account->opening_balance = $validated['opening_balance']  ?? 0;
        $account->closing_list_type = $validated['closing_list_type'];
        $account->save();
        return response()->json([
            'message'=>'تم تعديل الحساب بنجاح'
        ],200);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'account_number' => 'required|unique:accounts,account_number,NULL,id,company_id,' . auth()->user()->model_id,
            'name' => 'required|string|max:255',
            'account_type_id' => 'required',
            'parent_id' => 'required',
            'opening_balance' => 'nullable|numeric',
            'closing_list_type' => 'required|in:1,2',
            'islast' => 'nullable|boolean',

        ],
            [
            'account_number.required' => 'رقم الحساب مطلوب.',
            'account_number.unique' => 'رقم الحساب مستخدم بالفعل.',
            'name.required' => 'اسم الحساب مطلوب.',
            'parent_id.required' => 'الحساب الرئيسي  مطلوب.',
            'name.max' => 'اسم الحساب يجب ألا يتجاوز 255 حرفًا.',
            'account_type_id.required' => 'نوع الحساب مطلوب.',
            'account_type_id.exists' => 'نوع الحساب غير صحيح.',
            'parent_id.exists' => 'الحساب الرئيسي المحدد غير موجود.',
            'opening_balance.numeric' => 'الرصيد الافتتاحي يجب أن يكون رقمًا.',
            'closing_list_type.in' => 'نوع القائمة الختامية يجب أن يكون إما "قائمة الدخل" أو "الميزانيه العموميه".',
            'closing_list_type.required' => ' القائمة الختامية  مطلوبه".',
        ]);


        $account = new Account();
        $account->account_number = $validated['account_number'];
        $account->name = $validated['name'];
        $account->account_type_id = $validated['account_type_id'];
        $account->parent_id = $validated['parent_id'] ?? null;
        $account->company_id = auth()->user()->model_id;
        $account->opening_balance = $validated['opening_balance']  ?? 0;
        $account->closing_list_type = $validated['closing_list_type'];
        $account->islast = $request->boolean('islast');
        $account->save();


        return redirect()->back()->with('success', 'تم إنشاء الحساب بنجاح!');

    }

    private function buildTree($accounts, $parentId = 0)
    {
        $tree = [];

        foreach ($accounts as $account) {
            if ($account->parent_id == $parentId) {
                $children = $this->buildTree($accounts, $account->id);
                if ($children) {
                    $account->children = $children;
                }
                $tree[] = $account;
            }
        }

        return $tree;

    }
    public  function  delete($id){
        $account  = Account::find($id);

        if($account){
            $account->delete();
            return redirect()->back()->with('success' , 'تم حذف الحساب بنجاح');
        }
        return redirect()->back()->with('error' , 'هناك مشكلة حاول مرة أخرى');
    }



    public function filterAccounts(Request $request)
    {
        $level = $request->query('level');
        $accounts = collect();

        if ($level == 'all') {
            $accounts = Account::where('company_id', Auth::user()->model_id)
                ->with(['children', 'accountType'])
                ->get();

//            $accounts = $this->buildTree($accounts);
        } else {
            $level = (int)$level;
            $accounts = $this->getAccountsByLevel($level);
        }
        $formattedAccounts = $accounts->map(function ($account) {
            return $this->formatAccount($account);
        });

        return response()->json(['accounts' => $formattedAccounts]);
    }

    private function getAccountsByLevel($targetLevel)
    {
        $allAccounts = Account::where('company_id' ,Auth::user()->model_id)->with(['children', 'accountType'])->get();
        $filteredAccounts = collect();

        foreach ($allAccounts as $account) {
            $level = $this->calculateLevel($account);
            if ($level == $targetLevel) {
                $filteredAccounts->push($account);
            }
        }

        return $filteredAccounts;
    }
    private function formatAccount($account)
    {
        return [
            'id' => $account->id,
            'account_number' => $account->account_number,
            'name' => $account->name,
            'account_type_name' => $account->accountType->name ?? '',
            'islast' => $account->islast,
            'balance' => $account->getBalanceDetails(),
            'children' => $account->children->map(function ($child) {
                return $this->formatAccount($child); // Recursive
            })->toArray(),
        ];
    }


    private function calculateLevel($account, $currentLevel = 1)
    {
        if (!$account->parent_id || $account->parent_id == 0) {
            return $currentLevel;
        }

        $parent = Account::find($account->parent_id);
        if ($parent) {
            return $this->calculateLevel($parent, $currentLevel + 1);
        }

        return $currentLevel;
    }

    // تصدير إلى PDF
    public function exportPdf(Request $request)
    {
        $level = $request->query('level');
        $accounts = $this->getAccountsForExport($level);
        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        $html = view('financialaccounting.accountsTree.exports.accounts_pdf', compact('accounts', 'level'))->render();

        $mpdf->WriteHTML($html);
        return $mpdf->Output('accounts.pdf', 'D'); // 'D' للتحميل مباشرة
    }

    // تصدير إلى Excel
    public function exportExcel(Request $request)
    {
        $level = $request->query('level');
        return \Maatwebsite\Excel\Facades\Excel::download(new AccountsExport($level), 'accounts.xlsx');
    }

    // جلب البيانات للتصدير
    private function getAccountsForExport($level)
    {
        if ($level === 'all') {
            return Account::where('company_id' , Auth::user()->model_id)->with(['children', 'accountType'])
                ->where('parent_id',0)
                ->get();
        } else {
            return $this->getAccountsByLevel((int)$level);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $accounts = Account::where('company_id', Auth::user()->model_id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('account_number', 'LIKE', "%{$query}%");
            })
            ->get();

        $formattedAccounts = $accounts->map(function ($account) {
            $balance = $account->getBalanceDetails();
            return [
                'id' => $account->id,
                'account_number' => $account->account_number,
                'name' => $account->name,
                'account_type_name' => $account->accountType->name,
                'balance' => $balance,
                'children' => $account->children->map(function ($child) use ($balance) {
                    return [
                        'id' => $child->id,
                        'account_number' => $child->account_number,
                        'name' => $child->name,
                        'account_type_name' => $child->accountType->name,
                        'balance' => $child->getBalanceDetails(),
                    ];
                })->toArray(),
            ];
        });

        return response()->json(['accounts' => $formattedAccounts]);
    }



}
