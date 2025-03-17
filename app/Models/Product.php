<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'stock',
        'description',
        'price',
        'cost',
        'min_price',
        'company_id',
        'created_by',
        'branch_id',
        'tax',
        'image'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class , 'company_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryInvoice::class , 'category_id');
    }

}
