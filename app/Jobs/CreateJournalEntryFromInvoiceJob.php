<?php

namespace App\Jobs;

use App\Helpers\AccountTransactionHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateJournalEntryFromInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $invoice;
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // التحقق من وجود session_year قبل المعالجة
        if (empty($this->invoice->session_year)) {
            // الحصول على session_year الافتراضي للشركة
            $activeSessionYear = \App\Models\SessionYear::where('status', 1)->first();
            if ($activeSessionYear) {
                $this->invoice->update(['session_year' => $activeSessionYear->id]);
                // إعادة تحميل الفاتورة لضمان الحصول على القيمة المحدثة
                $this->invoice->refresh();
            } else {
                Log::error("No active session year found for invoice: {$this->invoice->id}");
                return;
            }
        }

        try {
            Log::info("Starting journal entry creation for invoice: {$this->invoice->id} with session_year: {$this->invoice->session_year}");
            AccountTransactionHelper::createJournalEntryFromInvoice($this->invoice);
            Log::info("Journal entry created successfully for invoice: {$this->invoice->id}");
        } catch (\Exception $e) {
            Log::error("Failed to create journal entry for invoice {$this->invoice->id}: " . $e->getMessage());
            throw $e;
        }
    }
}
