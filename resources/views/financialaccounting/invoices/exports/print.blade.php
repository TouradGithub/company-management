<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاتورة ضريبية</title>
    <style>
        body {
            font-family: Cairo, sans-serif; /* Ensure Cairo font is available in mPDF */
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #cccccc;
        }

        .header {
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
            margin-left: 40px;
            overflow: hidden; /* Clear floats */
        }

        .company-info {
            float: right;
            width: 70%;
            text-align: right;
        }

        .company-info h2 {
            color: #007bff;
            font-size: 20pt;
            margin-bottom: 5px;
        }

        .company-info p {
            color: #444444;
            font-size: 10pt;
            line-height: 1.5;
            margin: 0;
        }

        .qr-code {
            float: left;
            width: 25%;
            text-align: left;
        }

        .qr-code img {
            width: 70px;
            height: 70px;
            border: 2px solid #007bff;
            padding: 5px;
        }

        .invoice-details-container {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-right: 4px solid #007bff;
            overflow: hidden; /* Clear floats */
        }

        .invoice-details-container div {
            width: 48%;
            float: right;
            vertical-align: top;
        }

        .invoice-details-container .left {
            float: left;
            text-align: left;
        }

        .invoice-details-container h3 {
            color: #007bff;
            font-size: 14pt;
            margin-bottom: 8px;
        }

        .invoice-details-container p {
            color: #555555;
            font-size: 10pt;
            margin: 5px 0;
        }

        .table-container {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #dddddd;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
            font-size: 10pt;
            font-weight: bold;
        }

        td {
            color: #666666;
            font-size: 10pt;
        }

        .total {
            padding: 15px;
            background-color: #f8f9fa;
            margin-top: 20px;
            text-align: left;
        }

        .total p {
            color: #333333;
            font-size: 11pt;
            margin: 5px 0;
        }

        .total p:last-child {
            color: #007bff;
            font-size: 14pt;
            font-weight: bold;
            border-top: 1px solid #cccccc;
            padding-top: 8px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #007bff;
            text-align: center;
            color: #666666;
            font-size: 10pt;
        }

        .footer p:first-child {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="header">
        <div class="company-info">
            <h2>مؤسسة إيلاف الجوف التجارية</h2>
            <p>بلاستيك، منظفات، ورق</p>
            <p>القريات - هاتف: 0146410300</p>
            <p>الرقم الضريبي: 30107609930003</p>
        </div>
        <div class="qr-code">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=INV-2023-001" alt="QR Code">
        </div>
    </div>

    <div class="invoice-details-container">
        <div>
            <h3>العميل</h3>
            <p><strong>الاسم:</strong> {{ $invoice->customer ? $invoice->customer->name : ($invoice->supplier->name ?? 'غير متوفر') }}</p>
            <p><strong>الهاتف:</strong>  {{ $invoice->customer ? $invoice->customer->contact_info : ($invoice->supplier->contact_info ?? 'غير متوفر') }}</p>
        </div>
        <div class="left">
            <h3>تفاصيل الفاتورة</h3>
            <p><strong>رقم:</strong> {{ $invoice->invoice_number ?? 'INV-2023-001' }}</p>
            <p><strong>التاريخ:</strong> {{ $invoice->invoice_date ? date('d/m/Y', strtotime($invoice->invoice_date)) : 'غير متوفر' }}</p>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>الوصف</th>
                <th>الكمية</th>
                <th>سعر الوحدة</th>
                <th>المجموع</th>
            </tr>
            </thead>
            <tbody>
            @if ($invoice->items && count($invoice->items) > 0)
                @foreach ($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity ?? 0 }}</td>
                        <td>{{ number_format($item->price ?? 0, 2) }} ر.س</td>
                        <td>{{ number_format($item->total ?? 0, 2) }} ر.س</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">لا توجد عناصر في الفاتورة</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    <div class="total">
        <p>المجموع الفرعي: {{ number_format($invoice->subtotal ?? 0, 2) }} ر.س</p>
        <p>الخصم: {{ number_format($invoice->discount ?? 0, 2) }} ر.س</p>
        <p>الضريبة : %{{ number_format($invoice->tax ?? 0, 0) }} </p>
        <p>الإجمالي: {{ number_format($invoice->total ?? 0, 2) }} ر.س</p>
    </div>

    <div class="footer">
        <p>شكراً لتعاملكم معنا</p>
        <p>للاستفسارات: 0146410300 | info@example.com</p>
    </div>
</div>
</body>
</html>
