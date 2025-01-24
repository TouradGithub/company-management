<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Employee;
use App\Models\Branch;
class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::where('branch_id', getBranch()->id)->with('employee')->get();
        return view('branch.loans.index', compact('loans'));
    }

    public function create()
    {
        $employees = Employee::where('branch_id', getBranch()->id)->get();
        return view('branch.loans.create', compact('employees'));
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
        $loan->branch_id = getBranch()->id;
        $loan->amount = $request->amount;
        $loan->loan_date = $request->loan_date ?? null;
        $loan->save();

        return redirect()->route('branch.loans.index')->with('success', 'Loan created successfully.');
    }

    public function edit(Loan $loan)
    {
        $employees = Employee::where('branch_id', getBranch()->id)->get();
        return view('branch.loans.edit', compact('loan', 'employees'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
        ]);

        $loan->update($request->all());
        return redirect()->route('branch.loans.index')->with('success', 'Loan updated successfully.');
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('branch.loans.index')->with('success', 'Loan deleted successfully.');
    }

}
