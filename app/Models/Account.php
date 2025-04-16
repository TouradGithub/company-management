<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['account_number', 'name', 'account_type_id', 'parent_id', 'company_id','opening_balance', 'closing_list_type','islast'];


    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

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


    public function getBalanceDetails()
    {

        $transactions = $this->transactions;
        $currentDebit = $transactions->sum('debit');
        $currentCredit = $transactions->sum('credit');

        $closingBalance = $this->opening_balance + $currentDebit - $currentCredit;

        return $closingBalance;
    }

    public function getCreditDebit()
    {

        $transactions = $this->transactions;
        $currentDebit = $transactions->sum('debit');
        $currentCredit = $transactions->sum('credit');

        $closingBalance =  $currentDebit - $currentCredit;

        return $closingBalance;
    }
}
