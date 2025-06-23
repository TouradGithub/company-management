<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    protected $fillable = [
        'transfer_id', 'product_code', 'product_name', 'category',
        'quantity', 'unit_cost', 'total_cost'
    ];

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }
}
