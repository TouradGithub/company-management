@extends('financialaccounting.layouts.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/modern-invoice-simple.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modern-invoice-stable.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* CSS مضمن للتأكد من ظهور التنسيقات */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%) !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .modern-invoice-container {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 20px !important;
            background: transparent !important;
            min-height: 100vh !important;
        }

        /* رأس الفاتورة */
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 2rem !important;
            border-radius: 16px !important;
            margin-bottom: 2rem !important;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3) !important;
        }

        .invoice-title h1 {
            font-size: 2rem !important;
            margin: 0 0 1rem 0 !important;
            display: flex !important;
            align-items: center !important;
            gap: 1rem !important;
            color: white !important;
        }

        .invoice-number {
            background: rgba(255, 255, 255, 0.2) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-weight: 600 !important;
            backdrop-filter: blur(10px) !important;
            color: white !important;
        }

        /* الإحصائيات السريعة */
        .quick-stats {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)) !important;
            gap: 1.5rem !important;
            margin-bottom: 2rem !important;
        }

        .stat-card {
            background: white !important;
            padding: 1.5rem !important;
            border-radius: 12px !important;
            text-align: center !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
            transition: transform 0.3s ease !important;
            border: 1px solid #e5e7eb !important;
        }

        .stat-card:hover {
            transform: translateY(-5px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .stat-icon {
            font-size: 2rem !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        .stat-number {
            font-size: 1.8rem !important;
            font-weight: bold !important;
            color: #667eea !important;
            margin: 0.5rem 0 !important;
        }

        .stat-label {
            color: #64748b !important;
            font-size: 0.875rem !important;
            margin: 0 !important;
        }

        /* الأقسام الرئيسية */
        .invoice-info-section,
        .smart-search-container,
        .items-section,
        .invoice-summary {
            background: white !important;
            padding: 2rem !important;
            border-radius: 12px !important;
            margin-bottom: 2rem !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid #e5e7eb !important;
        }

        /* شبكة المعلومات */
        .info-grid {
            display: grid !important;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important;
            gap: 1.5rem !important;
        }

        .info-card {
            background: #f8fafc !important;
            padding: 1.5rem !important;
            border-radius: 8px !important;
            border: 1px solid #e5e7eb !important;
        }

        .info-card-header {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            margin-bottom: 1rem !important;
            font-weight: 600 !important;
            color: #667eea !important;
        }

        /* رسوم متحركة للإشعارات */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* مؤشر التحميل في الأزرار */
        .loading-spinner {
            display: none !important;
            width: 16px !important;
            height: 16px !important;
            border: 2px solid transparent !important;
            border-top: 2px solid currentColor !important;
            border-radius: 50% !important;
            animation: spin 1s linear infinite !important;
            margin-right: 8px !important;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* إخفاء الأيقونة عند إظهار مؤشر التحميل */
        .modern-btn:disabled .loading-spinner {
            display: inline-block !important;
        }

        .modern-btn:disabled i.fas {
            display: none !important;
        }

        /* المدخلات الحديثة */
        .modern-input-group {
            margin-bottom: 1rem !important;
        }

        .modern-label {
            display: block !important;
            margin-bottom: 0.5rem !important;
            font-weight: 500 !important;
            color: #374151 !important;
        }

        .modern-input,
        .modern-select {
            width: 100% !important;
            padding: 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            font-size: 0.875rem !important;
            transition: all 0.2s !important;
        }

        .modern-input:focus,
        .modern-select:focus {
            outline: none !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        }

        /* البحث الذكي */
        .smart-search-container {
            position: relative !important;
        }

        .search-icon {
            position: absolute !important;
            left: 1rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #9ca3af !important;
            z-index: 1 !important;
        }

        .smart-search-input {
            width: 100% !important;
            padding: 1rem 1rem 1rem 3rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            font-size: 1rem !important;
            transition: all 0.3s !important;
        }

        .smart-search-input:focus {
            outline: none !important;
            border-color: #667eea !important;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1) !important;
        }

        .search-results {
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            max-height: 400px !important;
            overflow-y: auto !important;
            z-index: 1000 !important;
            display: none !important;
        }

        .search-result-item {
            padding: 1rem !important;
            border-bottom: 1px solid #f3f4f6 !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
        }

        .search-result-item:hover {
            background: #f8fafc !important;
        }

        .search-result-item:last-child {
            border-bottom: none !important;
        }

        /* جدول الأصناف */
        .items-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 1.5rem !important;
        }

        .items-counter {
            background: #667eea !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 20px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
        }

        .modern-table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 1rem !important;
        }

        .modern-table th,
        .modern-table td {
            padding: 0.75rem !important;
            text-align: right !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .modern-table th {
            background: #f8fafc !important;
            font-weight: 600 !important;
            color: #374151 !important;
        }

        .table-input {
            width: 100% !important;
            padding: 0.5rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            text-align: center !important;
        }

        /* الأزرار */
        .action-buttons {
            display: flex !important;
            gap: 1rem !important;
            flex-wrap: wrap !important;
            justify-content: center !important;
            margin-top: 2rem !important;
        }

        .modern-btn {
            padding: 0.75rem 1.5rem !important;
            border: none !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            font-size: 0.875rem !important;
            text-decoration: none !important;
        }

        .btn-primary {
            background: #667eea !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: #5a67d8 !important;
            transform: translateY(-2px) !important;
        }

        .btn-success {
            background: #10b981 !important;
            color: white !important;
        }

        .btn-success:hover {
            background: #059669 !important;
            transform: translateY(-2px) !important;
        }

        .btn-info {
            background: #3b82f6 !important;
            color: white !important;
        }

        .btn-info:hover {
            background: #2563eb !important;
            transform: translateY(-2px) !important;
        }

        .btn-outline {
            background: white !important;
            color: #667eea !important;
            border: 2px solid #667eea !important;
        }

        .btn-outline:hover {
            background: #667eea !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }

        /* ملخص الفاتورة */
        .summary-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 2rem !important;
            align-items: start !important;
        }

        .summary-calculations {
            background: #f8fafc !important;
            padding: 1.5rem !important;
            border-radius: 8px !important;
            border: 1px solid #e5e7eb !important;
        }

        .calc-row {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-bottom: 1rem !important;
            padding-bottom: 0.5rem !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .calc-row:last-child {
            margin-bottom: 0 !important;
            border-bottom: 2px solid #667eea !important;
            padding-bottom: 1rem !important;
            font-weight: 700 !important;
        }

        .calc-input {
            width: 80px !important;
            padding: 0.25rem 0.5rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 4px !important;
            text-align: center !important;
        }

        /* الإشعارات */
        .notification {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            padding: 1rem 1.5rem !important;
            border-radius: 8px !important;
            color: white !important;
            font-weight: 600 !important;
            z-index: 9999 !important;
            transform: translateX(400px) !important;
            transition: transform 0.3s ease !important;
        }

        .notification.show {
            transform: translateX(0) !important;
        }

        .notification.success {
            background: #10b981 !important;
        }

        .notification.error {
            background: #ef4444 !important;
        }

        .notification.info {
            background: #3b82f6 !important;
        }

        /* تجاوبية */
        @media (max-width: 768px) {
            .modern-invoice-container {
                padding: 1rem !important;
            }

            .invoice-header {
                padding: 1.5rem !important;
            }

            .invoice-title h1 {
                font-size: 1.5rem !important;
            }

            .quick-stats {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
            }

            .info-grid {
                grid-template-columns: 1fr !important;
            }

            .summary-grid {
                grid-template-columns: 1fr !important;
            }

            .action-buttons {
                flex-direction: column !important;
            }
        }

        /* أزرار التحكم في الكمية */
        .quantity-controls {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            justify-content: center !important;
        }

        .qty-btn {
            width: 32px !important;
            height: 32px !important;
            border: none !important;
            border-radius: 6px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }

        .plus-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            color: white !important;
        }

        .plus-btn:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
            transform: scale(1.1) !important;
        }

        .minus-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white !important;
        }

        .minus-btn:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
            transform: scale(1.1) !important;
        }

        .btn-pulse {
            animation: pulse 0.2s ease !important;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* زر الحذف المحسن */
        .delete-btn {
            min-width: 70px !important;
            height: 34px !important;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            color: white !important;
            border: none !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            gap: 4px !important;
            padding: 0 8px !important;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4) !important;
        }

        .delete-btn .btn-text {
            opacity: 0 !important;
            transition: opacity 0.2s ease !important;
        }

        .delete-btn:hover .btn-text {
            opacity: 1 !important;
        }

        /* عداد محسن */
        .items-counter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            padding: 0.5rem 1rem !important;
            border-radius: 20px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3) !important;
        }

        .items-counter.counter-update {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.5) !important;
        }

        .quantity-display {
            min-width: 60px;
            text-align: center;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e3f2fd;
            border-radius: 10px;
            padding: 8px 12px;
            margin: 0 6px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            color: #2563eb;
        }

        .quantity-display.counter-update {
            transform: scale(1.1) !important;
            border-color: #3b82f6 !important;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%) !important;
            color: #1e40af !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3) !important;
        }
    </style>
