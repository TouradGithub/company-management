@section('css')
    <link rel="stylesheet" href="{{asset("inventory-css/styles.css")}}">
    <link rel="stylesheet" href="{{asset("inventory-css/dashboard-icons.css")}}">
    <link rel="stylesheet" href="{{asset('inventory-css/transfer-multi-select.css')}}">
    <link rel="stylesheet" href="{{asset("inventory-css/waste-disposal.css")}}">
    <link rel="stylesheet" href="{{asset("inventory-css/daily-sales-report.css")}}">

        <link rel="stylesheet" href="{{asset('css/add.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <link rel="stylesheet" href="{{asset("inventory-css/monthly-summary-report.css")}}">
    <link rel="stylesheet" href="{{asset('inventory-css/export-print-styles.css')}}">
    <style>


        #editModal {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
        }

        #editModal .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        #editForm label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        #editForm input,
        #editForm select,
        #editForm textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        #editForm button[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

        #editForm button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* تحسين المظهر على الشاشات الصغيرة */
        @media (max-width: 480px) {
            #editModal .modal-content {
                padding: 20px;
            }

            #editForm label {
                font-size: 14px;
            }

            #editForm input,
            #editForm select,
            #editForm textarea {
                font-size: 13px;
            }
        }


    </style>
    <style>
    .container {
        max-width: 100%;
        padding: 0;
        margin: 0;
        }
