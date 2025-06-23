

@section('css')

<link rel="stylesheet" href="{{asset('css/styles.css')}}">
{{-- <link rel="stylesheet" href="{{asset('main-css/icons.css')}}"> --}}

{{-- <link rel="stylesheet" href="{{asset('main-css/chat-icon.css')}}"> --}}
{{-- <link rel="stylesheet" href="{{asset('main-css/top-bar.css')}}"> --}}

{{-- <script src="{{asset('main-js/sidebar.js')}}"></script> --}}
{{-- <script src="{{asset('main-js/top-bar.js')}}"></script> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}
@endsection


        @extends('financialaccounting.layouts.master')



    @section('content')
        <div  id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;"></div>
        <header class="header">
            <h1 style="color:#fff">نظام إدارة الإيجارات</h1>
        </header>

        <div id="main-dashboard" class="dashboard">
            <div class="icon-card" onclick="showForm('property-form')">
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <h3>تسجيل عقار جديد</h3>
                <p>إضافة عقار جديد بكافة التفاصيل</p>
            </div>

            <div class="icon-card" onclick="showForm('contracts-list')">
                <div class="icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3>عرض العقود</h3>
                <p>عرض كافة عقود الإيجار</p>
            </div>

            <div class="icon-card" onclick="showForm('payment-form')">
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>تسجيل دفعة إيجار</h3>
                <p>إضافة دفعة لعقد إيجار</p>
            </div>

            <div class="icon-card" onclick="showForm('journal-entries')">
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>قيود اليومية</h3>
                <p>عرض القيود المحاسبية للإيجارات</p>
            </div>
        </div>

        <!-- نموذج تسجيل العقار -->
        <div id="property-form" class="form-container hidden">
            <div class="form-header">
                <h2>تسجيل عقار جديد</h2>
                <button class="close-btn" onclick="hideForm('property-form')"><i class="fas fa-times"></i></button>
            </div>
            <form id="new-property-form">
                <div class="form-group">
                    <label for="property-name">اسم العقار</label>
                    <input type="text" id="property-name" required>
                </div>

                <div class="form-group">
                    <label for="tenant-name">اسم المستأجر</label>
                    <input type="text" id="tenant-name" required>
                </div>

                <div class="form-group">
                    <label for="landlord-name">اسم المؤجر</label>
                    <input type="text" id="landlord-name" required>
                </div>

                <div class="form-group">
                    <label for="property-address">عنوان العقار</label>
                    <textarea id="property-address" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start-date">تاريخ بداية العقد</label>
                        <input type="date" id="start-date" required>
                    </div>

                    <div class="form-group">
                        <label for="end-date">تاريخ نهاية العقد</label>
                        <input type="date" id="end-date" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="rent-amount">قيمة الإيجار</label>
                        <input type="number" id="rent-amount" required>
                    </div>

                    <div class="form-group">
                        <label for="payment-cycle">دورة الدفع</label>
                        <select id="payment-cycle" required>
                            <option value="monthly">شهري</option>
                            <option value="quarterly">ربع سنوي</option>
                            <option value="biannual">نصف سنوي</option>
                            <option value="annual">سنوي</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contract-details">تفاصيل إضافية</label>
                    <textarea id="contract-details"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">حفظ العقار</button>
                    <button type="button" class="cancel-btn" onclick="hideForm('property-form')">إلغاء</button>
                </div>
            </form>
        </div>

        <!-- قائمة العقود -->
        <div id="contracts-list" class="form-container hidden">
            <div class="form-header">
                <h2>قائمة العقود</h2>
                <button class="close-btn" onclick="hideForm('contracts-list')"><i class="fas fa-times"></i></button>
            </div>
            <div class="search-container">
                <input type="text" id="contract-search" placeholder="بحث في العقود...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="contracts-container" id="contracts-container">
                <!-- ستتم إضافة العقود هنا ديناميكياً -->
            </div>
        </div>

        <!-- نموذج تسجيل دفعة -->
        <div id="payment-form" class="form-container hidden">
            <div class="form-header">
                <h2>تسجيل دفعة إيجار</h2>
                <button class="close-btn" onclick="hideForm('payment-form')"><i class="fas fa-times"></i></button>
            </div>
            <form id="new-payment-form">
                <div class="form-group">
                    <label for="contract-select">اختر العقار</label>
                    <select id="contract-select" required>
                        <option value="">-- اختر العقار --</option>
                        <!-- ستتم إضافة العقارات هنا ديناميكياً -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment-amount">مبلغ الدفعة</label>
                    <input type="number" id="payment-amount" required>
                </div>

                <div class="form-group">
                    <label for="payment-date">تاريخ الدفع</label>
                    <input type="date" id="payment-date" required>
                </div>

                <div class="form-group">
                    <label for="payment-method">طريقة الدفع</label>
                    <select id="payment-method" required>
                        <option value="cash">نقدي</option>
                        <option value="check">شيك</option>
                        <option value="transfer">تحويل بنكي</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment-notes">ملاحظات</label>
                    <textarea id="payment-notes"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">تسجيل الدفعة</button>
                    <button type="button" class="cancel-btn" onclick="hideForm('payment-form')">إلغاء</button>
                </div>
            </form>
        </div>

        <!-- قائمة قيود اليومية -->
        <div id="journal-entries" class="form-container hidden">
            <div class="form-header">
                <h2>قيود اليومية</h2>
                <button class="close-btn" onclick="hideForm('journal-entries')"><i class="fas fa-times"></i></button>
            </div>
            <div class="search-container">
                <input type="text" id="journal-search" placeholder="بحث في القيود...">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-container">
                <select id="property-filter" onchange="filterJournalEntries()">
                    <option value="all">جميع العقارات</option>
                    <!-- سيتم ملؤها بالعقارات ديناميكياً -->
                </select>
                <select id="entry-type-filter" onchange="filterJournalEntries()">
                    <option value="all">جميع أنواع القيود</option>
                    <option value="rent">استحقاق الإيجار</option>
                    <option value="payment">دفعات الإيجار</option>
                </select>
            </div>
            <div class="journal-entries-container" id="journal-entries-container">
                <!-- سيتم إضافة قيود اليومية هنا ديناميكياً -->
            </div>
        </div>

        {{-- <div class="contract-actions">
            <button class="action-btn view-btn" onclick="viewContractDetails()">عرض التفاصيل</button>
            <button class="action-btn view-btn" onclick="viewAccountStatement()">كشف حساب</button>
            <button class="action-btn edit-btn" onclick="editContract()">تعديل</button>
            <button class="action-btn delete-btn" onclick="deleteContract()">حذف</button>
        </div> --}}
        <div class="modal" id="editModal" style="display: none;">
            <div class="modal-content">
                <h3>تعديل العقار</h3>
                <form id="editForm">
                    <input type="hidden" name="id" id="property_id">
                    <label>اسم العقار</label>
                    <input type="text" name="property_name" id="property_name" required>
                    <label>اسم المستأجر</label>
                    <input type="text" name="tenant_name" id="tenant_name" required>
                    <label>اسم المؤجر</label>
                    <input type="text" name="landlord_name" id="landlord_name" required>
                    <label>تاريخ البداية</label>
                    <input type="date" name="start_date" id="start_date" required>
                    <label>تاريخ النهاية</label>
                    <input type="date" name="end_date" id="end_date" required>
                    <label>قيمة الإيجار</label>
                    <input type="number" step="0.01" name="rent_amount" id="rent_amount" required>
                    {{-- <label>دورة الدفع</label> --}}
                    {{-- <input type="text" name="payment_cycle" id="payment_cycle" required> --}}
                    <div class="form-group">
                        <label for="payment-cycle">دورة الدفع</label>
                        <select name="payment_cycle" id="payment_cycle" required>
                            <option value="monthly">شهري</option>
                            <option value="quarterly">ربع سنوي</option>
                            <option value="biannual">نصف سنوي</option>
                            <option value="annual">سنوي</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button class="submit-btn" type="submit">حفظ التعديلات</button>
                        <button class="cancel-btn" type="button" onclick="closeModal()">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="back-to-dashboard" class="back-to-dashboard hidden">
            <button class="back-btn" onclick="backToDashboard()">
                <i class="fas fa-arrow-right"></i> العودة للقائمة الرئيسية
            </button>
        </div>
    @endsection


@section('js')
    <script src="{{asset('js/journal-entries.js')}}"></script>
    <script src="{{asset('main-js/script.js')}}"></script>


<script>
    function showForm(formId) {
        // إخفاء القائمة الرئيسية
        document.getElementById('main-dashboard').classList.add('hidden');

        // إظهار زر الرجوع للقائمة الرئيسية
        document.getElementById('back-to-dashboard').classList.remove('hidden');

        // إخفاء جميع النماذج أولاً
        document.querySelectorAll('.form-container').forEach(form => {
            form.classList.add('hidden');
        });

        // إظهار النموذج المطلوب
        document.getElementById(formId).classList.remove('hidden');

        // تنفيذ العمليات المناسبة بناءً على النموذج المطلوب
        if (formId === 'contracts-list') {
            fetchProperties();
        } else if (formId === 'payment-form') {
            fetchPropertiesforpaymentform();
            // populateContractSelect();
        } else if (formId === 'journal-entries') {
            // تحديث قيود اليومية إذا كانت الوظيفة موجودة
            if (typeof displayJournalEntries === 'function') {
                // populatePropertyFilter();
                displayJournalEntries();
            }
        }
    }
    function hideForm(formId) {
        // إخفاء النموذج المحدد
        document.getElementById(formId).classList.add('hidden');

        // إظهار القائمة الرئيسية
        document.getElementById('main-dashboard').classList.remove('hidden');

        // إخفاء زر الرجوع للقائمة الرئيسية
        document.getElementById('back-to-dashboard').classList.add('hidden');
    }

    function backToDashboard() {
        // إخفاء جميع النماذج
        document.querySelectorAll('.form-container').forEach(form => {
            form.classList.add('hidden');
        });

        // إظهار القائمة الرئيسية
        document.getElementById('main-dashboard').classList.remove('hidden');

        // إخفاء زر الرجوع للقائمة الرئيسية
        document.getElementById('back-to-dashboard').classList.add('hidden');
    }

    // إضافة مستمع أحداث عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        // التأكد من أن زر العودة للقائمة الرئيسية مخفي عند بدء التطبيق
        const backButton = document.getElementById('back-to-dashboard');
        if (backButton) {
            backButton.classList.add('hidden');
        }
    });

