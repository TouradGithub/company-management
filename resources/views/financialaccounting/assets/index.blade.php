@section('css')

<link rel="stylesheet" href="{{asset('css/main.css')}}">
<link rel="stylesheet" href="{{asset('css/filter.css')}}">
@endsection

@extends('financialaccounting.layouts.master')
@section('content')
    <div id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;"></div>

    <header class="header">
        <h1 style="color: #fff">نظام إدارة الأصول الثابتة</h1>
    </header>

    <div class="tabs">
        <div class="tab active" data-tab="register">تسجيل أصل جديد</div>
        <div class="tab" data-tab="sell">بيع أو التخلص من أصل</div>
        {{-- <div class="tab" data-tab="depreciation">تسجيل الإهلاكات السنوية</div> --}}
        <div class="tab" data-tab="depreciationDetails">تفاصيل الإهلاكات</div>
        <div class="tab" data-tab="categoryManagement">إدارة فئات الأصول</div>
        <div class="tab" data-tab="report">جدول الإهلاكات العام</div>
        <div class="tab" data-tab="annualDepreciation">الإهلاك السنوي العام</div>
        <div class="tab" data-tab="assetsTable">جدول الأصول</div>
    </div>

    <div class="tab-content active" id="register">
        <h2>تسجيل أصل جديد</h2>
        <form id="assetForm" onsubmit="submitAssetForm(event)">
            <div class="form-group">
                <label for="assetName">اسم الأصل</label>
                <input type="text" id="assetName" name="assetName" required>
            </div>

            <div class="form-group">
                <label for="assetType">نوع الأصل</label>
                <select id="assetType" name="category_management_id" required>
                    <option value="">اختر النوع</option>
                </select>
            </div>

            <div class="form-group">
                <label for="purchaseDate">تاريخ الشراء</label>
                <input type="date" id="purchaseDate" name="purchaseDate" required>
            </div>

            <div class="form-group">
                <label for="originalCost">التكلفة الأصلية</label>
                <input type="number" id="originalCost" name="originalCost" required>
            </div>



            <button type="submit">حفظ الأصل</button>
        </form>
    </div>

    <div class="tab-content" id="sell">
        <h2>بيع أو التخلص من أصل</h2>
        <form id="sellAssetForm">
            <div class="form-group">
                <label for="sellAssetSelect">اختر الأصل</label>
                <select id="sellAssetSelect" required>
                    <option value="">-- اختر الأصل --</option>
                    <!-- سيتم ملء هذه القائمة بواسطة JavaScript -->
                </select>
            </div>

            <div class="form-group">
                <label for="originalCostDisplay">التكلفة الأصلية</label>
                <input type="text" id="originalCostDisplay" readonly>
            </div>

            <div class="form-group">
                <label for="purchaseDateDisplay">تاريخ الشراء</label>
                <input type="date" id="purchaseDateDisplay" readonly>
            </div>

            <div class="form-group">
                <label for="accumulatedDepreciation">مجمع الإهلاك</label>
                <input type="text" id="accumulatedDepreciation" readonly>
            </div>

            <div class="form-group">
                <label for="currentBookValue">القيمة الدفترية الحالية</label>
                <input type="text" id="currentBookValue" readonly>
            </div>

            <div class="form-group">
                <label for="saleDate">تاريخ البيع</label>
                <input type="date" id="saleDate" required>
            </div>

            <div class="form-group">
                <label for="saleAmount">قيمة البيع</label>
                <input type="text" id="saleAmount" required>
            </div>

            <button type="submit">تسجيل عملية البيع</button>
        </form>

        <div id="saleResult" style="margin-top: 20px; padding: 15px; border-radius: 5px; display: none;">
            <!-- سيتم ملء هذا القسم بنتيجة عملية البيع بواسطة JavaScript -->
        </div>
    </div>

    <div class="tab-content" id="depreciation">
        <h2>تسجيل الإهلاكات السنوية</h2>
        <form id="depreciationForm">
            <div class="form-group">
                <label for="year">السنة</label>
                <input type="number" id="year" required>
            </div>

            <div class="form-group">
                <label for="depreciationValue">قيمة الإهلاك لهذه السنة</label>
                <input type="number" id="depreciationValue" required>
            </div>

            <button type="submit">حفظ الإهلاك</button>
        </form>
    </div>

    <div class="tab-content" id="report">
        <h2>جدول الإهلاكات العام</h2>
        <table id="depreciationTable">
            <thead>
                <tr>
                    <th>اسم الأصل</th>
                    <th>نوع الأصل</th>
                    <th>تاريخ الشراء</th>
                    <th>التكلفة الأصلية</th>
                    <th>القيمة التخريدية</th>
                    <th>العمر الإنتاجي</th>
                    <th>الإهلاك السنوي</th>
                    <th>إجمالي الإهلاك</th>
                    <th>القيمة الدفترية</th>
                    <th>القيود اليومية</th>
                </tr>
            </thead>
            <tbody>
                <!-- سيتم ملء الجدول بواسطة JavaScript -->
            </tbody>
        </table>
    </div>

    <div  class="tab-content" id="annualDepreciation">
        <h2>الإهلاك السنوي العام</h2>
        <div id="annualDepreciationTableContainer">
            <!-- سيتم ملء الجدول بواسطة JavaScript -->
        </div>

        <div class="journal-entries-examples">
            <h3>أمثلة لقيود الإهلاك المحاسبية</h3>

            <div class="journal-entry">
                <h4>قيد الإهلاك للمباني</h4>
                <div class="entry-details">
                    <div>البيان</div>
                    <div>مدين</div>
                    <div>دائن</div>
                </div>
                <div class="entry-row debit">
                    <div>من حـ/ مصروف إهلاك المباني</div>
                    <div>50,000</div>
                    <div></div>
                </div>
                <div class="entry-row">
                    <div>إلى حـ/ مجمع إهلاك المباني</div>
                    <div></div>
                    <div>50,000</div>
                </div>
                <div class="entry-date">31 ديسمبر 2023</div>
            </div>

            <div class="journal-entry">
                <h4>قيد الإهلاك للسيارات</h4>
                <div class="entry-details">
                    <div>البيان</div>
                    <div>مدين</div>
                    <div>دائن</div>
                </div>
                <div class="entry-row debit">
                    <div>من حـ/ مصروف إهلاك السيارات</div>
                    <div>15,000</div>
                    <div></div>
                </div>
                <div class="entry-row">
                    <div>إلى حـ/ مجمع إهلاك السيارات</div>
                    <div></div>
                    <div>15,000</div>
                </div>
                <div class="entry-date">31 ديسمبر 2023</div>
            </div>

            <div class="journal-entry">
                <h4>قيد الإهلاك للأثاث</h4>
                <div class="entry-details">
                    <div>البيان</div>
                    <div>مدين</div>
                    <div>دائن</div>
                </div>
                <div class="entry-row debit">
                    <div>من حـ/ مصروف إهلاك الأثاث</div>
                    <div>8,500</div>
                    <div></div>
                </div>
                <div class="entry-row">
                    <div>إلى حـ/ مجمع إهلاك الأثاث</div>
                    <div></div>
                    <div>8,500</div>
                </div>
                <div class="entry-date">31 ديسمبر 2023</div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="categoryManagement">
        <h2>إدارة فئات الأصول</h2>
        <div class="category-management-container">
            <div class="form-group">
                <label for="newCategoryName">اسم الفئة الجديدة</label>
                <input type="text" id="newCategoryName" placeholder="أدخل اسم الفئة بالعربية">
            </div>
            <div class="form-group">
                <label for="newCategoryCode">رمز الفئة</label>
                <input type="text" id="newCategoryCode" placeholder="أدخل رمز الفئة بالإنجليزية (مثل: equipment)">
            </div>
            <div class="form-group">
                <label for="newCategoryLifespan">العمر الإنتاجي الافتراضي (بالسنوات)</label>
                <input type="number" id="newCategoryLifespan" value="5" min="1">
            </div>
            <div class="form-group">
                <label for="newCategoryRate">نسبة الإهلاك السنوي (%)</label>
                <input type="number" id="newCategoryRate" value="20" min="0" step="0.01" readonly>
            </div>
            <button id="addCategoryBtn">إضافة فئة جديدة</button>
        </div>

        <div class="categories-list-container">
            <h3>الفئات الحالية</h3>
            <table id="categoriesTable">
                <thead>
                    <tr>
                        <th>اسم الفئة</th>
                        <th>رمز الفئة</th>
                        <th>العمر الافتراضي</th>
                        <th>نسبة الإهلاك</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- سيتم ملء هذا الجدول بواسطة JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-content" id="depreciationDetails">
        <h2>تفاصيل الإهلاكات</h2>
        <div class="filter-controls">
            <div class="search-container">
                <input type="text" id="deprecationSearchInput" placeholder="البحث عن الأصول..." class="input-field">
                <button id="depreciationSearchBtn" class="btn-search">بحث</button>
            </div>

            <div class="filter-options">
                <div class="form-group">
                    <label for="depreciationCategoryFilter" class="label">تصفية حسب الفئة</label>
                    <select id="depreciationCategoryFilter" class="select-field">
                        <option value="">جميع الفئات</option>
                        <!-- Will be populated dynamically from categories -->
                    </select>
                </div>

                <button id="resetDepreciationFiltersBtn" class="btn-reset">إعادة تعيين الفلاتر</button>
            </div>
        </div>

        <div class="export-controls">
            <button id="exportDepreciationExcelBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                    <path d="M10 12l4 4m0 -4l-4 4"></path>
                </svg>
                تصدير اكسل
            </button>
            <button id="printDepreciationBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9v-3a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v3"></path>
                    <path d="M6 18h12a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2"></path>
                    <path d="M8 14h8"></path><path d="M8 18h8"></path>
                </svg>
                طباعة
            </button>
            <button id="saveDepreciationChangesBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                حفظ التغييرات
            </button>
        </div>


        <div class="assets-table-container">
            <table id="depreciationDetailsTable">
                <thead>
                    <tr>
                        <th>رقم الأصل</th>
                        <th>اسم الأصل</th>
                        <th>قيمة الإهلاك</th>
                        <th>نسبة الإهلاك</th>
                        <th>القيمة الدفترية</th>
                        <th>مجمع الإهلاك</th>
                        <th>قيمة الشراء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- سيتم ملء الجدول بواسطة JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-content" id="assetsTable">
        <h2>جدول الأصول</h2>

        <div class="filter-controls">
            <div class="search-container">
                <input type="text" id="assetSearchInput" placeholder="البحث عن الأصول...">
                <button id="assetSearchBtn">بحث</button>
            </div>

            <div class="filter-options">
                {{-- <div class="form-group">
                    <label for="branchFilter">تصفية حسب الفرع</label>
                    <select id="branchFilter">
                        <option value="">جميع الفروع</option>
                        <option value="الرئيسي">الرئيسي</option>
                    </select>
                </div> --}}

                <div class="form-group">
                    <label for="categoryFilter">تصفية حسب الفئة</label>
                    <select id="categoryFilter">
                        <option value="">جميع الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="sortBy">ترتيب حسب</label>
                    <select id="sortBy">
                        <option value="id">رقم الأصل</option>
                        <option value="name">اسم الأصل</option>
                        <option value="purchaseDate">تاريخ الشراء</option>
                        <option value="originalCost">قيمة الشراء</option>
                        <option value="bookValue">القيمة الدفترية</option>
                    </select>
                </div>

                <button id="resetFiltersBtn">إعادة تعيين الفلاتر</button>
            </div>
        </div>

        <div class="export-controls">
            <button id="exportExcelBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                    <path d="M10 12l4 4m0 -4l-4 4"></path>
                </svg>
                تصدير اكسل
            </button>
            <button id="exportPDFBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                    <path d="M9 9h1"></path><path d="M9 13h6"></path><path d="M9 17h6"></path>
                </svg>
                تصدير PDF
            </button>
            <button id="printTableBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9v-3a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v3"></path>
                    <path d="M6 18h12a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2"></path>
                    <path d="M8 14h8"></path><path d="M8 18h8"></path>
                </svg>
                طباعة
            </button>
            <button id="previewReportBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="2"></circle>
                    <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                </svg>
                معاينة التقرير
            </button>
        </div>

        <div class="assets-table-container">
            <table id="assetsListTable">
                <thead>
                    <tr>
                        <th>رقم الأصل</th>
                        <th>اسم الأصل</th>
                        {{-- <th>الفرع</th> --}}
                        <th>الفئة</th>
                        <th>تاريخ الشراء</th>
                        <th>قيمة الشراء</th>
                        <th>مجمع الإهلاك</th>
                        <th>القيمة الدفترية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Assets will be loaded here dynamically -->
                </tbody>
            </table>
        </div>

        <div id="assetDetailsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>تفاصيل الأصل</h2>
                <div id="assetDetailsContent">
                    <!-- Asset details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- إضافة النافذة المنبثقة للقيود اليومية -->
    <div id="journalEntriesModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>القيود اليومية للإهلاكات</h2>
            <table id="journalEntriesTable">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الحساب</th>
                        <th>مدين</th>
                        <th>دائن</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- سيتم ملء الجدول بواسطة JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- إضافة النافذة المنبثقة لتفاصيل الإهلاك -->
    <div id="assetDepreciationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>تفاصيل إهلاك الأصل</h2>
            <div id="assetDepreciationContent">
                <!-- سيتم ملء المحتوى بواسطة JavaScript -->
            </div>
        </div>
    </div>
    <!-- المودال والخلفية -->
    <div id="editModalBackdrop" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9998;"></div>

        <div id="editModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
        background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        max-width: 90%; width: 400px; z-index: 9999; font-family: sans-serif;">

        <h3 style="margin-bottom: 15px; text-align: center;">تعديل الفئة</h3>
        <input type="hidden" id="editCategoryId">

        <div style="margin-bottom: 10px;">
            <label>الاسم:</label>
            <input type="text" id="editCategoryName" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label>كود:</label>
            <input type="text" id="editCategoryCode" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label>مدة العمر:</label>
            <input type="number" id="editCategoryLifespan" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label>نسبة الإهلاك:</label>
            <input type="number" id="editCategoryRate" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>

        <div style="text-align: center;">
            <button onclick="submitEditCategory()" style="padding: 8px 15px; background-color: #28a745; color: white; border: none; border-radius: 6px; cursor: pointer;">💾 حفظ</button>
            <button onclick="closeEditModal()" style="padding: 8px 15px; background-color: #dc3545; color: white; border: none; border-radius: 6px; margin-right: 10px; cursor: pointer;">❌ إلغاء</button>
        </div>
    </div>

    <div id="editAssetModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>تعديل الأصل</h3>
            <form id="editAssetForm">
            <input type="hidden" name="id" id="editAssetId">

            <label>اسم الأصل:</label>
            <input type="text" name="assetname" id="editAssetName" required>

            <label>التصنيف:</label>
            <select name="category_management_id" id="editCategorySelect" required>
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <label>تاريخ الشراء:</label>
            <input type="date" name="purchasedate" id="editPurchaseDate" required>

            <label>قيمة الشراء:</label>
            <input type="number" name="originalcost" id="editOriginalCost" required>

            <button type="submit">حفظ التعديلات</button>
            </form>
        </div>
    </div>