@endsection

@section('content')
<div class="modern-invoice-container">
    <!-- رأس الفاتورة -->
    <div class="invoice-header">
        <div class="invoice-title">
            <h1>
                <i class="fas fa-shopping-cart"></i>
                فاتورة مشتريات جديدة
            </h1>
            <div class="invoice-number">
                <i class="fas fa-hashtag"></i>
                <span id="invoiceNumber">PUR-{{ date('Ymd') }}-001</span>
            </div>
        </div>
        <p style="margin: 0; opacity: 0.9;">إنشاء وإدارة فاتورة مشتريات بطريقة عصرية وذكية</p>
    </div>

    <!-- الإحصائيات السريعة -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-number" id="todayStats">8</div>
            <div class="stat-label">مشتريات اليوم</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number" id="monthStats">156</div>
            <div class="stat-label">مشتريات الشهر</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏪</div>
            <div class="stat-number" id="suppliersCount">{{ $suppliers->count() }}</div>
            <div class="stat-label">الموردين</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-number" id="pendingStats">3</div>
            <div class="stat-label">معلقة</div>
        </div>
    </div>

    <form id="modernPurchasesInvoiceForm">
        <!-- معلومات الفاتورة الأساسية -->
        <div class="invoice-info-section">
            <h3 style="margin-bottom: 1.5rem; color: var(--dark);">
                <i class="fas fa-info-circle"></i> معلومات الفاتورة
            </h3>

            <div class="info-grid">
                <!-- معلومات الفاتورة -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-file-invoice"></i>
                        تفاصيل الفاتورة
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">التاريخ</label>
                        <input type="date" class="modern-input" id="invoiceDate" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">الفرع</label>
                        <select class="modern-select" id="branchSelect">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- معلومات المورد -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-truck"></i>
                        معلومات المورد
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">المورد</label>
                        <select class="modern-select suppliers" id="supplierSelect" style="width: 100%;">
                            <option value="">اختر المورد...</option>
                        </select>
                    </div>
                </div>


            </div>
        </div>

        <!-- البحث الذكي -->
        <div class="smart-search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="smart-search-input" id="smartSearch"
                   placeholder="🔍 ابحث عن المنتجات... (مثال: أجهزة، مواد خام، أدوات)">
            <div class="search-results" id="searchResults"></div>
        </div>

        <!-- قسم الأصناف -->
        <div class="items-section">
            <div class="items-header">
                <h3>
                    <i class="fas fa-boxes"></i>
                    أصناف المشتريات
                </h3>
                <div class="items-counter" id="itemsCounter">
                    <i class="fas fa-cart-plus me-1"></i>
                    العربة فارغة
                </div>
            </div>

            <table class="modern-table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">م</th>
                        <th>📦 الصنف</th>
                        <th style="width: 100px;">📏 الوحدة</th>
                        <th style="width: 100px;">🔢 الكمية</th>
                        <th style="width: 120px;">💰 السعر</th>
                        <th style="width: 120px;">💵 الإجمالي</th>
                        <th style="width: 80px;">حذف</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
                </tbody>
            </table>

            <!-- رسالة فارغة -->
            <div id="emptyMessage" style="text-align: center; padding: 3rem; color: #64748b;">
                <i class="fas fa-boxes" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>لم يتم إضافة أي أصناف بعد</p>
                <p>استخدم البحث الذكي أعلاه لإضافة المنتجات</p>
            </div>
        </div>

        <!-- أزرار الإجراءات السريعة -->
        <div class="action-buttons">
            <button type="button" class="modern-btn btn-info" onclick="addQuickItem()">
                <i class="fas fa-plus"></i>
                إضافة صنف سريع
            </button>
            <button type="button" class="modern-btn btn-outline" onclick="addNewSupplier()">
                <i class="fas fa-truck-loading"></i>
                مورد جديد
            </button>
        </div>

        <!-- ملخص الفاتورة -->
        <div class="invoice-summary">
            <div class="summary-grid">
                <div>
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">
                        <i class="fas fa-calculator"></i>
                        ملخص الفاتورة
                    </h3>
                    <p style="color: #64748b; margin: 0;">مراجعة المبالغ والحسابات النهائية</p>
                </div>

                <div class="summary-calculations">
                    <div class="calc-row">
                        <span>المجموع الفرعي:</span>
                        <span id="subtotal">0.00 ر.س</span>
                    </div>
                    <div class="calc-row">
                        <span>الخصم:</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" class="calc-input" id="discountPercent" value="0" step="0.01" min="0" max="100">
                            <span>%</span>
                            <span id="discountAmount">0.00 ر.س</span>
                        </div>
                    </div>
                    <div class="calc-row">
                        <span>الضريبة:</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="number" class="calc-input" id="taxPercent" value="15" step="0.01" min="0" max="100">
                            <span>%</span>
                            <span id="taxAmount">0.00 ر.س</span>
                        </div>
                    </div>
                    <div class="calc-row">
                        <span>🔥 الإجمالي النهائي:</span>
                        <span id="grandTotal" style="color: var(--success); font-size: 1.5rem;">0.00 ر.س</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الحفظ والإجراءات -->
        <div class="action-buttons">
            <button type="button" class="modern-btn btn-primary" id="saveInvoiceBtn">
                <i class="fas fa-save"></i>
                <span class="loading-spinner"></span>
                حفظ الفاتورة
            </button>
            <button type="button" class="modern-btn btn-success" onclick="window.print()">
                <i class="fas fa-print"></i>
                طباعة
            </button>
            <button type="button" class="modern-btn btn-outline" onclick="resetForm()">
                <i class="fas fa-undo"></i>
                إعادة تعيين
            </button>
        </div>
    </form>
