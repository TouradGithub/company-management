<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrialBalanceController extends Controller
{
    public function index()
    {
        $branches = Branch::where('company_id', getCompanyId())->get();
        return view('financialaccounting.trial-balance.trial-balance' , compact('branches'));
    }
    public function getTrialBalance(Request $request)
    {

        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $branchId = $request->input('branch_id');

        if (!$fromDate || !$toDate) {
            return response()->json(['error' => 'يجب تحديد تاريخ البداية والنهاية'], 400);
        }

        $accounts = Account::where('company_id', getCompanyId())
            ->with(['transactions' => function ($query) use ($fromDate, $toDate , $branchId) {
                $query->where('branch_id', $branchId)
                    ->whereBetween('transaction_date', [$fromDate, $toDate]);
            }])
            ->get();

        $result = $accounts->map(function ($account) use ($fromDate, $toDate) {

            $openingDebit = $account->opening_balance < 0 ? abs($account->opening_balance) : 0;
            $openingCredit = $account->opening_balance >= 0 ? $account->opening_balance : 0;

            $transactions = $account->transactions;
            $currentDebit = 0;
            $currentCredit= 0;

            if($account->getCreditDebit()>= 0 ){
                $currentDebit = $account->getCreditDebit();
            }else{

                $currentCredit = abs($account->getCreditDebit());
            }


            $closingBalance = $account->opening_balance + $currentDebit - $currentCredit;
            $closingDebit = $closingBalance < 0 ? abs($closingBalance) : 0;

            $closingCredit = $closingBalance >= 0 ? $closingBalance : 0;

            return [
                'account_number' => $account->account_number,
                'account_name' => $account->name,
                'opening_debit' => $openingDebit,
                'opening_credit' => $openingCredit,
                'current_debit' => $currentDebit,
                'current_credit' => $currentCredit,
                'closing_debit' => $closingDebit,
                'closing_credit' => $closingCredit,
                'closing_balance' => $closingBalance,
            ];
        });

        return response()->json($result);
    }
}
