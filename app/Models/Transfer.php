<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'transfer_number', 'transfer_date', 'from_branch_id', 'to_branch_id',
        'notes', 'total_items', 'total_quantity', 'total_cost'
    ];

    public function items()
    {
        return $this->hasMany(TransferItem::class);
    }
}

