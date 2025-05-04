<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountYear extends Model
{
    use HasFactory;
    protected $table= 'session_years_company_balance';
    protected $fillable = ['session_year_id', 'company_id','balance','credit','debit', 'account_id'];

    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class , 'session_year_id');
    }
}
