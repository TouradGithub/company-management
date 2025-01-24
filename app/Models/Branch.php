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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
