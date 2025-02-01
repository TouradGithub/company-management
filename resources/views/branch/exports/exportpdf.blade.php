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
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
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
                <th>الراتب الصافي</th>
                <th>بدل التنقل</th>
                <th>بدل الإعاشة</th>
                <th>الإضافي</th>
                <th>الخصومات</th>
                <th>السلف</th>
                <th>الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_basic_salary = 0;
                $total_transportation = 0;
                $total_food = 0;
                $total_overtime = 0;
                $total_deduction = 0;
                $total_loans = 0;
                $total_net_salary = 0;
            @endphp

            @forelse ($payrolls as $payroll)
                @php
                    $total_basic_salary += $payroll->basic_salary;
                    $total_transportation += $payroll->transportation;
                    $total_food += $payroll->food;
                    $total_overtime += $payroll->overtime;
                    $total_deduction += $payroll->deduction;
                    $total_loans += $payroll->loans;
                    $total_net_salary += $payroll->net_salary;
                @endphp
                <tr>
                    <td>{{ $payroll->employee->name ?? '' }}</td>
                    <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                    <td>{{ number_format($payroll->transportation, 2) }}</td>
                    <td>{{ number_format($payroll->food, 2) }}</td>
                    <td>{{ number_format($payroll->overtime, 2) }}</td>
                    <td>{{ number_format($payroll->deduction, 2) }}</td>
                    <td>{{ number_format($payroll->loans, 2) }}</td>
                    <td>{{ number_format($payroll->net_salary, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: gray;">
                        لا توجد بيانات لعرضها لهذا الشهر
                    </td>
                </tr>
            @endforelse

            <!-- صف المجموع -->
            @if($payrolls->count() > 0)
                <tr style="font-weight: bold; background-color: #e6e6e6;">
                    <td colspan="7" style="text-align: center;">الإجمالي</td>

                    <td>{{ number_format($total_net_salary, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
