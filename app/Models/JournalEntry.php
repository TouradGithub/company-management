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
        'session_year',
    ];
    public function sessionYear()
    {
        return $this->belongsTo(SessionYear::class , 'session_year');
    }


    public static function generateEntryNumber($journalCode, $companyId)
    {
        // Get the last entry for this company
        $lastEntry = self::where('company_id', $companyId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastEntry && $lastEntry->entry_number) {
            // Extract parts from format JRN-002-0000001
            preg_match('/([A-Z]+)-(\d+)$/', $lastEntry->entry_number, $matches);
            $baseNumber = isset($matches[2]) ? intval($matches[2]) + 1 : 2; // Increment middle part
            $counter = isset($matches[3]) ? intval($matches[3]) : 0;
        } else {
            $baseNumber = 2;
            $counter = 1;
        }

        $newEntryNumber = sprintf('%s-%03d%07d',
            $journalCode,
            $baseNumber,
            $counter
        );

        // Check for uniqueness and increment counter if needed
        while (self::where('entry_number', $newEntryNumber)->exists()) {
            $counter++;
            $newEntryNumber = sprintf('%s-%03d-%07d',
                $journalCode,
                $baseNumber,
                $counter
            );
        }

        return $newEntryNumber;
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
