<?php

namespace App\Helpers;

use App\Models\AccountTransaction;
use App\Models\JournalEntry;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\JournalEntryDetail;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;

class AccountTransactionHelper
{


    public static function updateAccountTransactions($model)
    {

        if ($model instanceof JournalEntry) {
            self::handleJournalEntry($model);
        } elseif ($model instanceof Invoice) {
//            self::handleInvoice($model);
        }

        self::updateRunningBalance($model->company_id);

    }



    public static function getSupplierAccountId($supplierId)
    {
        return Supplier::find($supplierId)->account_id ?? null;
    }

    public static function getCustomerAccountId($customerId)
    {
        return \App\Models\Customer::find($customerId)->account_id ?? null;
    }
    public static function getInventoryAccountId($company_id)
    {
        return Account::where('type_account_register', 3)->where('company_id' ,$company_id)->first()->id ?? null;
    }
    public static function createJournalEntryFromInvoice(Invoice $invoice)
    {

        $entryNumber = JournalEntry::generateEntryNumber("INV" , $invoice->company_id );
        Log::info("Creating journal entry for invoice #{$invoice->id} with type {$invoice->invoice_type}");
        $journalEntry = JournalEntry::create([
            'entry_number' => $entryNumber,
            'entry_date' => $invoice->invoice_date,
            'company_id' => $invoice->company_id,
            'branch_id' => $invoice->branch_id,
            'source_type' => 'Invoice',
            'source_id' => $invoice->id,
            'session_year' => $invoice->session_year,
        ]);

        if ($invoice->invoice_type === 'Purchases') {
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => self::getInventoryAccountId($invoice->company_id),
                'debit' => $invoice->subtotal,
                "cost_center_id"=>1,
                'credit' => 0,
                'comment' => 'المخزون - فاتورة مشتريات',
            ]);

            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                "cost_center_id"=>1,
                'account_id' => self::getSupplierAccountId($invoice->supplier_id),
                'debit' => 0,
                'credit' => $invoice->total,
                'comment' => 'المورد - فاتورة مشتريات',
            ]);
        }

        elseif ($invoice->invoice_type === 'Sales') {

            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                "cost_center_id"=>1,
                'account_id' => self::getCustomerAccountId($invoice->customer_id),
                'debit' => $invoice->total,
                'credit' => 0,
                'comment' => 'العميل - فاتورة مبيعات',
            ]);


        }

        self::handleJournalEntry($journalEntry);
    }



    private static function handleJournalEntry(JournalEntry $journalEntry)
    {
        foreach ($journalEntry->details as $detail) {
            $baseNumber = $journalEntry->entry_number;
            $transactionNumber = $baseNumber;
            $counter = 1;
            while (AccountTransaction::where('transaction_number', $transactionNumber)->exists()) {
                $transactionNumber = "{$baseNumber}-" . sprintf('%03d', $counter++);
            }
            AccountTransaction::create([
                'account_id' => $detail->account_id,
                'transaction_number' => $transactionNumber,
                'transaction_date' => $journalEntry->entry_date,
                'debit' => $detail->debit,
                'credit' => $detail->credit,
                'session_year' => getCurrentYear(),
                'company_id' => $journalEntry->company_id,
                'branch_id' => $journalEntry->branch_id,
                'description' => $detail->comment ?? 'Journal Entry #' . $journalEntry->entry_number,
                'journal_entry_id' => $journalEntry->id,
                'source_type' => 'JournalEntry',
                'source_id' => $journalEntry->id,
            ]);
            $account = Account::find($detail->account_id);
            if($account && getCurrentYear()){
                $account->updateOwnBalance(getCurrentYear());
                $account->updateParentBalanceFromChildren(getCurrentYear());
            }
        }
    }


    private static function handleInvoice(Invoice $invoice)
    {
        $accountId = $invoice->customer->account->id ?? $invoice->supplier->account->id; // حسب نوع الفاتورة
        $description = "{$invoice->invoice_type} Invoice #{$invoice->invoice_number}";
        AccountTransaction::create([
            'account_id' => $accountId,
            'transaction_number' => $invoice->invoice_number,
            'session_year' => getCurrentYear(),
            'transaction_date' => $invoice->invoice_date,
            'debit' => in_array($invoice->invoice_type, ['Sales', 'PurchasesReturn']) ? $invoice->total : 0,
            'credit' => in_array($invoice->invoice_type, ['Purchases', 'SalesReturn']) ? $invoice->total : 0,
            'company_id' => $invoice->company_id,
            'branch_id' => $invoice->branch_id,
            'description' => $description,
            'source_type' => 'Invoice',
            'source_id' => $invoice->id,
        ]);
    }


    private static function updateRunningBalance($companyId)
    {
        $transactions = AccountTransaction::where('company_id', $companyId)
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();
        $balance = [];
        foreach ($transactions as $transaction) {
            $accountId = $transaction->account_id;
            if (!isset($balance[$accountId])) {
                $balance[$accountId] = Account::find($accountId)->opening_balance ?? 0;
            }
            $balance[$accountId] += $transaction->debit - $transaction->credit;
            $transaction->update(['balance' => $balance[$accountId]]);
        }
    }



    public static function deleteAccountTransactions($model)
    {
        $sourceType = null;
        $sourceId = $model->id;
        $companyId = $model->company_id;
        if ($model instanceof JournalEntry) {
            $sourceType = 'JournalEntry';
        } elseif ($model instanceof Invoice) {
            $sourceType = 'Invoice';
        }
        if ($sourceType) {
            AccountTransaction::where('source_type', $sourceType)
                ->where('source_id', $sourceId)
                ->delete();
            self::updateRunningBalance($companyId);
        }
    }
}
