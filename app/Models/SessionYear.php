<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionYear extends Model
{
    use HasFactory;
    protected $fillable = ['name','company_id', 'is_current'];

    public static function current()
    {
        return self::where('is_current', true)->first();
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class , 'session_year');
    }
    public function entryJournals()
    {
        return $this->hasMany(JournalEntry::class , 'session_year');
    }

}
