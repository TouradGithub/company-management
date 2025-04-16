<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $table = 'invoice_items';
    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_name', // أضف هذا الحقل هنا
        'quantity',
        'price',
        'total',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
}