</script>

<script>
    document.getElementById("new-property-form").addEventListener("submit", function(e) {
        e.preventDefault();

        const data = {
            property_name: document.getElementById("property-name").value,
            tenant_name: document.getElementById("tenant-name").value,
            landlord_name: document.getElementById("landlord-name").value,
            property_address: document.getElementById("property-address").value,
            start_date: document.getElementById("start-date").value,
            end_date: document.getElementById("end-date").value,
            rent_amount: document.getElementById("rent-amount").value,
            payment_cycle: document.getElementById("payment-cycle").value,
            contract_details: document.getElementById("contract-details").value,
        };

        fetch("{{ route('properties.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            showToast(result.message);
            // showToast('تم إضافة الأصل بنجاح');

            document.getElementById("new-property-form").reset();
            hideForm('property-form');
        })
        .catch(error => {
            console.error('Error:', error);
            showToast("حدث خطأ أثناء الحفظ!", '#d00000');
            // showToast('حدث خطأ، حاول مجددًا.', '#d00000');

        });
    });
    function showToast(message, color = '#38b000') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.style.backgroundColor = color;
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
</script>

{{-- formatDate --}}
<script>
    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('ar-SA', options);
    }

    function formatCurrency(amount) {
        return amount.toLocaleString('ar-SA', { style: 'currency', currency: 'SAR' });
    }

    function translatePaymentCycle(cycle) {
        const translations = {
            'monthly': 'شهري',
            'quarterly': 'ربع سنوي',
            'biannual': 'نصف سنوي',
            'annual': 'سنوي'
        };
        return translations[cycle] || cycle;
    }

    function translatePaymentMethod(method) {
        const translations = {
            'cash': 'نقدي',
            'check': 'شيك',
            'transfer': 'تحويل بنكي'
        };
        return translations[method] || method;
    }
    function viewAccountStatement(id ) {
        const property = allProperties.find(p => p.id === id);
        if (!property) return;

        const propertyTransactions = transactions.filter(transaction => transaction.propertyId === id)
            .sort((a, b) => new Date(a.date) - new Date(b.date));

        if (propertyTransactions.length === 0) {
            showToast('لا توجد معاملات مالية لهذا العقار','#d00000');
            // showToast(result.message);

            return;
        }

        const statementWindow = window.open('', 'كشف حساب', 'width=800,height=600');

        statementWindow.document.write(`
                <meta charset="UTF-8">
                <title>كشف حساب - ${property.propertyName}</title>
                <style>
                    body {
                        font-family: 'Tajawal', sans-serif;
                        padding: 20px;
                        direction: rtl;
                    }
                    h1, h2 {
                        text-align: center;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 10px;
                        text-align: right;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .debit {
                        color: #e74c3c;
                    }
                    .credit {
                        color: #2ecc71;
                    }
                    .total-row {
                        font-weight: bold;
                        background-color: #f9f9f9;
                    }
                    .print-btn {
                        display: block;
                        margin: 20px auto;
                        padding: 10px 20px;
                        background-color: #4a6da7;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    }
                </style>

                <h1>كشف حساب</h1>
                <h2>${property.property_name} - ${property.tenant_name}</h2>
                <div>
                    <p><strong>المؤجر:</strong> ${property.landlord_name}</p>
                    <p><strong>فترة العقد:</strong> ${formatDate(property.start_date)} إلى ${formatDate(property.end_date)}</p>
                    <p><strong>قيمة الإيجار:</strong> ${formatCurrency(property.rent_amount)}</p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الوصف</th>
                            <th>مدين</th>
                            <th>دائن</th>
                            <th>الرصيد</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${propertyTransactions.map(transaction => `
                            <tr>
                                <td>${formatDate(transaction.date)}</td>
                                <td>${transaction.description}</td>
                                <td class="debit">${transaction.debit > 0 ? formatCurrency(transaction.debit) : ''}</td>
                                <td class="credit">${transaction.credit > 0 ? formatCurrency(transaction.credit) : ''}</td>
                                <td>${formatCurrency(transaction.balance)}</td>
                            </tr>
                        `).join('')}
                        <tr class="total-row">
                            <td colspan="4">الرصيد النهائي</td>
                            <td>${formatCurrency(propertyTransactions[propertyTransactions.length - 1].balance)}</td>
                        </tr>
                    </tbody>
                </table>

                <button class="print-btn" onclick="window.print()">طباعة كشف الحساب</button>

        `);
    }
