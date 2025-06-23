// بيانات السندات
let vouchers = [];
function fetchVouchers() {
    fetch('/vouchers/index')
        .then(res => res.json())
        .then(async data => {
            // تأكد أن البيانات مصفوفة
            vouchers = Array.isArray(data) ? data : [];



            renderVouchers(vouchers)
            // renderFundsStatistics(funds)
            // renderAccounts(funds); // عرض البيانات في الواجهة
            // renderStatistics(funds); // عرض البيانات في الواجهة
        })
        .catch(error => {
            console.error("حدث خطأ أثناء جلب الحسابات:", error);
        });
}

// php artisan make:model Transaction_Acount -m

fetchVouchers();


// بيانات الفواتير
let bills = [
    {
        id: 1,
        number: "INV-001",
        type: "مبيعات",
        date: "2023-06-25",
        amount: 12500,
        customer: "شركة الأمل للتجارة",
        description: "بيع منتجات متنوعة",
        items: [
            { name: "منتج A", quantity: 5, price: 1000, total: 5000 },
            { name: "منتج B", quantity: 3, price: 2500, total: 7500 }
        ]
    },
    {
        id: 2,
        number: "INV-002",
        type: "مشتريات",
        date: "2023-06-20",
        amount: 8000,
        supplier: "مؤسسة المستقبل للتوريدات",
        description: "شراء مواد خام",
        items: [
            { name: "مادة X", quantity: 10, price: 400, total: 4000 },
            { name: "مادة Y", quantity: 8, price: 500, total: 4000 }
        ]
    }
];

// المتغيرات العامة
let currentAccountId = null;
let transactionType = null;
let currentTransaction = null;
let currentTransactionIndex = null;
let currentView = 'banks';

// تهيئة التطبيق
document.addEventListener('DOMContentLoaded', function() {
    // renderStatistics();
    // renderAccounts();
    setupEventListeners();
    setupPreviewActions();
    setupSidebar();
});

// إعداد الشريط الجانبي
function setupSidebar() {
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    document.getElementById('bankViewBtn').addEventListener('click', function() {
        window.location.href = '/bank-managment';
        // switchView('banks');
    });

    document.getElementById('fundsViewBtn').addEventListener('click', function() {
        window.location.href = '/fund/index';
    });


    document.getElementById('vouchersViewBtn').addEventListener('click', function() {
        window.location.href = '/vouchers';
    });

    document.getElementById('billsViewBtn').addEventListener('click', function() {
        window.location.href = '/bills';
    });
}

// إعداد مستمعي الأحداث
function setupEventListeners() {


    // أزرار البحث والفلترة للسندات
    document.getElementById('voucherSearchBtn').addEventListener('click', function() {
        searchVouchers();
    });

    document.getElementById('voucherSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchVouchers();
        }
    });

    document.getElementById('voucherTypeFilter').addEventListener('change', function() {
        searchVouchers();
    });

    document.getElementById('voucherDateFilter').addEventListener('change', function() {
        searchVouchers();
    });

    document.getElementById('resetFiltersBtn').addEventListener('click', function() {
        document.getElementById('voucherSearchInput').value = '';
        document.getElementById('voucherTypeFilter').value = 'all';
        document.getElementById('voucherDateFilter').value = '';
        renderVouchers();
    });
}

// وظيفة البحث والفلترة للسندات
function searchVouchers() {
    const searchText = document.getElementById('voucherSearchInput').value.toLowerCase();
    const typeFilter = document.getElementById('voucherTypeFilter').value;
    const dateFilter = document.getElementById('voucherDateFilter').value;

    let filteredVouchers = [...vouchers];

    // تطبيق فلتر النص
    if (searchText) {
        filteredVouchers = filteredVouchers.filter(voucher =>
            voucher.number.toLowerCase().includes(searchText) ||
            voucher.fromTo.toLowerCase().includes(searchText) ||
            voucher.description.toLowerCase().includes(searchText)
        );
    }

    // تطبيق فلتر النوع
    if (typeFilter !== 'all') {
        filteredVouchers = filteredVouchers.filter(voucher => voucher.type === typeFilter);
    }

    // تطبيق فلتر التاريخ
    if (dateFilter) {
        filteredVouchers = filteredVouchers.filter(voucher => voucher.date === dateFilter);
    }

    renderFilteredVouchers(filteredVouchers);
}

