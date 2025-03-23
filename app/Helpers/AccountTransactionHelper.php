<?php

namespace App\Helpers;

use App\Models\AccountTransaction;
use App\Models\JournalEntry;
use App\Models\Invoice;
use App\Models\Account;

class AccountTransactionHelper
{


    public static function updateAccountTransactions($model)
    {
        if ($model instanceof JournalEntry) {
            self::handleJournalEntry($model);
        } elseif ($model instanceof Invoice) {
            self::handleInvoice($model);
        }

        self::updateRunningBalance($model->company_id);
    }



    private static function handleJournalEntry(JournalEntry $journalEntry)
    {
        foreach ($journalEntry->details as $detail) {

            AccountTransaction::create([
                'account_id' => $detail->account_id,
                'transaction_number' => $journalEntry->entry_number,
                'transaction_date' => $journalEntry->entry_date,
                'debit' => $detail->debit,
                'credit' => $detail->credit,
                'company_id' => $journalEntry->company_id,
                'branch_id' => $journalEntry->branch_id,
                'description' => $detail->comment ?? 'Journal Entry #' . $journalEntry->entry_number,
                'journal_entry_id' => $journalEntry->id,
                'source_type' => 'JournalEntry',
                'source_id' => $journalEntry->id,
            ]);
        }
    }


    private static function handleInvoice(Invoice $invoice)
    {
        // افترض أن الفاتورة تؤثر على حساب العميل/المورد وحساب آخر (مثل المبيعات/المشتريات)
        $accountId = $invoice->customer_id ?? $invoice->supplier_id; // حسب نوع الفاتورة
        $description = "{$invoice->invoice_type} Invoice #{$invoice->invoice_number}";

        AccountTransaction::create([
            'account_id' => $accountId,
            'transaction_number' => $invoice->invoice_number,
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
}
