<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryInvoice extends Model
{
    use HasFactory;
    protected $table = 'categorie_invoices';
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'company_id',
    ];
    public function products()
    {
        return $this->hasMany(Product::class , 'category_id');
    }

    public function children()
    {
        return $this->hasMany(CategoryInvoice::class , 'parent_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class , 'company_id');
    }

}
