<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Branch;
use App\Models\CostCenter;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
class JournalEntriesImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $journalCode = 'GEN';
        $journal = Journal::where('code', $journalCode)->first();
        $companyId = getCompanyId();

        if (!$journal) {
            throw new \Exception("دفتر اليومية برمز $journalCode غير موجود.");
        }

        $validDetails = [];

        foreach ($rows->skip(1) as $row) {
            $account = Account::where('company_id', $companyId)
                ->where('name', 'like', '%' . $row[0] . '%')
                ->orWhere('code', 'like', '%' . $row[0] . '%')
                ->first();

            $cost_center = CostCenter::where('company_id', $companyId)
                ->where('name', 'like', '%' . $row[1] . '%')
                ->orWhere('code', 'like', '%' . $row[0] . '%')
                ->first();

            if (!$account || !$cost_center) {
                continue;
            }

            $validDetails[] = [
                'account_id' => $account->id,
                'cost_center_id' => $cost_center->id,
                'debit' => is_numeric($row[2]) ? $row[2] : 0,
                'credit' => is_numeric($row[3]) ? $row[3] : 0,
                'comment' => $row[4] ?? '',
            ];
        }

        if (count($validDetails) > 0) {
            DB::transaction(function () use ($journalCode, $journal, $companyId, $validDetails) {
                $journalEntry = JournalEntry::create([
                    'entry_number' => JournalEntry::generateEntryNumber($journalCode, getCompanyId()),
                    'entry_date' => now(),
                    'branch_id' => 1,
                    'company_id' => $companyId,
                    'created_by' => Auth::user()->name,
                    'session_year' => getCurrentYear(),
                ]);

                foreach ($validDetails as $detail) {
                    JournalEntryDetail::create(array_merge($detail, [
                        'journal_entry_id' => $journalEntry->id,
                    ]));
                }
            });
        }

//        });
    }
}
