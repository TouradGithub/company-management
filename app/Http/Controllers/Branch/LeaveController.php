<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Branch;
class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('branch_id', getBranch()->id)->with('employee')->get();
        return view('branch.leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employees = Employee::where('branch_id', getBranch()->id)->get();
        return view('branch.leaves.create', compact('employees'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        Leave::create([
            'employee_id'=> $validated['employee_id'],
            'branch_id'=>getBranch()->id,
            'start_date'=> $validated['start_date'],
            'end_date'=> $validated['end_date'],
            'reason'=> $validated['reason']??'',
        ]);

        return redirect()->back()->with('success', 'تم تسجيل الإجازة بنجاح.');
    }
    public function edit(Leave $leave)
    {
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        $employees = Employee::where('branch_id', $leave->branch_id)->get(); // Employees for the branch
        return view('branch.leaves.edit', compact('leave', 'branches', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $leave->update($validated);

        return redirect()->route('branch.leaves.index')->with('success', 'تم تعديل الإجازة بنجاح');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('branch.leaves.index')->with('success', 'تم حذف الإجازة بنجاح');
    }

}
