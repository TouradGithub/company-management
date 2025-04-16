<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جدول الحسابات</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: right; }
        th { background-color: #f2f2f2; }
        .child-level-1 { padding-right: 20px; }
        .child-level-2 { padding-right: 40px; }
    </style>
</head>
<body>
<h2>جدول الحسابات</h2>
<table>
    <thead>
    <tr>
        <th>رقم الحساب</th>
        <th>اسم الحساب</th>
        <th>نوع الحساب</th>
        <th>نوع القائمة الختاميه</th>
        <th> الرصيد الإفتتاحي</th>
        <th>رصيد مدين</th>
        <th>رصيد دائن</th>
        <th>الرصيد</th>
    </tr>
    </thead>
    <tbody>
    @if($accounts->isEmpty())
        <tr>
            <td colspan="6" style="text-align: center;">لا توجد بيانات</td>
        </tr>
    @else
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->account_number }}</td>
                <td>{{ $account->name }}</td>
                <td>{{ $account->accountType->name }}</td>
                <td>{{ $account->closing_list_type == 1?'قائمة الدخل':'الميزانيه العموميه'}}</td>
                <td>{{ $account->opening_balance ?? 0}}</td>
                <td>{{ $account->getBalanceDetails() < 0 ? abs($account->getBalanceDetails()) : 0 }}</td>
                <td>{{ $account->getBalanceDetails() >= 0 ? $account->getBalanceDetails() : 0 }}</td>
                <td>{{ $account->getBalanceDetails() }}</td>
            </tr>
            @if($level === 'all' && $account->children->count() > 0)
                @foreach($account->children as $child)
                    <tr class="child-level-1">
                        <td class="child-level-1">{{ $child->account_number }}</td>
                        <td class="child-level-1">{{ $child->name }}</td>
                        <td>{{ $child->accountType->name }}</td>
                        <td>{{ $account->closing_list_type == 1?'قائمة الدخل':'الميزانيه العموميه'}}</td>
                        <td>{{ $account->opening_balance ?? 0}}</td>
                        <td>{{ $child->getBalanceDetails() < 0 ? abs($child->getBalanceDetails()) : 0 }}</td>
                        <td>{{ $child->getBalanceDetails() >= 0 ? $child->getBalanceDetails() : 0 }}</td>
                        <td>{{ $child->getBalanceDetails() }}</td>
                    </tr>
                    @if($child->children->count() > 0)
                        @foreach($child->children as $grandchild)
                            <tr class="child-level-2">
                                <td class="child-level-2">{{ $grandchild->account_number }}</td>
                                <td class="child-level-2">{{ $grandchild->name }}</td>
                                <td>{{ $grandchild->accountType->name }}</td>
                                <td>{{ $account->closing_list_type == 1?'قائمة الدخل':'الميزانيه العموميه'}}</td>
                                <td>{{ $account->opening_balance ?? 0}}</td>
                                <td>{{ $grandchild->getBalanceDetails() < 0 ? abs($grandchild->getBalanceDetails()) : 0 }}</td>
                                <td>{{ $grandchild->getBalanceDetails() >= 0 ? $grandchild->getBalanceDetails() : 0 }}</td>
                                <td>{{ $grandchild->getBalanceDetails() }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
    </tbody>
</table>
</body>
</html>
