<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index()
    {
        $assets = [];
        $liabilities = [];
        $equity = [];

        $companyId = getCompanyId();
        $currentYear = getCurrentYear($companyId);

        $parentAccounts = Account::where('accounts.company_id', $companyId)
            ->where('accounts.parent_id', 0)
            ->leftJoin('session_years_company_balance as syc', function($join) use ($currentYear, $companyId) {
                $join->on('accounts.id', '=', 'syc.account_id')
                     ->where('syc.session_year_id', '=', $currentYear)
                     ->where('syc.company_id', '=', $companyId);
            })
            ->select('accounts.*', 'syc.balance')
            ->get();


        foreach ($parentAccounts as $parentAccount) {
            $balance = $parentAccount->balance ?? 0;

            $refId = $parentAccount->ref_account_id;
            if ($refId == 1) {
                // الأصول
                $assets[] = [
                    'name' => $parentAccount->name,
                    'value' => abs($balance) // الأصول دائماً موجبة
                ];
            } elseif ($refId == 2) {
                // الخصوم
                $liabilities[] = [
                    'name' => $parentAccount->name,
                    'value' => abs($balance) // الخصوم تظهر كقيم موجبة
                ];
            } elseif ($refId == 3) {
                // حقوق الملكية
                $equity[] = [
                    'name' => $parentAccount->name,
                    'value' => abs($balance)
                ];
            } elseif ($refId == 4) {
                // المصروفات تقلل من حقوق الملكية
                $equity[] = [
                    'name' => $parentAccount->name . ' (مصروفات)',
                    'value' => -abs($balance) // المصروفات تقلل حقوق الملكية
                ];
            } elseif ($refId == 5) {
                // الإيرادات تزيد حقوق الملكية
                $equity[] = [
                    'name' => $parentAccount->name . ' (إيرادات)',
                    'value' => abs($balance) // الإيرادات تزيد حقوق الملكية
                ];
            }
        }
        return view('financialaccounting.balance-sheet.index', compact('assets', 'liabilities', 'equity'));
    }
}
