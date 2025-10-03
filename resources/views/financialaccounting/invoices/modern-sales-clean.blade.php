@extends('financialaccounting.layouts.master')

@section('css')
<link rel="stylesheet" href="{{ asset('css/modern-invoice-simple.css') }}">
<style>
/* إصلاح نهائي لـ z-index نتائج البحث */
.search-results {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    right: 0 !important;
    background: white !important;
    border: 2px solid #e5e7eb !important;
    border-top: none !important;
    border-radius: 0 0 8px 8px !important;
    max-height: 300px !important;
    overflow-y: auto !important;
    z-index: 999999 !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    display: none !important;
    width: 100% !important;
}

.search-results.show {
    display: block !important;
}

.smart-search-container {
    position: relative !important;
    z-index: 1000 !important;
}

.search-card {
    position: relative !important;
    z-index: 1000 !important;
    overflow: visible !important;
}

.search-card .card-body {
    overflow: visible !important;
}

.left-panel {
    position: relative !important;
    z-index: 100 !important;
}

.invoice-content-grid {
    position: relative !important;
    z-index: 1 !important;
}

/* إصلاح أي تداخل محتمل */
.items-card, .right-panel {
    z-index: 1 !important;
}
</style>
@endsection

@section('content')

<div class="modern-invoice-container">
    <!-- الهيدر العصري -->
    <div class="invoice-header-card">
        <div class="header-content">
            <div class="invoice-title">
                <i class="fas fa-receipt primary-icon"></i>
                <h1>فاتورة المبيعات العصرية</h1>
            </div>
            <div class="invoice-meta">
                <div class="invoice-number">
                    <strong id="invoiceNumber">INV-2024-001</strong>
                </div>
                <div class="invoice-date">
                    <input type="date" class="form-control" id="invoiceDate" value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="stats-row">
        <div class="stat-card stat-primary">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3 id="todayStats">
                    <i class="fas fa-spinner fa-spin" style="font-size: 16px;"></i>
                </h3>
                <p>فواتير اليوم</p>
            </div>
        </div>
        <div class="stat-card stat-success">
            <div class="stat-icon">📈</div>
            <div class="stat-content">
                <h3 id="monthStats">
                    <i class="fas fa-spinner fa-spin" style="font-size: 16px;"></i>
                </h3>
                <p>فواتير الشهر</p>
            </div>
        </div>
        <div class="stat-card stat-info">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3 id="totalAmountStats">
                    <i class="fas fa-spinner fa-spin" style="font-size: 16px;"></i>
                </h3>
                <p>إجمالي المبيعات</p>
            </div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3 id="customersStats">
                    <i class="fas fa-spinner fa-spin" style="font-size: 16px;"></i>
                </h3>
                <p>عدد العملاء</p>
            </div>
        </div>
    </div>

    <!-- محتوى الفاتورة -->
    <div class="invoice-content-grid">
        <!-- الجانب الأيسر: معلومات العميل والبحث -->
        <div class="left-panel">
            <!-- معلومات العميل -->
            <div class="customer-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>معلومات العميل</h3>
                </div>
                <div class="card-body">
                    <div class="form-group" id="customerGroup">
                        <label>👤 اختر العميل</label>
                        <div class="customer-select-container">
                            <select class="form-control select2" id="customerSelect">
                                <option value="">اختر العميل...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="add-customer-btn" onclick="showAddCustomerModal()" title="إضافة عميل جديد">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" id="branchGroup">
                        <label>🏢 الفرع</label>
                        <select class="form-control select2" id="branchSelect">
                            <option value="">اختر الفرع...</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- البحث الذكي -->
            <div class="search-card">
                <div class="card-header">
                    <i class="fas fa-search"></i>
                    <h3>البحث الذكي</h3>
                </div>
                <div class="card-body">
                    <div class="smart-search-container">
                        <div class="search-input-wrapper">
                            <input type="text" class="form-control search-input"
                                   id="smartSearch" placeholder="ابحث عن منتج...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الجانب الأيمن: جدول الأصناف -->
        <div class="right-panel">
            <div class="items-card">
                <div class="card-header">
                    <div class="header-left">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>الأصناف المختارة</h3>
                        <span class="items-counter" id="itemsCount">0</span>
                    </div>
                    <button type="button" class="reset-btn" onclick="resetForm()">
                        <i class="fas fa-redo"></i>
                        إعادة تعيين
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>الكود</th>
                                    <th>اسم الصنف</th>
                                    <th>الكمية</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- سيتم إضافة الأصناف هنا -->
                            </tbody>
                        </table>

                        <!-- رسالة فارغة -->
                        <div class="empty-message" id="emptyMessage">
                            <i class="fas fa-shopping-basket"></i>
                            <p>لم يتم إضافة أي أصناف بعد</p>
                            <small>استخدم البحث الذكي لإضافة الأصناف</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ملخص الفاتورة -->
    <div class="invoice-summary-card">
        <div class="summary-content">
            <div class="summary-left">
                <div class="form-row">
                    <div class="form-group">
                        <label>نسبة الخصم (%)</label>
                        <input type="number" class="form-control" id="discountPercent" value="0" min="0" max="100" step="0.1">
                    </div>
                    <div class="form-group">
                        <label>نسبة الضريبة (%)</label>
                        <input type="number" class="form-control" id="taxPercent" value="15" min="0" max="100" step="0.1">
                    </div>
                </div>
            </div>

            <div class="summary-right">
                <div class="summary-row">
                    <span>المجموع الفرعي:</span>
                    <strong id="subtotal">0.00 ر.س</strong>
                </div>
                <div class="summary-row">
                    <span>الخصم:</span>
                    <strong id="discountAmount" class="text-warning">0.00 ر.س</strong>
                </div>
                <div class="summary-row">
                    <span>الضريبة:</span>
                    <strong id="taxAmount" class="text-info">0.00 ر.س</strong>
                </div>
                <div class="summary-row grand-total">
                    <span>الإجمالي النهائي:</span>
                    <strong id="grandTotal">0.00 ر.س</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار العمليات -->
    <div class="actions-card">
        <button type="button" class="btn btn-success btn-lg" id="saveInvoiceBtn" onclick="saveInvoice()">
            <i class="fas fa-save"></i>
            حفظ الفاتورة
        </button>
        <button type="button" class="btn btn-info btn-lg" onclick="previewInvoice()">
            <i class="fas fa-eye"></i>
            معاينة
        </button>
        <button type="button" class="btn btn-secondary btn-lg" onclick="resetForm()">
            <i class="fas fa-undo"></i>
            إعادة تعيين
        </button>
    </div>
