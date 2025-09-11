<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\Invoice;
use App\Helpers\AccountTransactionHelper;
use Illuminate\Support\Facades\DB;

class FixUnbalancedJournalEntries extends Command
{
    protected $signature = 'journal:fix-unbalanced';
    protected $description = 'إصلاح القيود اليومية غير المتوازنة';

    public function handle()
    {
        $this->info('بدء عملية إصلاح القيود اليومية غير المتوازنة...');

        // البحث عن القيود غير المتوازنة
        $unbalancedEntries = DB::select("
            SELECT
                je.id,
                je.entry_number,
                SUM(jed.debit) as total_debit,
                SUM(jed.credit) as total_credit,
                (SUM(jed.debit) - SUM(jed.credit)) as difference
            FROM journal_entries je
            LEFT JOIN journal_entry_details jed ON je.id = jed.journal_entry_id
            GROUP BY je.id, je.entry_number
            HAVING ABS(difference) > 0.01
            ORDER BY je.id DESC
        ");

        if (empty($unbalancedEntries)) {
            $this->info('✅ جميع القيود اليومية متوازنة!');
            return 0;
        }

        $this->info('تم العثور على ' . count($unbalancedEntries) . ' قيد غير متوازن');

        $bar = $this->output->createProgressBar(count($unbalancedEntries));
        $bar->start();

        foreach ($unbalancedEntries as $entry) {
            try {
                $this->fixJournalEntry($entry->id, $entry->difference);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nخطأ في إصلاح القيد #{$entry->id}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->info("\n✅ تم الانتهاء من عملية الإصلاح!");

        return 0;
    }

    private function fixJournalEntry($entryId, $difference)
    {
        $journalEntry = JournalEntry::with('details')->find($entryId);

        if (!$journalEntry) {
            throw new \Exception("لم يتم العثور على القيد اليومي");
        }

        // إذا كان القيد يحتوي على قيد واحد فقط، فهو من فاتورة
        if ($journalEntry->details->count() == 1) {
            $this->recreateFromInvoice($journalEntry);
        } else {
            // إضافة قيد تعديل للفرق
            $this->addAdjustmentEntry($journalEntry, $difference);
        }
    }

    private function recreateFromInvoice($journalEntry)
    {
        // محاولة العثور على الفاتورة من رقم القيد
        $entryNumber = $journalEntry->entry_number;

        // استخراج رقم الفاتورة من رقم القيد (مثال: INV-002-0000008)
        if (preg_match('/INV-\d+-(\d+)/', $entryNumber, $matches)) {
            $invoiceNumber = ltrim($matches[1], '0') ?: '1';

            $invoice = Invoice::where('company_id', $journalEntry->company_id)
                ->where('invoice_number', str_pad($invoiceNumber, 7, '0', STR_PAD_LEFT))
                ->first();

            if ($invoice) {
                // حذف القيود القديمة
                $journalEntry->details()->delete();

                // إعادة إنشاء القيود الصحيحة
                $this->recreateJournalEntryDetails($journalEntry, $invoice);

                $this->line("✅ تم إصلاح القيد #{$journalEntry->id} للفاتورة #{$invoice->id}");
                return;
            }
        }

        // إذا لم نجد الفاتورة، أضف قيد تعديل
        $this->addAdjustmentEntry($journalEntry, $journalEntry->details->sum('debit') - $journalEntry->details->sum('credit'));
    }

    private function recreateJournalEntryDetails($journalEntry, $invoice)
    {
        if ($invoice->invoice_type === 'Sales') {
            // العميل - مدين
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => AccountTransactionHelper::getCustomerAccountId($invoice->customer_id),
                'debit' => $invoice->total,
                'credit' => 0,
                'cost_center_id' => 1,
                'comment' => 'العميل - فاتورة مبيعات آجل',
                'session_year' => $journalEntry->session_year,
            ]);

            // المبيعات - دائن
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => AccountTransactionHelper::getSalesAccountId($invoice->company_id),
                'debit' => 0,
                'credit' => $invoice->subtotal,
                'cost_center_id' => 1,
                'comment' => 'مبيعات - فاتورة مبيعات',
                'session_year' => $journalEntry->session_year,
            ]);

            // الضريبة - دائن (إن وجدت)
            if ($invoice->tax > 0) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    'account_id' => AccountTransactionHelper::getTaxAccountId($invoice->company_id),
                    'debit' => 0,
                    'credit' => $invoice->tax,
                    'cost_center_id' => 1,
                    'comment' => 'ضريبة المبيعات',
                    'session_year' => $journalEntry->session_year,
                ]);
            }
        }
        // يمكن إضافة المزيد من أنواع الفواتير هنا
    }

    private function addAdjustmentEntry($journalEntry, $difference)
    {
        if (abs($difference) > 1) {
            throw new \Exception("الفرق كبير جداً: {$difference}");
        }

        $adjustmentAccountId = AccountTransactionHelper::getAdjustmentAccountId($journalEntry->company_id);

        JournalEntryDetail::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $adjustmentAccountId,
            'debit' => $difference < 0 ? abs($difference) : 0,
            'credit' => $difference > 0 ? $difference : 0,
            'cost_center_id' => 1,
            'comment' => 'قيد تعديل توازن',
            'session_year' => $journalEntry->session_year,
        ]);

        $this->line("✅ تم إضافة قيد تعديل للقيد #{$journalEntry->id}, الفرق: {$difference}");
    }
}
