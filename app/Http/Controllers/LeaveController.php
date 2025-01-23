<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Branch;
class LeaveController extends Controller
{

    public function index()
    {
        $leaves = Leave::with('employee','branch')->get();
        return view('campany.leaves.index', compact('leaves'));
    }

    public function create()
    {
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        return view('campany.leaves.create', compact('branches'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'branch_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        Leave::create($validated);

        return redirect()->back()->with('success', 'تم تسجيل الإجازة بنجاح.');
    }
    public function edit(Leave $leave)
    {
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        $employees = Employee::where('branch_id', $leave->branch_id)->get(); // Employees for the branch
        return view('campany.leaves.edit', compact('leave', 'branches', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $leave->update($validated);

        return redirect()->route('company.leaves.index')->with('success', 'تم تعديل الإجازة بنجاح');
    }

    public function destroy(Leave $leave)
    {
        $leave->delete();
        return redirect()->route('company.leaves.index')->with('success', 'تم حذف الإجازة بنجاح');
    }



}
