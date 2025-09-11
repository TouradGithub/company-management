<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountYear;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialDashboardController extends Controller
{
    public function index()
    {
        $companyId = getCompanyId();
        $sessionYear = getCurrentYear();

        // إجمالي المبيعات (الإيرادات)
        $totalSales = $this->getAccountBalance($companyId, $sessionYear, 'إيرادات%', true);

        // رصيد العملاء
        $customersBalance = $this->getAccountBalance($companyId, $sessionYear, 'عملاء%');

        // رصيد الصندوق
        $cashBalance = $this->getAccountBalance($companyId, $sessionYear, 'صندوق%');

        // الضرائب المستحقة
        $taxesPayable = $this->getAccountBalance($companyId, $sessionYear, 'ضريبة%', true);

        // آخر القيود اليومية مع التحقق من التوازن
        $recentEntries = JournalEntry::select([
                'journal_entries.*',
                DB::raw('COALESCE(SUM(jed.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(jed.credit), 0) as total_credit')
            ])
            ->leftJoin('journal_entry_details as jed', 'journal_entries.id', '=', 'jed.journal_entry_id')
            ->where('journal_entries.company_id', $companyId)
            ->groupBy('journal_entries.id')
            ->orderBy('journal_entries.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('financialaccounting.dashboard.financial-overview', compact(
            'totalSales', 'customersBalance', 'cashBalance', 'taxesPayable', 'recentEntries'
        ));
    }

    private function getAccountBalance($companyId, $sessionYear, $accountNamePattern, $isCredit = false)
    {
        $balance = AccountYear::select(DB::raw('SUM(balance) as total_balance'))
            ->whereHas('account', function($query) use ($companyId, $accountNamePattern) {
                $query->where('company_id', $companyId)
                      ->where('name', 'LIKE', $accountNamePattern);
            })
            ->where('session_year_id', $sessionYear)
            ->first();

        $total = $balance->total_balance ?? 0;

        // للحسابات الدائنة مثل الإيرادات والضرائب، نعكس الإشارة
        return $isCredit ? abs($total) : $total;
    }

    public function getFinancialSummary()
    {
        $companyId = getCompanyId();
        $sessionYear = getCurrentYear();

        // حساب إجماليات سريعة
        $summary = [
            'total_assets' => $this->getAccountsByTypeTotal($companyId, $sessionYear, '1%'),
            'total_liabilities' => $this->getAccountsByTypeTotal($companyId, $sessionYear, '2%'),
            'total_equity' => $this->getAccountsByTypeTotal($companyId, $sessionYear, '3%'),
            'total_revenues' => abs($this->getAccountsByTypeTotal($companyId, $sessionYear, '5%')),
            'total_expenses' => $this->getAccountsByTypeTotal($companyId, $sessionYear, '4%'),
        ];

        $summary['net_income'] = $summary['total_revenues'] - $summary['total_expenses'];

        return response()->json($summary);
    }

    private function getAccountsByTypeTotal($companyId, $sessionYear, $pattern)
    {
        return AccountYear::select(DB::raw('SUM(balance) as total'))
            ->whereHas('account', function($query) use ($companyId, $pattern) {
                $query->where('company_id', $companyId)
                      ->where('account_number', 'LIKE', $pattern);
            })
            ->where('session_year_id', $sessionYear)
            ->first()
            ->total ?? 0;
    }
}
