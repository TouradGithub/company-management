<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class SupplierController extends Controller
{


    public function index(){
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();
        $account = Account::where('company_id', Auth::user()->model_id)
            ->where('type_account_register', 3)
            ->first();
        $cach_register = Account::where('company_id', Auth::user()->model_id)
            ->where('type_account_register', 2)
            ->first();
        $supplier = Account::where('company_id', Auth::user()->model_id)
            ->where('type_account_register', 3)
            ->first();
        return view('financialaccounting.suppliers.index' , compact('accounts' , 'supplier', 'account','cach_register'));
    }

    public function customers(){
        $customers = Customer::where('company_id', Auth::user()->model_id)->get();
        return view('financialaccounting.suppliers.customer' , compact('customers'));
    }
    public function linkAccountToCustomers(Request $request)
    {
        $accountId = $request->input('account_id');
        $account = Account::where('type_account_register' , 1)->first();
        if($account){
            $account->type_account_register = null;
            $account->save();
        }

        $account = Account::findOrFail($accountId);
        $account->type_account_register = 1;
        $account->save();
        return response()->json(['message' => 'تم ربط هذا الحساب  بالعملاء بنجاح']);
    }

    public function linkCashRegister(Request $request)
    {
        $accountId = $request->input('account_id');
        $haveOtherLink = Account::findOrFail($accountId);
        if($haveOtherLink){
            $message = '';
            if($haveOtherLink->type_account_register == 1){
                $message = 'هذا الحساب مربوط بالعملاء';
                return response()->json(['success' =>false , 'message' => $message]);
            }


        }

        $account = Account::where('type_account_register' , 2)->first();
        if($account){
            $account->type_account_register = null;
            $account->save();
        }

        $account = Account::findOrFail($accountId);
        $account->type_account_register = 2;
        $account->save();
        return response()->json(['success' =>true , 'message' => 'تم ربط هذا الحساب  بالصاديق بنجاح']);
    }
    public function linkToSupplier(Request $request)
    {
        $accountId = $request->input('account_id');
        $haveOtherLink = Account::findOrFail($accountId);
        if($haveOtherLink){
            $message = '';
            if($haveOtherLink->type_account_register == 1){
                $message = 'هذا الحساب مربوط بالعملاء';
                return response()->json(['success' =>false , 'message' => $message]);
            }

            if($haveOtherLink->type_account_register == 2){
                $message = 'هذا الحساب مربوط الموردين';
                return response()->json(['success' =>false , 'message' => $message]);
            }


        }

        $account = Account::where('type_account_register' , 3)->first();
        if($account){
            $account->type_account_register = null;
            $account->save();
        }

        $account = Account::findOrFail($accountId);
        $account->type_account_register = 3;
        $account->save();
        return response()->json(['success' =>true , 'message' => 'تم ربط هذا الحساب  بالصاديق بنجاح']);
    }


    public function getCustomers()
    {
        $suppliers = Supplier::where('company_id' , Auth::user()->model_id)->with('branch')->get();
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
        $supplier->company_id = Auth::user()->model_id;
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
            ->where('company_id', Auth::user()->model_id)
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
