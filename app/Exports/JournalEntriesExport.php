<?php
namespace App\Exports;

use App\Models\JournalEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JournalEntriesExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
    $this->filters = $filters;
    }

    public function collection()
    {
    $query = JournalEntry::with(['details.account', 'branch', 'details.costcenter']);

    if (!empty($this->filters['branch_id'])) {
    $query->where('branch_id', $this->filters['branch_id']);
    }

    if (!empty($this->filters['from_date']) && !empty($this->filters['to_date'])) {
    $query->whereBetween('entry_date', [$this->filters['from_date'], $this->filters['to_date']]);
    } elseif (!empty($this->filters['from_date'])) {
    $query->where('entry_date', '>=', $this->filters['from_date']);
    } elseif (!empty($this->filters['to_date'])) {
    $query->where('entry_date', '<=', $this->filters['to_date']);
    }

    if (!empty($this->filters['entry_number'])) {
    $query->where('entry_number', 'like', '%' . $this->filters['entry_number'] . '%');
    }

    $entries = $query->get();
    $data = collect();

        foreach ($entries as $entry) {
            $data->push([
            'رقم القيد' => $entry->entry_number,
            'التاريخ' => $entry->entry_date,
            'الفرع' => $entry->branch ? $entry->branch->name : 'N/A',
            'مدين' => $entry->details->sum('debit'),
            'دائن' => $entry->details->sum('credit'),
        ]);

        foreach ($entry->details as $detail) {
            $data->push([
                'رقم القيد' => '',
                'التاريخ' => '',
                'الفرع' => '',
                'مدين' => $detail->debit ?: '-',
                'دائن' => $detail->credit ?: '-',
                'الحساب' => $detail->account ? $detail->account->name . ' - ' . $detail->account->account_number : 'N/A',
                'مركز التكلفة' => $detail->costcenter ? $detail->costcenter->name . ' - ' . $detail->costcenter->code : 'N/A',
                'ملاحظات' => $detail->comment ?: '',
            ]);
        }
    }

    return $data;
    }

    public function headings(): array
    {
        return [
            'رقم القيد',
            'التاريخ',
            'الفرع',
            'مدين',
            'دائن',
            'الحساب',
            'مركز التكلفة',
            'ملاحظات',
        ];
    }
}
