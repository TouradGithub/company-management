<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class BranchController extends Controller
{
    public function create()
    {
        return view('branches.create'); // Pointing to resources/views/company/create.blade.php
    }


    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'name_admin_company' => 'required|string|max:255',
            'email'              => 'required|email|unique:companies,email|unique:branches,email|unique:users,email',
            'password'           => 'required|string|min:6|max:255',
        ], [
            'name.required' => 'اسم الفرع مطلوب.',
            'name_admin_company.required' => 'اسم المسؤول مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $hashedPassword = Hash::make($validated['password']);
                $branch = Branch::create([
                    'name'               => $validated['name'],
                    'name_admin_company' => $validated['name_admin_company'],
                    'email'              => $validated['email'],
                    'company_id'         => auth()->user()->model_id,
                    'password'           => $hashedPassword,
                ]);

                $user = User::create([
                    'name'        => $validated['name'],
                    'email'       => $validated['email'],
                    'password'    => $hashedPassword,
                    'model_type'  => 'BRANCH',
                    'model_id'    => $branch->id,
                    'is_admin'    => 1,
                ]);
                $permissions = Permission::pluck('name')->toArray();
                $user->givePermissionTo($permissions);
            });

            return redirect()->back()->with('success', 'تم إضافة الفرع بنجاح!');
        } catch (\Throwable $th) {
            \Log::error('خطأ أثناء إنشاء الفرع: ' . $th->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الفرع. يرجى المحاولة لاحقًا.');
        }
    }


    public function index()
    {
        $companies = Branch::where('company_id',auth()->user()->model_id)->get();
        return view('branches.index', compact('companies')); // Adjust view path as needed
    }

    /**
     * Show a form to edit a company.
     */
    public function edit($id)
    {
        $company = Branch::findOrFail($id);
        return view('branches.edit', compact('company')); // Adjust view path as needed
    }

    /**
     * Update an existing company.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_admin_company' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);
        $branch = Branch::findOrFail($id);
        $branch->name = $validated['name'];
        $branch->company_id =auth()->user()->model_id;
        $branch->name_admin_company = $validated['name_admin_company'];
        $branch->email = $validated['email'];
        if (!empty($validated['password'])) {
            $branch->password = bcrypt($validated['password']);
        }
        $branch->save();

        $user =  User::where([
            'model_type' =>"BRANCH",
            'model_id' =>$branch->id,
            'is_admin' =>1,
        ])->first();

        if ($user) {
            $user->name = $validated['name_admin_company'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password =Hash::make($validated['password']);
            }
            $user->save();
        }
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully!');
    }

    /**
     * Delete a company.
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);

        if ($branch->leaves()->exists()) {
            return redirect()->route('branches.index')->with('error', 'لا يمكن حذف الفرع لأنه يحتوي على إجازات مرتبطة.');
        }

        if ($branch->deducations()->exists()) {
            return redirect()->route('branches.index')->with('error', 'لا يمكن حذف الفرع لأنه يحتوي على خصومات مرتبطة.');
        }

        if ($branch->overtimes()->exists()) {
            return redirect()->route('branches.index')->with('error', 'لا يمكن حذف الفرع لأنه يحتوي على إضافيات مرتبطة.');
        }

        if ($branch->loans()->exists()) {
            return redirect()->route('branches.index')->with('error', 'لا يمكن حذف الفرع لأنه يحتوي على سلف مرتبط.');
        }
        if ($branch->employees()->exists()) {
            return redirect()->route('branches.index')->with('error', 'لا يمكن حذف الفرع لأنه يحتوي على موظفين مرتبط.');
        }
        $branch->delete();


        return redirect()->route('branches.index')->with('success', 'تم حذف الفرع بنجاح');
    }
}

