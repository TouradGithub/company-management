@extends('financialaccounting.layouts.master')

@section('content')


    <div class="invoice-container active invoice-container-style" id="sales-return-invoice">
        <form id="salesReturnInvoiceForm">
{{--            <div>--}}

{{--                <h1 style="text-align: center"> <h1>إضافة فاتورة مرتجع مبيعات</h1>--}}
{{--            </div>--}}
            <div class="header">
                <div>
                    <h1 style="color: #e67e22;">فاتورة مرتجع مبيعات</h1>
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        <p style="margin: 0; background: #fdf2e9; padding: 8px; border-radius: 4px; border: 1px solid #e67e22;">
                            مرجع الفاتورة: <span id="salesReturnRef" style="font-weight: bold; color: #e67e22;">SR-{{ date('Ymd') }}-001</span>
                        </p>
                    </div>
                </div>
                <svg viewBox="0 0 40 40" style="color: #e67e22;"></svg>
            </div>

            <div class="invoice-info" style="border: 2px solid #e67e22; padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                <div style="display: flex; gap: 2rem; align-items: center; width: 100%;">
                    <div class="return-info-group" style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: #e67e22; font-weight: bold;">رقم فاتورة المبيعات:</label>
                        <input type="text" id="originalSalesInvoiceNumber" style="background: #fdf2e9; border: 1px solid #e67e22; padding: 8px 12px; border-radius: 6px; width: 100%;">
                    </div>
                    <div class="return-date-group" style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: #e67e22; font-weight: bold;">تاريخ المرتجع:</label>
                        <input type="date" id="salesReturnInvoiceDate" value="{{ date('Y-m-d') }}" style="border: 1px solid #e67e22; padding: 8px; border-radius: 6px; width: 100%;">
                    </div>
                </div>
                <div style="display: flex; gap: 2rem; align-items: center; width: 100%;">
                    <div class="employee-info-group" style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: #e67e22; font-weight: bold;">اسم الموظف:</label>
                        <input type="text" id="salesReturnEmployeeName" list="employeeListSalesReturn" style="border: 1px solid #e67e22; padding: 8px 12px; border-radius: 6px; width: 100%;">
                        <datalist id="employeeListSalesReturn"></datalist>
                    </div>
                    <div class="shift-info-group" style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: #e67e22; font-weight: bold;">الوردية:</label>
                        <select id="salesReturnShiftSelect" style="border: 1px solid #e67e22; padding: 8px 12px; border-radius: 6px; width: 100%;">
                            <option value="صباح">صباح</option>
                            <option value="مساء">مساء</option>
                        </select>
                    </div>
                    <div class="branch-info-group" style="flex: 1;">
                        <label style="display: block; margin-bottom: 0.5rem; color: #e67e22; font-weight: bold;">الفرع:</label>
                        <select id="salesReturnBranchSelect" style="border: 1px solid #e67e22; padding: 8px 12px; border-radius: 6px; width: 100%;">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="info-section supplier-return-info" style="border: 2px solid #e67e22; padding: 1.5rem; border-radius: 12px;  background: #fffaf5;">
                <div class="client-info" style="padding-right: 2rem; border-right: 2px dashed #e67e22;">
                    <h3 style="color: #e67e22; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #e67e22;">العميل المرتجع</h3>
                    <select name="customer_id" class="customers sales-return-customer-id" id="salesReturnCustomerSelect" style="width: 100%; padding: 10px; font-size: 16px;"></select>
                    <div class="limit-credit" style="display: none"></div>
                </div>
                <div class="company-info">
                    <h3>شركة بوادي المتحده {{ getCompany()->name }}</h3>
                    <p>شركة التجارة</p>
                    <p>الرياض، المملكة العربية السعودية</p>
                    <p>الرقم الضريبي 102031141121</p>
                </div>
            </div>

            <table id="salesReturnItemsTable">
                <datalist id="salesReturnItemsDatalist"></datalist>
                <thead>
                <tr>
                    <th>مسلسل</th>
                    <th>الصنف</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>السعر</th>
                    <th>الإجمالي</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr class="total-row">
                    <td colspan="5">المبلغ الإجمالي</td>
                    <td id="salesReturnSubtotal">0.00</td>
                    <td></td>
                </tr>
                <tr class="calculation-row">
                    <td colspan="5">الخصم (%) <input type="number" id="salesReturnDiscountPercent" step="0.01" value="0"></td>
                    <td id="salesReturnDiscountAmount">0.00</td>
                    <td></td>
                </tr>
                <tr class="calculation-row">
                    <td colspan="5">الضريبة (%) <input type="number" id="salesReturnTaxPercent" step="0.01" value="15"></td>
                    <td id="salesReturnTaxAmount">0.00</td>
                    <td></td>
                </tr>
                <tr class="total-row">
                    <td colspan="5">المبلغ النهائي</td>
                    <td id="salesReturnGrandTotal">0.00</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>

            <div class="actions">
                <button class="add-client-btn" type="button" onclick="addNewClient('sales-return')">
                    <svg viewBox="0 0 24 24" width="20" height="20" style="margin-left:8px" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>
                    إضافة عميل
                </button>
                <button class="print-btn" type="button" onclick="window.print()">طباعة</button>
                <button class="save-btn" id="saveSalesReturnInvoiceBtn" type="button">حفظ</button>
            </div>
        </form>
    </div>
    @include('financialaccounting.invoices.modal')
