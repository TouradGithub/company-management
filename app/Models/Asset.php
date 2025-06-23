<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'assetname','category_management_id','purchasedate','originalcost','sold','sale_date','sale_amount','gain_or_loss'
    ];

    public function categoryManagment()
    {
        return $this->belongsTo(CategoryManagement::class, 'category_management_id');
    }
}
