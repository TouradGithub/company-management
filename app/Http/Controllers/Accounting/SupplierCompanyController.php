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
class SupplierCompanyController extends Controller
{
    public function index()
    {
        $branches = Branch::where('company_id', Auth::user()->model_id)->get();
        $suppliers = Supplier::where('company_id', Auth::user()->model_id)->get();

        $mainCustomerAccount = Account::where('company_id', Auth::user()->model_id)
            ->where('account_type_id', 1)
            ->first();
        if ($mainCustomerAccount) {
            $accountIds = $this->getAccountTreeIds(collect([$mainCustomerAccount]));
            $accounts = Account::where('company_id', Auth::user()->model_id)
                ->whereIn('id', $accountIds)
                ->get();
        } else {
            $accounts = collect();
        }
        return view('financialaccounting.suppliers.supplier', compact('accounts', 'branches', 'suppliers'));
    }
    public function getSuppliers(Request $request)
    {
        $suppliers = Supplier::where('company_id', Auth::user()->model_id)
            ->with(['branch', 'account']);
        if ($request->branch_id && $request->branch_id != "all") {
            $suppliers->where('branch_id', $request->branch_id);
        }
        $suppliers = $suppliers->get();
        $suppliers->each(function ($supplier) {
            $salesTotal = $supplier->invoices()
                ->where('invoice_type', 'Sales')
                ->sum('total');
            $salesReturnTotal = $supplier->invoices()
                ->where('invoice_type', 'SalesReturn')
                ->sum('total');
            $supplier->balance = $salesTotal - $salesReturnTotal ;
        });

        return response()->json(['suppliers' => $suppliers]);
    }
    private function getAccountTreeIds($accounts, &$accountIds = [])
    {
        foreach ($accounts as $account) {
            $accountIds[] = $account->id;
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
            'account_id' => 'required',

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
            'message' =>"تم إضافة لعميل بنجاح"
        ], 201);
    }

    public function storeCustomer(Request $request){
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
            'customer' =>$supplier,
            'message' =>"تم إضافة لعميل بنجاح"
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
        if ($supplier->invoices()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف العميل لأنه مرتبط بفواتير.'
            ], 404); // 403 Forbidden status
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

        $customer = Supplier::findOrFail($id);
        $customer->update([
            'name'=>$request->name,
            'contact_info'=>$request->contact_info,
            'branch_id'=>$request->branch_id??'all',
        ]);
        return response()->json(['status' => 'نجاح', 'message' => 'تم تحديث العميل بنجاح', 'success' => true]);
    }
}