</script>

{{--  --}}
<script>

    let allProperties = [];
    let allPayments = [];
    let transactions = [];

    function fetchProperties(searchTerm = '') {
        fetch(`/properties/show?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                allProperties = data.properties;
                allPayments = data.payments; // إذا كنت ترجع المدفوعات أيضًا
                // console.log(allPayments)
                transactions = data.transactions; // إذا كنت ترجع المدفوعات أيضًا
                populateContractsList();
            })
            .catch(error => {
                console.error('Error fetching properties:', error);
                showToast('Error fetching properties','#d00000');

            });
    }

    function populateContractsList() {
        const contractsContainer = document.getElementById('contracts-container');
        contractsContainer.innerHTML = '';

        if (allProperties.length === 0) {
            contractsContainer.innerHTML = '<div class="no-data">لا توجد عقود مسجلة بعد</div>';
            return;
        }

        allProperties.forEach(property => {
            const today = new Date();
            const endDate = new Date(property.end_date);
            const isActive = today <= endDate;

            const propertyPayments = allPayments.filter(payment => payment.property_id === property.id);
            const totalPaid = propertyPayments.reduce((sum, payment) => sum + parseFloat(payment.amount), 0);

            const contractCard = document.createElement('div');
            contractCard.className = 'contract-card';
            contractCard.id = `property-${property.id}`; // ← هنا تضيف الـ ID المهم للحذف لاحقاً
            contractCard.innerHTML = `
                <div class="contract-header">
                    <div class="contract-title">${property.property_name}</div>
                    <div class="contract-status ${isActive ? 'status-active' : 'status-expired'}">${isActive ? 'نشط' : 'منتهي'}</div>
                </div>
                <div class="contract-details">
                    <div class="contract-info">
                        <span><strong>المستأجر:</strong> ${property.tenant_name}</span>
                        <span><strong>المؤجر:</strong> ${property.landlord_name}</span>
                    </div>
                    <div class="contract-info">
                        <span><strong>تاريخ البداية:</strong> ${formatDate(property.start_date)}</span>
                        <span><strong>تاريخ النهاية:</strong> ${formatDate(property.end_date)}</span>
                    </div>
                    <div class="contract-info">
                        <span><strong>قيمة الإيجار:</strong> ${formatCurrency(property.rent_amount)}</span>
                        <span><strong>دورة الدفع:</strong> ${translatePaymentCycle(property.payment_cycle)}</span>
                    </div>
                    <div class="contract-info">
                        <span><strong>إجمالي المدفوعات:</strong> ${formatCurrency(totalPaid)}</span>
                        <span><strong>المتبقي:</strong> ${formatCurrency(property.rent_amount - totalPaid)}</span>
                    </div>
                </div>
                <div class="contract-actions">
                    <button class="action-btn view-btn" onclick="viewContractDetails(${property.id})">عرض التفاصيل</button>
                    <button class="action-btn edit-btn" onclick="editContract(${property.id})">تعديل</button>
                    <button class="action-btn delete-btn" onclick="deleteContract(${property.id})">حذف</button>
                    <button class="action-btn account-statement-btn" onclick="viewAccountStatement(${property.id})">كشف الحساب</button>
                </div>
            `;

            contractsContainer.appendChild(contractCard);
        });
    }

    document.getElementById('contract-search').addEventListener('input', function (e) {
        const term = e.target.value.trim();
        fetchProperties(term);
    });

    // أول تحميل
    fetchProperties();
</script>

<script>
    function editContract(id) {
        fetch(`/properties/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('property_id').value = data.id;
                document.getElementById('property_name').value = data.property_name;
                document.getElementById('tenant_name').value = data.tenant_name;
                document.getElementById('landlord_name').value = data.landlord_name;
                document.getElementById('start_date').value = data.start_date;
                document.getElementById('end_date').value = data.end_date;
                document.getElementById('rent_amount').value = data.rent_amount;
                document.getElementById('payment_cycle').value = data.payment_cycle;
                document.getElementById('editModal').style.display = 'block';
            });
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('property_id').value;
        const formData = new FormData(this);

        fetch(`/properties/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(res => res.json())
            .then(data => {
                showToast('تم تعديل العقار بنجاح');
                closeModal();
                fetchProperties();
            });
    });

    function deleteContract(id) {
        if (confirm("هل أنت متأكد من حذف هذا العقار؟")) {
            fetch(`/properties/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                showToast('تم حذف العقار بنجاح','#d00000');
                document.getElementById(`property-${id}`).remove(); // يفترض أنك حاط div برقم ID
            });
        }
    }

