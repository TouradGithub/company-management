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

        // تحويل الحسابات إلى شجرة
        $accountsTree = $this->buildTree($accounts);
//        dd($accountsTree);
        return view('financialaccounting.accountsTree.index', compact('accounttypes', 'accountsTree','accounts'));
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
        ], [
            'account_number.required' => 'رقم الحساب مطلوب.',
            'account_number.unique' => 'رقم الحساب مستخدم بالفعل.',
            'name.required' => 'اسم الحساب مطلوب.',
            'parent_id.required' => 'الحساب الرئيسي  مطلوب.',
            'name.max' => 'اسم الحساب يجب ألا يتجاوز 255 حرفًا.',
            'account_type_id.required' => 'نوع الحساب مطلوب.',
            'account_type_id.exists' => 'نوع الحساب غير صحيح.',
            'parent_id.exists' => 'الحساب الرئيسي المحدد غير موجود.',
        ]);


        $account = new Account();
        $account->account_number = $validated['account_number'];
        $account->name = $validated['name'];
        $account->account_type_id = $validated['account_type_id'];
        $account->parent_id = $validated['parent_id'] ?? null;
        $account->company_id = auth()->user()->model_id;
        $account->opening_balance = $validated['opening_balance']  ?? 0;
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


}
