<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Deduction;
use App\Models\Employee;
use App\Models\Branch;
class DeductionController extends Controller
{
    public function index()
    {
        $deductions = Deduction::where('branch_id', getBranch()->id)->with('employee')->get();
        return view('branch.deductions.index', compact('deductions'));
    }

    public function create()
    {
        $employees = Employee::where('branch_id', getBranch()->id)->get();
        return view('branch.deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'employe_id' => 'required|exists:employees,id',
            'deduction_date' => 'required|date',
            'deduction_days' => 'required|numeric|min:1',
            'deduction_type' => 'required',
            'month_days'=> 'nullable|numeric',
            'deduction_value' => 'nullable|numeric|min:0', // Only required for fixed_amount
            'reason' => 'nullable|string', // Only required for fixed_amount
        ]);

        if ($validatedData['deduction_type'] === 'fixed_amount' && !isset($validatedData['deduction_value'])) {
            return back()->withErrors(['deduction_value' => 'قيمة المبلغ الثابت مطلوبة عند اختيار مبلغ ثابت.']);
        }
        $employee = Employee::find($validatedData['employe_id']);


        Deduction::create([
            'employee_id' => $validatedData['employe_id'],
            'branch_id' =>  $employee->branch->id,
            'deduction_date' => $validatedData['deduction_date'],
            'deduction_days' => $validatedData['deduction_days'],
            'deduction_type' => $validatedData['deduction_type'],
            'deduction_value' => $validatedData['deduction_value']??null,
            'month_days' => $validatedData['month_days'] ?? 30,
            'remaining_deduction' => $validatedData['deduction_value']??0,
            'reason' => $validatedData['reason']??null
        ]);

        getUnpaidDeductionsTotal(  $validatedData['employe_id']);

        return redirect()->route('branch.deductions.index')->with('success', 'تم إضافة الخصم بنجاح');
    }

    public function edit(Deduction $deduction)
    {
        $employees = Employee::where('branch_id', getBranch()->id)->get();
        return view('branch.deductions.edit', compact('deduction', 'employees'));
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

        getUnpaidDeductionsTotal( $request->employe_id);

        return redirect()->route('branch.deductions.index')->with('success', 'تم تحديث الخصم بنجاح');
    }

    public function destroy(Deduction $deduction)
    {

        $IdEmployee =  $deduction->employee_id;
        $deduction->delete();
        getUnpaidDeductionsTotal( $IdEmployee);

        return redirect()->route('branch.deductions.index')->with('success', 'تم حذف الخصم بنجاح');
    }
}
