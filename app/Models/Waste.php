<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Waste.php
class Waste extends Model
{
    protected $fillable = [
        'waste_number', 'waste_date', 'branch_id', 'reason', 'notes',
        'total_items', 'total_quantity', 'total_cost'
    ];

    public function items()
    {
        return $this->hasMany(WasteItem::class);
    }
}
