<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Branch;
class OvertimeController extends Controller
{

    public function index()
    {
        $overtimes = Overtime::with('employee', 'branch')->get(); // جلب بيانات الإضافي مع الموظف والفرع
        return view('campany.overtimes.index', compact('overtimes'));
    }
    public function create()
    {
        $branches = Branch::all();
        return view('campany.overtimes.create', compact('branches'));
    }

    public function store(Request $request)
    {
        
        // Validate incoming data
        $validated = $request->validate([
            'date' => 'required|date',
            'employe_id' => 'required',
            'overtimeType' => 'required|string',
            'fixedAmount' => 'nullable|numeric',
            'hours' => 'nullable|numeric',
            'hourMultiplier' => 'nullable|string',
            'days' => 'nullable|numeric',
            'dailyRate' => 'nullable|numeric',
            'totalAmount' => 'required|numeric',
        ]);

        $employee = Employee::find( $validated['employe_id']);


        $overtime = new Overtime();
        $overtime->date = $validated['date'];
        $overtime->branch_id =  $employee->branch->id; // You can store branches as a JSON
        $overtime->employee_id = $validated['employe_id'];
        $overtime->overtime_type = $validated['overtimeType'];
        $overtime->fixed_amount = $validated['fixedAmount'] ?? null;
        $overtime->hours = $validated['hours'] ?? null;
        $overtime->hour_multiplier = $validated['hourMultiplier'] ?? null;
        $overtime->days = $validated['days'] ?? null;
        $overtime->daily_rate = $validated['dailyRate'] ?? null;
        $overtime->total_amount = $validated['totalAmount'];

        // Save the Overtime entry to the database
        $overtime->save();

        // Return a success response
        return redirect()->back()->with([
            'message' => 'Overtime saved successfully',
            'overtime' => $overtime
        ]);
            }
    public function edit(Overtime $overtime)
    {
        $branches = Branch::all();
        return view('campany.overtimes.edit', compact('overtime', 'branches'));
    }

    public function update(Request $request, Overtime $overtime)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'branch_id' => 'required|exists:branches,id',
            'fixed_amount' => 'required|numeric|min:0',
            'percentage_of_salary' => 'required|numeric|min:0|max:100',
            'overtime_hours' => 'required|integer|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $basic_salary = $employee->basic_salary;

        $overtime_value = $validated['fixed_amount'] +
                        ($validated['percentage_of_salary'] / 100) * $basic_salary * $validated['overtime_hours'];

        $overtime->update([
            'employee_id' => $validated['employee_id'],
            'branch_id' => $validated['branch_id'],
            'fixed_amount' => $validated['fixed_amount'],
            'percentage_of_salary' => $validated['percentage_of_salary'],
            'overtime_hours' => $validated['overtime_hours'],
            'overtime_value' => $overtime_value,
            'basic_salary' => $basic_salary,
        ]);

        return redirect()->route('company.overtimes.index')->with('success', 'تم تعديل الإضافي بنجاح');
    }

    public function destroy(Overtime $overtime)
    {
        $overtime->delete();
        return redirect()->route('company.overtimes.index')->with('success', 'تم حذف الإضافي بنجاح');
    }


    public function getEmployeesByBranch(Request $request)
    {
        // return $request;
        // Get the selected branches from the request
        $branches = $request->input('branches', []);

        // Query employees that match the selected branches
        $employees = Employee::whereIn('branch_id', $branches)->get();

        // Return the employees in JSON format
        return response()->json($employees);
    }

}
