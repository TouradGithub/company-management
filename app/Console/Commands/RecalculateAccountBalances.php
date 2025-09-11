<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\SessionYearsCompanyBalance;
use Illuminate\Support\Facades\DB;

class RecalculateAccountBalances extends Command
{
    protected $signature = 'accounts:recalculate-balances {--company_id=4}';
    protected $description = 'إعادة حساب أرصدة الحسابات من القيود اليومية';

    public function handle()
    {
        $companyId = $this->option('company_id');

        $this->info("إعادة حساب أرصدة الحسابات للشركة رقم: {$companyId}");

        // حذف الأرصدة الحالية
        SessionYearsCompanyBalance::where('company_id', $companyId)->delete();

        // حساب الأرصدة الجديدة من تفاصيل القيود اليومية
        $balances = JournalEntryDetail::select([
                'account_id',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(credit) as total_credit'),
                DB::raw('SUM(debit) - SUM(credit) as balance')
            ])
            ->whereHas('journalEntry', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->where('session_year', 1)
            ->groupBy('account_id')
            ->get();

        foreach ($balances as $balance) {
            SessionYearsCompanyBalance::create([
                'company_id' => $companyId,
                'account_id' => $balance->account_id,
                'session_year_id' => '1',
                'debit' => $balance->total_debit,
                'credit' => $balance->total_credit,
                'balance' => $balance->balance,
            ]);

            $this->info("تم تحديث رصيد الحساب {$balance->account_id}: {$balance->balance}");
        }

        $this->info('تم إعادة حساب جميع الأرصدة بنجاح!');
    }
}
