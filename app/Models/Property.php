<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_name',
        'tenant_name',
        'landlord_name',
        'property_address',
        'start_date',
        'end_date',
        'rent_amount',
        'payment_cycle',
        'contract_details'
    ];
}