// عرض السندات
function renderVouchers() {
    const vouchersContainer = document.getElementById('vouchersList');
    vouchersContainer.innerHTML = '';

    // تغيير طريقة العرض لتكون بشكل صفوف بدلاً من بطاقات
    const table = document.createElement('table');
    table.className = 'vouchers-table';

    // إنشاء رأس الجدول
    const tableHead = document.createElement('thead');
    tableHead.innerHTML = `
        <tr>
            <th>رقم السند</th>
            <th>التاريخ</th>
            <th>النوع</th>
            <th>المستفيد/المرسل</th>
            <th>المبلغ</th>
            <th>البيان</th>
            <th>الإجراءات</th>
        </tr>
    `;
    table.appendChild(tableHead);

    // إنشاء جسم الجدول
    const tableBody = document.createElement('tbody');

    if (vouchers.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="no-results">لا توجد سندات متاحة</td>
            </tr>
        `;
    } else {
        vouchers.forEach(voucher => {
            const row = document.createElement('tr');
            row.className = voucher.type === 'قبض' ? 'receipt-row' : 'payment-row';

            row.innerHTML = `
                <td>${voucher.number}</td>
                <td>${formatDate(voucher.date)}</td>
                <td>${voucher.type === 'قبض' ? '<span class="badge receipt-badge">قبض</span>' : '<span class="badge payment-badge">صرف</span>'}</td>
                <td>${voucher.fromTo}</td>
                <td class="${voucher.type === 'قبض' ? 'amount-positive' : 'amount-negative'}">${formatCurrency(voucher.amount)}</td>
                <td>${voucher.description}</td>
                <td class="actions-cell">
                    <button class="action-icon preview-voucher" data-id="${voucher.id}" title="معاينة">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-icon edit-voucher" data-id="${voucher.id}" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-icon delete-voucher" data-id="${voucher.id}" title="حذف">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    table.appendChild(tableBody);
    vouchersContainer.appendChild(table);

    // إضافة مستمعات الأحداث للأزرار في الجدول
    setupVoucherActionListeners();
}

// إعداد مستمعات الأحداث لأزرار السندات
function setupVoucherActionListeners() {
    // معاينة السند
    document.querySelectorAll('.preview-voucher').forEach(btn => {
        btn.addEventListener('click', function() {
            const voucherId = parseInt(this.dataset.id);
            previewVoucher(voucherId);
        });
    });

    // تعديل السند
    document.querySelectorAll('.edit-voucher').forEach(btn => {
        btn.addEventListener('click', function() {
            const voucherId = parseInt(this.dataset.id);
            editVoucher(voucherId);
        });
    });

    // حذف السند
    document.querySelectorAll('.delete-voucher').forEach(btn => {
        btn.addEventListener('click', function() {
            const voucherId = parseInt(this.dataset.id);
            deleteVoucher(voucherId);
        });
    });
}

// وظيفة معاينة السند
function previewVoucher(voucherId) {
    const voucher = vouchers.find(v => v.id === voucherId);
    if (!voucher) return;

    const previewContainer = document.getElementById('documentPreview');
    previewContainer.innerHTML = '';

    let documentHTML = '';

    if (voucher.type === 'قبض') {
        documentHTML = `
            <div class="preview-document deposit-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="document-title">سند قبض</div>
                </div>
                <div class="document-body">
                    <div class="document-row">
                        <div class="document-field">رقم السند:</div>
                        <div class="document-value">${voucher.number}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(voucher.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">استلمنا من:</div>
                        <div class="document-value">${voucher.fromTo}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المبلغ:</div>
                        <div class="document-value amount-positive">${formatCurrency(voucher.amount)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">طريقة الدفع:</div>
                        <div class="document-value">${voucher.paymentMethod || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">البيان:</div>
                        <div class="document-value document-description">${voucher.description}</div>
                    </div>
                </div>
                <div class="document-footer">
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>المستلم</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المحاسب</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المدير المالي</div>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (voucher.type === 'صرف') {
        documentHTML = `
            <div class="preview-document transaction-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="document-title">سند صرف</div>
                </div>
                <div class="document-body">
                    <div class="document-row">
                        <div class="document-field">رقم السند:</div>
                        <div class="document-value">${voucher.number}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(voucher.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">صرفنا إلى:</div>
                        <div class="document-value">${voucher.fromTo}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المبلغ:</div>
                        <div class="document-value amount-negative">${formatCurrency(voucher.amount)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">طريقة الدفع:</div>
                        <div class="document-value">${voucher.paymentMethod || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">البيان:</div>
                        <div class="document-value document-description">${voucher.description}</div>
                    </div>
                </div>
                <div class="document-footer">
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>المستلم</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المحاسب</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المدير المالي</div>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    previewContainer.innerHTML = documentHTML;
    document.getElementById('previewDocumentModal').style.display = 'block';
}

function updateVoucher(id, type) {


    let data = {
        number: type === 'قبض' ? document.getElementById('generalReceiptNumber2').value : document.getElementById('generalPaymentNumber2').value,
        date: type === 'قبض' ? document.getElementById('generalReceiptDate2').value : document.getElementById('generalPaymentDate2').value,
        fromTo: type === 'قبض' ? document.getElementById('receiptFromName2').value : document.getElementById('paidToName2').value,
        amount: type === 'قبض' ? document.getElementById('generalReceiptAmount2').value : document.getElementById('generalPaymentAmount2').value,
        paymentMethod: type === 'قبض' ? document.getElementById('paymentMethod2').value : document.getElementById('paymentMethodOut2').value,
        description: type === 'قبض' ? document.getElementById('generalReceiptDescription2').value : document.getElementById('generalPaymentDescription2').value,
        type: type
    };

    fetch(`/vouchers/update/${id}`, {


        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const response = await res.json();

        if (!res.ok) {
            showToast(response.message || 'حدث خطأ أثناء التحديث', '#d00000');
            return;
        }

        showToast(response.message || 'تم التحديث بنجاح');
        if (type === 'قبض') {
            document.getElementById('generalReceiptForm2').reset();
            document.getElementById('generalReceiptModal2').style.display = 'none';
        } else {
            document.getElementById('generalPaymentForm2').reset();
            document.getElementById('generalPaymentModal2').style.display = 'none';
        }


        // تحديث البيانات في الجدول أو إعادة التحميل
        fetchVouchers(); // تأكد أن لديك دالة fetchVouchers لإعادة تحميل السندات
    })
    .catch(error => {
        console.error('Error updating voucher:', error);
        showToast('فشل الاتصال بالخادم', '#d00000');
    });
}

// وظيفة تعديل السند
function editVoucher(voucherId) {
    const voucher = vouchers.find(v => v.id === voucherId);
    if (!voucher) return;

    if (voucher.type === 'قبض') {
        // openGeneralReceiptModal();
        document.getElementById('generalReceiptModal2').style.display = 'block';

        document.getElementById('generalReceiptNumber2').value = voucher.number;
        document.getElementById('generalReceiptDate2').value = voucher.date;
        document.getElementById('receiptFromName2').value = voucher.fromTo;
        document.getElementById('generalReceiptAmount2').value = voucher.amount;
        document.getElementById('paymentMethod2').value = voucher.paymentMethod || 'نقدي';
        document.getElementById('generalReceiptDescription2').value = voucher.description;

        // تغيير وظيفة إرسال النموذج للتحديث بدلاً من الإضافة
        const form = document.getElementById('generalReceiptForm2');
        form.dataset.editId = voucherId;

        form.onsubmit = function(e) {
            e.preventDefault();
            // console.log('ok')

            updateVoucher(voucherId, 'قبض');
        };
    } else if (voucher.type === 'صرف') {
        // openGeneralPaymentModal();
        document.getElementById('generalPaymentModal2').style.display = 'block';

        document.getElementById('generalPaymentNumber2').value = voucher.number;
        document.getElementById('generalPaymentDate2').value = voucher.date;
        document.getElementById('paidToName2').value = voucher.fromTo;
        document.getElementById('generalPaymentAmount2').value = voucher.amount;
        document.getElementById('paymentMethodOut2').value = voucher.paymentMethod || 'نقدي';
        document.getElementById('generalPaymentDescription2').value = voucher.description;

        // تغيير وظيفة إرسال النموذج للتحديث بدلاً من الإضافة
        const form = document.getElementById('generalPaymentForm2');
        form.dataset.editId = voucherId;

        form.onsubmit = function(e) {
            e.preventDefault();
            updateVoucher(voucherId, 'صرف');
        };
    }
}



function deleteVoucher(voucherId) {
    if (confirm('هل أنت متأكد من حذف هذا السند؟')) {
        fetch(`/vouchers/delete/${voucherId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // احذف السند من المصفوفة المحلية
                vouchers = vouchers.filter(v => v.id !== voucherId);
                renderVouchers();
                showToast('تم الحذف بنجاح');
            } else {
                showToast('حدث خطأ أثناء الحذف','#d00000');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('فشل في الاتصال بالخادم','#d00000');
        });
    }
}


// عرض السندات بعد التصفية
function renderFilteredVouchers(filteredVouchers) {
    const vouchersContainer = document.getElementById('vouchersList');
    vouchersContainer.innerHTML = '';

    if (filteredVouchers.length === 0) {
        vouchersContainer.innerHTML = '<div class="no-results">لا توجد نتائج مطابقة للبحث</div>';
        return;
    }

    // إنشاء جدول للسندات المصفاة
    const table = document.createElement('table');
    table.className = 'vouchers-table';

    // إنشاء رأس الجدول
    const tableHead = document.createElement('thead');
    tableHead.innerHTML = `
        <tr>
            <th>رقم السند</th>
            <th>التاريخ</th>
            <th>النوع</th>
            <th>المستفيد/المرسل</th>
            <th>المبلغ</th>
            <th>البيان</th>
            <th>الإجراءات</th>
        </tr>
    `;
    table.appendChild(tableHead);

    // إنشاء جسم الجدول
    const tableBody = document.createElement('tbody');

    filteredVouchers.forEach(voucher => {
        const row = document.createElement('tr');
        row.className = voucher.type === 'قبض' ? 'receipt-row' : 'payment-row';

        row.innerHTML = `
            <td>${voucher.number}</td>
            <td>${formatDate(voucher.date)}</td>
            <td>${voucher.type === 'قبض' ? '<span class="badge receipt-badge">قبض</span>' : '<span class="badge payment-badge">صرف</span>'}</td>
            <td>${voucher.fromTo}</td>
            <td class="${voucher.type === 'قبض' ? 'amount-positive' : 'amount-negative'}">${formatCurrency(voucher.amount)}</td>
            <td>${voucher.description}</td>
            <td class="actions-cell">
                <button class="action-icon preview-voucher" data-id="${voucher.id}" title="معاينة">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-icon edit-voucher" data-id="${voucher.id}" title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-icon delete-voucher" data-id="${voucher.id}" title="حذف">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(row);
    });

    table.appendChild(tableBody);
    vouchersContainer.appendChild(table);

    // إعداد مستمعات الأحداث للأزرار
    setupVoucherActionListeners();
}

// طباعة المستند
function printDocument() {
    const printContentElement = document.getElementById('printableDocument');
    if (!printContentElement) {
        alert("لا يوجد محتوى للطباعة.");
        return;
    }

    const printContent = printContentElement.innerHTML;
    const originalContent = document.body.innerHTML;

    // طباعة آمنة دون التأثير على الـ DOM بالكامل
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>طباعة المستند</title>
            <style>
                body { font-family: Arial, sans-serif; direction: rtl; }
                .document-preview { margin: 20px; }
            </style>
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}




///end السند







// عرض الفواتير
function renderBills() {
    const billsContainer = document.getElementById('billsList');
    billsContainer.innerHTML = '';

    const table = document.createElement('table');
    table.className = 'bills-table';

    // إنشاء رأس الجدول
    const tableHead = document.createElement('thead');
    tableHead.innerHTML = `
        <tr>
            <th>رقم الفاتورة</th>
            <th>التاريخ</th>
            <th>النوع</th>
            <th>العميل/المورد</th>
            <th>المبلغ</th>
            <th>البيان</th>
            <th>الإجراءات</th>
        </tr>
    `;
    table.appendChild(tableHead);

    // إنشاء جسم الجدول
    const tableBody = document.createElement('tbody');

    if (bills.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="no-results">لا توجد فواتير متاحة</td>
            </tr>
        `;
    } else {
        bills.forEach(bill => {
            const row = document.createElement('tr');
            row.className = bill.type === 'مبيعات' ? 'sales-row' : 'purchase-row';

            row.innerHTML = `
                <td>${bill.number}</td>
                <td>${formatDate(bill.date)}</td>
                <td>${bill.type === 'مبيعات' ? '<span class="badge sales-badge">مبيعات</span>' : '<span class="badge purchase-badge">مشتريات</span>'}</td>
                <td>${bill.type === 'مبيعات' ? bill.customer : bill.supplier}</td>
                <td class="${bill.type === 'مبيعات' ? 'amount-positive' : 'amount-negative'}">${formatCurrency(bill.amount)}</td>
                <td>${bill.description}</td>
                <td class="actions-cell">
                    <button class="action-icon preview-bill" data-id="${bill.id}" title="معاينة">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-icon edit-bill" data-id="${bill.id}" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-icon delete-bill" data-id="${bill.id}" title="حذف">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });
    }

    table.appendChild(tableBody);
    billsContainer.appendChild(table);

    // إضافة مستمعات الأحداث للأزرار في الجدول
    setupBillActionListeners();
}

// إعداد مستمعات الأحداث لأزرار الفواتير
function setupBillActionListeners() {
    document.querySelectorAll('.preview-bill').forEach(btn => {
        btn.addEventListener('click', function() {
            const billId = parseInt(this.dataset.id);
            previewBill(billId);
        });
    });

    document.querySelectorAll('.edit-bill').forEach(btn => {
        btn.addEventListener('click', function() {
            const billId = parseInt(this.dataset.id);
            alert(`سيتم إضافة تعديل الفاتورة رقم ${billId} قريبًا`);
        });
    });

    document.querySelectorAll('.delete-bill').forEach(btn => {
        btn.addEventListener('click', function() {
            const billId = parseInt(this.dataset.id);
            if (confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) {
                bills = bills.filter(b => b.id !== billId);
                renderBills();
            }
        });
    });
}

// وظيفة البحث والفلترة للفواتير
function searchBills() {
    const searchText = document.getElementById('billSearchInput').value.toLowerCase();
    const typeFilter = document.getElementById('billTypeFilter').value;
    const dateFilter = document.getElementById('billDateFilter').value;

    let filteredBills = [...bills];

    // تطبيق فلتر النص
    if (searchText) {
        filteredBills = filteredBills.filter(bill =>
            bill.number.toLowerCase().includes(searchText) ||
            (bill.type === 'مبيعات' ? bill.customer.toLowerCase().includes(searchText) : bill.supplier.toLowerCase().includes(searchText)) ||
            bill.description.toLowerCase().includes(searchText)
        );
    }

    // تطبيق فلتر النوع
    if (typeFilter !== 'all') {
        filteredBills = filteredBills.filter(bill => bill.type === typeFilter);
    }

    // تطبيق فلتر التاريخ
    if (dateFilter) {
        filteredBills = filteredBills.filter(bill => bill.date === dateFilter);
    }

    renderFilteredBills(filteredBills);
}

// عرض الفواتير بعد التصفية
function renderFilteredBills(filteredBills) {
    const billsContainer = document.getElementById('billsList');
    billsContainer.innerHTML = '';

    if (filteredBills.length === 0) {
        billsContainer.innerHTML = '<div class="no-results">لا توجد نتائج مطابقة للبحث</div>';
        return;
    }

    const table = document.createElement('table');
    table.className = 'bills-table';

    // إنشاء رأس الجدول
    const tableHead = document.createElement('thead');
    tableHead.innerHTML = `
        <tr>
            <th>رقم الفاتورة</th>
            <th>التاريخ</th>
            <th>النوع</th>
            <th>العميل/المورد</th>
            <th>المبلغ</th>
            <th>البيان</th>
            <th>الإجراءات</th>
        </tr>
    `;
    table.appendChild(tableHead);

    // إنشاء جسم الجدول
    const tableBody = document.createElement('tbody');

    filteredBills.forEach(bill => {
        const row = document.createElement('tr');
        row.className = bill.type === 'مبيعات' ? 'sales-row' : 'purchase-row';

        row.innerHTML = `
            <td>${bill.number}</td>
            <td>${formatDate(bill.date)}</td>
            <td>${bill.type === 'مبيعات' ? '<span class="badge sales-badge">مبيعات</span>' : '<span class="badge purchase-badge">مشتريات</span>'}</td>
            <td>${bill.type === 'مبيعات' ? bill.customer : bill.supplier}</td>
            <td class="${bill.type === 'مبيعات' ? 'amount-positive' : 'amount-negative'}">${formatCurrency(bill.amount)}</td>
            <td>${bill.description}</td>
            <td class="actions-cell">
                <button class="action-icon preview-bill" data-id="${bill.id}" title="معاينة">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="action-icon edit-bill" data-id="${bill.id}" title="تعديل">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="action-icon delete-bill" data-id="${bill.id}" title="حذف">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(row);
    });

    table.appendChild(tableBody);
    billsContainer.appendChild(table);

    // إعداد مستمعات الأحداث للأزرار
    setupBillActionListeners();
}

// وظيفة معاينة الفاتورة
function previewBill(billId) {
    const bill = bills.find(b => b.id === billId);
    if (!bill) return;

    const previewContainer = document.getElementById('documentPreview');
    previewContainer.innerHTML = '';

    let documentHTML = '';

    if (bill.type === 'مبيعات') {
        documentHTML = `
            <div class="preview-document invoice-document sales-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="document-title">فاتورة مبيعات</div>
                </div>
                <div class="invoice-info">
                    <div class="document-row">
                        <div class="document-field">رقم الفاتورة:</div>
                        <div class="document-value">${bill.number}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(bill.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">العميل:</div>
                        <div class="document-value">${bill.customer}</div>
                    </div>
                </div>
                <div class="invoice-items-table">
                    <table>
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bill.items.map(item => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>${formatCurrency(item.price)}</td>
                                    <td>${formatCurrency(item.total)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="invoice-totals-preview">
                    <div class="total-row">
                        <span>إجمالي الفاتورة:</span>
                        <span>${formatCurrency(bill.subtotal)}</span>
                    </div>
                    <div class="total-row">
                        <span>الضريبة (15%):</span>
                        <span>${formatCurrency(bill.tax)}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>المجموع النهائي:</span>
                        <span>${formatCurrency(bill.amount)}</span>
                    </div>
                </div>
                <div class="invoice-notes">
                    <div class="document-field">ملاحظات:</div>
                    <div class="document-value">${bill.description || 'لا توجد ملاحظات'}</div>
                </div>
                <div class="document-footer">
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>العميل</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>البائع</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المدير</div>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (bill.type === 'مشتريات') {
        documentHTML = `
            <div class="preview-document invoice-document purchase-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                    <div class="document-title">فاتورة مشتريات</div>
                </div>
                <div class="invoice-info">
                    <div class="document-row">
                        <div class="document-field">رقم الفاتورة:</div>
                        <div class="document-value">${bill.number}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(bill.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المورد:</div>
                        <div class="document-value">${bill.supplier}</div>
                    </div>
                </div>
                <div class="invoice-items-table">
                    <table>
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${bill.items.map(item => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>${formatCurrency(item.price)}</td>
                                    <td>${formatCurrency(item.total)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                <div class="invoice-totals-preview">
                    <div class="total-row">
                        <span>إجمالي الفاتورة:</span>
                        <span>${formatCurrency(bill.subtotal)}</span>
                    </div>
                    <div class="total-row">
                        <span>الضريبة (15%):</span>
                        <span>${formatCurrency(bill.tax)}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>المجموع النهائي:</span>
                        <span>${formatCurrency(bill.amount)}</span>
                    </div>
                </div>
                <div class="invoice-notes">
                    <div class="document-field">ملاحظات:</div>
                    <div class="document-value">${bill.description || 'لا توجد ملاحظات'}</div>
                </div>
                <div class="document-footer">
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>المورد</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المشتري</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>المدير</div>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    previewContainer.innerHTML = documentHTML;
    document.getElementById('previewDocumentModal').style.display = 'block';
}

// إعداد أحداث معاينة المستندات
function setupPreviewActions() {
    // معاينة المستند
    // document.getElementById('editDocumentBtn').addEventListener('click', function() {
    //     document.getElementById('previewDocumentModal').style.display = 'none';
    //     // تنفيذ العملية بناء على نوع المستند الحالي
    //     if (currentTransaction) {
    //         editTransaction(currentTransaction);
    //     }
    // });

    // document.getElementById('deleteDocumentBtn').addEventListener('click', function() {
    //     if (confirm('هل أنت متأكد من حذف هذا المستند؟')) {
    //         document.getElementById('previewDocumentModal').style.display = 'none';
    //         // تنفيذ عملية الحذف بناء على نوع المستند الحالي
    //         if (currentTransaction) {
    //             deleteTransaction(currentTransaction);
    //         }
    //     }
    // });
    const previewModal = document.getElementById('previewDocumentModal');

    if (!previewModal) return;

    // لما تظهر المودال، وقتها نضيف الحدث
    previewModal.addEventListener('show', function () {
        const printBtn = document.getElementById('printDocumentBtn');
        if (printBtn) {
            printBtn.addEventListener('click', function () {
                printDocument();
            });
        }
    });

    // أو إذا كنت تفتح المودال بكود، مباشرة بعدها نربط الحدث:
    const printBtn = document.getElementById('printDocumentBtn');
    if (printBtn) {
        printBtn.addEventListener('click', function () {
            printDocument();
        });
    }

    document.getElementById('printDocumentBtn').addEventListener('click', function() {
        window.print();
    });
}



// تنسيق العملة
function formatCurrency(amount) {
    const num = parseFloat(amount);

    if (isNaN(num)) {
        return '—'; // أو تقدر ترجع '0.00 ر.س' مثلاً بدل الشرطة
    }

    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SAR',
        minimumFractionDigits: 2
    }).format(num);
}


// تنسيق التاريخ
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('ar-SA', options);
}

// فتح نافذة فاتورة المبيعات
function showSalesInvoiceForm() {
    // إخفاء قائمة الفواتير
    document.getElementById('billsList').style.display = 'none';
    document.getElementById('billsButtons').style.display = 'none';
    document.getElementById('billsSearchContainer').style.display = 'none';

    // إنشاء وعرض نموذج الفاتورة مباشرة في المحتوى
    const billsContainer = document.getElementById('billsContainer');

    // إنشاء نموذج الفاتورة
    const salesFormHTML = `
        <div class="bills-back-button">
            <button id="backToBillsBtn"><i class="fas fa-arrow-right"></i> العودة للفواتير</button>
        </div>
        <h2>فاتورة مبيعات</h2>
        <div class="invoice-form">
            <div class="invoice-header">
                <div class="invoice-logo">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="invoice-title">فاتورة مبيعات</div>
            </div>
            <form id="salesInvoiceForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="invoiceNumber">رقم الفاتورة:</label>
                        <input type="text" id="invoiceNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="invoiceDate">التاريخ:</label>
                        <input type="date" id="invoiceDate" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="customerName">اسم العميل:</label>
                    <input type="text" id="customerName" required>
                </div>
                <div class="form-group">
                    <label for="customerPhone">رقم الهاتف:</label>
                    <input type="text" id="customerPhone">
                </div>

                <div class="invoice-items">
                    <h3>بنود الفاتورة</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody">
                            <tr>
                                <td><input type="text" class="item-name" required></td>
                                <td><input type="number" class="item-quantity" min="1" value="1" required></td>
                                <td><input type="number" class="item-price" min="0" value="0" required></td>
                                <td class="item-total">0</td>
                                <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="addInvoiceItem" class="add-item-btn"><i class="fas fa-plus"></i> إضافة منتج</button>
                </div>

                <div class="invoice-totals">
                    <div class="total-row">
                        <span>إجمالي الفاتورة:</span>
                        <span id="invoiceSubtotal">0.00</span>
                    </div>
                    <div class="total-row">
                        <span>الضريبة (15%):</span>
                        <span id="invoiceTax">0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>المجموع النهائي:</span>
                        <span id="invoiceTotal">0.00</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="invoiceNotes">ملاحظات:</label>
                    <textarea id="invoiceNotes"></textarea>
                </div>

                <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ الفاتورة</button>
            </form>
        </div>
    `;

    // إضافة قسم للفاتورة إذا لم يكن موجودًا
    const salesFormContainer = document.createElement('div');
    salesFormContainer.id = 'salesFormContainer';
    salesFormContainer.innerHTML = salesFormHTML;

    billsContainer.appendChild(salesFormContainer);

    // تعيين التاريخ الحالي
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('invoiceDate').value = today;

    // توليد رقم فاتورة
    const salesInvoiceCount = bills.filter(b => b.type === 'مبيعات').length;
    document.getElementById('invoiceNumber').value = `INV-S-${String(salesInvoiceCount + 1).padStart(3, '0')}`;

    // إضافة مستمعي الأحداث
    setupInvoiceItemListeners();

    // إضافة حدث العودة للفواتير
    document.getElementById('backToBillsBtn').addEventListener('click', function() {
        document.getElementById('salesFormContainer').remove();
        document.getElementById('billsList').style.display = 'block';
        document.getElementById('billsButtons').style.display = 'flex';
        document.getElementById('billsSearchContainer').style.display = 'block';
    });

    // تعديل حدث إرسال النموذج
    document.getElementById('salesInvoiceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveSalesInvoice();
        document.getElementById('salesFormContainer').remove();
        document.getElementById('billsList').style.display = 'block';
        document.getElementById('billsButtons').style.display = 'flex';
        document.getElementById('billsSearchContainer').style.display = 'block';
        renderBills();
    });
}

// تغيير وظيفة فتح فاتورة المشتريات (عرض مباشر بدلاً من النافذة المنبثقة)
function showPurchaseInvoiceForm() {
    // إخفاء قائمة الفواتير
    document.getElementById('billsList').style.display = 'none';
    document.getElementById('billsButtons').style.display = 'none';
    document.getElementById('billsSearchContainer').style.display = 'none';

    // إنشاء وعرض نموذج الفاتورة مباشرة في المحتوى
    const billsContainer = document.getElementById('billsContainer');

    // إنشاء نموذج الفاتورة
    const purchaseFormHTML = `
        <div class="bills-back-button">
            <button id="backToBillsBtn"><i class="fas fa-arrow-right"></i> العودة للفواتير</button>
        </div>
        <h2>فاتورة مشتريات</h2>
        <div class="invoice-form">
            <div class="invoice-header">
                <div class="invoice-logo">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <div class="invoice-title">فاتورة مشتريات</div>
            </div>
            <form id="purchaseInvoiceForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="purchaseInvoiceNumber">رقم الفاتورة:</label>
                        <input type="text" id="purchaseInvoiceNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="purchaseInvoiceDate">التاريخ:</label>
                        <input type="date" id="purchaseInvoiceDate" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="supplierName">اسم المورد:</label>
                    <input type="text" id="supplierName" required>
                </div>
                <div class="form-group">
                    <label for="supplierPhone">رقم الهاتف:</label>
                    <input type="text" id="supplierPhone">
                </div>

                <div class="invoice-items">
                    <h3>بنود الفاتورة</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchaseInvoiceItemsBody">
                            <tr>
                                <td><input type="text" class="item-name" required></td>
                                <td><input type="number" class="item-quantity" min="1" value="1" required></td>
                                <td><input type="number" class="item-price" min="0" value="0" required></td>
                                <td class="item-total">0</td>
                                <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="addPurchaseInvoiceItem" class="add-item-btn"><i class="fas fa-plus"></i> إضافة منتج</button>
                </div>

                <div class="invoice-totals">
                    <div class="total-row">
                        <span>إجمالي الفاتورة:</span>
                        <span id="purchaseInvoiceSubtotal">0.00</span>
                    </div>
                    <div class="total-row">
                        <span>الضريبة (15%):</span>
                        <span id="purchaseInvoiceTax">0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>المجموع النهائي:</span>
                        <span id="purchaseInvoiceTotal">0.00</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="purchaseInvoiceNotes">ملاحظات:</label>
                    <textarea id="purchaseInvoiceNotes"></textarea>
                </div>

                <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ الفاتورة</button>
            </form>
        </div>
    `;

    // إضافة قسم للفاتورة إذا لم يكن موجودًا
    const purchaseFormContainer = document.createElement('div');
    purchaseFormContainer.id = 'purchaseFormContainer';
    purchaseFormContainer.innerHTML = purchaseFormHTML;

    billsContainer.appendChild(purchaseFormContainer);

    // تعيين التاريخ الحالي
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('purchaseInvoiceDate').value = today;

    // توليد رقم فاتورة
    const purchaseInvoiceCount = bills.filter(b => b.type === 'مشتريات').length;
    document.getElementById('purchaseInvoiceNumber').value = `INV-P-${String(purchaseInvoiceCount + 1).padStart(3, '0')}`;

    // إضافة مستمعي الأحداث
    setupPurchaseInvoiceItemListeners();

    // إضافة حدث العودة للفواتير
    document.getElementById('backToBillsBtn').addEventListener('click', function() {
        document.getElementById('purchaseFormContainer').remove();
        document.getElementById('billsList').style.display = 'block';
        document.getElementById('billsButtons').style.display = 'flex';
        document.getElementById('billsSearchContainer').style.display = 'block';
    });

    // تعديل حدث إرسال النموذج
    document.getElementById('purchaseInvoiceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        savePurchaseInvoice();
        document.getElementById('purchaseFormContainer').remove();
        document.getElementById('billsList').style.display = 'block';
        document.getElementById('billsButtons').style.display = 'flex';
        document.getElementById('billsSearchContainer').style.display = 'block';
        renderBills();
    });
}

// إعداد أحداث بنود الفاتورة
function setupInvoiceItemListeners() {
    // حساب الإجمالي عند تغيير الكمية أو السعر
    document.querySelectorAll('.item-quantity, .item-price').forEach(input => {
        input.addEventListener('input', updateInvoiceTotals);
    });

    // إزالة بند عند النقر على زر الحذف
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            if (document.querySelectorAll('#invoiceItemsBody tr').length > 1) {
                row.remove();
                updateInvoiceTotals();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
    });

    // زر إضافة بند جديد
    document.getElementById('addInvoiceItem').addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" class="item-name" required></td>
            <td><input type="number" class="item-quantity" min="1" value="1" required></td>
            <td><input type="number" class="item-price" min="0" value="0" required></td>
            <td class="item-total">0</td>
            <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
        `;
        document.getElementById('invoiceItemsBody').appendChild(newRow);

        // إضافة مستمعي الأحداث للبند الجديد
        newRow.querySelector('.item-quantity').addEventListener('input', updateInvoiceTotals);
        newRow.querySelector('.item-price').addEventListener('input', updateInvoiceTotals);
        newRow.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('#invoiceItemsBody tr').length > 1) {
                newRow.remove();
                updateInvoiceTotals();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
    });

    // إرسال نموذج الفاتورة
    document.getElementById('salesInvoiceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveSalesInvoice();
    });
}

// تحديث إجماليات الفاتورة
function updateInvoiceTotals() {
    let subtotal = 0;

    // حساب إجمالي كل بند وإضافته للإجمالي العام
    document.querySelectorAll('#invoiceItemsBody tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = quantity * price;

        row.querySelector('.item-total').textContent = total.toFixed(2);
        subtotal += total;
    });

    // حساب الضريبة والإجمالي النهائي
    const tax = subtotal * 0.15;
    const finalTotal = subtotal + tax;

    // عرض المجاميع
    document.getElementById('invoiceSubtotal').textContent = subtotal.toFixed(2);
    document.getElementById('invoiceTax').textContent = tax.toFixed(2);
    document.getElementById('invoiceTotal').textContent = finalTotal.toFixed(2);
}

// حفظ فاتورة المبيعات
function saveSalesInvoice() {
    const invoiceNumber = document.getElementById('invoiceNumber').value;
    const invoiceDate = document.getElementById('invoiceDate').value;
    const customerName = document.getElementById('customerName').value;
    const customerPhone = document.getElementById('customerPhone').value;
    const notes = document.getElementById('invoiceNotes').value;

    // تجميع بنود الفاتورة
    const items = [];
    let invoiceTotal = 0;

    document.querySelectorAll('#invoiceItemsBody tr').forEach(row => {
        const name = row.querySelector('.item-name').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value);
        const price = parseFloat(row.querySelector('.item-price').value);
        const total = quantity * price;

        items.push({
            name: name,
            quantity: quantity,
            price: price,
            total: total
        });

        invoiceTotal += total;
    });

    // إضافة الضريبة
    const tax = invoiceTotal * 0.15;
    const finalTotal = invoiceTotal + tax;

    // إنشاء كائن الفاتورة
    const newInvoice = {
        id: bills.length > 0 ? Math.max(...bills.map(b => b.id)) + 1 : 1,
        number: invoiceNumber,
        type: 'مبيعات',
        date: invoiceDate,
        customer: customerName,
        customerPhone: customerPhone,
        amount: finalTotal,
        subtotal: invoiceTotal,
        tax: tax,
        description: notes,
        items: items
    };

    // إضافة الفاتورة للقائمة
    bills.push(newInvoice);

    // إعادة عرض قائمة الفواتير إذا كنا في شاشة الفواتير
    if (currentView === 'bills') {
        renderBills();
    }

    // إظهار رسالة نجاح
    alert(`تم حفظ الفاتورة ${invoiceNumber} بنجاح`);
}

// إعداد أحداث بنود فاتورة المشتريات
function setupPurchaseInvoiceItemListeners() {
    // حساب الإجمالي عند تغيير الكمية أو السعر
    document.querySelectorAll('#purchaseInvoiceItemsBody .item-quantity, #purchaseInvoiceItemsBody .item-price').forEach(input => {
        input.addEventListener('input', updatePurchaseInvoiceTotals);
    });

    // إزالة بند عند النقر على زر الحذف
    document.querySelectorAll('#purchaseInvoiceItemsBody .remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            if (document.querySelectorAll('#purchaseInvoiceItemsBody tr').length > 1) {
                row.remove();
                updatePurchaseInvoiceTotals();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
    });

    // زر إضافة بند جديد
    document.getElementById('addPurchaseInvoiceItem').addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" class="item-name" required></td>
            <td><input type="number" class="item-quantity" min="1" value="1" required></td>
            <td><input type="number" class="item-price" min="0" value="0" required></td>
            <td class="item-total">0</td>
            <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
        `;
        document.getElementById('purchaseInvoiceItemsBody').appendChild(newRow);

        // إضافة مستمعي الأحداث للبند الجديد
        newRow.querySelector('.item-quantity').addEventListener('input', updatePurchaseInvoiceTotals);
        newRow.querySelector('.item-price').addEventListener('input', updatePurchaseInvoiceTotals);
        newRow.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('#purchaseInvoiceItemsBody tr').length > 1) {
                newRow.remove();
                updatePurchaseInvoiceTotals();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
    });

    // إرسال نموذج الفاتورة
    document.getElementById('purchaseInvoiceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        savePurchaseInvoice();
    });
}

// تحديث إجماليات فاتورة المشتريات
function updatePurchaseInvoiceTotals() {
    let subtotal = 0;

    // حساب إجمالي كل بند وإضافته للإجمالي العام
    document.querySelectorAll('#purchaseInvoiceItemsBody tr').forEach(row => {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const total = quantity * price;

        row.querySelector('.item-total').textContent = total.toFixed(2);
        subtotal += total;
    });

    // حساب الضريبة والإجمالي النهائي
    const tax = subtotal * 0.15;
    const finalTotal = subtotal + tax;

    // عرض المجاميع
    document.getElementById('purchaseInvoiceSubtotal').textContent = subtotal.toFixed(2);
    document.getElementById('purchaseInvoiceTax').textContent = tax.toFixed(2);
    document.getElementById('purchaseInvoiceTotal').textContent = finalTotal.toFixed(2);
}

// حفظ فاتورة المشتريات
function savePurchaseInvoice() {
    const invoiceNumber = document.getElementById('purchaseInvoiceNumber').value;
    const invoiceDate = document.getElementById('purchaseInvoiceDate').value;
    const supplierName = document.getElementById('supplierName').value;
    const supplierPhone = document.getElementById('supplierPhone').value;
    const notes = document.getElementById('purchaseInvoiceNotes').value;

    // تجميع بنود الفاتورة
    const items = [];
    let invoiceTotal = 0;

    document.querySelectorAll('#purchaseInvoiceItemsBody tr').forEach(row => {
        const name = row.querySelector('.item-name').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value);
        const price = parseFloat(row.querySelector('.item-price').value);
        const total = quantity * price;

        items.push({
            name: name,
            quantity: quantity,
            price: price,
            total: total
        });

        invoiceTotal += total;
    });

    // إضافة الضريبة
    const tax = invoiceTotal * 0.15;
    const finalTotal = invoiceTotal + tax;

    // إنشاء كائن الفاتورة
    const newInvoice = {
        id: bills.length > 0 ? Math.max(...bills.map(b => b.id)) + 1 : 1,
        number: invoiceNumber,
        type: 'مشتريات',
        date: invoiceDate,
        supplier: supplierName,
        supplierPhone: supplierPhone,
        amount: finalTotal,
        subtotal: invoiceTotal,
        tax: tax,
        description: notes,
        items: items
    };

    // إضافة الفاتورة للقائمة
    bills.push(newInvoice);

    // إعادة عرض قائمة الفواتير إذا كنا في شاشة الفواتير
    if (currentView === 'bills') {
        renderBills();
    }

    // إظهار رسالة نجاح
    alert(`تم حفظ الفاتورة ${invoiceNumber} بنجاح`);
}

// تعديل وظيفة حفظ فاتورة المبيعات
function saveSalesInvoice() {
    // ... existing code ...

    // إغلاق النافذة المنبثقة - تم إلغاء هذا السطر
    // document.getElementById('salesInvoiceModal').style.display = 'none';

    // ... existing code ...
}

// تعديل وظيفة حفظ فاتورة المشتريات
function savePurchaseInvoice() {
    // ... existing code ...

    // إغلاق النافذة المنبثقة - تم إلغاء هذا السطر
    // document.getElementById('purchaseInvoiceModal').style.display = 'none';

    // ... existing code ...
}
// فتح نافذة كشف الحساب
function openAccountStatement(accountId) {
    currentAccountId = accountId;
    const account = accounts.find(acc => acc.id === accountId);

    if (account) {
        document.getElementById('accountTitle').textContent = `كشف حساب - ${account.bankName}`;

        const tableBody = document.getElementById('statementTableBody');
        tableBody.innerHTML = '';

        const sortedTransactions = [...account.transactions].sort((a, b) =>
            new Date(b.date) - new Date(a.date)
        );

        sortedTransactions.forEach((transaction, index) => {
            const row = document.createElement('tr');
            transaction.index = index;

            const dateObj = new Date(transaction.date);
            const formattedDate = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;

            const amountClass = transaction.amount >= 0 ? 'amount-positive' : 'amount-negative';

            row.innerHTML = `
                <td>${formattedDate}</td>
                <td>${transaction.type}</td>
                <td class="${amountClass}">${formatCurrency(transaction.amount)}</td>
                <td>${transaction.description}</td>
                <td>
                    <button class="preview-btn" data-index="${index}">
                        <i class="fas fa-eye"></i> معاينة
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        document.getElementById('accountStatementModal').style.display = 'block';
    }
}

function openTransactionModal(type, accountId) {
    currentAccountId = accountId;
    transactionType = type;

    document.getElementById('transactionType').value = type;

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('transactionDate').value = today;

    // console.log(accounts)
    const account = accounts.find(acc => acc.id === accountId);
    // console.log(account)
    // console.log(account.transactions)
    // const transactionCount = account.transactions.filter(t => t.type === type).length ?? null;

    let typePrefix = '';
    let title = '';
    let iconClass = '';
    let labelName = '';
    let nameInputId = '';

    switch (type) {
        case 'إيداع':
            typePrefix = 'DEP';
            title = 'سند إيداع بنكي';
            iconClass = 'fas fa-money-bill-wave';
            labelName = 'اسم المودع:';
            nameInputId = 'beneficiaryName';
            break;
        case 'تحويل':
            typePrefix = 'TRN';
            title = 'سند تحويل بنكي';
            iconClass = 'fas fa-exchange-alt';
            labelName = 'اسم المستفيد:';
            nameInputId = 'beneficiaryName';
            break;
        case 'عهدة':
            typePrefix = 'CST';
            title = 'سند عهدة';
            iconClass = 'fas fa-user-shield';
            labelName = 'اسم المستلم:';
            nameInputId = 'beneficiaryName';
            break;
        default:
            typePrefix = 'GEN';
            title = 'سند عام';
            iconClass = 'fas fa-receipt';
            labelName = 'الاسم:';
            nameInputId = 'beneficiaryName';
    }

    // تحديث واجهة النافذة
    document.getElementById('transactionTitle').textContent = `إضافة ${title}`;
    document.getElementById('voucherTypeTitle').textContent = title;
    document.getElementById('transactionVoucherNumber').value = `${typePrefix}-${accountId}-${3 + 1}`;

    const iconElement = document.getElementById('transactionIcon');
    iconElement.className = iconClass;

    document.getElementById('beneficiaryName').value = '';
    document.getElementById('transactionAmount').value = '';
    document.getElementById('transactionDescription').value = '';
    document.querySelector("label[for='beneficiaryName']").textContent = labelName;

    document.getElementById('addTransactionModal').style.display = 'block';
}


// فتح نافذة إضافة سند إيداع
function openDepositModal(accountId) {
    currentAccountId = accountId;

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('depositDate').value = today;

    const account = accounts.find(acc => acc.id === accountId);
    const depositCount = account.transactions.filter(t => t.type === 'إيداع').length;
    document.getElementById('receiptNumber').value = `DEP-${accountId}-${depositCount + 1}`;

    document.getElementById('depositorName').value = '';
    document.getElementById('depositAmount').value = '';
    document.getElementById('depositDescription').value = '';

    document.getElementById('accountStatementModal').style.display = 'block';
}
