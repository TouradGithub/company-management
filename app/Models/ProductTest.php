<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTest extends Model
{
    use HasFactory;
    protected $table = 'product_tests';
    protected $fillable = [
        'name',
        'price'
    ];
}
