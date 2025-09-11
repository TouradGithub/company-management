<?php

namespace App\Helpers;

use App\Models\AccountTransaction;
use App\Models\JournalEntry;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\AccountYear;
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
        $customer = \App\Models\Customer::find($customerId);
        if ($customer && $customer->account_id) {
            return $customer->account_id;
        }

        // إذا لم يكن للعميل حساب محدد، استخدم حساب العملاء العام
        $companyId = $customer ? $customer->company_id : 2; // افتراضي
        return Account::where('company_id', $companyId)
            ->where('name', 'LIKE', '%عميل%')
            ->first()->id ?? null;
    }
    public static function getInventoryAccountId($company_id)
    {
        return Account::where('type_account_register', 3)->where('company_id' ,$company_id)->first()->id ?? null;
    }

    public static function getCashAccountId($company_id)
    {
        $cashAccount = Account::where('company_id', $company_id)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%نقدي%')
                      ->orWhere('name', 'LIKE', '%كاش%')
                      ->orWhere('name', 'LIKE', '%cash%')
                      ->orWhere('name', 'LIKE', '%صندوق%');
            })
            ->where('islast', 1)
            ->first();

        return $cashAccount ? $cashAccount->id : null;
    }

    public static function getBankAccountId($company_id, $bank_account_id = null)
    {
        if ($bank_account_id) {
            return $bank_account_id;
        }

        return Account::where('name', 'LIKE', '%بنك%')
            ->orWhere('name', 'LIKE', '%bank%')
            ->where('company_id', $company_id)
            ->first()->id ?? null;
    }

    public static function getSalesAccountId($company_id)
    {
        // البحث عن حساب المبيعات في الإيرادات
        $salesAccount = Account::where('company_id', $company_id)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%مبيعات%')
                      ->orWhere('name', 'LIKE', '%إيرادات%')
                      ->orWhere('name', 'LIKE', '%النشاط الرئيسي%')
                      ->orWhere('account_number', 'LIKE', '151%'); // رقم حساب إيرادات النشاط الرئيسي
            })
            ->where('islast', 1)
            ->first();

        if ($salesAccount) {
            return $salesAccount->id;
        }

        // إنشاء حساب المبيعات إذا لم يكن موجود
        $salesAccount = Account::create([
            'account_number' => '151001',
            'name' => 'إيرادات النشاط الرئيسي',
            'account_type_id' => 5, // إيرادات
            'ref_account_id' => 5,
            'parent_id' => 0,
            'company_id' => $company_id,
            'opening_balance' => 0,
            'type_account_register' => null,
            'islast' => 1
        ]);

        return $salesAccount->id;
    }    public static function getTaxAccountId($company_id)
    {
        // البحث عن حساب الضريبة أو إنشاء واحد افتراضي
        $taxAccount = Account::where('company_id', $company_id)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%ضريبة%')
                      ->orWhere('name', 'LIKE', '%tax%')
                      ->orWhere('name', 'LIKE', '%VAT%');
            })
            ->first();

        if (!$taxAccount) {
            // إنشاء حساب ضريبة افتراضي
            $taxAccount = Account::create([
                'account_number' => '12103',
                'name' => 'ضريبة القيمة المضافة',
                'account_type_id' => 2, // خصوم
                'ref_account_id' => 2,
                'parent_id' => 14, // الخصوم المتداولة
                'company_id' => $company_id,
                'opening_balance' => 0,
                'type_account_register' => null,
                'islast' => 1
            ]);
        }

        return $taxAccount->id;
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
            // قيد المشتريات - دائماً مدين
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => self::getInventoryAccountId($invoice->company_id),
                'debit' => $invoice->subtotal,
                "cost_center_id"=>1,
                'credit' => 0,
                'comment' => 'المخزون - فاتورة مشتريات',
                'session_year' => $invoice->session_year,
            ]);

            // قيد الضريبة إن وجدت
            if ($invoice->tax > 0) {
                $taxAccountId = self::getTaxAccountId($invoice->company_id);
                if ($taxAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $taxAccountId,
                        'debit' => $invoice->tax,
                        "cost_center_id"=>1,
                        'credit' => 0,
                        'comment' => 'ضريبة المشتريات',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }

            // حسب طريقة الدفع
            $paymentMethod = $invoice->payment_method ?? 'credit';

            if ($paymentMethod === 'cash') {
                // نقدي - من ح المشتريات إلى النقدية
                $cashAccountId = self::getCashAccountId($invoice->company_id);
                if ($cashAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $cashAccountId,
                        'debit' => 0,
                        'credit' => $invoice->total,
                        'comment' => 'النقدية - فاتورة مشتريات نقدي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } elseif ($paymentMethod === 'bank') {
                // بنكي - من ح المشتريات إلى البنك
                $bankAccountId = self::getBankAccountId($invoice->company_id, $invoice->bank_account_id);
                if ($bankAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $bankAccountId,
                        'debit' => 0,
                        'credit' => $invoice->total,
                        'comment' => 'البنك - فاتورة مشتريات بنكي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } else {
                // آجل - من ح المشتريات إلى المورد
                $supplierAccountId = self::getSupplierAccountId($invoice->supplier_id);
                if ($supplierAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $supplierAccountId,
                        'debit' => 0,
                        'credit' => $invoice->total,
                        'comment' => 'المورد - فاتورة مشتريات آجل',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }
        }

        elseif ($invoice->invoice_type === 'Sales') {
            // حسب طريقة الدفع - الجانب المدين
            $paymentMethod = $invoice->payment_method ?? 'credit';

            if ($paymentMethod === 'cash') {
                // نقدي - النقدية مدين
                $cashAccountId = self::getCashAccountId($invoice->company_id);
                if ($cashAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $cashAccountId,
                        'debit' => $invoice->total,
                        'credit' => 0,
                        'comment' => 'النقدية - فاتورة مبيعات نقدي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } elseif ($paymentMethod === 'bank') {
                // بنكي - البنك مدين
                $bankAccountId = self::getBankAccountId($invoice->company_id, $invoice->bank_account_id);
                if ($bankAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $bankAccountId,
                        'debit' => $invoice->total,
                        'credit' => 0,
                        'comment' => 'البنك - فاتورة مبيعات بنكي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } else {
                // آجل - العميل مدين
                $customerAccountId = self::getCustomerAccountId($invoice->customer_id);
                if ($customerAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $customerAccountId,
                        'debit' => $invoice->total,
                        'credit' => 0,
                        'comment' => 'العميل - فاتورة مبيعات آجل',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }

            // قيد المبيعات - دائماً دائن
            $salesAccountId = self::getSalesAccountId($invoice->company_id);
            Log::info("Sales Account ID for company {$invoice->company_id}: {$salesAccountId}");
            if ($salesAccountId) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    "cost_center_id"=>1,
                    'account_id' => $salesAccountId,
                    'debit' => 0,
                    'credit' => $invoice->subtotal, // المبلغ قبل الضريبة
                    'comment' => 'مبيعات - فاتورة مبيعات',
                    'session_year' => $invoice->session_year,
                ]);
                Log::info("Created sales journal entry detail for invoice {$invoice->id}");
            } else {
                Log::error("Sales account ID is null for company {$invoice->company_id}");
            }

            // قيد الضريبة إن وجدت - دائماً دائن
            if ($invoice->tax > 0) {
                $taxAccountId = self::getTaxAccountId($invoice->company_id);
                Log::info("Tax Account ID for company {$invoice->company_id}: {$taxAccountId}, Tax Amount: {$invoice->tax}");
                if ($taxAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $taxAccountId,
                        'debit' => 0,
                        'credit' => $invoice->tax,
                        'comment' => 'ضريبة المبيعات',
                        'session_year' => $invoice->session_year,
                    ]);
                    Log::info("Created tax journal entry detail for invoice {$invoice->id}");
                } else {
                    Log::error("Tax account ID is null for company {$invoice->company_id}");
                }
            } else {
                Log::info("No tax amount for invoice {$invoice->id}");
            }
        }

        elseif ($invoice->invoice_type === 'SalesReturn') {
            // مرتجع المبيعات - عكس قيود المبيعات

            // حسب طريقة الدفع - الجانب الدائن (عكس المبيعات)
            $paymentMethod = $invoice->payment_method ?? 'credit';

            if ($paymentMethod === 'cash') {
                // نقدي - النقدية دائن
                $cashAccountId = self::getCashAccountId($invoice->company_id);
                if ($cashAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $cashAccountId,
                        'debit' => 0,
                        'credit' => $invoice->total,
                        'comment' => 'النقدية - مرتجع مبيعات نقدي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } elseif ($paymentMethod === 'bank') {
                // بنكي - البنك دائن
                $bankAccountId = self::getBankAccountId($invoice->company_id, $invoice->bank_account_id);
                if ($bankAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $bankAccountId,
                        'debit' => 0,
                        'credit' => $invoice->total,
                        'comment' => 'البنك - مرتجع مبيعات بنكي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } else {
                // آجل - العميل دائن
                $customerAccountId = self::getCustomerAccountId($invoice->customer_id);
                if ($customerAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $customerAccountId,
                        'debit' => 0,
                        'credit' => $invoice->total,
                        'comment' => 'العميل - مرتجع مبيعات آجل',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }

            // قيد المبيعات - مدين (عكس المبيعات)
            $salesAccountId = self::getSalesAccountId($invoice->company_id);
            if ($salesAccountId) {
                JournalEntryDetail::create([
                    'journal_entry_id' => $journalEntry->id,
                    "cost_center_id"=>1,
                    'account_id' => $salesAccountId,
                    'debit' => $invoice->subtotal,
                    'credit' => 0,
                    'comment' => 'مبيعات - مرتجع مبيعات',
                    'session_year' => $invoice->session_year,
                ]);
            }

            // قيد الضريبة إن وجدت - مدين (عكس المبيعات)
            if ($invoice->tax > 0) {
                $taxAccountId = self::getTaxAccountId($invoice->company_id);
                if ($taxAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $taxAccountId,
                        'debit' => $invoice->tax,
                        'credit' => 0,
                        'comment' => 'ضريبة مرتجع المبيعات',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }
        }

        elseif ($invoice->invoice_type === 'PurchasesReturn') {
            // مرتجع المشتريات - عكس قيود المشتريات

            // قيد المشتريات - دائن (عكس المشتريات)
            JournalEntryDetail::create([
                'journal_entry_id' => $journalEntry->id,
                'account_id' => self::getInventoryAccountId($invoice->company_id),
                'debit' => 0,
                'credit' => $invoice->subtotal,
                "cost_center_id"=>1,
                'comment' => 'المخزون - مرتجع مشتريات',
                'session_year' => $invoice->session_year,
            ]);

            // قيد الضريبة إن وجدت - دائن (عكس المشتريات)
            if ($invoice->tax > 0) {
                $taxAccountId = self::getTaxAccountId($invoice->company_id);
                if ($taxAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        'account_id' => $taxAccountId,
                        'debit' => 0,
                        'credit' => $invoice->tax,
                        "cost_center_id"=>1,
                        'comment' => 'ضريبة مرتجع المشتريات',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }

            // حسب طريقة الدفع - مدين (عكس المشتريات)
            $paymentMethod = $invoice->payment_method ?? 'credit';

            if ($paymentMethod === 'cash') {
                // نقدي
                $cashAccountId = self::getCashAccountId($invoice->company_id);
                if ($cashAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $cashAccountId,
                        'debit' => $invoice->total,
                        'credit' => 0,
                        'comment' => 'النقدية - مرتجع مشتريات نقدي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } elseif ($paymentMethod === 'bank') {
                // بنكي
                $bankAccountId = self::getBankAccountId($invoice->company_id, $invoice->bank_account_id);
                if ($bankAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $bankAccountId,
                        'debit' => $invoice->total,
                        'credit' => 0,
                        'comment' => 'البنك - مرتجع مشتريات بنكي',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            } else {
                // آجل - المورد
                $supplierAccountId = self::getSupplierAccountId($invoice->supplier_id);
                if ($supplierAccountId) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $journalEntry->id,
                        "cost_center_id"=>1,
                        'account_id' => $supplierAccountId,
                        'debit' => $invoice->total,
                        'credit' => 0,
                        'comment' => 'المورد - مرتجع مشتريات آجل',
                        'session_year' => $invoice->session_year,
                    ]);
                }
            }
        }

        // التحقق من توازن القيد اليومي
        self::validateJournalEntryBalance($journalEntry);

        // تحديث أرصدة الحسابات في جدول AccountYear وجدول session_years_company_balance
        self::updateAccountBalances($journalEntry);

        self::handleJournalEntry($journalEntry);

        // التحقق من توازن القيد اليومي
        self::validateJournalEntryBalance($journalEntry);

        return $journalEntry;
    }

    /**
     * التحقق من توازن القيد اليومي
     */
    public static function validateJournalEntryBalance(JournalEntry $journalEntry)
    {
        $details = JournalEntryDetail::where('journal_entry_id', $journalEntry->id)->get();

        $totalDebit = $details->sum('debit');
        $totalCredit = $details->sum('credit');
        $difference = $totalDebit - $totalCredit;

        if (abs($difference) > 0.01) { // تسامح صغير للأرقام العشرية
            Log::error("Journal Entry #{$journalEntry->id} is not balanced: Debit={$totalDebit}, Credit={$totalCredit}, Difference={$difference}");
            throw new \Exception("القيد اليومي غير متوازن: الفرق = {$difference}");
        } else {
            Log::info("Journal Entry #{$journalEntry->id} is balanced: Debit={$totalDebit}, Credit={$totalCredit}");
        }

        // تحديث أرصدة الحسابات
        self::updateAccountBalances($journalEntry);
    }

    /**
     * تحديث أرصدة الحسابات في جدول session_years_company_balance
     */
    private static function updateAccountBalances(JournalEntry $journalEntry)
    {
        $details = $journalEntry->details;

        foreach ($details as $detail) {
            $account = Account::find($detail->account_id);
            if ($account && $account->islast == 1) {
                // تحديث رصيد الحساب الفرعي
                $account->updateOwnBalance($journalEntry->session_year);

                // تحديث أرصدة الحسابات الأب
                $account->updateParentBalanceFromChildren($journalEntry->session_year);

                // تحديث مباشر في جدول session_years_company_balance
                self::updateSessionYearBalance($account, $journalEntry, $detail);
            }
        }

        Log::info("Account balances updated for journal entry ID: {$journalEntry->id}");
    }

    /**
     * تحديث رصيد الحساب في جدول session_years_company_balance مباشرة
     */
    private static function updateSessionYearBalance(Account $account, JournalEntry $journalEntry, $detail)
    {
        $existingBalance = \DB::table('session_years_company_balance')
            ->where('account_id', $account->id)
            ->where('company_id', $journalEntry->company_id)
            ->where('session_year_id', $journalEntry->session_year)
            ->first();

        if ($existingBalance) {
            // تحديث الرصيد الموجود
            \DB::table('session_years_company_balance')
                ->where('id', $existingBalance->id)
                ->update([
                    'balance' => $existingBalance->balance + $detail->debit - $detail->credit,
                    'debit' => $existingBalance->debit + $detail->debit,
                    'credit' => $existingBalance->credit + $detail->credit,
                    'updated_at' => now()
                ]);
        } else {
            // إنشاء سجل جديد
            \DB::table('session_years_company_balance')->insert([
                'account_id' => $account->id,
                'company_id' => $journalEntry->company_id,
                'session_year_id' => $journalEntry->session_year,
                'balance' => $detail->debit - $detail->credit + ($account->opening_balance ?? 0),
                'debit' => $detail->debit,
                'credit' => $detail->credit,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
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
                'session_year' => $detail->session_year,
                'company_id' => $journalEntry->company_id,
                'branch_id' => $journalEntry->branch_id,
                'description' => $detail->comment ?? 'Journal Entry #' . $journalEntry->entry_number,
                'journal_entry_id' => $journalEntry->id,
                'source_type' => 'JournalEntry',
                'source_id' => $journalEntry->id,
            ]);
            $account = Account::find($detail->account_id);
            if($account){
                $sessionYear = $journalEntry->session_year;
                $account->updateOwnBalance($sessionYear);
                $account->updateParentBalanceFromChildren($sessionYear);
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
            'session_year' =>$invoice->session_year,
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
