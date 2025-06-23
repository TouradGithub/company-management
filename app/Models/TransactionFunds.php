<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionFunds extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'amount',
        'funds_id',
        'description',
        'voucher_number',
        'beneficiary',
    ];

    public function fund()
    {
        return $this->belongsTo(funds::class);
    }

}
