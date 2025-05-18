<?php

namespace App\Http\Controllers\AccountingBranch;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class SupplierController extends Controller
{


    public function index(){
        $accounts = Account::where('company_id', getCompanyId())
            ->where('type_account_register', null)->get();

        return view('financialaccountingbranch.suppliers.index' , compact('accounts' ));
    }

    public function customers(){
        $customers = Customer::where('company_id', getCompanyId())->get();
        return view('financialaccountingbranch.suppliers.customer' , compact('customers'));
    }
//    public function linkAccountToCustomers(Request $request)
//    {
//        $accountId = $request->input('account_id');
//        $account = Account::findOrFail($accountId);
//
//        if ($this->treeHasOtherTypes($account, [2, 3])) {
//            return response()->json(['success' => false, 'message' => 'لا يمكن ربط هذا الحساب لأنه أو أحد أبناءه/آبائه مرتبط بنوع آخر']);
//        }
//
//        $oldCustomerAccount = Account::where('type_account_register', 1)->first();
//        if ($oldCustomerAccount) {
//            $oldCustomerAccount->updateTreeTypeRegister(null);
//        }
//
//        $account->updateTreeTypeRegister(1);
//
//        return response()->json(['success' => true, 'message' => 'تم ربط هذا الحساب وشجرته بالعملاء بنجاح']);
//    }
    private function treeHasOtherTypes(Account $account, array $notAllowedTypes)
    {
        if (in_array($account->type_account_register, $notAllowedTypes)) {
            return true;
        }
        foreach ($account->children as $child) {
            if ($this->treeHasOtherTypes($child, $notAllowedTypes)) {
                return true;
            }
        }
        if ($account->parent && in_array($account->parent->type_account_register, $notAllowedTypes)) {
            return true;
        }

        return false;
    }
    public function linkAccountToCustomers(Request $request)
    {
        return $this->linkAccountWithType($request, 1, 'العملاء');
    }
    public function linkCashRegister(Request $request)
    {
        return $this->linkAccountWithType($request, 2, 'الصندوق');
    }

    public function linkToSupplier(Request $request)
    {
        return $this->linkAccountWithType($request, 3, 'الموردين');
    }

    private function linkAccountWithType(Request $request, int $type, string $typeName)
    {
        $accountId = $request->input('account_id');

        $targetAccount = Account::findOrFail($accountId);
        if ($targetAccount->type_account_register && $targetAccount->type_account_register != $type) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الحساب مرتبط بنوع آخر بالفعل.',
            ]);
        }
        $existing = Account::where('type_account_register', $type)->first();
        if ($existing) {
            $existing->type_account_register = null;
            $existing->linked_root_id = null;
            $existing->save();
            Account::where('linked_root_id', 1)->update(['linked_root_id' => null]);
        }

        DB::transaction(function () use ($targetAccount, $type) {
            $targetAccount->type_account_register = $type;
            $targetAccount->linked_root_id = 1;
            $targetAccount->save();
            $this->updateChildrenWithRoot($targetAccount->id, $targetAccount->id , $type);
        });

        return response()->json([
            'success' => true,
            'message' => "تم ربط هذا الحساب بـ{$typeName} بنجاح.",
        ]);
    }

    private function updateChildrenWithRoot($parentId, $rootId, $type)
    {
        $children = Account::where('parent_id', $parentId)->get();
        foreach ($children as $child) {
            $child->type_account_register = $type;
            $child->save();

            $this->updateChildrenWithRoot($child->id, $rootId ,$type);
        }
    }


    public function getCustomers()
    {
        $suppliers = Supplier::where('company_id' , getCompanyId())->with('branch')->get();
        return response()->json(['suppliers' => $suppliers]);
    }
    private function getAccountTreeIds($accounts, &$accountIds = [])
    {
        foreach ($accounts as $account) {
            $accountIds[] = $account->id;

            // Get child accounts recursively
            $childAccounts = Account::where('parent_id', $account->id)->get();
            if ($childAccounts->isNotEmpty()) {
                $this->getAccountTreeIds($childAccounts, $accountIds);
            }
        }

        return $accountIds;
    }



    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'contact_info' => 'required',
            'branch_id' => 'nullable',
            'account_id' => 'required|numeric',

        ]);

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->contact_info = $request->contact_info;
        $supplier->account_id = $request->account_id;
        $supplier->branch_id = $request->branch_id??'all';
        $supplier->company_id = getCompanyId();
        $supplier->save();
        return response()->json([
            'status' => 'تم',
            'success' =>true,
            'supplier' =>$supplier,
            'message' =>"تم إضافة لمورد بنجاح"
        ], 201);
    }

    public function delete($id)
    {
        $supplier = Supplier::where('id', $id)
            ->where('company_id', getCompanyId())
            ->first();

        if (!$supplier) {
            return response()->json(['success' => false, 'message' => 'العميل غير موجود أو لا يمكنك حذفه'], 404);
        }
        if ($supplier->invoices_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف العميل لأنه مرتبط بفواتير.'
            ], 403); // 403 Forbidden status
        }

        $supplier->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف العميل بنجاح']);
    }
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json(['success' => true, 'supplier' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:20',
            'branch_id' => 'nullable',
            'account_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'name'=>$request->name,
            'contact_info'=>$request->contact_info,
            'branch_id'=>$request->branch_id??'all',
        ]);
        return response()->json(['status' => 'نجاح', 'message' => 'تم تحديث العميل بنجاح', 'success' => true]);
    }
}
