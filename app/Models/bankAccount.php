<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bankAccount extends Model
{
    use HasFactory;
    protected $table = 'bank_accounts';
    protected $fillable = ['bankName', 'accountNumber', 'balance'];
    public function transactions()
    {
        return $this->hasMany(Transaction_Acount::class);
    }
}
