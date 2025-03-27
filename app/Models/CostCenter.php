<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'code' , 'company_id'];
    public function journalEntryDetails()
    {
        return $this->hasMany(JournalEntryDetail::class, 'cost_center_id');
    }

}
