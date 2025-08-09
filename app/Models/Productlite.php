<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productlite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'category_id', 'brand_id', 'barcode',
        'status', 'description', 'purchase_price', 'selling_price',
        'discount_price', 'tax_rate', 'price_unit', 'has_discount',
        'main_image', 'additional_images', 'stock', 'stock_alert',
        'warehouse_id','transfers', 'damages'
    ];
    protected $table ='product_inventories';
    protected $casts = [
        'additional_images' => 'array',
        'has_discount' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Branch::class, 'warehouse_id');
    }
    public function brand()
    {
        return $this->belongsTo(brand::class);
    }


}
