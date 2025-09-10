<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefAccount extends Model
{
    use HasFactory;
    protected $table = 'ref_account';
    protected $fillable = ['name', 'type'];
}
