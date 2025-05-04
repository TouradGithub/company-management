<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
    public function index()
    {
        $assets = [
//            ['name' => 'النقدية', 'value' => cach_register()->sessionBalance->balance],
//            ['name' => 'المدينون', 'value' => credit_and_debit()->total_debit],
//            ['name' => 'المخزون', 'value' => 0],
//            ['name' => ' الأصول', 'value' => ousoul()],
        ];
        $liabilitiesEquity = [
            ['name' => 'الدائنون', 'value' => credit_and_debit()->total_credit],
            ['name' => 'القروض', 'value' => 0],
            ['name' => 'رأس المال', 'value' => openiing_balance_of_all_company()],
            ['name' => 'الأرباح المحتجزة', 'value' => 0],
        ];
        $parentAccounts = Account::where('company_id' , getCompanyId())
            ->where('parent_id', 0)
            ->get();
        //add this accounts to assets
        foreach ($parentAccounts as $parentAccount) {
//            dd($parentAccount->sessionBalance->balance);
            $assets[] = [
                'name' => $parentAccount->name,
                'value' => $parentAccount->sessionBalance->balance
            ];
        }
        return view('financialaccounting.balance-sheet.index', compact('assets', 'liabilitiesEquity'));
    }
}