</div>

<!-- إشعارات النجاح/الخطأ -->
<div id="notification" class="notification"></div>

@endsection

@section('js')
<script>
// متغيرات عامة
let itemCounter = 0;
let invoiceItems = [];
let allProducts = [];
let allSuppliers = [];

// تهيئة الصفحة
$(document).ready(function() {
    // تطبيق التصميم العصري المستقر
    $('body').attr('data-modern-invoice', 'true');

    initializeInvoice();
    setupEventListeners();
    loadProductsAndSuppliers();
    loadStats();

    console.log('تم تحميل فاتورة المشتريات العصرية بنجاح');
});

// تهيئة الفاتورة
function initializeInvoice() {
    $('#supplierSelect').select2({
        placeholder: 'ابحث عن المورد...',
        allowClear: true,
        width: '100%'
    });

    updateInvoiceNumber();
    updateItemsCounter();
}

// إعداد مستمعي الأحداث
function setupEventListeners() {
    $('#smartSearch').on('input', handleSmartSearch);
    $('#discountPercent, #taxPercent').on('input', calculateTotals);
    $('#saveInvoiceBtn').on('click', saveInvoice);

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.smart-search-container').length) {
            $('#searchResults').hide();
        }
    });
}

// البحث الذكي
function handleSmartSearch() {
    const query = $(this).val().trim();
    console.log('🔍 البحث عن:', query);

    if (query.length < 2) {
        $('#searchResults').hide();
        return;
    }

    showSearchLoading();

    // إذا لم تكن المنتجات محملة، حاول البحث المباشر من الخادم
    if (!allProducts || allProducts.length === 0) {
        console.warn('⚠️ المنتجات غير محملة - محاولة البحث المباشر');
        searchProductsDirectly(query);
        return;
    }

    setTimeout(() => {
        console.log('📦 عدد المنتجات المتاحة للبحث:', allProducts.length);

        const results = allProducts.filter(product => {
            const searchTerm = query.toLowerCase();
            const productName = (product.name || '').toLowerCase();
            const productDescription = (product.description || '').toLowerCase();
            const productCode = (product.code || product.sku || '').toLowerCase();

            return productName.includes(searchTerm) ||
                   productDescription.includes(searchTerm) ||
                   productCode.includes(searchTerm);
        }).slice(0, 10);

        console.log('🔍 نتائج البحث:', results.length);
        showSearchResults(results);
    }, 300);
}

// البحث المباشر من الخادم عند عدم توفر المنتجات المحملة
function searchProductsDirectly(query) {
    const searchRoutes = [
        '/products/search?q=' + encodeURIComponent(query),
        '/api/products/search?query=' + encodeURIComponent(query),
        '/products/fetch?search=' + encodeURIComponent(query)
    ];

    let currentSearchRouteIndex = 0;

    function tryDirectSearch() {
        if (currentSearchRouteIndex >= searchRoutes.length) {
            showSearchResults([]);
            showNotification('❌ لا يمكن البحث في المنتجات حالياً', 'error');
            return;
        }

        const url = searchRoutes[currentSearchRouteIndex];
        console.log('🔍 البحث المباشر في:', url);

        $.ajax({
            url: url,
            method: 'GET',
            timeout: 5000,
            success: function(response) {
                console.log('✅ نتائج البحث المباشر:', response);

                let results = [];
                if (response.data && Array.isArray(response.data)) {
                    results = response.data;
                } else if (Array.isArray(response)) {
                    results = response;
                } else if (response.products && Array.isArray(response.products)) {
                    results = response.products;
                }

                showSearchResults(results.slice(0, 10));
            },
            error: function(xhr, status, error) {
                console.error('❌ فشل البحث المباشر من ' + url + ':', error);
                currentSearchRouteIndex++;
                setTimeout(tryDirectSearch, 300);
            }
        });
    }

    tryDirectSearch();
}

function showSearchLoading() {
    $('#searchResults').html(`
        <div class="search-result-item" style="text-align: center;">
            <i class="fas fa-spinner fa-spin"></i> جاري البحث...
        </div>
    `).show();
}

