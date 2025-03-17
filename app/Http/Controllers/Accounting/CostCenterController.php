<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\CostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostCenterController extends Controller
{
    public function index(){

        $costcenters = CostCenter::where('company_id', Auth::user()->model_id)->get();

        return view('financialaccounting.costCenter.index', compact('costcenters'));
    }

    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'code' => 'required',
            'name' => 'required',
        ], [
            'account_number.required' => 'رقم المركز مطلوب.',
            'name.required' => ' إسم المركز مطلوب.',
        ]);


        $account = new CostCenter();
        $account->code = $validated['code'];
        $account->name = $validated['name'];
        $account->company_id = auth()->user()->model_id;
        $account->save();
        return redirect()->back()->with('success', 'تم إنشاء لمركز بنجاح!');

    }

}
