

@section('css')
        <link rel="stylesheet" href="{{asset('css/add.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        <div style="display: block;"  class="container ">
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
                                        <option value="">اختر الفئة</option>
                                        <option value="1">الكترونيات</option>
                                        <option value="2">ملابس</option>
                                        <option value="3">أثاث</option>
                                        <option value="4">مواد غذائية</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="productBrand" class="form-label">العلامة التجارية</label>
                                    <select class="form-select" id="productBrand">
                                        <option value="">اختر العلامة التجارية</option>
                                        <option value="1">سامسونج</option>
                                        <option value="2">نايك</option>
                                        <option value="3">آبل</option>
                                        <option value="4">أخرى</option>
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
                                        <option value="">اختر المستودع</option>
                                        <option value="1">المستودع الرئيسي</option>
                                        <option value="2">مستودع الشرق</option>
                                        <option value="3">مستودع الغرب</option>
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
@endsection

@section('js')
    <script src="{{asset('main-js/add.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
