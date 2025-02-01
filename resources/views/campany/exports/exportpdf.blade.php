<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل كشف الرواتب</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
            background-color: #d9edf7;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center">سجل كشف الرواتب</h1>
    <p>الشهر: {{ $month }}</p>

    <table>
        <thead>
            <tr>
                <th>اسم الموظف</th>
                <th>اسم الفرع</th>
                <th>الراتب الصافي</th>
                <th>بدل التنقل</th>
                <th>بدل الاعاشه</th>
                <th>الاضافي </th>
                <th>الخصومات </th>
                <th>الاجمالي</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalNetSalary = 0;
            @endphp

            @forelse ($payrolls as $payroll)
                @php
                    $totalNetSalary += $payroll->net_salary;
                @endphp
                <tr>
                    <td>{{ $payroll->employee->name ?? '' }}</td>
                    <td>{{ $payroll->branch->name ?? '' }}</td>
                    <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td>{{ number_format($payroll->transportation, 2) }}</td>
                    <td>{{ number_format($payroll->food, 2) }}</td>
                    <td>{{ number_format($payroll->overtime, 2) }}</td>
                    <td>{{ number_format($payroll->deduction, 2) }}</td>
                    <td>{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: gray;">
                        لا توجد بيانات لعرضها لهذا الشهر
                    </td>
                </tr>
            @endforelse

            <!-- صف المجموع النهائي -->
            @if ($payrolls->isNotEmpty())
                <tr class="total-row">
                    <td colspan="7" style="text-align: center;">المجموع الكلي</td>
                    <td>{{ number_format($totalNetSalary, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
