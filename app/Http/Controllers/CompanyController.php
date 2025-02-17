<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class CompanyController extends Controller
{
    public function create()
    {
        return view('campany.create'); // Pointing to resources/views/company/create.blade.php
    }

    public function index()
    {
        $companies = Company::all();
        return view('campany.index', compact('companies')); // Pointing to resources/views/company/create.blade.php
    }

    /**
     * Store the company data in the database.
     */
    public function store(Request $request)
    {
        // return $request;
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'password' => 'required|string|max:500',
            'start_date' => 'required|string|max:500',
            'end_date' => 'required|string|max:500',
        ], [
            'name.required' => 'اسم الشركة مطلوب.',
            'name.string' => 'يجب أن يكون اسم الشركة نصًا.',
            'name.max' => 'يجب ألا يزيد اسم الشركة عن 255 حرفًا.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صالح.',
            'email.unique' => 'عنوان البريد الإلكتروني هذا مستخدم بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
            'password.max' => 'يجب ألا تتجاوز كلمة المرور 500 حرف.',

            'start_date.required' => 'تاريخ البدء مطلوب.',
            'start_date.string' => 'يجب أن يكون تاريخ البدء نصًا صالحًا.',

            'end_date.required' => 'تاريخ الانتهاء مطلوب.',
            'end_date.string' => 'يجب أن يكون تاريخ الانتهاء نصًا صالحًا.',
        ]);
        $validated['password'] = Hash::make($request->password);

        // Create a new company record
        $company = Company::create($validated);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($request->password),
            'model_type' =>"COMPANY",
            'model_id' =>$company->id,
            'is_admin' =>1,
        ]);

        // Redirect with a success message
        return redirect()->route('company.create')->with('success', 'Company created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $status = $request->input('status');

        if (in_array($status, ['active', 'inactive', 'cancelled'])) {
            $company->status = $status;
            $company->save();

            return redirect()->back()->with('success', 'Company status updated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid status.');
    }

}
