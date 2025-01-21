<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Branch;
use App\Models\Employee;
class EmployeeController extends Controller
{

    public function create()
    {

        $categories = Category::all();
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        return view('campany.employes.create' , compact('branches' ,'categories' ));
    }
    public function index()
    {

        // $categories = Category::all();
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        $employees = Employee:: with(['branch' => function ($query) {
        $query->select('id', 'name');
    },'loans','leaves','overtimes', 'deducations',
    ])->
     whereHas('branch', function ($query) {
            $query->where('company_id', auth()->user()->model_id);
        })

        ->get();
        return view('campany.employes.index' , compact('employees' , 'branches') );
    }

    public function getEmployeesByBranch($branchId)
    {
        $employees = Employee::where('branch_id', $branchId)->get();

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'iqamaNumber'=>'required',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure the category exists
            'branch_id' => 'required|exists:branches,id', // Ensure the branch exists
            'job' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
        ]);

        Employee::create($validated);
        return redirect()->back()->with('success', 'تم إنشاء الموظف بنجاح.');    }
}
