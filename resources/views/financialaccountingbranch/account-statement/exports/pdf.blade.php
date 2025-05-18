<!-- resources/views/financialaccounting/account-statement/exports/pdf-data.blade.php -->

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف حساب</title>
    <style>
        body {
            font-family: 'cairo', sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<h2>كشف حساب</h2>

@if($data['branch'])
    <p><strong>الفرع:</strong> {{ $data['branch']->name ?? '-' }}</p>
@endif

<p><strong>الفترة:</strong> {{ $data['from'] ?? '-' }} إلى {{ $data['to'] ?? '-' }}</p>
<p><strong>اسم الحساب:</strong> {{ $data['account']->name .' - '.$data['account']->account_number ?? '-' }}</p>

<table>
    <thead>
    <tr>
        <th>التاريخ</th>
        <th>رقم المستند</th>
        <th>البيان</th>
        <th>مدين</th>
        <th>دائن</th>
        <th>الرصيد</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="5">الرصيد الإفتتاحي</td>
        <td>{{ $data['account']->opening_balance ?? '' }}</td>
    </tr>
    @forelse ($data['data'] ?? [] as $item)
        <tr>
            <td>{{ \Carbon\Carbon::parse($item['transaction_date'])->format('d-m-Y') }}</td>

            <td>{{ $item['transaction_number'] ?? '' }}</td>

            <td>
                @if($item['source_type'] == 'JournalEntry')
                    قيد يومي
                @else
                    فاتورة
                @endif
            </td>
            <td>{{ $item['debit'] ?? '0.00' }}</td>
            <td>{{ $item['credit'] ?? '0.00' }}</td>
            <td>{{ $item['balance'] ??'0.00'  }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">لا توجد بيانات</td>
        </tr>
    @endforelse
    </tbody>
</table>

<br><br>
<p><strong>الرصيد الافتتاحي:</strong> {{ $data['opening_balance'] }}</p>
<p><strong>إجمالي المدين:</strong> {{ $data['total_debit'] }}</p>
<p><strong>إجمالي الدائن:</strong> {{ $data['total_credit'] }}</p>
<p><strong>الرصيد الختامي:</strong> {{ $data['closing_balance'] }}</p>

</body>
</html>
