<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['account_number', 'name', 'account_type_id', 'parent_id', 'company_id','opening_balance', 'closing_list_type',];

    // العلاقة مع نوع الحساب
    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    // العلاقة مع الحساب الرئيسي
    public function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }
    public function journalEntriesDetails()
    {
        return $this->belongsTo(JournalEntryDetail::class, 'account_id');
    }

    // العلاقة مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'account_id');
    }

    /**
     * Get the current balance details for the account.
     *
     * @return array
     */
    public function getBalanceDetails()
    {
        // الرصيد الافتتاحي
        $openingDebit = $this->opening_balance < 0 ? abs($this->opening_balance) : 0;
        $openingCredit = $this->opening_balance >= 0 ? $this->opening_balance : 0;

        // الحركات الحالية من account_transactions
        $transactions = $this->transactions;
        $currentDebit = $transactions->sum('debit');
        $currentCredit = $transactions->sum('credit');

        // الرصيد الختامي
        $closingBalance = $this->opening_balance + $currentDebit - $currentCredit;

        return $closingBalance;
    }

}
