

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قيود يومية</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; }
        .entry-header { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
<h2>قيود يومية</h2>
@if($entries->isEmpty())
    <p style="text-align: center;">لا يوجد قيود تطابق البحث</p>
@else
    @foreach($entries as $entry)
        <div class="entry-header">
            قيد رقم: {{ $entry->entry_number }} | التاريخ: {{ $entry->entry_date }} | الفرع: {{ $entry->branch ? $entry->branch->name : 'N/A' }}
            <br>مدين: {{ $entry->details->sum('debit') }} | دائن: {{ $entry->details->sum('credit') }}
        </div>
        <table>
            <thead>
            <tr>
                <th>الحساب</th>
                <th>مدين</th>
                <th>دائن</th>
                <th>مركز التكلفة</th>
                <th>ملاحظات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($entry->details as $detail)
                <tr>
                    <td>{{ $detail->account ? $detail->account->name . ' - ' . $detail->account->account_number : 'N/A' }}</td>
                    <td>{{ $detail->debit == 0 ? '-' : $detail->debit }}</td>
                    <td>{{ $detail->credit == 0 ? '-' : $detail->credit }}</td>
                    <td>{{ $detail->costcenter ? $detail->costcenter->name . ' - ' . $detail->costcenter->code : 'N/A' }}</td>
                    <td>{{ $detail->comment ?: '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
@endif
</body>
</html>
