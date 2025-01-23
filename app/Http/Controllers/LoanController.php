<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Employee;
use App\Models\Branch;
class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with('employee', 'branch')->get();
        return view('campany.loans.index', compact('loans'));
    }

    public function create()
    {
        $employees = Employee::all();
        $branches = Branch::where('company_id',auth()->user()->model_id)->get();
        return view('campany.loans.create', compact('employees', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employe_id' => 'required',
            'amount' => 'required',
            'loan_date' => 'required|date',
        ]);
        $employee = Employee::find($request->employe_id);

        $loan = new Loan();
        $loan->employee_id = $request->employe_id;
        $loan->branch_id =  $employee->branch->id;
        $loan->amount = $request->amount;
        $loan->loan_date = $request->loan_date ?? null;
        $loan->save();

        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    public function edit(Loan $loan)
    {
        $employees = Employee::all();
        $branches = Branch::all();
        return view('campany.loans.edit', compact('loan', 'employees', 'branches'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
        ]);

        $loan->update($request->all());
        return redirect()->route('loans.index')->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }

}
