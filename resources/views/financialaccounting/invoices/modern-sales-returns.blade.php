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
    </style>
@endsection

@section('content')
<div class="modern-invoice-container">
    <!-- رأس الفاتورة -->
    <div class="invoice-header">
        <div class="invoice-title">
            <h1>
                <i class="fas fa-undo"></i>
                مرتجع فاتورة مبيعات جديد
            </h1>
            <div class="invoice-number">
                <i class="fas fa-hashtag"></i>
                <span id="invoiceNumber">RET-{{ date('Ymd') }}-001</span>
            </div>
        </div>
        <p style="margin: 0; opacity: 0.9;">إنشاء وإدارة مرتجع فاتورة مبيعات بطريقة عصرية وذكية</p>
    </div>

    <!-- الإحصائيات السريعة -->
    <div class="quick-stats">
        <div class="stat-card">
            <div class="stat-icon">↩️</div>
            <div class="stat-number" id="todayStats">5</div>
            <div class="stat-label">مرتجعات اليوم</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-number" id="monthStats">87</div>
            <div class="stat-label">مرتجعات الشهر</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-number" id="customersCount">{{ $customers->count() }}</div>
            <div class="stat-label">العملاء</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔄</div>
            <div class="stat-number" id="pendingStats">2</div>
            <div class="stat-label">معلقة</div>
        </div>
    </div>

    <form id="modernSalesReturnsForm">
        <!-- معلومات الفاتورة الأساسية -->
        <div class="invoice-info-section">
            <h3 style="margin-bottom: 1.5rem; color: var(--dark);">
                <i class="fas fa-info-circle"></i> معلومات المرتجع
            </h3>

            <div class="info-grid">
                <!-- معلومات المرتجع -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-file-invoice"></i>
                        تفاصيل المرتجع
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">التاريخ</label>
                        <input type="date" class="modern-input" id="returnDate" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">الفرع</label>
                        <select class="modern-select" id="branchSelect">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">الفاتورة الأصلية</label>
                        <input type="text" class="modern-input" id="originalInvoice" placeholder="رقم الفاتورة الأصلية">
                    </div>
                </div>

                <!-- معلومات العميل -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-user-tie"></i>
                        معلومات العميل
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">العميل</label>
                        <select class="modern-select customers" id="customerSelect" style="width: 100%;">
                            <option value="">اختر العميل...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modern-input-group">
                        <label class="modern-label">سبب الإرجاع</label>
                        <select class="modern-select" id="returnReason">
                            <option value="">اختر السبب...</option>
                            <option value="defective">منتج معيب</option>
                            <option value="wrong_item">صنف خاطئ</option>
                            <option value="customer_request">طلب العميل</option>
                            <option value="quality_issue">مشكلة في الجودة</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                </div>

                <!-- معلومات الشركة -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-building"></i>
                        معلومات الشركة
                    </div>
                    <h4 style="margin: 0.5rem 0; color: var(--primary-solid);">{{ getCompany()->name }}</h4>
                    <p style="margin: 0.25rem 0; color: #64748b;">{{ getCompany()->address }}</p>
                    <p style="margin: 0.25rem 0; color: #64748b;">الرقم الضريبي: {{ getCompany()->tax_number }}</p>
                </div>
            </div>
        </div>

        <!-- البحث الذكي -->
        <div class="smart-search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="smart-search-input" id="smartSearch"
                   placeholder="🔍 ابحث عن المنتجات المراد إرجاعها...">
            <div class="search-results" id="searchResults"></div>
        </div>

        <!-- قسم الأصناف المرتجعة -->
        <div class="items-section">
            <div class="items-header">
                <h3>
                    <i class="fas fa-undo"></i>
                    الأصناف المرتجعة
                </h3>
                <div class="items-counter">
                    <span id="itemsCount">0</span> صنف
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
                        <th style="width: 150px;">🔍 سبب الإرجاع</th>
                        <th style="width: 80px;">حذف</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
                </tbody>
            </table>

            <!-- رسالة فارغة -->
            <div id="emptyMessage" style="text-align: center; padding: 3rem; color: #64748b;">
                <i class="fas fa-undo" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <p>لم يتم إضافة أي أصناف للإرجاع بعد</p>
                <p>استخدم البحث الذكي أعلاه لإضافة المنتجات</p>
            </div>
        </div>

        <!-- أزرار الإجراءات السريعة -->
        <div class="action-buttons">
            <button type="button" class="modern-btn btn-info" onclick="addQuickItem()">
                <i class="fas fa-plus"></i>
                إضافة صنف سريع
            </button>
            <button type="button" class="modern-btn btn-outline" onclick="searchOriginalInvoice()">
                <i class="fas fa-search"></i>
                البحث في الفاتورة الأصلية
            </button>
        </div>

        <!-- ملخص المرتجع -->
        <div class="invoice-summary">
            <div class="summary-grid">
                <div>
                    <h3 style="margin-bottom: 1rem; color: var(--dark);">
                        <i class="fas fa-calculator"></i>
                        ملخص المرتجع
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
                        <span>🔥 إجمالي المرتجع:</span>
                        <span id="grandTotal" style="color: var(--danger); font-size: 1.5rem;">0.00 ر.س</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الحفظ والإجراءات -->
        <div class="action-buttons">
            <button type="button" class="modern-btn btn-primary" id="saveReturnBtn">
                <i class="fas fa-save"></i>
                <span class="loading-spinner"></span>
                حفظ المرتجع
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
let returnItems = [];
let allProducts = @json($products);
let allCustomers = @json($customers);

