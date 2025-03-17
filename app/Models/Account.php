<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $fillable = ['account_number', 'name', 'account_type_id', 'parent_id', 'company_id','opening_balance'];

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
    public function journalEntriesDetails()
    {
        return $this->belongsTo(JournalEntryDetail::class, 'account_id');
    }

    // العلاقة مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
