<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'branch_id',
        'job',
        'basic_salary',
        'housing_allowance',
        'food_allowance',
        'transportation_allowance',
        'hire_date',
        'iqamaNumber',
    ];
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    public function deducations()
    {
        return $this->hasMany(Deduction::class, 'employee_id');
    }
    public function overtimes()
    {
        return $this->hasMany(Overtime::class, 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id'); // Use the foreign key that references the branches table
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function totalLoans()
    {
        return $this->loans->sum('amount');
    }

}
