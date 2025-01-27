<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Branch;
class OvertimeController extends Controller
{
    public function index()
    {
        $overtimes = Overtime::where('branch_id',getBranch()->id)->with('employee')->get(); // جلب بيانات الإضافي مع الموظف والفرع
        return view('branch.overtimes.index', compact('overtimes'));
    }
    public function create()
    {
        $employees = Employee::where('branch_id',getBranch()->id)->get();
        return view('branch.overtimes.create', compact('employees'));
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
    public function edit( $id)
    {
        $employees = Employee::where('branch_id', getBranch()->id)->get();
        $overtime = Overtime::with('employee')->findOrFail($id);

        // dd( $id);
        return view('branch.overtimes.edit', compact('overtime','employees'));
    }

    public function update(Request $request,  $id)
    {
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

        $employee = Employee::findOrFail($validated['employe_id']);
            $overtime =  Overtime::find($id);
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
            $overtime->save();

        return redirect()->route('branch.overtimes.index')->with('success', 'تم تعديل الإضافي بنجاح');
    }

    public function destroy( $id)
    {
        $overtime =  Overtime::find($id);
        $overtime->delete();
        return redirect()->route('branch.overtimes.index')->with('success', 'تم حذف الإضافي بنجاح');
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