// تهيئة الصفحة
$(document).ready(function() {
    // تطبيق التصميم العصري المستقر
    $('body').attr('data-modern-invoice', 'true');

    initializeReturn();
    setupEventListeners();
    loadStats();

    console.log('تم تحميل مرتجع المبيعات العصري بنجاح');
});

// تهيئة المرتجع
function initializeReturn() {
    $('#customerSelect').select2({
        placeholder: 'ابحث عن العميل...',
        allowClear: true,
        width: '100%'
    });

    updateReturnNumber();
    updateItemsCounter();
}

// إعداد مستمعي الأحداث
function setupEventListeners() {
    $('#smartSearch').on('input', handleSmartSearch);
    $('#discountPercent, #taxPercent').on('input', calculateTotals);
    $('#saveReturnBtn').on('click', saveReturn);

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.smart-search-container').length) {
            $('#searchResults').hide();
        }
    });
}

// البحث الذكي
function handleSmartSearch() {
    const query = $(this).val().trim();

    if (query.length < 2) {
        $('#searchResults').hide();
        return;
    }

    showSearchLoading();

    setTimeout(() => {
        const results = allProducts.filter(product => {
            const searchTerm = query.toLowerCase();
            return product.name.toLowerCase().includes(searchTerm) ||
                   product.description?.toLowerCase().includes(searchTerm);
        }).slice(0, 10);

        showSearchResults(results);
    }, 300);
}

function showSearchLoading() {
    $('#searchResults').html(`
        <div class="search-result-item" style="text-align: center;">
            <i class="fas fa-spinner fa-spin"></i> جاري البحث...
        </div>
    `).show();
}

function showSearchResults(results) {
    if (results.length === 0) {
        $('#searchResults').html(`
            <div class="search-result-item" style="text-align: center; color: #64748b;">
                <i class="fas fa-search"></i> لم يتم العثور على نتائج
            </div>
        `).show();
        return;
    }

    let html = '';
    results.forEach(item => {
        html += `
            <div class="search-result-item" onclick="addItemFromSearch(${item.id}, '${item.name.replace(/'/g, '\\'')}', ${item.price}, '${item.unit || 'وحدة'}')">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 40px; height: 40px; background: var(--danger-light); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        ↩️
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 500;">${item.name}</div>
                        <div style="color: #64748b; font-size: 0.875rem;">${item.price} ر.س / ${item.unit || 'وحدة'}</div>
                    </div>
                    <i class="fas fa-plus" style="color: var(--danger);"></i>
                </div>
            </div>
        `;
    });

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
        total: price,
        return_reason: 'other'
    };

    returnItems.push(item);
    addItemToTable(item);
    calculateTotals();
    updateItemsCounter();

    $('#smartSearch').val('');
    $('#searchResults').hide();

    showNotification('تم إضافة الصنف للإرجاع! ↩️', 'success');
}

