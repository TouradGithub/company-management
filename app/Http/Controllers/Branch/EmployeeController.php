<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Branch;
use App\Models\Employee;
class EmployeeController extends Controller
{
    public function create()
    {

        $categories = Category::where('company_id' , getBranch()->company->id)->get();
        return view('branch.employes.create' , compact('categories' ));
    }
    public function edit($id)
    {
        $categories = Category::where('company_id' , getBranch()->company->id)->get();
        $employee = Employee::find($id);
        return view('branch.employes.edit' , compact( 'categories' , 'employee' ));
    }
    public function index()
    {
        $categories = Category::where('company_id' , getBranch()->company->id)->get();
        $employees = Employee::where('branch_id', getBranch()->id)-> with(['branch' => function ($query) {
                    $query->select('id', 'name');
                },'loans','leaves','overtimes', 'deducations',
                ])->
                whereHas('branch', function ($query) {
            $query->where('company_id', getBranch()->company->id);
        })->get();
        return view('branch.employes.index' , compact('employees' , 'categories') );
    }

    public function getEmployeesByBranch($branchId)
    {
        $employees = Employee::where('branch_id', $branchId)->get();

        return response()->json($employees);
    }

    public function getEmployeesByBranchWithRelationShip(Request $request)
    {

        $monthYear = $request->month;

        [$year, $month] = explode('-', $monthYear);

        $employees = Employee::where('branch_id', getBranch()->id)
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
        $validated = $request->validate([
            'iqamaNumber' => 'required',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure the category exists
            'job' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
        ]);
        $employee = new Employee();
        $employee->iqamaNumber = $validated['iqamaNumber'];
        $employee->name = $validated['name'];
        $employee->category_id = $validated['category_id'];
        $employee->branch_id = getBranch()->id;
        $employee->job = $validated['job'];
        $employee->basic_salary = $validated['basic_salary'];
        $employee->housing_allowance = $validated['housing_allowance'] ?? null;
        $employee->food_allowance = $validated['food_allowance'] ?? null;
        $employee->transportation_allowance = $validated['transportation_allowance'] ?? null;
        $employee->hire_date = $validated['hire_date'];

        $employee->save();

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
            'job' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
        ]);

        $employee->update($validated);
        return redirect()->route('branch.employees.index')->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }
    public function delete($id)
    {
        $employee = Employee::findOrFail($id);

        $employee->delete();

        return redirect()->route('branch.employees.index')->with('success', 'تم حذف الموظف بنجاح.');
    }
}
