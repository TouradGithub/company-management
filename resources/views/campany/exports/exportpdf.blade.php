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
                <th> الاجمالي</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->name??'' }}</td>
                    <td>{{ $payroll->branch->name??'' }}</td>
                    <td>{{ $payroll->basic_salary }}</td>
                    <td>{{ $payroll->transportation }}</td>
                    <td>{{ $payroll->food }}</td>
                    <td>{{ $payroll->overtime }}</td>
                    <td>{{ $payroll->deduction }}</td>
                    <td>{{ $payroll->net_salary }}</td>
                </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: gray;">
                            لا توجد بيانات لعرضها لهذا الشهر
                        </td>
                    </tr>
                @endforelse
        </tbody>
    </table>
</body>
</html>
