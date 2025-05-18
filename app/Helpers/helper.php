<?php

use App\Models\Account;
use App\Models\Branch;
use App\Models\Loan;
use App\Models\Deduction;
use App\Models\Overtime;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

function branchId()
    {
        $branches = Branch::where('company_id', auth()->user()->model_id)->get();
        return $branches->pluck('id');
    }
    function getCompany()
    {
        if(auth()->user()->model_type == "BRANCH"){
            $branch = Branch::find(auth()->user()->model_id);
            return  Company::find($branch->company_id);
        }else{
            return  Company::find(auth()->user()->model_id);
        }

    }

    function getBranch()
    {
        $branch = Branch::find( auth()->user()->model_id);
        return $branch;
    }
    function getCompanyId(){
        if(auth()->user()->model_type == "BRANCH"){
            $branch = Branch::find(auth()->user()->model_id);
            return $branch->company_id;
        }else{
            return auth()->user()->model_id;
        }

    }

    // get the curect year
    function getCurrentYear(){
        $currentYear = \App\Models\SessionYear::where('is_current', true)->where('company_id', getCompanyId())->first();
        if ($currentYear) {
            return $currentYear->id;
        }
        return null;
    }

    function getCurentYearName(){
        $currentYear = \App\Models\SessionYear::where('is_current', true)->where('company_id', getCompanyId())->first();
        if ($currentYear) {
            return $currentYear->name;
        }
        return null;
    }

    function  cach_register()
    {
        return Account::where('company_id', getCompanyId())
            ->where('type_account_register', 2)
            ->where('linked_root_id', 1)
            ->first()??null;
    }
    function credit_and_debit()
    {
        return \App\Models\AccountYear::where('company_id', getCompanyId())
            ->where('session_year_id', getCurrentYear())
            ->whereIn('account_id', Account::where('company_id' , getCompanyId())
                ->where('opening_balance', '!=', 0)
                ->pluck('id'))
            ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
            ->first();
    }
    function openiing_balance_of_all_company()
    {
        return Account::where('company_id', getCompanyId())
            ->where('opening_balance', '!=', 0)
            ->sum('opening_balance');
    }
    function ousoul()
    {
        return Account::where('company_id', getCompanyId())
            ->where('name', 'like', '%الأصول%')
            ->first()->sessionBalance->balance??0;
    }
    function  suppliers_register()
        {
            return Account::where('company_id', getCompanyId())
                ->where('type_account_register', 3)
                ->where('linked_root_id', 1)
                ->first()??null;
        }
    function  customers_register()
    {
        return Account::where('company_id', getCompanyId())
            ->where('type_account_register', 1)
            ->where('linked_root_id', 1)
            ->first()??null;
    }
    function getAccountTreeIds($accounts, &$accountIds = [])
    {
        foreach ($accounts as $account) {
            $accountIds[] = $account->id;

            // Get child accounts recursively
            $childAccounts = Account::where('parent_id', $account->id)->get();
            if ($childAccounts->isNotEmpty()) {
                getAccountTreeIds($childAccounts, $accountIds);
            }
        }

        return $accountIds;
    }

    function processOvertimePayment(int $employeeId, float $OvertimeAmount)
    {
        if ($OvertimeAmount > 0) {
            $overtimes = Overtime::where('employee_id', $employeeId)
                        ->where('remaining_overtime', '>', 0)
                        ->get();
            foreach ($overtimes as $overtime) {
                if ($OvertimeAmount <= 0) {
                    break;
                }
                $remainingovertime = $overtime->remaining_overtime;
                if ($OvertimeAmount >= $remainingovertime) {
                    $overtime->paid_overtime += $remainingovertime;
                    $overtime->remaining_overtime = 0;
                    $overtime->save();
                    $OvertimeAmount -= $remainingovertime;
                } else {
                    $overtime->paid_overtime += $OvertimeAmount;
                    $overtime->remaining_overtime -= $OvertimeAmount;
                    $overtime->save();
                    $OvertimeAmount = 0;
                }
            }
        }
    }

    function processLoanPayment(int $employeeId, float $loanAmount)
    {
        if ($loanAmount > 0) {
            $loans = Loan::where('employee_id', $employeeId)
                        ->where('remaining_loan', '>', 0)
                        ->get();
            foreach ($loans as $loan) {
                if ($loanAmount <= 0) {
                    break;
                }
                $remainingLoan = $loan->remaining_loan;
                if ($loanAmount >= $remainingLoan) {
                    $loan->paid_loan += $remainingLoan;
                    $loan->remaining_loan = 0;
                    $loan->save();
                    $loanAmount -= $remainingLoan;
                } else {
                    $loan->paid_loan += $loanAmount;
                    $loan->remaining_loan -= $loanAmount;
                    $loan->save();
                    $loanAmount = 0;
                }
            }
        }
    }

    function processDeductionPayment(int $employeeId, float $deductionAmount)
    {
        if ($deductionAmount > 0) {
            $deductions = Deduction::where('employee_id', $employeeId)
                                ->where('remaining_deduction', '>', 0)
                                ->get();

            foreach ($deductions as $deduction) {
                if ($deductionAmount <= 0) {
                    break;
                }

                $remainingDeduction = $deduction->remaining_deduction;
                if ($deductionAmount >= $remainingDeduction) {
                    $deduction->paid_deduction += $remainingDeduction;
                    $deduction->remaining_deduction = 0;
                    $deduction->save();
                    $deductionAmount -= $remainingDeduction;
                } else {
                    $deduction->paid_deduction += $deductionAmount;
                    $deduction->remaining_deduction -= $deductionAmount;
                    $deduction->save();
                    $deductionAmount = 0;
                }
            }
        }
    }

    function reverseLoanPayment(int $employeeId, float $loanAmount)
    {
        if ($loanAmount > 0) {
        $loans = Loan::where('employee_id', $employeeId)
                    ->where('paid_loan', '>', 0)
                    ->orderBy('created_at', 'desc') // التراجع عن أحدث المدفوعات أولاً
                    ->get();

        foreach ($loans as $loan) {
            if ($loanAmount <= 0) {
                break;
            }

            $paidLoan = $loan->paid_loan;

            if ($loanAmount >= $paidLoan) {
                $loan->paid_loan = 0;
                $loan->remaining_loan += $paidLoan;
                $loan->save();
                $loanAmount -= $paidLoan;
            } else {
                $loan->paid_loan -= $loanAmount;
                $loan->remaining_loan += $loanAmount;
                $loan->save();
                $loanAmount = 0;
            }
        }
    }
}


    function reverseDeductionPayment(int $employeeId, float $deductionAmount)
    {
        if ($deductionAmount > 0) {
            $deductions = Deduction::where('employee_id', $employeeId)
                              ->where('paid_deduction', '>', 0)
                              ->orderBy('created_at', 'desc') // التراجع عن أحدث المدفوعات أولاً
                              ->get();

        foreach ($deductions as $deduction) {
            if ($deductionAmount <= 0) {
                break;
            }

            $paidDeduction = $deduction->paid_deduction;

            if ($deductionAmount >= $paidDeduction) {
                $deduction->paid_deduction = 0;
                $deduction->remaining_deduction += $paidDeduction;
                $deduction->save();
                $deductionAmount -= $paidDeduction;
            } else {
                $deduction->paid_deduction -= $deductionAmount;
                $deduction->remaining_deduction += $deductionAmount;
                $deduction->save();
                $deductionAmount = 0;
            }
        }
    }
}


    function reverseOvertimePayment(int $employeeId, float $overtimeAmount)
    {
        if ($overtimeAmount > 0) {
            $overtimes = Overtime::where('employee_id', $employeeId)
                              ->where('paid_overtime', '>', 0)
                              ->orderBy('created_at', 'desc') // التراجع عن أحدث المدفوعات أولاً
                              ->get();

        foreach ($overtimes as $overtime) {
            if ($overtimeAmount <= 0) {
                break;
            }

            $paidovertime = $overtime->paid_overtime;

            if ($overtimeAmount >= $paidovertime) {
                $overtime->paid_overtime = 0;
                $overtime->remaining_overtime += $paidovertime;
                $overtime->save();
                $overtimeAmount -= $paidovertime;
            } else {
                $overtime->paid_overtime -= $overtimeAmount;
                $overtime->remaining_overtime += $overtimeAmount;
                $overtime->save();
                $overtimeAmount = 0;
            }
        }
    }
}


    function getUnpaidDeductionsTotal($employeeId)
    {
        $total =  Deduction::where('employee_id', $employeeId)
                        ->where('remaining_deduction', '>', 0)
                        ->sum('remaining_deduction');

        $employee = Employee::find( $employeeId);
        $employee->deducation_total = $total;
        $employee->save();
    }

    function getUnpaidLoansTotal($employeeId)
    {
        $total =  Loan::where('employee_id', $employeeId)
                        ->where('remaining_loan', '>', 0)
                        ->sum('remaining_loan');

        $employee = Employee::find( $employeeId);
        $employee->loans_total = $total;
        $employee->save();
    }

    function getUnpaidOvertimeTotal($employeeId)
    {
        $total =  Overtime::where('employee_id', $employeeId)
                        ->where('remaining_overtime', '>', 0)
                        ->sum('remaining_overtime');

        $employee = Employee::find( $employeeId);
        $employee->overtime_total = $total;
        $employee->save();
    }


 function calculateRevenues()
{
    $invoices = \App\Models\Invoice::where('company_id', getCompanyId())
        ->where('invoice_type', 'Sales')
        ->with('items')
        ->get();

    $grossSales = 0;
    $totalCost = 0;

    foreach ($invoices as $invoice) {
        $grossSales += $invoice->total;

        foreach ($invoice->items as $item) {
            $product = \App\Models\Product::find($item->product_id);
            $totalCost += $product ? ($product->cost * $item->quantity) : 0;
        }
    }

    $netRevenue = $grossSales - $totalCost;

    return $netRevenue;
}


 function calculateExpenses()
{
    $company = \App\Models\Company::find(getCompanyId());
    $branchIds = $company->branches()->pluck('id')->toArray();

    // الرواتب
    $totalPayroll = \App\Models\Payroll::whereIn('branch_id', $branchIds)->sum('net_salary');

    // تكلفة المبيعات (نحسبها بنفس طريقة الإيرادات)
    $invoices = \App\Models\Invoice::where('company_id', getCompanyId())
        ->where('invoice_type', 'Sales')
        ->with('items')
        ->get();

    $totalCost = 0;

    foreach ($invoices as $invoice) {
        foreach ($invoice->items as $item) {
            $product = \App\Models\Product::find($item->product_id);
            $totalCost += $product ? ($product->cost * $item->quantity) : 0;
        }
    }

    $totalExpenses = $totalPayroll + $totalCost;

    return $totalExpenses;
}
