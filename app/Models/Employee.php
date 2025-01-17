<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'job',
        'basic_salary',
        'housing_allowance',
        'food_allowance',
        'transportation_allowance',
    ];
}
