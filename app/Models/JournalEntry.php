<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'entry_number',
        'entry_date',
        'branch_id',
        'company_id',
        'created_by',
        'session_year',
    ];



    public static function generateEntryNumber($companyId)
    {

        $lastEntry = self::where('company_id', $companyId)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextEntryNumber = $lastEntry ? intval($lastEntry->entry_number) + 1 : 1;

        return str_pad($nextEntryNumber, 7, '0', STR_PAD_LEFT);
    }



public function details()
    {
        return $this->hasMany(JournalEntryDetail::class, 'journal_entry_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
