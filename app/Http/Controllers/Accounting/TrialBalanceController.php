<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\JournalEntryDetail;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    public function index()
    {
        return view('financialaccounting.trial-balance.trial-balance');
    }
    public function getTrialBalance(Request $request)
    {
        // التحقق من إدخال التواريخ
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$fromDate || !$toDate) {
            return response()->json(['error' => 'يجب تحديد تاريخ البداية والنهاية'], 400);
        }

        // جلب الحسابات مع الحركات المالية المرتبطة بها عبر JournalEntry
        $accounts = Account::where('company_id', auth()->user()->model_id)->get();

        $result = $accounts->map(function ($account) use ($fromDate, $toDate) {
            // الأرصدة الافتتاحية قبل الفترة المحددة
            $openingDebit = JournalEntryDetail::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($fromDate) {
                    $query->where('entry_date', '<', $fromDate);
                })
                ->sum('debit');

            $openingCredit = JournalEntryDetail::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($fromDate) {
                    $query->where('entry_date', '<', $fromDate);
                })
                ->sum('credit');

            // الحركات خلال الفترة المحددة
            $currentDebit = JournalEntryDetail::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('entry_date', [$fromDate, $toDate]);
                })
                ->sum('debit');

            $currentCredit = JournalEntryDetail::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($query) use ($fromDate, $toDate) {
                    $query->whereBetween('entry_date', [$fromDate, $toDate]);
                })
                ->sum('credit');

            return [
                'account_number' => $account->account_number,
                'account_name' => $account->name,
                'opening_debit' => $openingDebit,
                'opening_credit' => $openingCredit,
                'current_debit' => $currentDebit,
                'current_credit' => $currentCredit,
                'closing_debit' => $openingDebit + $currentDebit,
                'closing_credit' => $openingCredit + $currentCredit,
            ];
        });

        return response()->json($result);
    }

}
