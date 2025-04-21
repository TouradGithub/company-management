

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
@if($journalEntry->details->isEmpty())
    <p style="text-align: center;">لا يوجد قيود تطابق البحث</p>
@else

        <div class="entry-header">
            قيد رقم: {{ $journalEntry->entry_number }} | التاريخ: {{ $journalEntry->entry_date }} | الفرع: {{ $journalEntry->branch ? $journalEntry->branch->name : 'N/A' }}
            <br>مدين: {{ $journalEntry->details->sum('debit') }} | دائن: {{ $journalEntry->details->sum('credit') }}
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
            @foreach($journalEntry->details as $detail)
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
@endif
</body>
</html>
