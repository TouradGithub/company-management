<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Company extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'start_date',
        'end_date',
        'status',
    ];
    public function branches()
    {
        return $this->hasMany(Branch::class, 'company_id');
    }
}