@endsection

@section('css')
    <style>
        /* Copy all styles from the provided HTML here */
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
        }
        /*body {*/
        /*    font-family: 'Arial', sans-serif;*/
        /*    padding: 2rem;*/
        /*    background: #ecf0f1;*/
        /*}*/
        .invoice-container-style {
            /*max-width: 100%;*/
            width: 90%;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 1.5rem;
            margin-bottom: 1rem;
            flex-direction: row-reverse;
        }
        .header > div:first-child {
            flex: 1;
            text-align: center;
            margin-left: 2rem;
        }
        h1 {
            margin: 0 0 1rem 0;
            white-space: nowrap;
        }
        .header svg {
            width: 40px;
            height: 40px;
        }
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
            border: 2px solid #e67e22;
            padding: 2rem;
            border-radius: 12px;
            background: #fffaf5;
            position: relative;
        }
        .info-section::before {
            content: "معلومات العميل";
            position: absolute;
            top: -12px;
            right: 20px;
            background: white;
            padding: 0 10px;
            color: #e67e22;
            font-weight: bold;
            font-size: 14px;
        }
        #purchase-invoice .info-section {
            border-color: var(--secondary);
            background: #f8fcff;
        }
        #purchase-invoice .info-section::before {
            content: "معلومات المورد";
            color: var(--secondary);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            border: 2px solid #e67e22;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        #purchaseItemsTable, #purchaseReturnItemsTable {
            border: 2px solid var(--secondary);
        }
        th, td {
            padding: 12px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }
        th {
            background: linear-gradient(to right, #e67e22, #d35400);
            color: white;
        }
        #purchaseItemsTable th, #purchaseReturnItemsTable th {
            background: linear-gradient(to right, var(--secondary), #2980b9);
        }
        .total-row {
            font-weight: bold;
            background: #f8f9fa;
        }
        .actions {
            margin-top: 2rem;
            text-align: center;
        }
        button {
            padding: 12px 24px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .print-btn {
            background: var(--secondary);
            color: white;
        }
        .save-btn {
            background: var(--accent);
            color: white;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 18px;
            line-height: 1;
        }
        .delete-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
        }
        .add-client-btn {
            background: #27ae60;
            color: white;
            display: inline-flex;
            align-items: center;
            direction: ltr;
        }
        @media print {
            button {
                display: none;
            }
        }
        .invoice-info {
            border: 2px solid #e67e22;
            padding: 1.5rem;
            border-radius: 12px;
            margin: 2rem 0;
            background: #fffaf5;
            position: relative;
        }
        #purchase-invoice .invoice-info {
            border: 2px solid var(--secondary);
            background: #f8fcff;
        }
        .invoice-info::before {
            content: "معلومات الفاتورة";
            position: absolute;
            top: -12px;
            right: 20px;
            background: white;
            padding: 0 10px;
            color: #e67e22;
            font-weight: bold;
            font-size: 14px;
        }
        #purchase-invoice .invoice-info::before {
            color: var(--secondary);
        }
        .invoice-info input,
        .invoice-info select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
        }
        table input[type="number"] {
            max-width: 80px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .calculation-row td {
            background: #f8f9fa;
        }
        .nav-tabs {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 1rem 0 2rem;
        }
        .nav-tabs button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background: #f0f0f0;
            cursor: pointer;
            transition: 0.3s;
        }
        .nav-tabs button.active {
            background: var(--secondary);
            color: white;
        }
        .hidden {
            display: none;
        }
        select.item-unit {
            padding: 8px 12px;
            border: 1px solid #e67e22;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }
        #purchase-invoice select.item-unit, #purchase-return-invoice select.item-unit {
            border-color: var(--secondary);
        }
        .invoice-form-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        .page-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('invoice.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('financialaccounting.invoices.script')
@endsection
