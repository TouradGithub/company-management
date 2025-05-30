<?php

namespace App\Http\Controllers\AccountingBranch;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class CustomerController extends Controller
{



    public function index()
    {

        $customers = Customer::where('company_id', getCompanyId())->where('branch_id' , getBranch()->id)->get();

        $mainCustomerAccount = Account::where('company_id', getCompanyId())
            ->where('account_type_id', 1)
            ->first();

        // If there is a main account, get its complete tree
        if ($mainCustomerAccount) {
            // Collect all account IDs in the tree starting from this account
            $accountIds = $this->getAccountTreeIds(collect([$mainCustomerAccount]));

            // Get all accounts that belong to this tree
            $accounts = Account::where('company_id', getCompanyId())
                ->whereIn('id', $accountIds)
                ->get();
        } else {
            $accounts = collect(); // Return empty collection if no main account
        }

        return view('financialaccountingbranch.customers.index', compact('accounts', 'customers'));
    }
    public function getCustomers(Request $request)
    {
        $customers = Customer::where('company_id', getCompanyId())
            ->where('branch_id' , getBranch()->id)
            ->with('account');


        $customers = $customers->get();

        $customers->each(function ($customer) {
            $salesTotal = $customer->invoices()
                ->where('invoice_type', 'Sales')
                ->sum('total');


            $salesReturnTotal = $customer->invoices()
                ->where('invoice_type', 'SalesReturn')
                ->sum('total');


            $customer->balance = $salesTotal - $salesReturnTotal ;
        });

        return response()->json(['customers' => $customers]);
    }
    private function getAccountTreeIds($accounts, &$accountIds = [])
    {
        foreach ($accounts as $account) {
            $accountIds[] = $account->id;

            // Get child accounts recursively
            $childAccounts = Account::where('company_id' , getCompanyId())->where('parent_id', $account->id)->get();
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
            'tax_number' => 'required',
            'account_id' => 'required',

        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->contact_info = $request->contact_info;
        $customer->tax_number = $request->tax_number;
        $customer->account_id = $request->account_id;
        $customer->branch_id = getBranch()->id;
        $customer->company_id = getCompanyId();
        $customer->save();
        return response()->json([
            'status' => 'تم',
            'success' =>true,
            'message' =>"تم إضافة لعميل بنجاح"
        ], 201);
    }

    public function storeCustomer(Request $request){
        $request->validate([
            'name' => 'required',
            'contact_info' => 'required',
            'tax_number' => 'required',
            'account_id' => 'required|numeric',
            'credit_limit' => 'required|numeric|min:0',
        ]);

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->contact_info = $request->contact_info;
        $customer->tax_number = $request->tax_number;
        $customer->credit_limit = $request->credit_limit;
        $customer->account_id = $request->account_id;
        $customer->branch_id = getBranch()->id;
        $customer->company_id = getCompanyId();
        $customer->save();
        return response()->json([
            'status' => 'تم',
            'success' =>true,
            'customer' =>$customer,
            'message' =>"تم إضافة لعميل بنجاح"
        ], 201);
    }

    public function delete($id)
    {
        $customer = Customer::where('id', $id)
            ->where('company_id', getCompanyId())
            ->first();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'العميل غير موجود أو لا يمكنك حذفه'], 404);
        }
        if ($customer->invoices()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف العميل لأنه مرتبط بفواتير.'
            ], 404); // 403 Forbidden status
        }

        $customer->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف العميل بنجاح']);
    }
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:20',
            'credit_limit' => 'required|numeric|min:0',
            'account_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer = Customer::findOrFail($id);
        $customer->update([
            'name'=>$request->name,
            'tax_number' => $request->tax_number,
            'credit_limit' => $request->credit_limit,
            'contact_info'=>$request->contact_info,
            'branch_id'=>getBranch()->id,
        ]);
        return response()->json(['status' => 'نجاح', 'message' => 'تم تحديث العميل بنجاح', 'success' => true]);
    }
}
