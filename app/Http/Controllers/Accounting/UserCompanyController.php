<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserCompanyController extends Controller
{


    public function index(){
        $companies = Company::all();
        $companyId = Auth::user()->model_id;
        $branches = Branch::where('company_id', $companyId)->get(); // فروع الشركة الحالية
        $branchIds = Branch::where('company_id', $companyId)->pluck('id')->toArray();

        $users = User::where(function ($query) use ($companyId, $branchIds) {
            $query->where('model_type', 'COMPANY')
                ->where('model_id', $companyId)
                ->orWhere('model_type', 'BRANCH')
                ->whereIn('model_id', $branchIds);
        })->get();
        $companyPermissions =  Role::findByName('company_admin')->permissions->pluck('name');
         $branchPermissions = Role::findByName('branch_user')->permissions->pluck('name');
        return view('financialaccounting.users.index' , compact('companies' , 'users' , 'branches' , 'companyPermissions' , 'branchPermissions'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $branches = Branch::where('company_id', Auth::user()->model_id)->get();
        $companyPermissions = \Spatie\Permission\Models\Role::findByName('company_admin')->permissions;
        $branchPermissions = \Spatie\Permission\Models\Role::findByName('branch_user')->permissions;
        $userPermission = $user->getDirectPermissions()->pluck('name')->toArray();

//        dd($companyPermissions);
        return view('financialaccounting.users.edit', compact('user','userPermission', 'branches', 'companyPermissions', 'branchPermissions'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);


        if(!$user){
            return response()->json(['success' => false, 'message' => ' المستخدم غير موجود']);
        }
        if($user->is_admin == 1){
            return response()->json(['success' => false, 'message' => 'لايمكن حذف المستخدم الانه حساب مدير']);
        }
        $user->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف المستخدم بنجاح']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'user_type' => 'required|in:company,branch',
            'permissions' => 'array',
        ]);
        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->user_type,
            'password' => Hash::make($request->password),
            'model_type' => $request->user_type === 'company' ? 'COMPANY' : 'BRANCH',
            'model_id' => $request->user_type === 'company' ? Auth::user()->model_id : $request->branch_id,
            'is_admin' => 0,
        ]);

        $role = $request->user_type === 'company' ? 'company_admin' : 'branch_user';
        $user->assignRole($role);
        if ($request->permissions) {
            $user->syncPermissions($request->permissions);
        }
        DB::commit();

        return response()->json(['success' => true, 'message' => 'تم إضافة المستخدم بنجاح']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|confirmed|min:6',
            'user_type' => 'required|in:company,branch',
            'permissions' => 'array',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->role = $request->user_type;
        $user->model_id =$request->user_type === 'company'? Auth::user()->model_id:$request->branch_id ;
        $user->model_type = $request->user_type === 'company' ? "COMPANY" : "BRANCH";

        $user->save();

        $user->syncPermissions($request->permissions);

        return response()->json(['success' => true, 'message' => 'تم تحديث المستخدم بنجاح']);
    }


}
