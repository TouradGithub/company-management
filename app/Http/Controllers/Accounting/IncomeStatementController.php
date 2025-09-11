<?php

namespace App\Http\Controllers\Accounting;

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
        $branches = Branch::where('company_id', getCompanyId())->get();
        return view('financialaccounting.income-statement.index' , compact('branches'));
    }

    public function getIncomeStatementData(Request $request)
    {
        $branchId = $request->input('branch_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $companyId = getCompanyId();

        // جلب حسابات الإيرادات (تبدأ بـ 15 أو 5)
        $revenueAccounts = Account::select('accounts.*', 'syc.balance')
            ->leftJoin('session_years_company_balance as syc', function($join) {
                $join->on('accounts.id', '=', 'syc.account_id')
                     ->where('syc.session_year_id', getCurrentYear());
            })
            ->where('accounts.company_id', $companyId)
            ->where(function($query) {
                $query->where('accounts.account_number', 'LIKE', '15%')
                      ->orWhere('accounts.account_number', 'LIKE', '5%');
            })
            ->whereNotNull('syc.balance')
            ->where('syc.balance', '!=', 0)
            ->orderBy('accounts.account_number')
            ->get();

        // جلب حسابات المصروفات (تبدأ بـ 4)
        $expenseAccounts = Account::select('accounts.*', 'syc.balance')
            ->leftJoin('session_years_company_balance as syc', function($join) {
                $join->on('accounts.id', '=', 'syc.account_id')
                     ->where('syc.session_year_id', getCurrentYear());
            })
            ->where('accounts.company_id', $companyId)
            ->where('accounts.account_number', 'LIKE', '4%')
            ->whereNotNull('syc.balance')
            ->where('syc.balance', '!=', 0)
            ->orderBy('accounts.account_number')
            ->get();

        // تحويل الإيرادات (الرصيد سالب يعني إيراد)
        $revenues = [];
        $totalRevenues = 0;
        foreach ($revenueAccounts as $account) {
            $amount = abs($account->balance); // تحويل السالب إلى موجب
            $revenues[] = [
                'name' => $account->name,
                'amount' => $amount
            ];
            $totalRevenues += $amount;
        }

        // تحويل المصروفات (الرصيد موجب)
        $expenses = [];
        $totalExpenses = 0;
        foreach ($expenseAccounts as $account) {
            $amount = abs($account->balance);
            $expenses[] = [
                'name' => $account->name,
                'amount' => $amount
            ];
            $totalExpenses += $amount;
        }

        // إضافة الرواتب كمصروف إضافي
        // جلب معرفات فروع الشركة
        $companyBranchIds = Branch::where('company_id', $companyId)->pluck('id')->toArray();

        $payrollQuery = Payroll::whereIn('branch_id', $companyBranchIds);
        if ($branchId) {
            $payrollQuery->where('branch_id', $branchId);
        }
        if ($startDate && $endDate) {
            $payrollQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $totalPayroll = $payrollQuery->sum('net_salary') ?? 0;

        if ($totalPayroll > 0) {
            $expenses[] = [
                'name' => 'مصاريف الرواتب',
                'amount' => $totalPayroll
            ];
            $totalExpenses += $totalPayroll;
        }

        $netIncome = $totalRevenues - $totalExpenses;

        return response()->json([
            'success' => true,
            'data' => [
                'revenues' => $revenues,
                'expenses' => $expenses,
                'total_revenues' => $totalRevenues,
                'total_expenses' => $totalExpenses,
                'net_income' => $netIncome
            ]
        ]);
    }
}