function showSearchResults(results) {
    console.log('📋 عرض نتائج البحث:', results);

    const $results = $('#searchResults');

    if (results.length === 0) {
        $results.html(`
            <div class="search-result-item" style="text-align: center; color: #64748b;">
                <i class="fas fa-search"></i> لم يتم العثور على نتائج
                <div style="font-size: 0.8rem; margin-top: 0.5rem;">جرب كتابة اسم المنتج أو الكود</div>
            </div>
        `).show();
        console.log('⚠️ لا توجد نتائج للعرض');
        return;
    }

    let html = '';
    results.forEach(item => {
        const itemName = item.name || 'منتج غير محدد';
        const itemPrice = parseFloat(item.price) || 0;
        const itemUnit = item.unit || item.unit_name || 'قطعة';
        const itemStock = item.stock || item.quantity || 0;

        const safeItemName = itemName.replace(/'/g, "\\'");
        html += '<div class="search-result-item" onclick="addItemFromSearch(' + item.id + ', \'' + safeItemName + '\', ' + itemPrice + ', \'' + itemUnit + '\')" ' +
                'style="cursor: pointer; transition: all 0.2s ease;">' +
                '<div style="display: flex; align-items: center; gap: 1rem;">' +
                    '<div style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">' +
                        '📦' +
                    '</div>' +
                    '<div style="flex: 1;">' +
                        '<div style="font-weight: 600; color: #1e293b; margin-bottom: 2px;">' + itemName + '</div>' +
                        '<div style="color: #64748b; font-size: 0.875rem; display: flex; gap: 1rem;">' +
                            '<span>💰 ' + itemPrice.toFixed(2) + ' ر.س</span>' +
                            '<span>📦 ' + itemUnit + '</span>' +
                            (itemStock > 0 ? '<span style="color: #059669;">🟢 ' + itemStock + ' متوفر</span>' : '<span style="color: #dc2626;">🔴 غير متوفر</span>') +
                        '</div>' +
                    '</div>' +
                    '<div style="background: #10b981; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">' +
                        '<i class="fas fa-plus"></i>' +
                    '</div>' +
                '</div>' +
            '</div>';
    });

    console.log('✅ تم تجهيز HTML للنتائج');

    $('#searchResults').html(html).show();
}

function addItemFromSearch(id, name, price, unit) {
    itemCounter++;

    const item = {
        id: itemCounter,
        product_id: id,
        name: name,
        unit: unit,
        quantity: 1,
        price: price,
        total: price
    };

    invoiceItems.push(item);
    addItemToTable(item);
    calculateTotals();
    updateItemsCounter();

    $('#smartSearch').val('');
    $('#searchResults').hide();

    showNotification('تم إضافة الصنف بنجاح! ✅', 'success');
}

function addItemToTable(item) {
    const row = '<tr data-item-id="' + item.id + '" style="animation: slideInRight 0.3s ease;" class="cart-item-row">' +
        '<td style="text-align: center; color: #64748b; font-weight: 500;">' + item.id + '</td>' +
        '<td style="font-weight: 600; color: #1e293b;">' +
            '<div style="display: flex; align-items: center; gap: 0.5rem;">' +
                '<span style="font-size: 1.2rem;">📦</span>' +
                item.name +
            '</div>' +
        '</td>' +
        '<td style="text-align: center; color: #64748b; font-weight: 500;">' + item.unit + '</td>' +
        '<td>' +
            '<div class="quantity-controls">' +
                '<button type="button" class="qty-btn minus-btn" onclick="decreaseQuantity(' + item.id + ')" title="تقليل الكمية">' +
                    '<i class="fas fa-minus" style="font-size: 0.7rem;"></i>' +
                '</button>' +
                '<div class="quantity-display" style="' +
                    'min-width: 60px; text-align: center; font-weight: 700; ' +
                    'background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); ' +
                    'border: 2px solid #e3f2fd; border-radius: 10px; padding: 8px 12px; ' +
                    'margin: 0 6px; transition: all 0.3s ease; ' +
                    'box-shadow: 0 2px 8px rgba(0,0,0,0.05); color: #2563eb; ' +
                    'position: relative; overflow: hidden;' +
                '">' +
                    '<div style="font-size: 1rem; line-height: 1;">' + item.quantity + '</div>' +
                    '<small style="font-size: 0.65rem; color: #64748b; margin-top: 2px; display: block;">قطعة</small>' +
                '</div>' +
                '<button type="button" class="qty-btn plus-btn" onclick="increaseQuantity(' + item.id + ')" title="زيادة الكمية">' +
                    '<i class="fas fa-plus" style="font-size: 0.7rem;"></i>' +
                '</button>' +
            '</div>' +
        '</td>' +
        '<td>' +
            '<div class="price-controls">' +
                '<input type="number" class="table-input price-input" value="' + item.price + '" ' +
                       'step="0.01" onchange="updateItemPrice(' + item.id + ', this.value)" ' +
                       'style="text-align: center; font-weight: 600;">' +
            '</div>' +
        '</td>' +
        '<td class="item-total" style="font-weight: 700; color: #059669; text-align: center;">' + item.total.toFixed(2) + ' ر.س</td>' +
        '<td style="text-align: center;">' +
            '<button type="button" class="delete-btn" onclick="confirmRemoveItem(' + item.id + ')" title="حذف الصنف من العربة">' +
                '<i class="fas fa-trash-alt" style="font-size: 0.8rem;"></i>' +
                '<span class="btn-text" style="font-size: 0.7rem; margin-right: 4px;">حذف</span>' +
            '</button>' +
        '</td>' +
    '</tr>';

    $('#itemsTableBody').append(row);
    $('#emptyMessage').hide();
}

function updateItemQuantity(itemId, quantity) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item) {
        const newQuantity = Math.max(1, parseFloat(quantity) || 1); // الحد الأدنى 1
        item.quantity = newQuantity;
        item.total = item.quantity * item.price;

        // تحديث القيم في الجدول
        const $quantityDisplay = $('tr[data-item-id="' + itemId + '"] .quantity-display');
        $quantityDisplay.html(
            '<div style="font-size: 1rem; line-height: 1;">' + newQuantity + '</div>' +
            '<small style="font-size: 0.65rem; color: #64748b; margin-top: 2px; display: block;">قطعة</small>'
        );

        $('tr[data-item-id="' + itemId + '"] .item-total').html(item.total.toFixed(2) + ' ر.س');

        // تأثير بصري للتحديث
        const $row = $('tr[data-item-id="' + itemId + '"]');
        $quantityDisplay.addClass('counter-update');
        $row.addClass('updating');

        setTimeout(() => {
            $quantityDisplay.removeClass('counter-update');
            $row.removeClass('updating');
        }, 300);

        calculateTotals();
        updateItemsCounter();
    }
}

