<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'contact_info', 'branch_id', 'company_id' , 'credit_limit'];
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }
}
