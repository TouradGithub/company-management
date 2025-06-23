<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vouchersContainer extends Model
{
    use HasFactory;
    protected $fillable = [
        'paymentMethod',
        'number',
        'type',
        'date',
        'amount',
        'fromTo',
        'description'
    ];
}