{{-- </div> --}}

@endsection
@section('js')
    {{-- اضافة جدول الاصول --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="{{asset('main-js/script.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/depreciation-report')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector('#report tbody');
                    tbody.innerHTML = '';

                    data.forEach(asset => {
                        const row = document.createElement('tr');

                        row.innerHTML = `
                            <td>${asset.asset_name}</td>
                            <td>${asset.category_name}</td>
                            <td>${asset.purchase_date}</td>
                            <td>${asset.original_cost}</td>
                            <td>${asset.scrap_value}</td>
                            <td>${asset.useful_life} سنوات</td>
                            <td>${asset.annual_depreciation}</td>
                            <td>${asset.total_depreciation}</td>
                            <td>${asset.book_value}</td>
                            <td>${asset.daily_entry}</td>
                        `;

                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('خطأ أثناء جلب البيانات:', error);
                });
        });
        </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchAssetsAjax();

        let allAssets = []; // لتخزين البيانات كاملة لاستخدامها في الفلترة لاحقًا

        function fetchAssetsAjax() {
            fetch('/fetch-assets')
                .then(response => response.json())
                .then(data => {
                    allAssets = data; // نخزن البيانات في المتغير العام
                    applyFiltersAndRender(); // نعرضها أول مرة
                })
                .catch(error => {
                    console.error('خطأ في جلب الأصول:', error);
                });
        }

        // فلترة البيانات حسب المدخلات والاختيارات
        function applyFiltersAndRender() {
            const searchInput = document.getElementById('assetSearchInput').value.toLowerCase();
            // const branchFilter = document.getElementById('branchFilter').value;
            const categoryFilter = document.getElementById('categoryFilter').value;
            const sortBy = document.getElementById('sortBy').value;

            let filteredAssets = allAssets.filter(asset => {
                const matchesSearch = asset.assetname.toLowerCase().includes(searchInput) || String(asset.id).includes(searchInput);
                // const matchesBranch = branchFilter === '' || asset.branch === branchFilter || branchFilter === 'الرئيسي';
                const matchesCategory = categoryFilter === '' || (asset.category_managment?.name === categoryFilter);
                return matchesSearch  && matchesCategory;
            });

            // ترتيب النتائج
            filteredAssets.sort((a, b) => {
                switch (sortBy) {
                    case 'id': return a.id - b.id;
                    case 'name': return a.assetname.localeCompare(b.assetname);
                    case 'purchaseDate': return new Date(a.purchasedate) - new Date(b.purchasedate);
                    case 'originalCost': return a.originalcost - b.originalcost;
                    case 'bookValue': return (a.book_value || 0) - (b.book_value || 0);
                    default: return 0;
                }
            });

            renderAssets(filteredAssets);
        }
        function renderAssets(assets) {
            const tbody = document.querySelector('#assetsListTable tbody');
            tbody.innerHTML = '';

            if (assets.length === 0) {
                const emptyRow = `<tr><td colspan="9" style="text-align: center;">لا توجد أصول</td></tr>`;
                tbody.innerHTML = emptyRow;
                return;
            }
            // console.log(assets)
            assets.forEach(asset => {
                const depreciation = asset.accumulated || 0;
                const bookValue = asset.book_value || 0;
                const isSold = asset.sold ? 'مباع' : 'غير مباع';

                const row = `
                    <tr>
                        <td>${asset.id}</td>
                        <td>${asset.assetname}</td>
                        <td><span class="asset-badge category-badge">${asset.category_managment?.name || '—'}</span></td>
                        <td>${asset.purchasedate}</td>
                        <td>${parseFloat(asset.originalcost).toLocaleString()}</td>
                        <td>${parseFloat(depreciation).toLocaleString()}</td>
                        <td>${parseFloat(bookValue).toLocaleString()}</td>
                        <td class="action-buttons">
                            <button class="icon-button view-asset" data-id="${asset.id}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            ${!asset.sold ? `
                            <button class="icon-button edit-asset" data-id="${asset.id}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            ` : ''}
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
                            // Add event listeners for view and edit buttons
            document.querySelectorAll('.view-asset').forEach(button => {
                button.addEventListener('click', function() {
                    const assetId = parseInt(this.getAttribute('data-id'));
                    showAssetDetails(assets.find(a => a.id === assetId));
                });
            });
        }

        document.getElementById('assetSearchBtn').addEventListener('click', applyFiltersAndRender);
        // document.getElementById('branchFilter').addEventListener('change', applyFiltersAndRender);
        document.getElementById('categoryFilter').addEventListener('change', applyFiltersAndRender);
        document.getElementById('sortBy').addEventListener('change', applyFiltersAndRender);
        document.getElementById('resetFiltersBtn').addEventListener('click', () => {
            document.getElementById('assetSearchInput').value = '';
            // document.getElementById('branchFilter').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('sortBy').value = 'id';
            applyFiltersAndRender();
        });

    });

    function showAssetDetails(asset) {
        if (!asset) return;

        const modalContent = document.getElementById('assetDetailsContent');
        const categories = window.getAssetCategories ? window.getAssetCategories() : [];
        const categoryNames = {};
        categories.forEach(cat => {
            categoryNames[cat.code] = cat.name;
        });

        // دعم الأسماء المحتملة المختلفة
        const name = asset.assetname || asset.name || '—';
        const branch = asset.branch || 'الرئيسي';
        const categoryName = asset.category_managment?.name || categoryNames[asset.type] || asset.type || '—';
        const purchaseDate = asset.purchasedate || asset.purchaseDate || '—';
        const originalCost = asset.originalcost || asset.originalCost || 0;
        const salvage_value = asset.salvage_value || asset.salvage_value || 0;
        const lifespan = asset.lifespan || asset.lifespan || '—';
        // const depreciations = asset.depreciations || [];
        // const depreciations = asset.accumulated || 0;

        const totalDepreciation = asset.accumulated || 0;
        const depreciations = Array.isArray(asset.depreciations) ? asset.depreciations : [];

        // const totalDepreciation = depreciations.reduce((sum, d) => sum + d.value, 0);
        const bookValue = asset.book_value || 0;

        // سجل الإهلاك
        let depreciationHistory = '';
        if (depreciations.length > 0) {
            depreciationHistory = '<h3>سجل الإهلاك</h3><table class="details-table">';
            depreciationHistory += '<tr><th>السنة</th><th>قيمة الإهلاك</th></tr>';

            depreciations.forEach(dep => {
                depreciationHistory += `<tr><td>${dep.year}</td><td>${dep.value.toLocaleString()}</td></tr>`;
            });

            depreciationHistory += '</table>';
        } else {
            depreciationHistory = '<p>لا يوجد سجل إهلاك تفصيلي، فقط مجمع الإهلاك: <strong>' + totalDepreciation.toLocaleString() + '</strong></p>';
        }


        // معلومات البيع
        let saleInfo = '';
        if (asset.sold && asset.saleDetails) {
            saleInfo = `
                <h3>معلومات البيع</h3>
                <div class="sale-info">
                    <p><strong>تاريخ البيع:</strong> ${asset.saleDetails.date}</p>
                    <p><strong>قيمة البيع:</strong> ${asset.saleDetails.amount.toLocaleString()}</p>
                    <p><strong>الربح/الخسارة:</strong> ${Math.abs(asset.saleDetails.gainOrLoss).toLocaleString()}
                        ${asset.saleDetails.gainOrLoss >= 0 ? '(ربح)' : '(خسارة)'}</p>
                </div>
            `;
        }

        modalContent.innerHTML = `
            <div class="asset-details">
                <div class="asset-header">
                    <h3>${name}</h3>
                    <span class="asset-id">رقم الأصل: ${asset.id}</span>
                </div>

                <div class="details-section">
                    <div class="details-row">
                        <div class="detail-item">
                            <span class="detail-label">الفرع:</span>
                            <span class="detail-value">${branch}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">الفئة:</span>
                            <span class="detail-value">${categoryName}</span>
                        </div>
                    </div>

                    <div class="details-row">
                        <div class="detail-item">
                            <span class="detail-label">تاريخ الشراء:</span>
                            <span class="detail-value">${purchaseDate}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">قيمة الشراء:</span>
                            <span class="detail-value">${originalCost.toLocaleString()}</span>
                        </div>
                    </div>

                    <div class="details-row">
                        <div class="detail-item">
                            <span class="detail-label">القيمة التخريدية:</span>
                            <span class="detail-value">${salvage_value > 0 ? salvage_value.toLocaleString() : '—'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">العمر الإنتاجي:</span>
                            <span class="detail-value">${lifespan > 0 ? lifespan + ' سنوات' : '—'}</span>
                        </div>
                    </div>

                    <div class="details-row">
                        <div class="detail-item">
                            <span class="detail-label">مجمع الإهلاك:</span>
                            <span class="detail-value">${totalDepreciation.toLocaleString()}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">القيمة الدفترية:</span>
                            <span class="detail-value">${bookValue.toLocaleString()}</span>
                        </div>
                    </div>
                </div>

                <div class="depreciation-history">
                    ${depreciationHistory}
                </div>

                ${saleInfo}
            </div>
        `;

        assetDetailsModal.style.display = 'block';
                // إغلاق النافذة عند النقر على زر الإغلاق
        const closeModalButton = assetDetailsModal.querySelector('.close');
        closeModalButton.onclick = function() {
            assetDetailsModal.style.display = 'none';
        }
    }
    document.addEventListener("click", function(e) {
        if (e.target.closest(".edit-asset")) {
            const assetId = e.target.closest(".edit-asset").dataset.id;

            // جلب بيانات الأصل من السيرفر
            fetch(`/assets/${assetId}/edit`)
            .then(response => response.json())
            .then(data => {
                // تعبئة النموذج
                document.getElementById("editAssetId").value = data.id;
                document.getElementById("editAssetName").value = data.assetname;
                document.getElementById("editCategorySelect").value = data.category_management_id;
                document.getElementById("editPurchaseDate").value = data.purchasedate;
                document.getElementById("editOriginalCost").value = data.originalcost;

                // عرض المودال
                document.getElementById("editAssetModal").style.display = "block";
            });
        }
    });

    function previewReport(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        // فتح نافذة جديدة
        const previewWindow = window.open('', '_blank');

        // تنسيق HTML
        previewWindow.document.write(`
                <title>معاينة تقرير الأصول</title>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        background: #f9f9f9;
                        margin: 0;
                        padding: 20px;
                    }
                    .container {
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    h1 {
                        text-align: center;
                        color: #2c3e50;
                        border-bottom: 2px solid #3498db;
                        padding-bottom: 10px;
                        margin-bottom: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: right;
                    }
                    th {
                        background-color: #3498db;
                        color: white;
                    }
                    tr:nth-child(even) {
                        background-color: #f2f2f2;
                    }
                </style>
                <div class="container">
                    <h1>معاينة تقرير الأصول</h1>
        `);

        // نسخ الجدول
        const clonedTable = table.cloneNode(true);

        // إزالة عمود الإجراءات
        clonedTable.querySelectorAll('tr').forEach(row => {
            const cells = row.querySelectorAll('td, th');
            if (cells.length > 0) {
                cells[cells.length - 1].remove();
            }
        });

        previewWindow.document.write(clonedTable.outerHTML);

        // غلق الصفحة
        previewWindow.document.write(`
                </div>
        `);

        previewWindow.document.close();
    }
    document.getElementById('previewReportBtn').addEventListener('click', function () {
        console.log('ok')
        previewReport('assetsListTable'); // غيّر 'assets-table' إلى الـ ID الخاص بجدولك إن كان مختلف
    });
    document.getElementById('printTableBtn').addEventListener('click', function () {

        printTable('assetsListTable'); // غيّر 'assets-table' إلى الـ ID الخاص بجدولك إن كان مختلف
    });
    document.getElementById('exportPDFBtn').addEventListener('click', function () {

        exportToPDF('assetsListTable'); // غيّر 'assets-table' إلى الـ ID الخاص بجدولك إن كان مختلف
    });
    document.getElementById('exportExcelBtn').addEventListener('click', function () {

        exportToExcel('assetsListTable'); // غيّر 'assets-table' إلى الـ ID الخاص بجدولك إن كان مختلف
    });
    // Print the table
    function printTable(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        // Create a new window for printing
        const printWindow = window.open('', '_blank');

        // Generate print-friendly HTML
        printWindow.document.write(`
                <title>تقرير الأصول الثابتة</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    h1 {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 30px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: right;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    @media print {
                        body {
                            margin: 0;
                            padding: 15px;
                        }
                        .no-print {
                            display: none;
                        }
                    }
                </style>
                <h1>تقرير الأصول الثابتة</h1>
        `);

        // Clone the table and remove the actions column
        const tableClone = table.cloneNode(true);

        // Get all rows
        const rows = Array.from(tableClone.querySelectorAll('tr'));

        // Remove last cell (actions column) from each row
        rows.forEach(row => {
            const cells = row.querySelectorAll('th, td');
            if (cells.length > 0) {
                cells[cells.length - 1].remove();
            }
        });

        printWindow.document.write(tableClone.outerHTML);

        // Close the HTML
        printWindow.document.write(`
                <div class="no-print" style="text-align: center; margin-top: 20px;">
                    <button onclick="window.print();">طباعة</button>
                    <button onclick="window.close();">إغلاق</button>
                </div>

        `);

        printWindow.document.close();

        // Automatically open print dialog after content loads
        printWindow.onload = function() {
            setTimeout(function() {
                printWindow.focus();
                printWindow.print();
            }, 500);
        };
    }

    // Export assets table to PDF
    function exportToPDF(tableId, filename = 'test-report') {
        const element = document.getElementById(tableId);
        if (!element) {
            alert("العنصر غير موجود");
            return;
        }

        const opt = {
            margin: 0.5,
            filename: `${filename}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().from(element).set(opt).save();
    }

    // Load jsPDF and its dependencies if needed
    function loadJsPDF() {
        return new Promise((resolve, reject) => {
            // Check if jsPDF is already loaded
            if (typeof jspdf !== 'undefined') {
                resolve();
                return;
            }

            // Create script for jsPDF
            const jsPdfScript = document.createElement('script');
            jsPdfScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
            jsPdfScript.onload = () => {
                // Load autoTable plugin
                const autoTableScript = document.createElement('script');
                autoTableScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js';
                autoTableScript.onload = resolve;
                document.head.appendChild(autoTableScript);
            };
            jspdf.jsPDF.API.events.push(['addFonts', function () {
            this.addFileToVFS("Amiri-Regular.ttf", "BASE64_FONT_STRING");
            this.addFont("Amiri-Regular.ttf", "Amiri", "normal");
            }]);
            jsPdfScript.onerror = reject;
            document.head.appendChild(jsPdfScript);
        });
    }

    // Export assets table to Excel
    function exportToExcel(tableId, filename = 'assets-report') {
        const table = document.getElementById(tableId);
        if (!table) return;

        // Get all rows
        const rows = Array.from(table.querySelectorAll('tr'));

        // Extract headers from the first row
        const headers = Array.from(rows[0].querySelectorAll('th')).map(th => th.textContent.trim());

        // Remove the last column (actions) as we don't want to export it
        headers.pop();

        // Process data rows
        const data = rows.slice(1).map(row => {
            const cells = Array.from(row.querySelectorAll('td'));

            // If this is an "empty results" row that spans multiple columns, return null
            if (cells.length === 1 && cells[0].hasAttribute('colspan')) {
                return null;
            }

            // Remove the last column (actions)
            cells.pop();

            // Extract text content from each cell
            return cells.map(cell => {
                // If cell contains a badge, get its text content
                const badge = cell.querySelector('.asset-badge');
                return badge ? badge.textContent.trim() : cell.textContent.trim();
            });
        }).filter(row => row !== null); // Filter out empty result rows

        // Create CSV content
        let csvContent = "\uFEFF"; // UTF-8 BOM for proper Arabic display

        // Add headers
        csvContent += headers.join(',') + '\n';

        // Add data rows
        data.forEach(row => {
            csvContent += row.join(',') + '\n';
        });

        // Create a Blob and download link
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `${filename}.csv`);
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    </script>

    {{-- بيع الاصول --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sellAssetSelect = document.getElementById('sellAssetSelect');

            // جلب الأصول من الخادم
            fetch('/get-assets')
                .then(response => response.json())
                .then(assets => {
                    // ملء قائمة الأصول
                    assets.forEach((asset, index) => {
                        const option = document.createElement('option');
                        option.value = asset.id;
                        option.textContent = asset.assetname; // عرض اسم الأصل
                        sellAssetSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading assets:', error));

            // تحديث الحقول عند اختيار أصل
            sellAssetSelect.addEventListener('change', function () {
                const selectedIndex = this.value;
                if (selectedIndex === '') {
                    return;
                }

                // جلب البيانات الخاصة بالأصل المختار
                fetch(`/get-assets/${selectedIndex}`)
                    .then(response => response.json())
                    .then(selectedAsset => {
                        // تحديث الحقول بناءً على البيانات المسترجعة
                        document.getElementById('originalCostDisplay').value = selectedAsset.original_cost;  // التكلفة الأصلية
                        document.getElementById('purchaseDateDisplay').value = selectedAsset.purchase_date; // تاريخ الشراء

                        // حساب مجمع الإهلاك والقيمة الدفترية
                        const totalDepreciation = selectedAsset.accumulated_depreciation; // مجمع الإهلاك
                        document.getElementById('accumulatedDepreciation').value = totalDepreciation;

                        const bookValue = selectedAsset.book_value; // القيمة الدفترية
                        document.getElementById('currentBookValue').value = bookValue;
                    })
                    .catch(error => console.error('Error loading selected asset:', error));
            });

            // التعامل مع عملية البيع
            document.getElementById('sellAssetForm').addEventListener('submit', function (e) {
                e.preventDefault();

                const selectedIndex = document.getElementById('sellAssetSelect').value;
                if (selectedIndex === '') {
                    alert('الرجاء اختيار أصل للبيع');
                    return;
                }

                const saleDate = document.getElementById('saleDate').value;
                const saleAmount = parseFloat(document.getElementById('saleAmount').value);

                // جلب الأصل المحدد من الخادم
                fetch(`/get-assets/${selectedIndex}`)
                    .then(response => response.json())
                    .then(selectedAsset => {
                        // حساب مجمع الإهلاك والقيمة الدفترية
                        const totalDepreciation = selectedAsset.accumulated_depreciation;
                        const bookValue = selectedAsset.book_value;

                        // حساب الربح أو الخسارة
                        const gainOrLoss = saleAmount - bookValue;

                        fetch(`/sell-asset/${selectedAsset.id}`, {
                            method: 'POST', // تأكد من أن هذه الطريقة مسموح بها

                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')

                            },
                            body: JSON.stringify({
                                saleDate: saleDate,
                                saleAmount: saleAmount,
                                gainOrLoss: gainOrLoss
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);

                            // التأكد من أن البيانات التي أرسلت تحتوي على العناصر المطلوبة
                            const saleResult = document.getElementById('saleResult');
                            const assetName = data.asset.assetname;  // اسم الأصل
                            const bookValue = data.bookValue;  // القيمة الدفترية
                            const saleAmount = data.asset.sale_amount;  // قيمة البيع
                            const gainOrLoss = data.gainOrLoss;  // الربح أو الخسارة

                            saleResult.innerHTML = `
                                <h3>نتيجة عملية البيع</h3>
                                <p>الأصل: ${assetName}</p>
                                <p>القيمة الدفترية: ${bookValue.toLocaleString()}</p>
                                <p>قيمة البيع: ${saleAmount.toLocaleString()}</p>
                                <p>الربح/الخسارة: ${Math.abs(gainOrLoss).toLocaleString()} ${gainOrLoss >= 0 ? '(ربح)' : '(خسارة)'}</p>
                            `;

                            // تغيير الألوان بناءً على الربح أو الخسارة
                            if (gainOrLoss >= 0) {
                                saleResult.style.backgroundColor = '#e8f8e8';
                                saleResult.style.color = '#27ae60';  // اللون الأخضر للربح
                            } else {
                                saleResult.style.backgroundColor = '#f8e8e8';
                                saleResult.style.color = '#c0392b';  // اللون الأحمر للخسارة
                            }

                            // إظهار النتيجة
                            saleResult.style.display = 'block';
                        })
                        .catch(error => console.error('Error processing sale:', error));

                        // إعادة تعيين النموذج
                        this.reset();
                        document.getElementById('originalCostDisplay').value = '';
                        document.getElementById('purchaseDateDisplay').value = '';
                        document.getElementById('accumulatedDepreciation').value = '';
                        document.getElementById('currentBookValue').value = '';
                    });
            });
        });

    </script>


    {{-- جدول الاهلاك --}}
    <script>
        // تحديث تفاصيل الإهلاك
        function updateDepreciationDetailsTable(search = '', category = '') {
            const tableBody = document.querySelector('#depreciationDetailsTable tbody');
            const params = new URLSearchParams();
            const url = new URL('/get-depreciation-details', window.location.origin);

            // إضافة الفلاتر في الرابط
            if (search) params.append('search', search);
            if (category) params.append('category', category);

            url.search = params.toString();
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('حدث خطأ في الخادم');
                    }
                    return response.json();
                })
                .then(data => {
                    tableBody.innerHTML = '';
                    data.forEach(asset => {
                        // let rate = 0;
                        // if (asset.categoryrate && asset.categoryrate != 0) {
                        //     rate = asset.categoryrate; // استخدام نسبة الإهلاك من الفئة
                        // } else if (asset.original_cost && asset.original_cost != 0) {
                        //     rate = (asset.annual_depreciation / asset.original_cost) * 100;
                        //     rate = rate.toFixed(2); // نخليه رقم عشري بـ منزلتين
                        // }

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${asset.id}</td>
                            <td>${asset.name}</td>
                            <td>${asset.annual_depreciation}</td>
                            <td>${asset.rate}%</td>
                            <td>${asset.book_value}</td>
                            <td>${asset.accumulated_depreciation}</td>
                            <td>${asset.original_cost}</td>
                            <td>
                                <button class="btn btn-info" id="viewDetailsBtn-${asset.id}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </td>
                        `;
                        tableBody.appendChild(row);

                        // إضافة مستمع الحدث لكل زر "عرض"
                        document.getElementById(`viewDetailsBtn-${asset.id}`).addEventListener('click', function() {
                            showAssetDepreciationDetails(asset);
                        });
                    });
                })
                .catch(error => {
                    console.error('خطأ في جلب بيانات الإهلاك:', error);
                });
        }


        // عرض تفاصيل إهلاكات الأصل
        function showAssetDepreciationDetails(asset) {
            const modal = document.getElementById('assetDepreciationModal');
            const modalContent = document.getElementById('assetDepreciationContent');

            modalContent.innerHTML = `
                <div class="asset-depreciation-details">
                    <h3>${asset.name}</h3>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">رقم الأصل:</span>
                            <span class="detail-value">${asset.id || '-'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">تاريخ الشراء:</span>
                            <span class="detail-value">${asset.purchaseDate}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">قيمة الشراء:</span>
                            <span class="detail-value">${asset.original_cost}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">العمر الإنتاجي:</span>
                            <span class="detail-value">${asset.lifespan} سنوات</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">القيمة التخريدية:</span>
                            <span class="detail-value">${asset.salvage_value}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">نسبة الإهلاك:</span>
                            <span class="detail-value">${asset.rate}%</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">الإهلاك السنوي:</span>
                            <span class="detail-value">${asset.annual_depreciation}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">مجمع الإهلاك:</span>
                            <span class="detail-value">${asset.accumulated_depreciation}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">القيمة الدفترية:</span>
                            <span class="detail-value">${asset.book_value}</span>
                        </div>
                    </div>
                </div>
            `;

            // عرض النافذة المنبثقة
            modal.style.display = 'block';

            // إغلاق النافذة عند النقر على زر الإغلاق
            const closeModalButton = modal.querySelector('.close');
            closeModalButton.onclick = function() {
                modal.style.display = 'none';
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            // تحميل الفئات من الخادم
            fetch('/get-depreciation-categories')
                .then(response => response.json())
                .then(data => {
                    const categoryFilter = document.getElementById('depreciationCategoryFilter');
                    data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categoryFilter.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading categories:', error));

            // استدعاء عند الضغط على زر البحث
            document.getElementById('depreciationSearchBtn').addEventListener('click', function () {
                const searchInput = document.getElementById('deprecationSearchInput').value;
                const categoryFilter = document.getElementById('depreciationCategoryFilter').value;
                updateDepreciationDetailsTable(searchInput, categoryFilter);
            });

            // استدعاء عند تغيير الفئة
            document.getElementById('depreciationCategoryFilter').addEventListener('change', function () {
                const searchInput = document.getElementById('deprecationSearchInput').value;
                const categoryFilter = document.getElementById('depreciationCategoryFilter').value;
                updateDepreciationDetailsTable(searchInput, categoryFilter);
            });

            // استدعاء عند النقر على "إعادة تعيين الفلاتر"
            document.getElementById('resetDepreciationFiltersBtn').addEventListener('click', function () {
                document.getElementById('deprecationSearchInput').value = '';
                document.getElementById('depreciationCategoryFilter').value = '';
                updateDepreciationDetailsTable();
            });

            // تحميل تفاصيل الإهلاك عند التحميل الأول
            updateDepreciationDetailsTable();
        });
        // document.getElementById('depreciationDetails').addEventListener('click', function () {
        //     updateDepreciationDetailsTable();
        // });


        document.addEventListener('DOMContentLoaded', function () {
            // جلب الفئات عبر Ajax عند تحميل الصفحة
            fetchCategories();


        });

        function fetchCategories() {
            fetch('/assets/create') // تأكد من المسار الصحيح
                .then(response => response.json())
                .then(data => {
                    const selectElement = document.getElementById('assetType');
                    // حذف الخيارات السابقة
                    selectElement.innerHTML = '<option value="">اختر النوع</option>';
                    // إضافة الخيارات الجديدة
                    data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                    showToast('حدث خطأ أثناء جلب الفئات.');

                });
        }

        function showToast(message, color = '#38b000') {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.style.backgroundColor = color;
                toast.style.display = 'block';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3000);
            }
        function submitAssetForm(event) {

        event.preventDefault(); // منع إرسال النموذج بشكل تقليدي (ريلود الصفحة)

        // الحصول على القيم من النموذج
        const assetName = document.getElementById("assetName").value;
        const categoryType = document.getElementById("assetType").value;
        const purchaseDate = document.getElementById("purchaseDate").value;
        const originalCost = document.getElementById("originalCost").value;

        // التأكد من ملء جميع الحقول
        if (!assetName || !categoryType || !purchaseDate || !originalCost ) {
            alert("يرجى ملء جميع الحقول.", '#d00000');
            // showToast('تم إضافة الأصل بنجاح');

            return;
        }

        // إرسال البيانات عبر Ajax باستخدام fetch
        fetch("/assets/store", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content // لتأمين الطلب
                },
                body: JSON.stringify({
                    assetName,
                    category_management_id: categoryType,
                    purchaseDate,
                    originalCost,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // عند النجاح: عرض رسالة نجاح أو تحديث الواجهة

                    showToast('تم إضافة الأصل بنجاح');

                    // يمكنك هنا تحديث القائمة أو إعادة تحميل البيانات باستخدام Ajax أيضًا
                    document.getElementById("assetForm").reset(); // إعادة تعيين النموذج بعد الإضافة
                } else {
                    showToast('حدث خطأ، حاول مجددًا.', '#d00000');

                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('حدث خطأ أثناء إرسال البيانات.', '#d00000');

            });
        }



        document.addEventListener("DOMContentLoaded", function () {
            fetchCategories();
            function showToast(message, color = '#38b000') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.backgroundColor = color;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // Calculate depreciation rate automatically when lifespan changes
        const lifespanInput = document.getElementById('newCategoryLifespan');
        const rateInput = document.getElementById('newCategoryRate');

        if (lifespanInput && rateInput) {
            lifespanInput.addEventListener('input', function() {
                const lifespan = parseInt(this.value);
                if (lifespan > 0) {
                    rateInput.value = (100 / lifespan).toFixed(2);
                } else {
                    rateInput.value = '';
                }
            });
        }

        // إدارة التبويبات
        const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    const tabId = tab.getAttribute('data-tab');
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    document.getElementById(tabId).classList.add('active');

                    // تحديث جدول الإهلاك السنوي العام عندما يتم فتح هذا التبويب
                    if (tabId === 'annualDepreciation') {
                        // updateAnnualDepreciationTable();
                        updateDepreciationDetailsTable();
                    }

                    // تحديث جدول الإهلاكات عندما يتم فتح هذا التبويب
                    if (tabId === 'depreciationDetails') {
                        updateDepreciationDetailsTable();
                    }
                });
            });
            // إضافة فئة
            document.getElementById("addCategoryBtn").addEventListener("click", function () {
                let data = {
                    name: document.getElementById("newCategoryName").value,
                    categorycode: document.getElementById("newCategoryCode").value,
                    categorylifespan: document.getElementById("newCategoryLifespan").value,
                    categoryrate: document.getElementById("newCategoryRate").value,
                };

                fetch('/assets-categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                }).then(res => res.json())
                    .then(() => {
                    fetchCategories();
                    clearForm();
                    showToast('تمت إضافة الفئة بنجاح');
                    });
            });

            // عرض الفئات
            function fetchCategories() {
                fetch('/assets-categories')
                    .then(res => res.json())
                    .then(categories => {
                        let tbody = document.querySelector("#categoriesTable tbody");
                        tbody.innerHTML = '';
                        categories.forEach(category => {
                            let row = `<tr>
                                            <td>${category.name}</td>
                                            <td>${category.categorycode}</td>
                                            <td>${category.categorylifespan}</td>
                                            <td>${category.categoryrate}</td>
                                            <td>
                                                <button onclick="openEditModal(${category.id}, '${category.name}', '${category.categorycode}', ${category.categorylifespan}, ${category.categoryrate})">✏️ تعديل</button>
                                                <button onclick="deleteCategory(${category.id})">🗑️ حذف</button>
                                            </td>
                                        </tr>`;
                            tbody.innerHTML += row;
                        });
                    });
            }
            window.openEditModal = function(id, name, code, lifespan, rate) {
                document.getElementById('editCategoryId').value = id;
                document.getElementById('editCategoryName').value = name;
                document.getElementById('editCategoryCode').value = code;
                document.getElementById('editCategoryLifespan').value = lifespan;
                document.getElementById('editCategoryRate').value = rate;
                document.getElementById('editModal').style.display = 'block';
            }

            window.closeEditModal = function() {
                document.getElementById('editModal').style.display = 'none';
            }
            window.submitEditCategory = function() {
                let id = document.getElementById('editCategoryId').value;
                let data = {
                    name: document.getElementById('editCategoryName').value,
                    categorycode: document.getElementById('editCategoryCode').value,
                    categorylifespan: document.getElementById('editCategoryLifespan').value,
                    categoryrate: document.getElementById('editCategoryRate').value,
                };

                fetch(`/assets-categories/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(() => {
                    closeEditModal();
                    fetchCategories();
                    showToast("تم تحديث الفئة بنجاح");
                });
            }

            // // تحديث فئة
            // window.submitEditCategory = function (id, field, value) {
            //     fetch(`/categories/${id}`, {
            //         method: 'PUT',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //         },
            //         body: JSON.stringify({ [field]: value })
            //     }).then(() => {
            //         showToast('تم تحديث الفئة بنجاح');
            //     });
            // };

            // حذف فئة
            window.deleteCategory = function (id) {
                if (!confirm("هل أنت متأكد من الحذف؟")) return;
                fetch(`/assets-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => {
                    fetchCategories();
                    showToast('تم حذف الفئة بنجاح', '#d00000');
                });
            };

            function clearForm() {
                document.getElementById("newCategoryName").value = "";
                document.getElementById("newCategoryCode").value = "";
                document.getElementById("newCategoryLifespan").value = 5;
                document.getElementById("newCategoryRate").value = 20;
            }
        });
    </script>
    {{-- <script src="{{asset('financialaccounting/script.js')}}"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("editAssetForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch('/assets/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast('تم التعديل بنجاح');
                    // يمكنك تحديث الجدول هنا بدون ريلود
                    window.location.reload();
                } else {
                    showToast('حدث خطأ أثناء التعديل');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('فشل الاتصال بالخادم');
            });
        });
                // إغلاق المودال
            document.querySelector("#editAssetModal .close").onclick = function () {
                document.getElementById("editAssetModal").style.display = "none";
            };


        });
    </script>
@endsection
