<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(){
        $accounts = Account::where('company_id', Auth::user()->model_id)->get();
        $account = Account::where('company_id', Auth::user()->model_id)
            ->where('type_account_register', 1)
            ->first();
        $cach_register = Account::where('company_id', Auth::user()->model_id)
            ->where('type_account_register', 2)
            ->first();
        return view('financialaccounting.suppliers.index' , compact('accounts' , 'account','cach_register'));
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
}
