<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index()
    {

        // أنواع الحسابات: 1=أصل، 2=خصم، 3=حقوق ملكية (تأكد من القيم الصحيحة في قاعدة البيانات)
        $assets = [];
        $liabilities = [];
        $equity = [];
        $parentAccounts = Account::where('company_id', getCompanyId())
            ->where('parent_id', 0)
            ->with(['sessionBalance', 'refAccount'])
            ->get();

        foreach ($parentAccounts as $parentAccount) {
            $balance = $parentAccount->sessionBalance->balance ?? 0;
            $type = $parentAccount->refAccount->type ?? null;
            if ($type === 'أصل') {
                $assets[] = [
                    'name' => $parentAccount->name,
                    'value' => $balance
                ];
            } elseif ($type === 'خصم') {
                $liabilities[] = [
                    'name' => $parentAccount->name,
                    'value' => $balance
                ];
            } elseif ($type === 'حقوق ملكية') {
                $equity[] = [
                    'name' => $parentAccount->name,
                    'value' => $balance
                ];
            }
        }
        return view('financialaccounting.balance-sheet.index', compact('assets', 'liabilities', 'equity'));
    }
}