// زيادة الكمية
function increaseQuantity(itemId) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item) {
        const newQuantity = item.quantity + 1;
        updateItemQuantity(itemId, newQuantity);

        // تأثير بصري للزيادة
        const $btn = $('tr[data-item-id="' + itemId + '"] .plus-btn');
        $btn.addClass('btn-pulse');
        setTimeout(() => $btn.removeClass('btn-pulse'), 200);

        showNotification('تم زيادة كمية ' + item.name + ' إلى ' + newQuantity + ' ⬆️', 'success');
    }
}

// تقليل الكمية
function decreaseQuantity(itemId) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item && item.quantity > 1) {
        const newQuantity = item.quantity - 1;
        updateItemQuantity(itemId, newQuantity);

        // تأثير بصري للنقصان
        const $btn = $('tr[data-item-id="' + itemId + '"] .minus-btn');
        $btn.addClass('btn-pulse');
        setTimeout(() => $btn.removeClass('btn-pulse'), 200);

        showNotification('تم تقليل كمية ' + item.name + ' إلى ' + newQuantity + ' ⬇️', 'info');
    } else if (item && item.quantity === 1) {
        // إظهار تأكيد للحذف إذا كانت الكمية 1
        if (confirm('هل تريد حذف "' + item.name + '" من العربة؟')) {
            removeItem(itemId);
        }
    }
}

// تأكيد حذف الصنف
function confirmRemoveItem(itemId) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (!item) return;

    // إنشاء رسالة تأكيد مخصصة
    const itemInfo = '\nالصنف: ' + item.name +
                    '\nالكمية: ' + item.quantity + ' ' + item.unit +
                    '\nالسعر: ' + (item.price * item.quantity).toLocaleString('ar-EG') + ' جنيه';

    if (confirm('هل أنت متأكد من حذف هذا الصنف من العربة؟\n' + itemInfo)) {
        removeItem(itemId);
    }
}

function updateItemPrice(itemId, price) {
    const item = invoiceItems.find(i => i.id === itemId);
    if (item) {
        item.price = parseFloat(price) || 0;
        item.total = item.quantity * item.price;
        $('tr[data-item-id="' + itemId + '"] .item-total').text(item.total.toFixed(2) + ' ر.س');
        calculateTotals();
    }
}

function removeItem(itemId) {
    invoiceItems = invoiceItems.filter(item => item.id !== itemId);

    $('tr[data-item-id="' + itemId + '"]').addClass('fadeOut').fadeOut(300, function() {
        $(this).remove();
        if (invoiceItems.length === 0) {
            $('#emptyMessage').show();
        }
        calculateTotals();
        updateItemsCounter();
    });

    showNotification('تم حذف الصنف ❌', 'error');
}

function calculateTotals() {
    const subtotal = invoiceItems.reduce((sum, item) => sum + item.total, 0);
    const discountPercent = parseFloat($('#discountPercent').val()) || 0;
    const taxPercent = parseFloat($('#taxPercent').val()) || 0;

    const discountAmount = subtotal * (discountPercent / 100);
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = taxableAmount * (taxPercent / 100);
    const grandTotal = taxableAmount + taxAmount;

    $('#subtotal').text(subtotal.toFixed(2) + ' ر.س');
    $('#discountAmount').text(discountAmount.toFixed(2) + ' ر.س');
    $('#taxAmount').text(taxAmount.toFixed(2) + ' ر.س');
    $('#grandTotal').text(grandTotal.toFixed(2) + ' ر.س');
}

function updateItemsCounter() {
    const itemsCount = invoiceItems.length;
    const totalQuantity = invoiceItems.reduce((sum, item) => sum + item.quantity, 0);
    const totalValue = invoiceItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    $('#itemsCount').text(itemsCount);

    // تحديث عنوان القسم مع معلومات إضافية
    const $header = $('.items-header h3');
    const $counterElement = $('#itemsCounter');

    if (itemsCount > 0) {
        const itemsText = itemsCount === 1 ? 'صنف واحد' : itemsCount + ' صنف';
        const quantityText = totalQuantity === 1 ? 'قطعة واحدة' : totalQuantity + ' قطعة';
        const valueText = totalValue.toLocaleString('ar-EG', {style: 'currency', currency: 'EGP'});

        $header.html(
            '<i class="fas fa-shopping-cart"></i>' +
            'عربة المشتريات' +
            '<span style="font-size: 0.8rem; font-weight: normal; color: #64748b;">' +
                '(' + quantityText + ' • ' + itemsText + ')' +
            '</span>'
        );

        $counterElement.html(
            '<i class="fas fa-box me-1"></i>' +
            itemsText + ' • ' + quantityText +
            '<br>' +
            '<small style="opacity: 0.8;">' + valueText + '</small>'
        );
        $counterElement.css('background', 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)');
    } else {
        $header.html(`
            <i class="fas fa-shopping-cart"></i>
            عربة المشتريات
        `);

        $counterElement.html(`
            <i class="fas fa-cart-plus me-1"></i>
            العربة فارغة
        `);
        $counterElement.css('background', 'linear-gradient(135deg, #95a5a6, #7f8c8d)');
    }

    // تأثير بصري للعداد
    $counterElement.addClass('counter-update');
    setTimeout(() => $counterElement.removeClass('counter-update'), 300);
}