</div>

<!-- نافذة إضافة عميل -->
@include('components.modal', [
    'modalId' => 'addCustomerModal',
    'title' => 'إضافة عميل جديد',
    'body' => 'customer-form'
])

<!-- إشعار -->
<div class="notification" id="notification"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/modern-invoice-functions.js') }}"></script>

<script>
// تهيئة البيانات الأساسية
window.invoiceItems = [];
window.itemCounter = 0;
window.allProducts = @json($products);
window.allCustomers = @json($customers);
window.statsData = @json($stats);

// تهيئة الصفحة
$(document).ready(function() {
    // تطبيق الدوال من الملف الخارجي
    updateInvoiceNumber();
    updateItemsCounter();
    calculateTotals();
    loadStats();

    // تهيئة Select2
    $('.select2').select2({
        dir: 'rtl',
        theme: 'bootstrap4',
        placeholder: 'اختر...'
    });

    // تحميل العملاء
    loadCustomers();

    // إعداد مستمعي الأحداث
    setupEventListeners();

    // إصلاح z-index لنتائج البحث
    if (typeof fixSearchResultsZIndex === 'function') {
        fixSearchResultsZIndex();
    }

    // تحديد الفرع الافتراضي إذا كان هناك فرع واحد فقط
    setTimeout(() => {
        const branchOptions = $('#branchSelect option');
        if (branchOptions.length === 2) { // واحد فارغ + واحد حقيقي
            $('#branchSelect').val(branchOptions.eq(1).val()).trigger('change');
        }

        // تطبيق التأثيرات المتحركة
        addPageAnimations();
    }, 500);
});

// تحميل العملاء
function loadCustomers() {
    $('#customerSelect').empty().append('<option value="">اختر العميل...</option>');

    allCustomers.forEach(customer => {
        $('#customerSelect').append(`
            <option value="${customer.id}">${customer.name}</option>
        `);
    });
}

