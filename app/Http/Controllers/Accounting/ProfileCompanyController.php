<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ProfileCompanyController extends Controller
{


    public function index(){

        $company = Company::find(getCompanyId());
        return view('financialaccounting.company-information' , compact('company'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
//            'name' => 'required|string|max:255',
//            'email' => 'required|email|unique:companies,email,' . $id,
//            'password' => 'nullable|string|min:8',
//            'start_date' => 'required|date',
//            'tax_number'=>'required',
//            'address'=>'required',
//            'phone_number'=>'required',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $company = Company::findOrFail($id);
        $data = $request->only(['name', 'email','phone_number', 'start_date', 'end_date' , 'address' , 'tax_number']);
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        $company->update($data);

        return redirect()->back()->with('success', 'تم تحديث بيانات الشركة بنجاح!');
    }




}
