// Update the showPOSModule function to inject content into main area instead of modal
function showADDModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
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

        <div class="tab-content" id="productTabsContent">
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
            <button class="btn btn-secondary" id="cancelForm"><i class="bi bi-x"></i> إلغاء</button>
            <button class="btn btn-primary" id="saveProduct"><i class="bi bi-save"></i> حفظ مؤقت</button>
            <button class="btn btn-success" id="submitProductBtn"><i class="bi bi-cloud-upload"></i> إرسال</button>
        </div>
        </div>
    `;

    // إعادة ربط الأحداث بعد إدراج النموذج
    initializeProductFormEvents();
}

window.backToHome = function() {
    location.reload();
};

window.openPOS = function(terminalId) {
    alert(`جاري فتح نقطة البيع رقم ${terminalId}`);
};



