<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'branch_id',
        'fixed_amount',
        'percentage_of_salary',
        'overtime_hours',
        'overtime_value',
        'basic_salary',
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