// تحميل البيانات من الخادم
function loadProductsAndSuppliers() {
    // إعداد CSRF token للطلبات
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    console.log('🔄 بدء تحميل البيانات...');

    // تحميل المنتجات - مع روابط متعددة
    const productRoutes = [
        '/products/fetch',
        '/products-data',
        '/products/list',
        '/branch/products/fetch'
    ];

    let currentProductRouteIndex = 0;

    function tryLoadProducts() {
        if (currentProductRouteIndex >= productRoutes.length) {
            console.error('❌ فشل في تحميل المنتجات من جميع الروابط');
            allProducts = [];
            showNotification('❌ فشل في تحميل المنتجات', 'error');
            return;
        }

        const url = productRoutes[currentProductRouteIndex];
        console.log('🔄 محاولة تحميل المنتجات من: ' + url);

        $.ajax({
            url: url,
            method: 'GET',
            timeout: 10000,
            success: function(response) {
            console.log('📦 استجابة المنتجات:', response);

            if (response.data && Array.isArray(response.data)) {
                allProducts = response.data;
                console.log('✅ تم العثور على المنتجات في response.data');
            } else if (Array.isArray(response)) {
                allProducts = response;
                console.log('✅ تم العثور على المنتجات في response مباشرة');
            } else if (response.products && Array.isArray(response.products)) {
                allProducts = response.products;
                console.log('✅ تم العثور على المنتجات في response.products');
            } else {
                allProducts = [];
                console.warn('⚠️ لم يتم العثور على مصفوفة المنتجات في الاستجابة');
            }

            console.log('📊 عدد المنتجات المحملة:', allProducts.length);
            if (allProducts.length > 0) {
                console.log('📦 أول منتج:', allProducts[0]);
                showNotification('✅ تم تحميل ' + allProducts.length + ' منتج', 'success');
            } else {
                showNotification('⚠️ لا توجد منتجات في المخزون', 'warning');
            }
            },
            error: function(xhr, status, error) {
                console.error('❌ خطأ في تحميل المنتجات من ' + url + ':', {xhr, status, error});

                if (xhr.status === 404 || xhr.status === 0) {
                    console.log('🔄 جربة المسار التالي...');
                    currentProductRouteIndex++;
                    setTimeout(tryLoadProducts, 500);
                } else {
                    allProducts = [];
                    showNotification('❌ فشل في تحميل المنتجات', 'error');
                }
            }
        });
    }

    // بدء تحميل المنتجات
    tryLoadProducts();

    // تحميل الموردين - مع روابط متعددة
    const supplierRoutes = [
        '/suppliers-company/get',
        '/branch/suppliers-company/get',
        '/suppliers/customers'
    ];

    let currentSupplierRouteIndex = 0;

    function tryLoadSuppliers() {
        if (currentSupplierRouteIndex >= supplierRoutes.length) {
            console.error('❌ فشل في تحميل الموردين من جميع الروابط');
            allSuppliers = [];
            showNotification('❌ فشل في تحميل الموردين', 'error');
            return;
        }

        const url = supplierRoutes[currentSupplierRouteIndex];
        console.log('🔄 محاولة تحميل الموردين من: ' + url);

        $.ajax({
            url: url,
            method: 'GET',
            timeout: 10000,
            success: function(response) {
            console.log('🏭 استجابة الموردين:', response);

            if (response.data && Array.isArray(response.data)) {
                allSuppliers = response.data;
            } else if (Array.isArray(response)) {
                allSuppliers = response;
            } else if (response.suppliers && Array.isArray(response.suppliers)) {
                allSuppliers = response.suppliers;
            } else {
                allSuppliers = [];
            }

            console.log('📊 عدد الموردين المحملين:', allSuppliers.length);
            loadSuppliersInSelect();

            if (allSuppliers.length === 0) {
                showNotification('⚠️ لا يوجد موردين - يمكنك إضافة مورد جديد', 'info');
            } else {
                showNotification('✅ تم تحميل ' + allSuppliers.length + ' مورد', 'success');
            }
            },
            error: function(xhr, status, error) {
                console.error('❌ خطأ في تحميل الموردين من ' + url + ':', {xhr, status, error});

                if (xhr.status === 404 || xhr.status === 0) {
                    console.log('🔄 جربة المسار التالي...');
                    currentSupplierRouteIndex++;
                    setTimeout(tryLoadSuppliers, 500);
                } else {
                    allSuppliers = [];
                    showNotification('❌ فشل في تحميل الموردين', 'error');
                }
            }
        });
    }

    // بدء تحميل الموردين
    tryLoadSuppliers();
    });

    // إتاحة دوال للاختبار في وحدة التحكم
    window.testProducts = function() {
        console.log('🧪 اختبار المنتجات:');
        console.log('📊 عدد المنتجات:', allProducts.length);
        console.log('📦 جميع المنتجات:', allProducts);
        return allProducts;
    };

    window.testSuppliers = function() {
        console.log('🧪 اختبار الموردين:');
        console.log('📊 عدد الموردين:', allSuppliers.length);
        console.log('🏭 جميع الموردين:', allSuppliers);
        return allSuppliers;
    };
}

// تحميل الموردين في القائمة المنسدلة
function loadSuppliersInSelect() {
    console.log('🔄 بدء تحميل الموردين في القائمة المنسدلة...');

    const $supplierSelect = $('#supplierSelect');
    console.log('🔍 عنصر supplierSelect موجود:', $supplierSelect.length > 0);

    $supplierSelect.empty().append('<option value="">اختر المورد...</option>');

    if (allSuppliers && allSuppliers.length > 0) {
        console.log('✅ إضافة الموردين إلى القائمة...');
        allSuppliers.forEach((supplier, index) => {
            console.log('🏭 إضافة المورد ' + (index + 1) + ':', {id: supplier.id, name: supplier.name});

            const supplierName = supplier.name || supplier.supplier_name || supplier.company_name || 'مورد ' + supplier.id;

            $supplierSelect.append(
                '<option value="' + supplier.id + '">' + supplierName + '</option>'
            );
        });

        console.log('✅ تم إضافة جميع الموردين إلى القائمة');
        console.log('📋 عدد الخيارات في القائمة:', $supplierSelect.find('option').length);

        // تحديث Select2 إذا كان مُفعل
        if ($supplierSelect.hasClass('select2-hidden-accessible')) {
            $supplierSelect.trigger('change');
            console.log('🔄 تم تحديث Select2');
        }
    } else {
        console.warn('⚠️ لا توجد موردين لإضافتهم إلى القائمة');
    }
}

function updateInvoiceNumber() {
    const today = new Date();
    const dateStr = today.getFullYear() +
                   String(today.getMonth() + 1).padStart(2, '0') +
                   String(today.getDate()).padStart(2, '0');
    $('#invoiceNumber').text('PUR-' + dateStr + '-001');
}

