<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class funds extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'balance',
        'cashier',
        'location'
    ];

    public function transactions()
    {
        return $this->hasMany(TransactionFunds::class);
    }
}
