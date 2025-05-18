<?php

namespace App\Http\Controllers\AccountingBranch;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\JournalEntryDetail;
use App\Models\Payroll;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeStatementController extends Controller
{
    public function index()
    {
        return view('financialaccountingbranch.income-statement.index' );
    }

    public function getIncomeStatementData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $invoicesQuery = Invoice::where('branch_id' ,getBranch()->id)->where('company_id' , getCompanyId())->where('invoice_type', 'Sales')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with('items');

        $invoices = $invoicesQuery->get();

        $grossSales = 0;
        $totalCost = 0;
        foreach ($invoices as $invoice) {
            $grossSales += $invoice->total;
            foreach ($invoice->items as $item) {
                $product = Product::find($item->product_id);
                $totalCost += $product ? ($product->cost * $item->quantity) : 0;
            }
        }
        $grossProfit = $grossSales - $totalCost;

        $payrollQuery = Payroll::where('branch_id',getBranch()->id)->whereBetween('date', [$startDate, $endDate]);
        $totalPayroll = $payrollQuery->sum('net_salary');

        $revenues = [
            ['name' => 'إجمالي المبيعات', 'amount' => $grossSales],
        ];

        $expenses = [
            ['name' => 'تكلفة المبيعات', 'amount' => $totalCost],
            ['name' => 'مصاريف الرواتب', 'amount' => $totalPayroll],
        ];

        $totalRevenues = $grossSales;
        $totalExpenses = array_sum(array_column($expenses, 'amount'));
        $netIncome = $totalRevenues - $totalExpenses;

        return response()->json([
            'success' => true,
            'data' => [
                'revenues' => $revenues,
                'expenses' => $expenses,
                'total_revenues' => $totalRevenues,
                'total_expenses' => $totalExpenses,
                'net_income' => $netIncome,
                'gross_profit' => $grossProfit
            ]
        ]);
    }
}
