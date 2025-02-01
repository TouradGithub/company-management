<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Branch;
class DeductionController extends Controller
{
    public function index()
    {
        $deductions = Deduction::whereIn('branch_id', branchId())->with('employee', 'branch')->get();
        return view('campany.deductions.index', compact('deductions'));
    }

    public function create()
    {
        $employees = Employee::whereIn('branch_id', branchId())->get();
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        return view('campany.deductions.create', compact('employees', 'branches'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'employe_id' => 'required|exists:employees,id',
            'deduction_date' => 'required|date',
            'deduction_days' => 'required|numeric|min:1',
            'deduction_type' => 'required',
            'deduction_value' => 'nullable|numeric|min:0', // Only required for fixed_amount
            'reason' => 'nullable', // Only required for fixed_amount
        ]);

        if ($validatedData['deduction_type'] === 'fixed_amount' && !isset($validatedData['deduction_value'])) {
            return back()->withErrors(['deduction_value' => 'قيمة المبلغ الثابت مطلوبة عند اختيار مبلغ ثابت.']);
        }

        //return $validatedData;
        $employee = Employee::find($validatedData['employe_id']);
        if (!$employee) {
            return redirect()->back()->withErrors(['employee_id' => 'هذا الموظف غير موجود']);
        }

       

        Deduction::create([
            'employee_id' => $validatedData['employe_id'],
            'branch_id' =>  $employee->branch->id,
            'deduction_date' => $validatedData['deduction_date'],
            'deduction_days' => $validatedData['deduction_days'],
            'deduction_type' => $validatedData['deduction_type'],
            'deduction_value' => $validatedData['deduction_value']??null,
            'remaining_deduction' => $validatedData['deduction_value']??0,
            'reason' => $validatedData['reason']??null
        ]);

        getUnpaidDeductionsTotal( $validatedData['employe_id']);

        return redirect()->route('deductions.index')->with('success', 'تم إضافة الخصم بنجاح');
    }

    public function edit(Deduction $deduction)
    {
        $employees = Employee::whereIn('branch_id', branchId())->get();
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        return view('campany.deductions.edit', compact('deduction', 'employees', 'branches'));
    }

    public function update(Request $request, Deduction $deduction)
    {

        $request->validate([
           'employe_id' => 'required|exists:employees,id',
            'deduction_date' => 'required|date',
            'deduction_days' => 'required|numeric|min:1',
            'deduction_type' => 'required',
            'deduction_value' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string',
        ]);

        $deduction->update($request->all());

        return redirect()->route('deductions.index')->with('success', 'تم تحديث الخصم بنجاح');
    }

    public function destroy(Deduction $deduction)
    {
        $IdEmployee =  $deduction->employee_id;
        $deduction->delete();
        getUnpaidDeductionsTotal(  $IdEmployee);
        return redirect()->route('deductions.index')->with('success', 'تم حذف الخصم بنجاح');
    }
}