function loadStats() {
    console.log('📊 بدء تحميل إحصائيات المشتريات...');

    // تحميل إحصائيات حقيقية من الخادم
    const statsRoutes = [
        '/api/purchases/stats',
        '/purchase-stats',
        '/invoices/purchases/stats'
    ];

    let currentStatsRouteIndex = 0;

    function tryLoadStats() {
        if (currentStatsRouteIndex >= statsRoutes.length) {
            console.warn('⚠️ لا يمكن تحميل الإحصائيات - استخدام قيم افتراضية');
            $('#todayStats').text('0');
            $('#monthStats').text('0');
            $('#pendingStats').text('0');
            return;
        }

        const url = statsRoutes[currentStatsRouteIndex];
        console.log('📊 محاولة تحميل الإحصائيات من:', url);

        $.ajax({
            url: url,
            method: 'GET',
            timeout: 5000,
            success: function(response) {
                console.log('✅ تم تحميل الإحصائيات:', response);

                // تحديث الإحصائيات من الاستجابة
                const todayCount = response.today || response.todayPurchases || 0;
                const monthCount = response.month || response.monthPurchases || 0;
                const pendingCount = response.pending || response.pendingPurchases || 0;

                $('#todayStats').text(todayCount);
                $('#monthStats').text(monthCount);
                $('#pendingStats').text(pendingCount);

                console.log('📈 الإحصائيات المحملة:', {
                    today: todayCount,
                    month: monthCount,
                    pending: pendingCount
                });
            },
            error: function(xhr, status, error) {
                console.warn('⚠️ فشل تحميل الإحصائيات من ' + url + ':', error);
                currentStatsRouteIndex++;
                setTimeout(tryLoadStats, 300);
            }
        });
    }

    tryLoadStats();
}

function saveInvoice() {
    console.log('🔄 بدء حفظ فاتورة المشتريات...');

    // التحقق من البيانات الأساسية
    const validationErrors = [];

    if (invoiceItems.length === 0) {
        validationErrors.push('يجب إضافة صنف واحد على الأقل');
    }

    if (!$('#supplierSelect').val()) {
        validationErrors.push('يجب اختيار المورد');
    }

    if (!$('#invoiceDate').val()) {
        validationErrors.push('يجب تحديد تاريخ الفاتورة');
    }

    // التحقق من بيانات الأصناف
    invoiceItems.forEach((item, index) => {
        if (!item.product_id && !item.id) {
            validationErrors.push('معرف المنتج مطلوب للصنف ' + (index + 1));
        }
        if (!item.price || parseFloat(item.price) <= 0) {
            validationErrors.push('سعر صحيح مطلوب للصنف ' + (index + 1));
        }
        if (!item.quantity || parseFloat(item.quantity) <= 0) {
            validationErrors.push('كمية صحيحة مطلوبة للصنف ' + (index + 1));
        }
        if (!item.total || parseFloat(item.total) <= 0) {
            validationErrors.push('إجمالي صحيح مطلوب للصنف ' + (index + 1));
        }
    });

    if (validationErrors.length > 0) {
        showNotification('خطأ في البيانات: ' + validationErrors.join(' • '), 'error');
        return;
    }

    // حساب الإجماليات قبل الحفظ
    const subtotal = invoiceItems.reduce((sum, item) => sum + item.total, 0);
    const discountPercent = parseFloat($('#discountPercent').val()) || 0;
    const taxPercent = parseFloat($('#taxPercent').val()) || 15;
    const discountAmount = (subtotal * discountPercent / 100) || 0;
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = (taxableAmount * taxPercent / 100) || 0;
    const grandTotal = taxableAmount + taxAmount;

    console.log('📊 إجماليات المشتريات:', {
        subtotal: subtotal.toFixed(2),
        discountPercent: discountPercent + '%',
        discountAmount: discountAmount.toFixed(2),
        taxPercent: taxPercent + '%',
        taxAmount: taxAmount.toFixed(2),
        grandTotal: grandTotal.toFixed(2)
    });

    // إظهار مؤشر التحميل
    const btn = $('#saveInvoiceBtn');
    btn.prop('disabled', true);
    btn.find('.loading-spinner').show();
    btn.find('i.fas').hide();

    // تجهيز البيانات للإرسال حسب توقعات الـ backend
    const flattenedData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        invoice_number: $('#invoiceNumber').text() || generatePurchaseInvoiceNumber(),
        invoice_date: $('#invoiceDate').val(),
        branch_id: $('#branchSelect').val() || 1,
        supplier_id: $('#supplierSelect').val(),
        subtotal: subtotal.toFixed(2),
        discount: Math.max(0, discountAmount).toFixed(2),
        tax: Math.max(0, taxAmount).toFixed(2),
        total: grandTotal.toFixed(2),
        invoice_type: 'purchase',
        status: 'pending',
        notes: ''
    };

    // إضافة items كحقول منفصلة
    invoiceItems.forEach((item, index) => {
        flattenedData['items[' + index + '][productId]'] = item.product_id || item.id;
        flattenedData['items[' + index + '][product_name]'] = item.name;
        flattenedData['items[' + index + '][quantity]'] = parseFloat(item.quantity);
        flattenedData['items[' + index + '][price]'] = parseFloat(item.price);
        flattenedData['items[' + index + '][total]'] = parseFloat(item.total);
        flattenedData['items[' + index + '][unit]'] = item.unit || 'قطعة';
    });

    console.log('📤 بيانات فاتورة المشتريات للإرسال:', flattenedData);

    // إرسال البيانات إلى الخادم - استخدام الروابط الصحيحة من قاعدة البيانات
    const saveRoutes = [
        '/invoices/purchases/store',
        '/branch/invoices/purchases/store',
        '/invoices/purchase-returns/store'
    ];

    let currentSaveRouteIndex = 0;

    function trySaveInvoice() {
        if (currentSaveRouteIndex >= saveRoutes.length) {
            console.error('❌ فشل في حفظ فاتورة المشتريات من جميع الروابط المحتملة');
            showNotification('❌ خطأ في حفظ الفاتورة - تحقق من الإعدادات', 'error');
            resetSaveButton(btn);
            return;
        }

        const url = saveRoutes[currentSaveRouteIndex];
        console.log('💾 محاولة حفظ فاتورة المشتريات في: ' + url);

        $.ajax({
            url: url,
            method: 'POST',
            data: flattenedData,
            dataType: 'json',
            timeout: 8000,
            success: function(response) {
                console.log('✅ تم حفظ فاتورة المشتريات بنجاح:', response);
                showNotification('تم حفظ فاتورة المشتريات بنجاح! 🎉', 'success');

                // إعادة تعيين الزر
                resetSaveButton(btn);

                // يمكن إعادة توجيه المستخدم أو إعادة تعيين النموذج
                setTimeout(() => {
                    if (confirm('هل تريد إنشاء فاتورة مشتريات جديدة؟')) {
                        resetForm();
                    } else {
                        window.location.href = '/purchases';
                    }
                }, 1500);
            },
            error: function(xhr, status, error) {
                console.error('❌ خطأ في حفظ فاتورة المشتريات:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    responseJSON: xhr.responseJSON
                });

                let errorMessage = 'حدث خطأ أثناء حفظ فاتورة المشتريات! ❌';

                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        // معالجة أخطاء Laravel validation
                        const errors = xhr.responseJSON.errors;
                        const errorList = [];

                        Object.keys(errors).forEach(field => {
                            if (Array.isArray(errors[field])) {
                                errorList.push(field + ': ' + errors[field].join(', '));
                            }
                        });

                        if (errorList.length > 0) {
                            errorMessage = 'أخطاء التحقق:\n' + errorList.join('\n');
                        }
                    }
                } else if (xhr.status === 422) {
                    errorMessage = 'بيانات غير صحيحة - تحقق من جميع الحقول المطلوبة';
                } else if (xhr.status === 500) {
                    errorMessage = 'خطأ في الخادم - حاول مرة أخرى';
                } else if (xhr.status === 0) {
                    errorMessage = 'مشكلة في الاتصال - تحقق من الإنترنت';
                }

                // جربة الرابط التالي إذا فشل الحالي
                if (xhr.status === 404 || xhr.status === 0) {
                    console.log('❌ فشل حفظ فاتورة المشتريات من ' + url + ', جربة الرابط التالي...');
                    currentSaveRouteIndex++;
                    setTimeout(trySaveInvoice, 500);
                    return;
                }

                showNotification(errorMessage, 'error');

                // إعادة تعيين الزر
                resetSaveButton(btn);
            }
        });
    }

    // بدء محاولة حفظ فاتورة المشتريات
    trySaveInvoice();

    // حماية إضافية: إخفاء مؤشر التحميل بعد 6 ثواني كحد أقصى
    setTimeout(() => {
        if (btn.prop('disabled')) {
            console.warn('⚠️ انتهت مهلة الحفظ - إعادة تفعيل الزر');
            resetSaveButton(btn);
            showNotification('⏱️ انتهت مهلة الحفظ - تحقق من الاتصال وحاول مرة أخرى', 'warning');
        }
    }, 6000);

    // حماية مضاعفة في حالة عدم وجود زر الحفظ
    if (!btn.length) {
        console.error('❌ زر الحفظ غير موجود!');
        showNotification('❌ خطأ في واجهة المستخدم - أعد تحميل الصفحة', 'error');
        return;
    }
}

