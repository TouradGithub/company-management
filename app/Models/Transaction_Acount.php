<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_Acount extends Model
{
    use HasFactory;
    protected $table = 'transaction_accounts';

    protected $fillable = [
        'date',
        'type',
        'amount',
        'description',
        'voucher_number',
        'beneficiary',
    ];

    public function account()
    {
        return $this->belongsTo(bankAccount::class);
    }
}
