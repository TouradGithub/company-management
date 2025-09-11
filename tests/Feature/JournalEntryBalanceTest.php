<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Helpers\AccountTransactionHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JournalEntryBalanceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار توازن القيد اليومي لفاتورة مبيعات
     */
    public function test_sales_invoice_journal_entry_is_balanced()
    {
        // إنشاء فاتورة مبيعات تجريبية
        $invoice = Invoice::factory()->create([
            'invoice_type' => 'Sales',
            'subtotal' => 100.00,
            'tax' => 15.00,
            'total' => 115.00,
            'payment_method' => 'credit'
        ]);

        // إنشاء القيد اليومي
        AccountTransactionHelper::createJournalEntryFromInvoice($invoice);

        // العثور على القيد اليومي المنشأ
        $journalEntry = JournalEntry::where('entry_number', 'LIKE', 'INV-%')->latest()->first();

        $this->assertNotNull($journalEntry, 'Journal entry should be created');

        // حساب إجمالي المدين والدائن
        $totalDebit = $journalEntry->details->sum('debit');
        $totalCredit = $journalEntry->details->sum('credit');

        // التحقق من التوازن
        $this->assertEquals($totalDebit, $totalCredit,
            "Journal entry should be balanced. Debit: {$totalDebit}, Credit: {$totalCredit}");

        // التحقق من وجود القيود المطلوبة
        $this->assertTrue($journalEntry->details->count() >= 2,
            'Journal entry should have at least 2 details (debit and credit)');
    }

    /**
     * اختبار توازن القيد اليومي لفاتورة مشتريات
     */
    public function test_purchases_invoice_journal_entry_is_balanced()
    {
        // إنشاء فاتورة مشتريات تجريبية
        $invoice = Invoice::factory()->create([
            'invoice_type' => 'Purchases',
            'subtotal' => 200.00,
            'tax' => 30.00,
            'total' => 230.00,
            'payment_method' => 'cash'
        ]);

        // إنشاء القيد اليومي
        AccountTransactionHelper::createJournalEntryFromInvoice($invoice);

        // العثور على القيد اليومي المنشأ
        $journalEntry = JournalEntry::where('entry_number', 'LIKE', 'INV-%')->latest()->first();

        $this->assertNotNull($journalEntry, 'Journal entry should be created');

        // حساب إجمالي المدين والدائن
        $totalDebit = $journalEntry->details->sum('debit');
        $totalCredit = $journalEntry->details->sum('credit');

        // التحقق من التوازن
        $this->assertEquals($totalDebit, $totalCredit,
            "Journal entry should be balanced. Debit: {$totalDebit}, Credit: {$totalCredit}");
    }

    /**
     * اختبار فحص جميع القيود اليومية الموجودة للتوازن
     */
    public function test_all_existing_journal_entries_are_balanced()
    {
        $journalEntries = JournalEntry::with('details')->get();

        foreach ($journalEntries as $journalEntry) {
            $totalDebit = $journalEntry->details->sum('debit');
            $totalCredit = $journalEntry->details->sum('credit');

            $this->assertEquals($totalDebit, $totalCredit,
                "Journal Entry #{$journalEntry->id} should be balanced. Debit: {$totalDebit}, Credit: {$totalCredit}");
        }
    }
}
