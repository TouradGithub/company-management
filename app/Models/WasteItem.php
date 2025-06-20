<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/WasteItem.php
class WasteItem extends Model
{
    protected $fillable = [
        'waste_id', 'product_code', 'product_name', 'category', 'quantity', 'unit_cost', 'total_cost'
    ];

    public function waste()
    {
        return $this->belongsTo(Waste::class);
    }
}
