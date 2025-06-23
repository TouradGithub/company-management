<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bills extends Model
{
    use HasFactory;
    protected $fillable = [
        'number',
        'type',
        'date',
        'amount',
        'customer',
        'description',
        'delivery_type_id',
        'payment_method_id',
    ];

    public function items()
    {
        return $this->hasMany(items::class);
    }
    public function deliveryType()
    {
        return $this->belongsTo(DeliveryType::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}