function addItemToTable(item) {
    const row = `
        <tr data-item-id="${item.id}" style="animation: slideInRight 0.3s ease;">
            <td>${item.id}</td>
            <td style="font-weight: 500;">📦 ${item.name}</td>
            <td>${item.unit}</td>
            <td>
                <input type="number" class="table-input quantity-input" value="${item.quantity}"
                       min="1" onchange="updateItemQuantity(${item.id}, this.value)">
            </td>
            <td>
                <input type="number" class="table-input price-input" value="${item.price}"
                       step="0.01" onchange="updateItemPrice(${item.id}, this.value)">
            </td>
            <td class="item-total">${item.total.toFixed(2)} ر.س</td>
            <td>
                <select class="modern-select-small" onchange="updateReturnReason(${item.id}, this.value)">
                    <option value="defective">منتج معيب</option>
                    <option value="wrong_item">صنف خاطئ</option>
                    <option value="customer_request">طلب العميل</option>
                    <option value="quality_issue">مشكلة في الجودة</option>
                    <option value="other" selected>أخرى</option>
                </select>
            </td>
            <td>
                <button type="button" onclick="removeItem(${item.id})"
                        style="background: var(--danger); color: white; border: none; padding: 0.5rem; border-radius: 50%; cursor: pointer;">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#itemsTableBody').append(row);
    $('#emptyMessage').hide();
}

function updateItemQuantity(itemId, quantity) {
    const item = returnItems.find(i => i.id === itemId);
    if (item) {
        item.quantity = parseFloat(quantity) || 1;
        item.total = item.quantity * item.price;
        $(`tr[data-item-id="${itemId}"] .item-total`).text(item.total.toFixed(2) + ' ر.س');
        calculateTotals();
    }
}

function updateItemPrice(itemId, price) {
    const item = returnItems.find(i => i.id === itemId);
    if (item) {
        item.price = parseFloat(price) || 0;
        item.total = item.quantity * item.price;
        $(`tr[data-item-id="${itemId}"] .item-total`).text(item.total.toFixed(2) + ' ر.س');
        calculateTotals();
    }
}

function updateReturnReason(itemId, reason) {
    const item = returnItems.find(i => i.id === itemId);
    if (item) {
        item.return_reason = reason;
    }
}

function removeItem(itemId) {
    returnItems = returnItems.filter(item => item.id !== itemId);

    $(`tr[data-item-id="${itemId}"]`).addClass('fadeOut').fadeOut(300, function() {
        $(this).remove();
        if (returnItems.length === 0) {
            $('#emptyMessage').show();
        }
        calculateTotals();
        updateItemsCounter();
    });

    showNotification('تم حذف الصنف من المرتجع ❌', 'error');
}

function calculateTotals() {
    const subtotal = returnItems.reduce((sum, item) => sum + item.total, 0);
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
    $('#itemsCount').text(returnItems.length);
}

function updateReturnNumber() {
    const today = new Date();
    const dateStr = today.getFullYear() +
                   String(today.getMonth() + 1).padStart(2, '0') +
                   String(today.getDate()).padStart(2, '0');
    $('#invoiceNumber').text(`RET-${dateStr}-001`);
}

function loadStats() {
    setTimeout(() => {
        $('#todayStats').text('5');
        $('#monthStats').text('87');
        $('#pendingStats').text('2');
    }, 1000);
}

function saveReturn() {
    if (returnItems.length === 0) {
        showNotification('يجب إضافة صنف واحد على الأقل للإرجاع! ⚠️', 'error');
        return;
    }

    if (!$('#customerSelect').val()) {
        showNotification('يجب اختيار العميل! ⚠️', 'error');
        return;
    }

    if (!$('#returnReason').val()) {
        showNotification('يجب اختيار سبب الإرجاع! ⚠️', 'error');
        return;
    }

    const btn = $('#saveReturnBtn');
    btn.prop('disabled', true);
    btn.find('.loading-spinner').show();
    btn.find('i.fas').hide();

    setTimeout(() => {
        showNotification('تم حفظ المرتجع بنجاح! 🎉', 'success');
        btn.prop('disabled', false);
        btn.find('.loading-spinner').hide();
        btn.find('i.fas').show();
    }, 2000);
}

function showNotification(message, type) {
    const notification = $('#notification');
    notification.removeClass('success error').addClass(type);
    notification.text(message);
    notification.addClass('show');

    setTimeout(() => {
        notification.removeClass('show');
    }, 3000);
}

function resetForm() {
    returnItems = [];
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

function searchOriginalInvoice() {
    const invoiceNum = prompt('رقم الفاتورة الأصلية:');
    if (invoiceNum) {
        $('#originalInvoice').val(invoiceNum);
        showNotification('سيتم البحث عن الفاتورة وتحميل أصنافها... 🔍', 'info');
    }
}
</script>
@endsection