</style>
@endsection
@extends('financialaccounting.layouts.master')
@section('content')

    <div style="display:block;" class="container">
        <header>
            <h1>تكاليف الوحدات المباعة</h1>
            <div id="dashboard-view" class="dashboard-grid">
                <!-- Dashboard icons will be loaded by dashboardHandler.js -->
            </div>
            <div class="tabs" style="display: none;">
                <button class="tab-btn " data-tab="add-product">إضافة منتج</button>
                <button class="tab-btn " data-tab="sale-report">تقرير فواتير المبيعات</button>
                <button class="tab-btn " data-tab="purchase-report">تقرير فواتير المشتريات</button>
                <button class="tab-btn " data-tab="products-report">تقرير المنتجات</button>
                <button class="tab-btn " data-tab="sales-products">تقرير ربح المنتجات </button>
                <button class="tab-btn " data-tab="sales-bills">تقرير ربح المنتجات </button>
                <button class="tab-btn " data-tab="sales-report">تقرير المبيعات</button>
                <button class="tab-btn active" data-tab="daily-sales-report">تقرير المبيعات اليومي</button>
                <button class="tab-btn" data-tab="monthly-summary-report">التقرير الشهري العام</button>
                <button class="tab-btn" data-tab="cost-detail">تقرير حركة المنتجات والرصيد النهائي </button>
                <button class="tab-btn" data-tab="inventory-summary">ملخص المخزون الفعلي</button>
                <button class="tab-btn" data-tab="profit-cost-report">تقرير الربح والتكلفة</button>
                <button class="tab-btn" data-tab="category-report">تقرير الفئات</button>
                <button class="tab-btn" data-tab="inventory-entry">إدخال الجرد الشهري</button>
                <button class="tab-btn" data-tab="material-transfer">تحويل المواد</button>
                <button class="tab-btn" data-tab="waste-disposal">إتلاف المواد</button>
            </div>
            <button id="back-to-dashboard" class="back-btn" style="display: none;">العودة للرئيسية</button>
        </header>

         <div id="add-product" class="tab-content ">
            <div class="row mb-4">
                <div class="col">
                    <h2><i class="bi bi-box-seam"></i> إضافة منتج جديد</h2>
                </div>
            </div>

            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                        <i class="bi bi-card-text"></i> البيانات الأساسية
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="prices-tab" data-bs-toggle="tab" data-bs-target="#prices" type="button" role="tab">
                        <i class="bi bi-tag"></i> الأسعار
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                        <i class="bi bi-images"></i> الصور
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">
                        <i class="bi bi-boxes"></i> الكميات والمخزون
                    </button>
                </li>
            </ul>

            <div style="display: block" class="tab-content" id="productTabsContent">
                <!-- تبويب البيانات الأساسية -->
                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                    <form id="basicForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productName" class="form-label required-field">اسم المنتج</label>
                                    <input type="text" class="form-control" id="productName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="productCode" class="form-label required-field">كود المنتج</label>
                                    <input type="text" class="form-control" id="productCode" required>
                                    <small class="text-muted">سيتم توليده تلقائياً إذا تركت فارغاً</small>
                                </div>
                                <div class="mb-3">
                                    <label for="productCategory" class="form-label required-field">الفئة</label>
                                    <select class="form-select" id="productCategory" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productBrand" class="form-label">العلامة التجارية</label>
                                    <select class="form-select" id="productBrand">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="productBarcode" class="form-label">باركود المنتج</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="productBarcode">
                                        <button class="btn btn-outline-secondary" type="button" id="generateBarcode">
                                            <i class="bi bi-upc-scan"></i> توليد
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="productStatus" class="form-label">حالة المنتج</label>
                                    <select class="form-select" id="productStatus">
                                        <option value="1" selected>نشط</option>
                                        <option value="0">غير نشط</option>
                                        <option value="2">منتهي الصلاحية</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="productDescription" class="form-label">وصف المنتج</label>
                                    <textarea class="form-control" id="productDescription" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- تبويب الأسعار -->
                <div class="tab-pane fade" id="prices" role="tabpanel">
                    <form id="pricesForm">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="purchasePrice" class="form-label required-field">سعر الشراء</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="purchasePrice" step="0.01" required>
                                        <span class="input-group-text">ر.س</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sellingPrice" class="form-label required-field">سعر البيع</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="sellingPrice" step="0.01" required>
                                        <span class="input-group-text">ر.س</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discountPrice" class="form-label">سعر الخصم</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="discountPrice" step="0.01">
                                        <span class="input-group-text">ر.س</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="taxRate" class="form-label">نسبة الضريبة</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="taxRate" value="15" step="0.1">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priceUnit" class="form-label">وحدة السعر</label>
                                    <select class="form-select" id="priceUnit">
                                        <option value="piece" selected>قطعة</option>
                                        <option value="kg">كيلوجرام</option>
                                        <option value="liter">لتر</option>
                                        <option value="box">علبة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="applyDiscount">
                                    <label class="form-check-label" for="applyDiscount">
                                        تطبيق خصم على هذا المنتج
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- تبويب الصور -->
                <div class="tab-pane fade" id="images" role="tabpanel">
                    <form id="imagesForm">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="mainImage" class="form-label required-field">الصورة الرئيسية</label>
                                    <input type="file" class="form-control" id="mainImage" accept="image/*" required>
                                    <small class="text-muted">الصورة التي تظهر في نتائج البحث والقوائم</small>
                                </div>
                                <div id="mainImagePreview" class="d-none">
                                    <img id="mainImagePreviewImg" class="img-thumbnail" style="max-height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger ms-2" id="removeMainImage">
                                        <i class="bi bi-trash"></i> إزالة
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="additionalImages" class="form-label">صور إضافية</label>
                                    <input type="file" class="form-control" id="additionalImages" accept="image/*" multiple>
                                    <small class="text-muted">يمكنك اختيار أكثر من صورة</small>
                                </div>
                                <div id="additionalImagesPreview" class="d-flex flex-wrap">
                                    <!-- سيتم عرض الصور الإضافية هنا -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- تبويب الكميات والمخزون -->
                <div class="tab-pane fade" id="inventory" role="tabpanel">
                    <form id="inventoryForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="initialStock" class="form-label required-field">الكمية الأولية</label>
                                    <input type="number" class="form-control" id="initialStock" required>
                                </div>
                                <div class="mb-3">
                                    <label for="lowStockAlert" class="form-label">حد التنبيه</label>
                                    <input type="number" class="form-control" id="lowStockAlert" value="5">
                                    <small class="text-muted">سيتم إعلامك عندما تقل الكمية عن هذا الرقم</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="warehouse" class="form-label required-field">المستودع</label>
                                    <select class="form-select" id="warehouse" required>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="stockUnit" class="form-label required-field">وحدة القياس</label>
                                    <select class="form-select" id="stockUnit" required>
                                        <option value="piece" selected>قطعة</option>
                                        <option value="kg">كيلوجرام</option>
                                        <option value="liter">لتر</option>
                                        <option value="box">علبة</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="trackInventory" checked>
                                    <label class="form-check-label" for="trackInventory">
                                        تتبع المخزون لهذا المنتج
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-4">
                <div class="mt-4 d-flex justify-content-between">
                     {{-- <button class="btn btn-secondary" id="cancelForm"><i class="bi bi-x"></i> إلغاء</button>
                    <button class="btn btn-primary" id="saveProduct"><i class="bi bi-save"></i> حفظ مؤقت</button> --}}
                    <button class="btn btn-success" id="submitProductBtn"><i class="bi bi-cloud-upload"></i> إرسال</button>
                </div>
            </div>
        </div>
        <div id="sale-report" class="tab-content ">
            <div class="controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="ابحث عن صنف...">
                    <button id="searchButton">بحث</button>
                </div>
                <div class="filter-box">
                    <select id="filterSelect">
                        <option value="all">جميع الأصناف</option>
                        <option value="الكترونيات">الكترونيات</option>
                        <option value="ملابس">ملابس</option>
                        <option value="أثاث">أثاث</option>
                        <option value="مواد غذائية">مواد غذائية</option>
                    </select>

                </div>
            </div>

            <div class="table-container">
                {{-- <table id="itemsTable"> --}}
                    <table id="sales-itemsTable" border="1">
                        <thead>
                            <tr>
                                <th>مسلسل</th>
                                <th>التاريخ</th>
                                <th>اسم العميل</th>
                                <th>اسم الصنف</th>
                                <th>رقم الصنف</th>
                                <th>رقم الفاتورة</th>
                                <th>عدد الوحدات</th>
                                <th>قيمة المبيعات</th>
                                <th>الضريبة</th>
                                <th>الإجمالي بعد الضريبة</th>
                                <th>متوسط سعر البيع </th>
                                <th>العدد الحالي</th>
                            </tr>
                        </thead>
                        <tbody id="sales-body">
                            <!-- البيانات ستوضع هنا -->
                        </tbody>
                    </table>
            </div>
        </div>
        <div id="purchase-report" class="tab-content ">
            {{-- <div class="controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="ابحث عن صنف...">
                    <button id="searchButton">بحث</button>
                </div>
                <div class="filter-box">
                    <select id="filterSelect">
                        <option value="all">جميع الأصناف</option>
                        <option value="الكترونيات">الكترونيات</option>
                        <option value="ملابس">ملابس</option>
                        <option value="أثاث">أثاث</option>
                        <option value="مواد غذائية">مواد غذائية</option>
                    </select>

                </div>
            </div> --}}

            <div class="table-container">
                <table id="purchase-itemsTable" border="1">
                    <thead>
                        <tr>
                            <th>مسلسل</th>
                            <th>التاريخ</th>
                            <th>اسم المورد  </th>
                            <th>اسم الصنف</th>
                            <th>رقم الصنف</th>
                            <th>رقم الفاتورة</th>
                            <th>عدد الوحدات المشتراه </th>
                            <th>قيمة المشتريات للصنف </th>
                            <th>الضريبة</th>
                            <th>المشتريات  بعد الضريبة </th>
                            <th>متوسط سعر الشراء  </th>
                            <th>العدد الحالي</th>
                        </tr>
                    </thead>
                    <tbody id="purchase-body">
                        <!-- البيانات ستوضع هنا -->
                    </tbody>
                </table>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        fetch('/sales-report') // غيّر الرابط حسب الراوت الفعلي
                            .then(response => response.json())
                            .then(data => {
                                const tbody = document.getElementById('sales-body');
                                tbody.innerHTML = ''; // تفريغ الجدول

                                data.forEach(row => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td>${row.serial}</td>
                                        <td>${row.date}</td>
                                        <td>${row.customer}</td>
                                        <td>${row.product_name}</td>
                                        <td>${row.product_code}</td>
                                        <td>${row.bill_number}</td>
                                        <td>${row.quantity}</td>
                                        <td>${row.total}</td>
                                        <td>${row.tax}</td>
                                        <td>${row.total_with_tax}</td>
                                        <td>${row.midel}</td>
                                        <td>${row.stock}</td>
                                    `;
                                    tbody.appendChild(tr);
                                });
                        });
                        fetch('/purchase-report') // غيّر الرابط حسب الراوت الفعلي
                            .then(response => response.json())
                            .then(data => {
                                const tbody = document.getElementById('purchase-body');
                                tbody.innerHTML = ''; // تفريغ الجدول
                                data.forEach(row => {
                                    const tr = document.createElement('tr');
                                    tr.innerHTML = `
                                        <td>${row.serial}</td>
                                        <td>${row.date}</td>
                                        <td>${row.customer}</td>
                                        <td>${row.product_name}</td>
                                        <td>${row.product_code}</td>
                                        <td>${row.bill_number}</td>
                                        <td>${row.quantity}</td>
                                        <td>${row.total}</td>
                                        <td>${row.tax}</td>
                                        <td>${row.total_with_tax}</td>
                                        <td>${row.midel}</td>
                                        <td>${row.stock}</td>
                                    `;
                                    tbody.appendChild(tr);
                                });
                        });
                    });
                </script>
            </div>
        </div>
        <div id="products-report" class="tab-content ">
            <div class="table-container">
                <table id="products-itemsTable" border="1">
                    <thead>
                        <tr>
                            <th>مسلسل</th>
                            <th>التاريخ</th>
                            <th>اسم الصنف</th>
                            <th>رقم الصنف</th>
                            <th>الفئة</th>
                            <th>العلامة التجارية </th>
                            <th>باركود المنتج  </th>
                            <th>حالة المنتج </th>
                            <th>سعر البيع  </th>
                            <th>سعر الشراء   </th>
                            <th>سعر الخصم </th>
                            <th>نسبة الضريبة  </th>
                            <th>الوحدة   </th>
                            <th>الكمية الأولية    </th>
                            <th>حد التنبية     </th>
                            <th>المستودع     </th>
                            <th>الاجرائات     </th>
                        </tr>
                    </thead>
                    <tbody id="products-body">
                        <!-- البيانات ستوضع هنا -->
                    </tbody>
                </table>
            </div>

            <div id="editModal" style="display:none;">

                <div class="modal-content">
                    <form id="editForm">
                        <input type="hidden" name="id" id="edit-id">

                        <label>الاسم:</label>
                        <input type="text" name="name" id="edit-name">

                        <label>الكود:</label>
                        <input type="text" name="code" id="edit-code">

                        <label>القسم:</label>
                        <select name="category_id" id="edit-category">
                            <!-- يتم تعبئة العناصر عبر JavaScript -->
                        </select>

                        <label>الماركة:</label>
                        <select name="brand_id" id="edit-brand">
                            <!-- يتم تعبئة العناصر عبر JavaScript -->
                        </select>

                        <label>الباركود:</label>
                        <input type="text" name="barcode" id="edit-barcode">

                        <label>الحالة:</label>
                        <select name="status" id="edit-status">
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>

                        <label>وصف:</label>
                        <textarea name="description" id="edit-description"></textarea>

                        <label>سعر الشراء:</label>
                        <input type="number" name="purchase_price" id="edit-purchase-price">

                        <label>سعر البيع:</label>
                        <input type="number" name="selling_price" id="edit-selling-price">

                        <label>سعر الخصم:</label>
                        <input type="number" name="discount_price" id="edit-discount-price">

                        <label>نسبة الضريبة:</label>
                        <input type="number" name="tax_rate" id="edit-tax-rate">

                        <label>وحدة السعر:</label>
                        <input type="text" name="price_unit" id="edit-price-unit">

                        <label>المخزون:</label>
                        <input type="number" name="stock" id="edit-stock">

                        <label>تنبيه المخزون:</label>
                        <input type="number" name="stock_alert" id="edit-stock-alert">

                        <label>المخزن:</label>
                        <select name="warehouse_id" id="edit-warehouse">
                        </select>
                        <button type="submit">حفظ التعديلات</button>
                    </form>
                </div>
            </div>


            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    fetchProductsReport();
                });

                function fetchProductsReport() {
                    fetch("{{ route('report.products') }}")
                        .then(response => response.json())
                        .then(products => {
                            let tbody = document.getElementById("products-body");
                            let status = '';
                            tbody.innerHTML = ""; // تفريغ الجدول أولاً
                            products.forEach((product, index) => {
                                let row = `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${new Date(product.created_at).toLocaleDateString()}</td>
                                        <td>${product.name}</td>
                                        <td>${product.code}</td>
                                        <td>${product.category?.name ?? '---'}</td>
                                        <td>${product.brand.name ?? '---'}</td>
                                        <td>${product.barcode}</td>
                                        <td>${product.status == 1 ? 'نشط' : 'غير نشط'}</td>
                                        <td>${product.selling_price}</td>
                                        <td>${product.purchase_price}</td>
                                        <td>${product.discount_price}</td>
                                        <td>${product.tax_rate}%</td>
                                        <td>${product.price_unit}</td>
                                        <td>${product.stock}</td>
                                        <td>${product.stock_alert}</td>
                                        <td>${product.warehouse?.name ?? '---'}</td>
                                        <td>
                                            <button class="edit-btn" data-id="${product.id}" title="تعديل" style="border: none; background: none;">
                                                <i class="fas fa-edit text-primary"></i>
                                            </button>
                                            <button class="delete-btn" data-id="${product.id}" title="حذف" style="border: none; background: none;">
                                                <i class="fas fa-trash text-danger"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                tbody.innerHTML += row;
                                function pr() {

                    }
                            });
                        })
                        .catch(error => console.error("فشل في جلب التقرير:", error));
                }
                document.addEventListener('DOMContentLoaded', function () {
                    fetchProductsReport();

                    // فتح المودال مع تعبئة البيانات
                    document.getElementById("products-body").addEventListener("click", function (e) {
                        if (e.target.classList.contains("edit-btn")) {
                            const productId = e.target.dataset.id;
                            fetch(`/products/${productId}`)
                                .then(res => res.json())
                                .then(data => {
                                    const product = data.product;
                                    const categories = data.categories;
                                    const brands = data.brands;
                                    const warehouses = data.warehouse;

                                    // تعبئة بيانات المنتج
                                    document.getElementById('edit-id').value = product.id;
                                    document.getElementById('edit-name').value = product.name;
                                    document.getElementById('edit-code').value = product.code;
                                    document.getElementById('edit-barcode').value = product.barcode;
                                    document.getElementById('edit-status').value = product.status;
                                    document.getElementById('edit-description').value = product.description;
                                    document.getElementById('edit-purchase-price').value = product.purchase_price;
                                    document.getElementById('edit-selling-price').value = product.selling_price;
                                    document.getElementById('edit-discount-price').value = product.discount_price;
                                    document.getElementById('edit-tax-rate').value = product.tax_rate;
                                    document.getElementById('edit-price-unit').value = product.price_unit;
                                    document.getElementById('edit-stock').value = product.stock;
                                    document.getElementById('edit-stock-alert').value = product.stock_alert;
                                    // document.getElementById('edit-warehouse').value = product.warehouse_id;

                                    // تعبئة قائمة الأقسام
                                    const categorySelect = document.getElementById('edit-category');
                                    categorySelect.innerHTML = '';
                                    categories.forEach(cat => {
                                        const option = document.createElement('option');
                                        option.value = cat.id;
                                        option.text = cat.name;
                                        if (cat.id == product.category_id) option.selected = true;
                                        categorySelect.appendChild(option);
                                    });

                                    // تعبئة قائمة الماركات
                                    const brandSelect = document.getElementById('edit-brand');
                                    brandSelect.innerHTML = '';
                                    brands.forEach(brand => {
                                        const option = document.createElement('option');
                                        option.value = brand.id;
                                        option.text = brand.name;
                                        if (brand.id == product.brand_id) option.selected = true;
                                        brandSelect.appendChild(option);
                                    });
                                    const warehouseSelect = document.getElementById('edit-warehouse');
                                    warehouseSelect.innerHTML = '';
                                    warehouses.forEach(warehouse => {
                                        const option = document.createElement('option');
                                        option.value = warehouse.id;
                                        option.text = warehouse.name;
                                        if (warehouse.id == product.warehouse_id) option.selected = true;
                                        warehouseSelect.appendChild(option);
                                    });

                                    // عرض المودال
                                    document.getElementById('editModal').style.display = 'block';
                                });
                        };
                        // حذف المنتج
                        if (e.target.classList.contains("delete-btn")) {
                            const productId = e.target.dataset.id;
                            if (confirm("هل أنت متأكد من الحذف؟")) {
                                fetch(`/products/${productId}`, {
                                    method: "DELETE",
                                    headers: {
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    alert("تم الحذف");
                                    fetchProductsReport(); // إعادة تحميل البيانات بدون reload
                                });
                            }
                        }
                    });

                    // إرسال التعديلات
                    // حفظ التعديل
                    document.getElementById('editForm').addEventListener('submit', function (e) {
                            e.preventDefault();
                            const id = document.getElementById('edit-id').value;
                            const formData = new FormData(this);

                            fetch(`/products/${id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                alert('تم التعديل بنجاح');
                                document.getElementById('editModal').style.display = 'none';
                                fetchProductsReport(); // إعادة تحميل البيانات بدون reload
                            });
                    });
                });

                    // حذف منتج
                    // document.querySelectorAll('.delete-btn').forEach(button => {
                    //     button.addEventListener('click', function () {
                    //         const productId = this.dataset.id;
                    //         if (confirm('هل أنت متأكد من الحذف؟')) {
                    //             fetch(`/products/${productId}`, {
                    //                 method: 'DELETE',
                    //                 headers: {
                    //                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    //                 }
                    //             })
                    //             .then(res => res.json())
                    //             .then(data => {
                    //                 alert('تم الحذف');
                    //                 location.reload();
                    //             });
                    //         }
                    //     });
                    // });
                // });
            </script>

        </div>
        <div id="sales-products" class="tab-content ">
            <div class="table-container">
                <table id="profit-table" border="1">
                    <thead>
                        <tr>
                            <th>اسم الصنف</th>
                            <th>رقم الصنف</th>
                            <th>الكمية المباعة</th>
                            <th>متوسط سعر البيع  </th>
                            <th>قيمة المباع   </th>
                            <th>متوسط سعر الشراء  </th>
                            <th>قيمة المشتريات   </th>
                            <th>إجمالي الربح   </th>
                        </tr>
                    </thead>
                    <tbody id="profit-body">
                        <!-- البيانات ستوضع هنا -->
                    </tbody>
                </table>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    fetch('/reports/profit')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("فشل في جلب البيانات");
                            }
                            return response.json();
                        })
                        .then(data => {
                            const tbody = document.getElementById('profit-body');
                            tbody.innerHTML = ''; // تفريغ الجدول قبل الإضافة

                            data.forEach(row => {
                                const tr = document.createElement('tr');

                                tr.innerHTML = `
                                    <td>${row.name}</td>
                                    <td>${row.product_id}</td>
                                    <td>${row.quantity_sold}</td>
                                    <td>${row.avg_selling_price}</td>
                                    <td>${row.total_selling}</td>
                                    <td>${row.avg_purchase_price}</td>
                                    <td>${row.total_purchase}</td>
                                    <td>${row.profit}</td>
                                `;


                                tbody.appendChild(tr);
                            });
                        })
                        .catch(error => {
                            alert("حدث خطأ أثناء تحميل البيانات");
                            console.error(error);
                        });
                });
                </script>

        </div>
        <div id="sales-bills" class="tab-content ">
            <div class="table-container">
                <table id="sales-bills-table" border="1">
                    <thead>
                        <tr>
                            <th>رقم الفاتورة </th>
                            <th>التاريخ </th>
                            <th>اجمالي قيمة الفاتورة </th>
                            <th>تكلفة الفاتورة   </th>
                            <th>الربح    </th>
                            <th>نسبة الربح المبيعات  </th>
                        </tr>
                    </thead>
                    <tbody id="sales-bills-body">
                        <!-- البيانات ستوضع هنا -->
                    </tbody>
                </table>
            </div>
            <script>
                fetch('/profit-per-bill') // عدّل الرابط حسب المسار في Laravel
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('sales-bills-body');
                        tbody.innerHTML = '';

                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.number}</td>
                                <td>${row.date}</td>
                                <td>${row.total_selling}</td>
                                <td>${row.total_purchase}</td>
                                <td>${row.profit}</td>
                                <td>${row.profit_percentage}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            </script>

        </div>

        <div id="cost-detail" class="tab-content">
            <div class="report-header">
                <div class="date-range">
                    <div class="date-field">
                        <label for="fromDate">من:</label>
                        <input type="date" id="fromDate">
                    </div>
                    <div class="date-field">
                        <label for="toDate">إلى:</label>
                        <input type="date" id="toDate">
                    </div>
                    <button id="retrieveBtn">عرض</button>
                </div>
            </div>

            <div class="cost-detail-container">
                <table id="costDetailTable">
                    <thead>
                        <tr>
                            <th>رقم المنتج</th>
                            <th>اسم المنتج</th>
                            <th>الفئة </th>
                            <th>وحدة المخزون</th>
                            <th>كمية البداية</th>
                            <th>رصيد البداية </th>
                            <th>قيمة المشتريات</th>
                            <th>كمية المشتريات</th>
                            <th>عدد التحويلات </th>
                            <th>قيمة التحويلات </th>
                            <th>عدد المبيعات </th>
                            <th>قيمة المبيعات </th>
                            <th>عدد التلفيات </th>
                            <th>قيمة التلفيات </th>
                            <th>القيمة الدفترية </th>
                            <th>العدد الدفتري  </th>
                        </tr>
                    </thead>
                    <tbody id="costDetailBody">
                        <!-- البيانات ستضاف هنا عن طريق جافاسكربت -->
                    </tbody>
                </table>
            </div>

            {{-- <div class="summary-charts">
                <div class="chart-container">
                    <canvas id="costBreakdownChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="inventoryValueChart"></canvas>
                </div>
            </div> --}}
        </div>

        <div id="inventory-summary" class="tab-content">
            <div class="report-header">
                <div class="inventory-header">
                    <h2>ملخص المخزون الفعلي</h2>
                    <div class="date-range">
                        <div class="date-field">
                            <label for="fromDateInv">من:</label>
                            <input type="date" id="fromDateInv">
                        </div>
                        <div class="date-field">
                            <label for="toDateInv">إلى:</label>
                            <input type="date" id="toDateInv">
                        </div>
                        <button id="retrieveInvBtn">عرض</button>
                    </div>
                </div>
            </div>

            <div class="inventory-container">
                <table id="inventorySummaryTable">
                    <thead>
                        <tr>
                            <th>رقم المنتج</th>
                            <th>اسم المنتج</th>
                            <th>وحدة القياس</th>
                            <th>الوحدة</th>
                            <th>الكمية المعدلة</th>
                            <th>كمية المخزون الدفتري</th>
                            <th>كمية المخزون الفعلي</th>
                            <th>فرق الكمية</th>
                            <th>فرق القيمة</th>
                            <th>رصيد البداية</th>
                            <th>رصيد الاستلام</th>
                            <th>رصيد البيع</th>
                            <th>رصيد النهاية المعدل</th>
                            <th>رصيد المخزون الفعلي</th>
                            <th>رصيد المخزون العابر</th>
                            <th>فرق المخزون التراكمي</th>
                        </tr>
                    </thead>
                    <tbody id="inventorySummaryBody">
                        <!-- البيانات ستضاف هنا عن طريق جافاسكربت -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="profit-cost-report" class="tab-content">
            <!-- Report will be rendered here by JavaScript -->
        </div>

        <div id="category-report" class="tab-content">
            <div class="report-header">
                <div class="category-header">
                    <h2>تقرير الفئات</h2>
                    <div class="date-range">
                        <div class="date-field">
                            <label for="fromDateCat">من:</label>
                            <input type="date" id="fromDateCat">
                        </div>
                        <div class="date-field">
                            <label for="toDateCat">إلى:</label>
                            <input type="date" id="toDateCat">
                        </div>
                        <button id="retrieveCatBtn" class="report-btn">عرض</button>
                    </div>
                </div>
            </div>

            <div class="category-container">
                <table id="categoryReportTable">
                    <thead>
                        <tr>
                            <th>اسم الفئة</th>
                            <th>المشتريات </th>
                            <th>المبيعات </th>
                            <th>الربح </th>
                        </tr>
                    </thead>
                    <tbody id="categoryReportBody">
                        <!-- البيانات ستضاف هنا عن طريق جافاسكربت -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>الإجمالي</td>
                            <td id="totalPurchase">0.00 ريال</td>
                            <td id="totalProduction">0.00 ريال</td>
                            <td id="totalVariance">0.00 ريال</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="category-chart-container">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div id="inventory-entry" class="tab-content">
            <div class="report-header">
                <div class="inventory-entry-header">
                    <h2>إدخال الجرد الشهري</h2>
                    <div class="entry-controls">
                        <div class="date-field">
                            <label for="entryDate">تاريخ الجرد:</label>
                            <input type="date" id="entryDate">
                        </div>
                        <div class="search-field">
                            <label for="productSearch">بحث عن صنف:</label>
                            <input type="text" id="productSearch" placeholder="أدخل رقم أو اسم الصنف...">
                            <button id="searchEntryBtn" class="report-btn">بحث</button>
                        </div>
                        <!-- Category selector will be added here by JavaScript -->
                    </div>
                </div>
            </div>

            <div class="inventory-entry-container">
                <div class="table-actions">
                    <!-- Bulk add button removed as we now show all items by default -->
                </div>

                <table id="inventoryEntryTable">
                    <thead>
                        <tr>
                            <th>رقم الصنف</th>
                            <th>اسم الصنف</th>
                            <th>الفئة</th>
                            <th>العدد الحالي</th>
                            <th>العدد الفعلي</th>
                            <th>الفروقات</th>
                            <th>قيمة الفروقات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="inventoryEntryBody">
                        <!-- البيانات ستضاف هنا عن طريق جافاسكربت -->
                    </tbody>
                </table>

                <div class="entry-summary">
                    <div class="summary-item">
                        <span class="summary-label">إجمالي الأصناف:</span>
                        <span id="totalItems" class="summary-value">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">إجمالي قيمة الفروقات:</span>
                        <span id="totalVarianceValue" class="summary-value">0.00 ريال</span>
                    </div>
                    <div class="form-actions">
                        <button id="saveInventoryBtn" class="report-btn save-btn">حفظ الجرد</button>
                        <button id="exportInventoryBtn" class="report-btn export-btn">تصدير إلى Excel</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="material-transfer" class="tab-content">
            <div class="transfer-header">
                <h2>نموذج تحويل المواد</h2>

                <div class="transfer-info">
                    <div class="transfer-field">
                        <label for="transferNumber">رقم التحويل:</label>
                        <input type="text" id="transferNumber" readonly>
                    </div>
                    <div class="transfer-field">
                        <label for="transferDate">تاريخ التحويل:</label>
                        <input type="date" id="transferDate">
                    </div>
                </div>

                <div class="branch-selection">
                    <div class="branch-field">
                        <label for="fromBranch">من فرع:</label>
                        <select id="fromBranch">
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="branch-field">
                        <label for="toBranch">إلى فرع:</label>
                        <select id="toBranch">
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="transfer-notes">
                    <label for="transferNotes">ملاحظات:</label>
                    <textarea id="transferNotes" rows="3"></textarea>
                </div>
            </div>

            <div class="transfer-item-selection">
                <h3>إضافة عناصر للتحويل</h3>

                <div class="transfer-filter-controls">
                    <div class="filter-field">
                        <label for="productSearch">بحث عن منتج:</label>
                        <div class="search-input-group">
                            <input type="text" id="productSearch" placeholder="أدخل رقم أو اسم المنتج...">
                            <button id="searchProductBtn" class="search-btn">بحث</button>
                        </div>
                    </div>
                    <!-- Category filter will be added by JavaScript -->
                </div>

                <div class="product-select-container" style="display: none;">
                    <div class="select-field">
                        <label for="productSelect">اختر المنتج:</label>
                        <select id="productSelect">
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="quantity-field">
                        <label for="quantity">الكمية:</label>
                        <input type="number" id="quantity" min="0.01" step="0.01">
                    </div>
                    <button id="addItemBtn" class="add-btn">
                        <i class="fas fa-plus"></i> إضافة
                    </button>
                </div>

                <div class="transfer-items-table-container">
                    <h3>العناصر المضافة للتحويل</h3>
                    <table id="transferItemsTable">
                        <thead>
                            <tr>
                                <th>رقم المنتج</th>
                                <th>اسم المنتج</th>
                                <th>الفئة</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الإجمالي</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="transferItemsBody">
                            <!-- سيتم إضافة البيانات هنا عن طريق JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="transfer-summary">
                    <div class="summary-item">
                        <span class="summary-label">إجمالي العناصر:</span>
                        <span id="totalItems" class="summary-value">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">إجمالي الكمية:</span>
                        <span id="totalQuantity" class="summary-value">0.00</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">إجمالي التكلفة:</span>
                        <span id="totalCost" class="summary-value">0.00 ريال</span>
                    </div>
                    <div class="form-actions">
                        <button id="saveTransferBtn" class="report-btn save-btn">حفظ التحويل</button>
                        <button id="printTransferBtn" class="report-btn print-btn">طباعة</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="waste-disposal" class="tab-content">
            <div class="waste-header">
                <h2>نموذج إتلاف المواد</h2>

                <div class="waste-info">
                    <div class="waste-field">
                        <label for="wasteNumber">رقم الإتلاف:</label>
                        <input type="text" id="wasteNumber" readonly>
                    </div>
                    <div class="waste-field">
                        <label for="wasteDate">تاريخ الإتلاف:</label>
                        <input type="date" id="wasteDate">
                    </div>
                </div>

                <div class="branch-selection">
                    <div class="branch-field">
                        <label for="wasteBranch">الفرع:</label>
                        <select id="wasteBranch">
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="reason-field">
                        <label for="wasteReason">سبب الإتلاف:</label>
                        <select id="wasteReason">
                            <option value="expired">منتهي الصلاحية</option>
                            <option value="damaged">تالف</option>
                            <option value="quality">غير مطابق للجودة</option>
                            <option value="other">أسباب أخرى</option>
                        </select>
                    </div>
                </div>

                <div class="waste-notes">
                    <label for="wasteNotes">ملاحظات:</label>
                    <textarea id="wasteNotes" rows="3"></textarea>
                </div>
            </div>

            <div class="waste-item-selection">
                <h3>إضافة عناصر للإتلاف</h3>

                <div class="waste-filter-controls">
                    <div class="filter-field">
                        <label for="wasteProductSearch">بحث عن منتج:</label>
                        <div class="search-input-group">
                            <input type="text" id="wasteProductSearch" placeholder="أدخل رقم أو اسم المنتج...">
                            <button id="wasteSearchProductBtn" class="search-btn">بحث</button>
                        </div>
                    </div>
                    <div class="filter-field">
                        <label for="wasteCategoryFilter">تصفية حسب الفئة:</label>
                        <select id="wasteCategoryFilter">
                            <option value="">جميع الفئات</option>
                            <!-- Will be populated by JavaScript -->
                        </select>
                    </div>
                    <button class="browse-products-btn" id="wasteBrowseProductsBtn">
                        <i class="fas fa-search"></i> تصفح المنتجات
                    </button>
                </div>

                <div class="waste-items-table-container">
                    <h3>العناصر المضافة للإتلاف</h3>
                    <table id="wasteItemsTable">
                        <thead>
                            <tr>
                                <th>رقم المنتج</th>
                                <th>اسم المنتج</th>
                                <th>الفئة</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الإجمالي</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="wasteItemsBody">
                            <!-- سيتم إضافة البيانات هنا عن طريق JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="waste-summary">
                    <div class="summary-item">
                        <span class="summary-label">إجمالي العناصر:</span>
                        <span id="wasteTotalItems" class="summary-value">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">إجمالي الكمية:</span>
                        <span id="wasteTotalQuantity" class="summary-value">0.00</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">إجمالي التكلفة:</span>
                        <span id="wasteTotalCost" class="summary-value">0.00 ريال</span>
                    </div>
                    <div class="form-actions">
                        <button id="saveWasteBtn" class="report-btn save-btn">حفظ التسجيل</button>
                        {{-- <button id="printWasteBtn" class="report-btn print-btn">طباعة</button> --}}
                    </div>
                </div>
            </div>
        </div>

        <div id="daily-sales-report" class="tab-content">
            <div class="sales-report-header">
                <h2>تقرير المبيعات اليومي</h2>
                <div class="sales-report-date-range">
                    <div class="date-field">
                        <label for="fromDateSales">من:</label>
                        <input type="date" id="fromDateSales">
                    </div>
                    <div class="date-field">
                        <label for="toDateSales">إلى:</label>
                        <input type="date" id="toDateSales">
                    </div>
                    <button id="retrieveSalesBtn" class="report-btn">عرض</button>
                </div>
                <div class="sales-action-buttons">
                    {{-- <button id="exportSalesBtn" class="report-btn export-btn">تصدير</button> --}}
                    <button id="printSalesBtn" class="report-btn print-btn">طباعة</button>
                </div>
            </div>

            <div class="payment-type-summary">
                <div class="payment-card payment-card-cash">
                    <div class="payment-card-title">مبيعات نقدية</div>
                    <div id="cashTotal" class="payment-card-value">0.00 ريال</div>
                </div>
                <div class="payment-card payment-card-electronic">
                    <div class="payment-card-title">دفع الكتروني</div>
                    <div id="electronicTotal" class="payment-card-value">0.00 ريال</div>
                </div>
                <div class="payment-card payment-card-delivery">
                    <div class="payment-card-title">خدمات التوصيل</div>
                    <div id="deliveryTotal" class="payment-card-value">0.00 ريال</div>
                </div>
            </div>

            <div class="sales-report-table-container">
                <table id="salesReportTable">
                    <thead>
                        <tr>
                            {{-- <th>اليوم</th> --}}
                            <th>التاريخ</th>
                            <th>المبيعات قبل الضريبة</th>
                            <th>الضريبة</th>
                            <th>المبيعات الإجمالية</th>
                            <th>هنقرستيشن</th>
                            <th>تويو</th>
                            <th>كيتا</th>
                        </tr>
                    </thead>
                    <tbody id="salesReportTableBody">
                        <!-- البيانات ستضاف هنا عن طريق جافاسكربت -->
                    </tbody>
                </table>
            </div>

            <div class="sales-chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div id="monthly-summary-report" class="tab-content">
            <div class="report-controls">
                <div class="control-group">
                    <div class="control-field">
                        <label for="reportMonth">الشهر:</label>
                        <select id="reportMonth">
                            <option value="1">يناير</option>
                            <option value="2">فبراير</option>
                            <option value="3">مارس</option>
                            <option value="4">أبريل</option>
                            <option value="5">مايو</option>
                            <option value="6">يونيو</option>
                            <option value="7">يوليو</option>
                            <option value="8">أغسطس</option>
                            <option value="9">سبتمبر</option>
                            <option value="10">أكتوبر</option>
                            <option value="11">نوفمبر</option>
                            <option value="12">ديسمبر</option>
                        </select>
                    </div>
                    <div class="control-field">
                        <label for="reportYear">السنة:</label>
                        <select id="reportYear">
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                        </select>
                    </div>
                </div>
                <button id="generateReportBtn" class="report-btn">عرض التقرير</button>
            </div>

            <div class="card-grid">
                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-icon icon-sales">💰</div>
                        <h3 class="card-title">إجمالي المبيعات</h3>
                    </div>
                    <div class="card-value" id="totalSalesValue">0.00 ريال</div>
                    <div class="detail-section">
                        <div class="detail-row">
                            <span class="detail-label">مبيعات نقدية</span>
                            <span class="detail-value" id="cashSalesValue">0.00 ريال</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">دفع إلكتروني</span>
                            <span class="detail-value" id="electronicSalesValue">0.00 ريال</span>
                        </div>
                    </div>
                </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-icon icon-purchases">🛒</div>
                        <h3 class="card-title">إجمالي المشتريات</h3>
                    </div>
                    <div class="card-value" id="totalPurchasesValue">0.00 ريال</div>
                </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-icon icon-inventory">📦</div>
                        <h3 class="card-title">قيمة المخزون الحالي</h3>
                    </div>
                    <div class="card-value" id="currentInventoryValue">0.00 ريال</div>
                </div>

                <div class="financial-card">
                    <div class="card-header">
                        <div class="card-icon icon-profit">📈</div>
                        <h3 class="card-title">صافي الربح</h3>
                    </div>
                    <div class="card-value" id="netProfitValue">0.00 ريال</div>
                    <div class="detail-section">
                        <div class="detail-row">
                            <span class="detail-label">إجمالي المصروفات</span>
                            <span class="detail-value" id="totalExpensesValue">0.00 ريال</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">الإيجار الشهري</span>
                            <span class="detail-value" id="monthlyRentValue">0.00 ريال</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">أجور العمالة</span>
                            <span class="detail-value" id="monthlyWagesValue">0.00 ريال</span>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="section-title">مؤشرات الأداء</h3>
            <div class="performance-grid">
                <div class="performance-card">
                    <div class="performance-icon icon-returns">↩️</div>
                    <h4 class="performance-title">مردودات المشتريات</h4>
                    <div class="performance-value" id="purchaseReturnsValue">0.00 ريال</div>
                    <div class="performance-subtitle" id="purchaseReturnsPercent">0.00%</div>
                </div>

                <div class="performance-card">
                    <div class="performance-icon icon-returns">↪️</div>
                    <h4 class="performance-title">مردودات المبيعات</h4>
                    <div class="performance-value" id="salesReturnsValue">0.00 ريال</div>
                    <div class="performance-subtitle" id="salesReturnsPercent">0.00%</div>
                </div>

                <div class="performance-card">
                    <div class="performance-icon icon-movements">🗑️</div>
                    <h4 class="performance-title">قيمة التلفيات</h4>
                    <div class="performance-value" id="disposalValue">0.00 ريال</div>
                </div>

                <div class="performance-card">
                    <div class="performance-icon icon-movements">🔄</div>
                    <h4 class="performance-title">قيمة المخزون المحول</h4>
                    <div class="performance-value" id="transferredValue">0.00 ريال</div>
                </div>

                <div class="performance-card">
                    <div class="performance-icon icon-profit">💹</div>
                    <h4 class="performance-title">هامش الربح</h4>
                    <div class="performance-value" id="profitMarginValue">0.00%</div>
                </div>

                <div class="performance-card">
                    <div class="performance-icon icon-profit">💸</div>
                    <h4 class="performance-title">إجمالي الربح</h4>
                    <div class="performance-value" id="grossProfitValue">0.00 ريال</div>
                </div>
            </div>

            <h3 class="section-title">تحليل البيانات</h3>
            <div class="chart-section">
                <div class="chart-container">
                    <canvas id="summaryPieChart"></canvas>
                </div>
                <div class="chart-container">
                    <canvas id="summaryBarChart"></canvas>
                </div>
            </div>

            <div class="summary-table-section">
                <h3 class="section-title">ملخص التقرير الشهري</h3>
                <div class="summary-table-container">
                    <table class="summary-table" id="summarySalesTable">
                        <thead>
                            <tr>
                                <th>البيان</th>
                                <th>القيمة</th>
                                <th>النسبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>إجمالي المبيعات</td>
                                <td id="tableSalesValue">0.00 ريال</td>
                                <td>100%</td>
                            </tr>
                            <tr>
                                <td>مبيعات نقدية</td>
                                <td id="tableCashSalesValue">0.00 ريال</td>
                                <td id="tableCashSalesPercent">0%</td>
                            </tr>
                            <tr>
                                <td>مبيعات دفع إلكتروني</td>
                                <td id="tableElectronicSalesValue">0.00 ريال</td>
                                <td id="tableElectronicSalesPercent">0%</td>
                            </tr>
                            <tr>
                                <td>إجمالي المشتريات</td>
                                <td id="tablePurchasesValue">0.00 ريال</td>
                                <td id="tablePurchasesPercent">0%</td>
                            </tr>
                            <tr>
                                <td>مردودات المشتريات</td>
                                <td id="tablePurchaseReturnsValue">0.00 ريال</td>
                                <td id="tablePurchaseReturnsPercent">0%</td>
                            </tr>
                            <tr>
                                <td>مردودات المبيعات</td>
                                <td id="tableSalesReturnsValue">0.00 ريال</td>
                                <td id="tableSalesReturnsPercent">0%</td>
                            </tr>
                            <tr>
                                <td>قيمة المخزون الحالي</td>
                                <td id="tableInventoryValue">0.00 ريال</td>
                                <td id="tableInventoryPercent">0%</td>
                            </tr>
                            <tr>
                                <td>قيمة التلفيات</td>
                                <td id="tableDisposalValue">0.00 ريال</td>
                                <td id="tableDisposalPercent">0%</td>
                            </tr>
                            <tr>
                                <td>قيمة المخزون المحول</td>
                                <td id="tableTransferredValue">0.00 ريال</td>
                                <td id="tableTransferredPercent">0%</td>
                            </tr>
                            <tr>
                                <td>إجمالي المصروفات</td>
                                <td id="tableExpensesValue">0.00 ريال</td>
                                <td id="tableExpensesPercent">0%</td>
                            </tr>
                            <tr>
                                <td>الإيجار الشهري</td>
                                <td id="tableRentValue">0.00 ريال</td>
                                <td id="tableRentPercent">0%</td>
                            </tr>
                            <tr>
                                <td>أجور العمالة الشهرية</td>
                                <td id="tableWagesValue">0.00 ريال</td>
                                <td id="tableWagesPercent">0%</td>
                            </tr>
                            <tr>
                                <td>إجمالي الربح</td>
                                <td id="tableGrossProfitValue">0.00 ريال</td>
                                <td id="tableGrossProfitPercent">0%</td>
                            </tr>
                            <tr>
                                <td>صافي الربح العام للفرع</td>
                                <td id="tableNetProfitValue">0.00 ريال</td>
                                <td id="tableNetProfitPercent">0%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('main-js/add.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{asset('inventory-js/dashboardHandler.js')}}"></script>
    <script src="{{asset('inventory-js/categoryReportHandler.js')}}"></script>
    <script src="{{asset('inventory-js/transferHandler.js')}}"></script>
    <script src="{{asset('inventory-js/transferUIHelper.js')}}"></script>
    <script src="{{asset("inventory-js/reportViewer.js")}}"></script>
    <script src="{{asset("inventory-js/inventoryEntryHandler.js")}}"></script>
    <script src="{{asset("inventory-js/bulkInventoryHandler.js")}}"></script>
    <script src="{{asset("inventory-js/wasteDisposalHandler.js")}}"></script>
    <script src="{{asset("inventory-js/dailySalesReportHandler.js")}}"></script>
    <script src="{{asset("inventory-js/monthlyReportHandler.js")}}"></script>
    {{-- <script src="{{asset("inventory-js/exportPrintUtils.js")}}"></script> --}}
    <script src="{{asset('inventory-js/reportExportHandler.js')}}"></script>
    <script src="{{asset("inventory-js/script.js")}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('/getselects')
        .then(response => response.json())
        .then(data => {
            // تعبئة المستودعات
            const warehouseSelect = document.getElementById('warehouse');
            data.warehouse.forEach(item => {
                let option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                warehouseSelect.appendChild(option);
            });

            // تعبئة العلامات التجارية
            const brandSelect = document.getElementById('productBrand');
            data.brands.forEach(item => {
                let option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                brandSelect.appendChild(option);
            });

            // تعبئة الفئات
            const categorySelect = document.getElementById('productCategory');
            data.categories.forEach(item => {
                let option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                categorySelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('خطأ في جلب البيانات:', error);
        });
});
</script>

<script>
    initializeProductFormEvents();
    function initializeProductFormEvents() {
        // توليد الباركود
        const barcodeBtn = document.getElementById('generateBarcode');
        if (barcodeBtn) {
            barcodeBtn.addEventListener('click', function () {
                const randomBarcode = Math.floor(100000000000 + Math.random() * 900000000000).toString();
                document.getElementById('productBarcode').value = randomBarcode;
            });
        }


        // معاينة الصورة الرئيسية
        const mainImageInput = document.getElementById('mainImage');
        if (mainImageInput) {
            mainImageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        document.getElementById('mainImagePreviewImg').src = event.target.result;
                        document.getElementById('mainImagePreview').classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // حفظ المنتج
        const saveBtn = document.getElementById('saveProduct');
        if (saveBtn) {
            saveBtn.addEventListener('click', function () {
                // التحقق من صحة البيانات الأساسية
                if (!document.getElementById('productName').value) {
                    alert('يرجى إدخال اسم المنتج');
                    document.getElementById('basic-tab').click();
                    return;
                }

                // يمكنك متابعة التحقق هنا...

                alert('تم حفظ المنتج بنجاح!');
            });
        }

        // زر الإرسال عبر API
        const submitBtn = document.getElementById('submitProductBtn');
        if (submitBtn) {
            submitBtn.addEventListener('click', async function () {
                const formData = new FormData();
                // تحقق من الباركود، إذا كان فارغًا يتم توليد واحد تلقائيًا
                let barcode = document.getElementById("productCode").value.trim();
                if (!barcode) {
                    barcode = Math.floor(100000000000 + Math.random() * 900000000000).toString();
                    document.getElementById("productCode").value = barcode; // عرض الباركود في الحقل
                }
                // اجمع البيانات من كل تبويب
                formData.append("name", document.getElementById("productName").value);
                formData.append("code", document.getElementById("productCode").value);
                formData.append("category_id", document.getElementById("productCategory").value);
                formData.append("brand_id", document.getElementById("productBrand").value);
                formData.append("barcode", document.getElementById("productBarcode").value);
                formData.append("status", document.getElementById("productStatus").value);
                formData.append("description", document.getElementById("productDescription").value);

                formData.append("purchase_price", document.getElementById("purchasePrice").value);
                formData.append("selling_price", document.getElementById("sellingPrice").value);
                formData.append("discount_price", document.getElementById("discountPrice").value);
                formData.append("tax_rate", document.getElementById("taxRate").value);
                formData.append("price_unit", document.getElementById("priceUnit").value);
                formData.append("has_discount", document.getElementById("applyDiscount").checked ? 1 : 0);

                formData.append("stock", document.getElementById("initialStock").value);
                formData.append("stock_alert", document.getElementById("lowStockAlert").value);
                formData.append("warehouse_id", document.getElementById("warehouse").value);

                const mainImage = document.getElementById("mainImage").files[0];
                if (mainImage) formData.append("main_image", mainImage);

                const additionalImages = document.getElementById("additionalImages").files;
                for (let i = 0; i < additionalImages.length; i++) {
                    formData.append("additional_images[]", additionalImages[i]);
                }

                try {
                    const response = await fetch("{{ route('products.store') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const result = await response.json();
                    if (result.success) {
                        console.log(result.message)
                        alert(result.message);
                        // يمكنك تحديث جدول المنتجات أو التنقل إلى صفحة أخرى هنا
                    } else {
                        alert("فشل في الإضافة",'#d00000');
                    }
                } catch (error) {
                    console.error(error);
                    alert("حدث خطأ أثناء الإرسال",'#d00000');
                }
            });
        }

        // وهكذا لبقية الأحداث...
    }

    function showToast(message, color = '#38b000') {
        const toast = `
        <div  id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;">

        `;
        toast.textContent = message;
        toast.style.backgroundColor = color;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
</script>

@endsection
