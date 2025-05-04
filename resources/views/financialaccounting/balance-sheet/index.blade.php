@extends('financialaccounting.layouts.master')
@section('content')
    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الميزانية العمومية</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            direction: rtl;
        }
        h1, h2 {
            text-align: center;
        }
        .balance-sheet {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 20px;
        }
        .section {
            width: 48%;
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .section h3 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        .total {
            font-weight: bold;
            border-top: 1px solid #000;
            margin-top: 15px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<h1>الميزانية العمومية</h1>
<h2>للسنة المالية {{getCurentYearName()}}</h2>
<div class="balance-sheet">
    <div class="section">
        <h3>الأصول</h3>
        @php $totalAssets = 0; @endphp
        @foreach($assets as $item)
            <div class="item"><span>{{ $item['name'] }}</span><span>{{ number_format($item['value']) }}</span></div>
            @php $totalAssets += $item['value']; @endphp
        @endforeach
        <div class="total item"><span>إجمالي الأصول</span><span>{{ number_format($totalAssets) }}</span></div>
    </div>
    <div class="section">
        <h3>الخصوم وحقوق الملكية</h3>
        @php $totalLE = 0; @endphp
        @foreach($liabilitiesEquity as $item)
            <div class="item"><span>{{ $item['name'] }}</span><span>{{ number_format($item['value']) }}</span></div>
            @php $totalLE += $item['value']; @endphp
        @endforeach
        <div class="total item"><span>إجمالي الخصوم وحقوق الملكية</span><span>{{ number_format($totalLE) }}</span></div>
    </div>
</div>
</body>
</html>
@endsection
@section('js')
@endsection
