<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsTreeController extends Controller
{
    public function index(){
        $accounttypes =  AccountType::all();
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();


        $accountsTree = $this->buildTree($accounts);

        return view('financialaccounting.accountsTree.index', compact('accounttypes', 'accountsTree','accounts'));
    }
    public function accountTable(){
        $accounttypes =  AccountType::all();
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();

        // تحويل الحسابات إلى شجرة
        $accountsTree = $this->buildTree($accounts);
//        dd($accountsTree);
        return view('financialaccounting.accountsTree.account-table', compact('accounttypes', 'accountsTree','accounts'));
    }

    public  function edit($id)
    {
        $account = Account::where('company_id',Auth::user()->model_id)->where('id' ,$id)->first();
        return response()->json($account,200);
    }

    public  function update(Request $request)
    {
//        return $request;
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
//        return $request;
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'account_number' => 'required|unique:accounts,account_number,NULL,id,company_id,' . auth()->user()->model_id,
            'name' => 'required|string|max:255',
            'account_type_id' => 'required',
            'parent_id' => 'required',
            'opening_balance' => 'nullable|numeric',
            'closing_list_type' => 'required|in:1,2',

        ], [
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
//
            // Fetch all accounts with children
            $accounts = Account::where('company_id' ,Auth::user()->model_id)->with(['children', 'accountType'])
//                ->where('parent_id',0) // Start with root accounts
                ->get();
        } else {
//            dd("OK");
            // Determine level based on hierarchy
            $level = (int)$level;
            $accounts = $this->getAccountsByLevel($level);
        }

        // Format data for frontend
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
}