// دالة مساعدة لإعادة تعيين زر الحفظ
function resetSaveButton(btn) {
    btn.prop('disabled', false);
    btn.find('.loading-spinner').hide();
    btn.find('i.fas').show();
}
}

// توليد رقم فاتورة المشتريات
function generatePurchaseInvoiceNumber() {
    const today = new Date();
    const dateStr = today.getFullYear() +
                   String(today.getMonth() + 1).padStart(2, '0') +
                   String(today.getDate()).padStart(2, '0');
    const timeStr = String(today.getHours()).padStart(2, '0') +
                   String(today.getMinutes()).padStart(2, '0');
    return 'PUR-' + dateStr + '-' + timeStr;
}

function showNotification(message, type = 'info') {
    // إزالة الإشعارات السابقة
    const existingNotifications = $('.notification-toast');
    existingNotifications.remove();

    // تحديد الأيقونة والألوان حسب النوع
    let icon, bgColor, textColor;
    switch(type) {
        case 'success':
            icon = '<i class="fas fa-check-circle"></i>';
            bgColor = '#10b981';
            textColor = '#ffffff';
            break;
        case 'error':
            icon = '<i class="fas fa-exclamation-triangle"></i>';
            bgColor = '#ef4444';
            textColor = '#ffffff';
            break;
        case 'warning':
            icon = '<i class="fas fa-exclamation-circle"></i>';
            bgColor = '#f59e0b';
            textColor = '#ffffff';
            break;
        default: // info
            icon = '<i class="fas fa-info-circle"></i>';
            bgColor = '#3b82f6';
            textColor = '#ffffff';
    }

    // إنشاء الإشعار
    const notification = $('<div class="notification-toast"></div>');

    notification.css({
        'position': 'fixed',
        'top': '20px',
        'right': '20px',
        'background': bgColor,
        'color': textColor,
        'padding': '1rem 1.5rem',
        'border-radius': '8px',
        'box-shadow': '0 10px 25px rgba(0,0,0,0.15)',
        'z-index': '10000',
        'display': 'flex',
        'align-items': 'center',
        'gap': '0.75rem',
        'font-weight': '500',
        'max-width': '400px',
        'animation': 'slideInRight 0.3s ease-out'
    });

    notification.html(icon + '<span>' + message + '</span>');

    // إضافة الإشعار للصفحة
    $('body').append(notification);

    // إخفاء الإشعار بعد 4 ثواني
    setTimeout(() => {
        notification.fadeOut(300, () => notification.remove());
    }, 4000);
}

function resetForm() {
    invoiceItems = [];
    itemCounter = 0;
    $('#itemsTableBody').empty();
    $('#emptyMessage').show();
    $('#smartSearch').val('');
    $('#searchResults').hide();
    calculateTotals();
    updateItemsCounter();
    showNotification('تم إعادة تعيين النموذج ✨', 'success');
}

function addQuickItem() {
    const name = prompt('اسم الصنف:');
    const price = parseFloat(prompt('السعر:')) || 0;

    if (name && price > 0) {
        addItemFromSearch(Date.now(), name, price, 'وحدة');
    }
}

function addNewSupplier() {
    showNotification('سيتم فتح نافذة إضافة مورد جديد قريباً... 🚚', 'info');
}
</script>
@endsection
