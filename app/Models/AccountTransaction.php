<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use HasFactory;



    protected $table = 'account_transactions';



    protected $fillable = [
        'account_id',
        'transaction_number',
        'transaction_date',
        'debit',
        'credit',
        'balance',
        'company_id',
        'branch_id',
        'description',
        'journal_entry_id',
        'source_type',
        'source_id',
    ];



    protected $casts = [
        'transaction_date' => 'date',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
        'source_type' => 'string',
    ];



    public function account()
    {
        return $this->belongsTo(Account::class);
    }


    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }


    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'source_id')->where('source_type', 'Invoice');
    }



    public function scopeBySourceType($query, $sourceType)
    {
        return $query->where('source_type', $sourceType);
    }



    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
