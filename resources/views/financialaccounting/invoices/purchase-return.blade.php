
@extends('financialaccounting.layouts.master')

@section('content')
    <div class="invoices-header">
        <h1>إضافة فاتورة مرتجع مشتريات</h1>
    </div>
    <div class="invoice-container active invoice-container-style" id="purchase-return-invoice">
        <form id="purchaseReturnInvoiceForm">
            <div class="header">
                <div>
                    <h1 style="color: var(--secondary);">فاتورة مرتجع مشتريات</h1>


                </div>
                <svg viewBox="0 0 40 40"></svg>
            </div>

            <div class="invoice-header-section return-header purchase-return">
                <div class="header-fields">
                    <div class="field-group">
                        <label>رقم فاتورة المشتريات الأصلية</label>
                        <input type="text" id="originalPurchaseInvoiceNumber" class="original-invoice">
                    </div>
                    <div class="field-group">
                        <label>تاريخ المرتجع</label>
                        <input type="date" id="purchaseReturnInvoiceDate" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="field-group">
                        <label>الفرع</label>
                        <select id="purchaseReturnBranchSelect">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div>
            </div>

            <div class="info-section supplier-return-info" style="border: 2px solid var(--secondary); padding: 1.5rem; border-radius: 12px;  background: #f8fcff;">
                <div class="client-info" style="padding-right: 2rem; border-right: 2px dashed var(--secondary);">
                    <h3 style="color: var(--secondary); margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--secondary);">المورد المرتجع</h3>
                    <select name="supplier_id" class="suppliers purchase-return-supplier-id" id="purchaseReturnSupplierSelect" style="width: 100%; padding: 10px; font-size: 16px;"></select>
                </div>
                <div class="company-info">
                    <h3>   {{ getCompany()->name }}</h3>
                    <p>{{getCompany()->address}}</p>
                    <p>الرقم الضريبي {{getCompany()->tax_number}}</p>
                </div>
            </div>

            <table id="purchaseReturnItemsTable">
                <datalist id="purchaseReturnItemsDatalist"></datalist>
                <thead>
                <tr>
                    <th>مسلسل</th>
                    <th>الصنف</th>
                    <th>الوحدة</th>
                    <th>الكمية</th>
                    <th>سعر الشراء</th>
                    <th>الإجمالي</th>
                    <th>حذف</th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr class="total-row">
                    <td colspan="5">المبلغ الإجمالي</td>
                    <td id="purchaseReturnSubtotal">0.00</td>
                    <td></td>
                </tr>
                <tr class="calculation-row">
                    <td colspan="5">الخصم (%) <input type="number" id="purchaseReturnDiscountPercent" step="0.01" value="0"></td>
                    <td id="purchaseReturnDiscountAmount">0.00</td>
                    <td></td>
                </tr>
                <tr class="calculation-row">
                    <td colspan="5">الضريبة (%) <input type="number" id="purchaseReturnTaxPercent" step="0.01" value="15"></td>
                    <td id="purchaseReturnTaxAmount">0.00</td>
                    <td></td>
                </tr>
                <tr class="total-row">
                    <td colspan="5">المبلغ النهائي</td>
                    <td id="purchaseReturnGrandTotal">0.00</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>

            <div class="actions">
                <button class="add-client-btn" type="button" onclick="addNewClient('purchase-return')">
                    <svg viewBox="0 0 24 24" width="20" height="20" style="margin-left:8px" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                    </svg>
                    إضافة مورد
                </button>
                <button class="print-btn" type="button" onclick="window.print()">طباعة</button>
                <button class="save-btn" id="savePurchaseReturnInvoiceBtn" type="button">حفظ</button>
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

        /* Simple and Clean Purchase Return Header */
        .invoice-header-section.return-header.purchase-return {
            background: #f0f8ff;
            border: 1px solid var(--secondary);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.1);
        }

        .header-fields {
            display: flex;
            gap: 2rem;
            align-items: end;
            flex-wrap: wrap;
        }

        .field-group {
            flex: 1;
            min-width: 200px;
        }

        .field-group label {
            display: block;
            font-weight: 600;
            color: var(--secondary);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .field-group input,
        .field-group select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--secondary);
            border-radius: 8px;
            font-size: 1rem;
            color: #374151;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .field-group input:focus,
        .field-group select:focus {
            outline: none;
            border-color: #2980b9;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .original-invoice {
            background: #e6f4ff !important;
            border-color: var(--secondary) !important;
            font-weight: 600;
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

        /* Spinner Styles */
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            display: inline-block;
            animation: spin 0.8s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .save-btn:disabled {
            opacity: 0.8;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            background-color: #27ae60 !important;
        }

        /* تحسين أسلوب النص أثناء التحميل */
        .btn-loading {
            font-weight: bold;
            letter-spacing: 0.5px;
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
