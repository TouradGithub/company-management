<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryManagement  extends Model
{
    use HasFactory;
    protected $fillable = ['name','categorycode','categorylifespan','categoryrate'];
    protected $table = 'category_management';
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
