<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'name_admin_company',
    ];


    public function leaves()
    {
        return $this->hasMany(Leave::class, 'branch_id');
    }

    public function deducations()
    {
        return $this->hasMany(Deduction::class, 'branch_id');
    }
    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'branch_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function  payrolls()
    {
        return $this->hasMany(Payroll::class, 'branch_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'branch_id');
    }

}
