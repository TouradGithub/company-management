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
    public function edit($id)
    {
        $categories = Category::where('company_id',auth()->user()->model_id)->get();
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        $employee = Employee::find($id);
        return view('campany.employes.edit' , compact('branches' ,'categories' , 'employee' ));
    }
    public function index()
    {

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

    public function getEmployeesByBranchWithRelationShip(Request $request)
    {
        $branches = $request->branches;

        $monthYear = $request->month;

        [$year, $month] = explode('-', $monthYear);

        $employees = Employee::whereIn('branch_id', $branches)
        ->with('branch') // Load branch relationship
        ->with([
            'loans' => function ($query) use ($year, $month) {
                $query->whereYear('loan_date', $year)
                      ->whereMonth('loan_date', $month)
                      ->selectRaw(' SUM(amount) as total_amount')
                      ->groupBy('employee_id');
            },
            'overtimes' => function ($query) use ($year, $month) {
                $query->whereYear('date', $year)
                      ->whereMonth('date', $month)
                      ->selectRaw(' SUM(total_amount) as total_amount')
                      ->groupBy('employee_id');
            },
            'deducations' => function ($query) use ($year, $month) {
                $query->whereYear('deduction_date', $year)
                      ->whereMonth('deduction_date', $month)
                      ->selectRaw(' SUM(deduction_value) as total_amount')
                      ->groupBy('employee_id');
            },
        ])


                ->get();

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
        return redirect()->back()->with('success', 'تم إنشاء الموظف بنجاح.');
     }
     public function update(Request $request, $id)
    {
        // Find the employee by ID
        $employee = Employee::findOrFail($id);

        // Validate the incoming data
        $validated = $request->validate([
            'iqamaNumber' => 'required',
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

        $employee->update($validated);
        return redirect()->route('company.employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }
    public function delete($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return redirect()->route('company.employees.index')->with('success', 'تم حذف الموظف بنجاح.');
    }


}