// إعداد مستمعي الأحداث
function setupEventListeners() {
    // البحث الذكي
    $('#smartSearch').on('input', handleSmartSearch);

    // حسابات الخصم والضريبة
    $('#discountPercent, #taxPercent').on('input', calculateTotals);

    // حفظ الفاتورة
    $('#saveInvoiceBtn').on('click', saveInvoice);

    // تمييز العميل عند الاختيار
    $('#customerSelect').on('change', function() {
        const customerGroup = $('#customerGroup');
        if ($(this).val()) {
            customerGroup.addClass('selected');
            showNotification('تم اختيار العميل ✅', 'success');
        } else {
            customerGroup.removeClass('selected');
        }
    });

    // تمييز الفرع عند الاختيار
    $('#branchSelect').on('change', function() {
        const branchGroup = $('#branchGroup');
        if ($(this).val()) {
            branchGroup.addClass('selected');
            showNotification('تم اختيار الفرع ✅', 'success');
        } else {
            branchGroup.removeClass('selected');
        }
    });

    // إخفاء نتائج البحث عند النقر خارجها
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.smart-search-container').length) {
            $('#searchResults').removeClass('show').hide();
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

    // إظهار مؤشر التحميل
    showSearchLoading();

    // البحث في المنتجات الحقيقية من قاعدة البيانات
    setTimeout(() => {
        const results = allProducts.filter(product => {
            const searchTerm = query.toLowerCase();
            return product.name.toLowerCase().includes(searchTerm) ||
                   product.description?.toLowerCase().includes(searchTerm);
        }).slice(0, 10); // أقصى 10 نتائج

        showSearchResults(results);
    }, 300);
}

// إظهار مؤشر التحميل للبحث
function showSearchLoading() {
    $('#searchResults').html(`
        <div class="search-loading">
            <i class="fas fa-spinner fa-spin"></i>
            <span>جاري البحث...</span>
        </div>
    `).css({
        'position': 'absolute',
        'z-index': '999999',
        'background': 'white',
        'border': '2px solid #e5e7eb',
        'border-top': 'none',
        'border-radius': '0 0 8px 8px',
        'width': '100%',
        'top': '100%',
        'left': '0',
        'right': '0'
    }).show();
}

// إظهار نتائج البحث
function showSearchResults(results) {
    const resultsContainer = $('#searchResults');

    if (results.length === 0) {
        resultsContainer.html(`
            <div class="no-results">
                <i class="fas fa-search"></i>
                <p>لا توجد نتائج مطابقة</p>
            </div>
        `);
    } else {
        let html = '<div class="search-results-list">';

        results.forEach(product => {
            const stockClass = product.stock > 10 ? 'stock-good' :
                              product.stock > 0 ? 'stock-low' : 'stock-out';

            html += `
                <div class="search-result-item" onclick="addItemFromSearch(${product.id}, '${product.name}', ${product.price})">
                    <div class="item-info">
                        <div class="item-name">📦 ${product.name}</div>
                        <div class="item-details">
                            <span class="item-price">${parseFloat(product.price).toFixed(2)} ر.س</span>
                            <span class="item-stock ${stockClass}">متوفر: ${product.stock}</span>
                        </div>
                    </div>
                    <div class="add-btn">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        resultsContainer.html(html);
    }

    // تطبيق الـ CSS المباشر وإظهار النتائج
    resultsContainer.css({
        'position': 'absolute',
        'z-index': '999999',
        'background': 'white',
        'border': '2px solid #e5e7eb',
        'border-top': 'none',
        'border-radius': '0 0 8px 8px',
        'box-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
        'max-height': '300px',
        'overflow-y': 'auto',
        'width': '100%',
        'top': '100%',
        'left': '0',
        'right': '0'
    }).addClass('show').show();
}

// إظهار نافذة إضافة عميل
function showAddCustomerModal() {
    showModal('addCustomerModal');
}

// إظهار النافذة المنبثقة
function showModal(modalId) {
    $(`#${modalId}`).show();
}

// إغلاق النافذة المنبثقة
function closeModal() {
    $('.invoice-form-modal').hide();
}

// إغلاق النافذة عند النقر خارجها
$(document).on('click', '.invoice-form-modal', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// إغلاق النافذة بـ ESC
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// معاينة الفاتورة
function previewInvoice() {
    const items = getInvoiceItems();
    if (items.length === 0) {
        showNotification('يجب إضافة صنف واحد على الأقل للمعاينة! ⚠️', 'warning');
        return;
    }

    showNotification('تم فتح معاينة الفاتورة في نافذة جديدة 👁️', 'info');
    // يمكن إضافة منطق المعاينة هنا
    window.open('/invoices/preview', '_blank');
}

// إضافة تأثيرات عند تحميل الصفحة
function addPageAnimations() {
    // تأثير تدريجي للعناصر
    $('.stat-card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(index * 200).animate({
            'opacity': '1'
        }, 500).animate({
            'transform': 'translateY(0)'
        }, 300);
    });

    // تأثير للبطاقات
    $('.customer-card, .search-card').css({
        'opacity': '0',
        'transform': 'translateX(-20px)'
    }).delay(800).animate({
        'opacity': '1'
    }, 600).animate({
        'transform': 'translateX(0)'
    }, 400);
}
</script>
@endsection

@section('js')
<script src="{{ asset('js/modern-invoice-functions.js') }}"></script>
@endsection
