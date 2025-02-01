<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'employee_id',
        'branch_id',
        'basic_salary',
        'transportation',
        'food',
        'overtime',
        'deduction',
        'loans',
        'net_salary',
        // Add other fields as needed
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