</script>
{{-- add new payments --}}
<script>

    // let allProperties = [];

    // getElementById('new-payment-form').addEventListener('submit', function (e) {})
    function fetchPropertiesforpaymentform() {
        fetch(`/properties/show`)
            .then(response => response.json())
            .then(data => {
                allProperties = data.properties;
                fullselect(allProperties);
            })
            .catch(error => {
                console.error('Error fetching properties:', error);
                showToast('حدث خطأ أثناء جلب العقارات', '#d00000');
            });
    }

    function fullselect(properties) {
        const select = document.getElementById('contract-select');
        select.innerHTML = `<option value="">-- اختر العقار --</option>`; // إعادة تعيين القائمة

        properties.forEach(property => {
            const option = document.createElement('option');
            option.value = property.id;
            option.textContent = property.property_name;
            select.appendChild(option);
        });
    }
    document.getElementById('new-payment-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const propertyId = document.getElementById('contract-select').value;
        const amount = document.getElementById('payment-amount').value;
        const date = document.getElementById('payment-date').value;
        const method = document.getElementById('payment-method').value;
        const notes = document.getElementById('payment-notes').value;

        fetch('/payments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                property_id: propertyId,
                amount: amount,
                date: date,
                method: method,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ✅ عند نجاح تسجيل الدفعة، نُنشئ القيد المحاسبي
                // createJournalEntry({
                //     property_id: propertyId,
                //     amount: amount,
                //     payment_date: date,
                //     entry_type: 'payment'  // مهم جداً لتمييز نوع القيد
                // });

                showToast('تم تسجيل الدفعة بنجاح');
                hideForm('payment-form');
                document.getElementById('new-payment-form').reset();
            } else {
                showToast('حدث خطأ: ' + data.message,'#d00000');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('فشل في إرسال البيانات','#d00000');
        });
    });
</script>
@endsection

