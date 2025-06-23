

let bills = [];
function fetchBills() {
    fetch('/bills/index')
        .then(res => res.json())
        .then(async data => {
            // تأكد أن البيانات مصفوفة
            bills = Array.isArray(data) ? data : [];

            // جلب المعاملات لكل حساب
            // for (let i = 0; i < bills.length; i++) {
            //     const res = await fetch(`/bills/${bills[i].id}/items`);
            //     const fullData = await res.json();//accounts/{id}/transactions
            //     funds[i].transactions = fullData.transactions || []; // ضمان وجود array
            // }
                console.log(bills)
            renderBills(bills)
            // renderFundsStatistics(bills)
            // renderAccounts(funds); // عرض البيانات في الواجهة
            // renderStatistics(funds); // عرض البيانات في الواجهة
        })
        .catch(error => {
            console.error("حدث خطأ أثناء جلب الحسابات:", error);
        });
}

// php artisan make:model Transaction_Acount -m

fetchBills();

// function fetchBills() {
//     fetch('/bills/index')
//         .then(res => res.json())
//         .then(data => {
//             // البيانات الآن تحتوي على الفواتير + البنود
//             bills = Array.isArray(data) ? data : [];

//             // حساب subtotal والضريبة لكل فاتورة (لحسابات العرض)
//             bills.forEach(bill => {
//                 const subtotal = bill.items.reduce((sum, item) => sum + item.total, 0);
//                 bill.subtotal = subtotal;
//                 bill.tax = subtotal * 0.15;
//             });

//             renderBills(bills);
//         })
//         .catch(error => {
//             console.error("حدث خطأ أثناء جلب الفواتير:", error);
//         });
// }


// المتغيرات العامة
let currentAccountId = null;
let transactionType = null;
let currentTransaction = null;
let currentTransactionIndex = null;
let currentView = 'banks';

// تهيئة التطبيق
document.addEventListener('DOMContentLoaded', function() {

    setupEventListeners();
    setupPreviewActions();

});



// التبديل بين شاشة البنوك وشاشة الصناديق وشاشة السندات وشاشة الفواتير
function switchView(view) {
    currentView = view;


  if (view === 'bills') {
        document.getElementById('banksContainer').style.display = 'none';
        document.getElementById('fundsContainer').style.display = 'none';
        document.getElementById('vouchersContainer').style.display = 'none';
        document.getElementById('billsContainer').style.display = 'block';
        document.getElementById('bankViewBtn').classList.remove('active');
        document.getElementById('fundsViewBtn').classList.remove('active');
        document.getElementById('vouchersViewBtn').classList.remove('active');
        document.getElementById('billsViewBtn').classList.add('active');
        renderBills();
    }

    // إغلاق الشريط الجانبي في الشاشات الصغيرة
    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.remove('active');
    }
}

// تحديث الإحصائيات بعد كل عملية تغيير في البيانات
function updateStatistics() {
    if (currentView === 'banks') {
        renderStatistics();
    } else if (currentView === 'funds') {
        renderFundsStatistics();
    }
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


// عرض الفواتير
function renderBills() {
    const billsContainer = document.getElementById('billsList');
    billsContainer.innerHTML = '';

    const table = document.createElement('table');
    table.className = 'bills-table vouchers-table';

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
                <td>${bill.type === 'مبيعات' ? bill.customer : bill.customer}</td>
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
        btn.addEventListener('click', function () {
            const billId = parseInt(this.dataset.id);
            const bill = bills.find(b => b.id === billId);
            if (!bill) return;

            if (bill.type === 'مبيعات') {
                showEditSalesInvoiceForm(bill);
            } else {
                showEditPurchaseInvoiceForm(bill);
            }
        });
    });


    document.querySelectorAll('.delete-bill').forEach(btn => {
        btn.addEventListener('click', function() {
            const billId = this.dataset.id;
            if (confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) {
                fetch(`bills/delete/${billId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // حذف العنصر من الواجهة
                        // bills = bills.filter(v => v.id !== billId);
                        fetchBills();
                        showToast(data.message);
                    } else {
                        showToast('حدث خطأ أثناء الحذف','#d00000');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('فشل في الاتصال بالخادم','#d00000');
                });
            }
        });
    });
}

function showEditSalesInvoiceForm(bill) {
    showeditSalesInvoiceForm(); // استخدم النموذج نفسه

    setTimeout(() => {
        document.getElementById('invoiceNumber').value = bill.number;
        document.getElementById('invoiceDate').value = bill.date;
        document.getElementById('customerName').value = bill.customer;
        document.getElementById('invoiceNotes').value = bill.description;
        document.getElementById('deliveryType').value = bill.deliveryType;
        document.getElementById('paymentMethod').value = bill.paymentMethod;

        // تعبئة البنود
        const tbody = document.getElementById('editinvoiceItemsBody');
        tbody.innerHTML = ''; // مسح البنود القديمة

        bill.items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="item-name" value="${item.name}" required></td>
                <td><input type="number" class="item-quantity" value="${item.quantity}" required></td>
                <td><input type="number" class="item-price" value="${item.price}" required></td>
                <td><input type="text" class="item-tax_rate" value="${item.tax_rate}" required></td>
                <td><input type="text" class="item-code" readonly value="${item.code}" required></td>
                <td><input type="text" class="product_id" readonly  value="${item.product_id}" required></td>
                <td class="item-total">${(item.price * item.quantity).toFixed(2)}</td>
                <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
            `;
            tbody.appendChild(row);
        });

        updateInvoiceTotals2();

        // تعديل زر الإرسال
        const form = document.getElementById('saleseditInvoiceForm');
        form.onsubmit = null;
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            updateSalesInvoice(bill.id);
            document.getElementById('saleseditFormContainer').remove();
            document.getElementById('billsList').style.display = 'block';
            document.getElementById('billsButtons').style.display = 'flex';
            document.getElementById('billsSearchContainer').style.display = 'block';
            renderBills();
        });
    }, 0);
}
function showeditSalesInvoiceForm() {
    if (document.getElementById('saleseditFormContainer')) return; // ⛔️ لا تنشئ مرتين
    fetch('/delivery-types')
    .then(response => response.json())
    .then(data => {
        const deliverySelect = document.getElementById('deliveryType');
        data.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id; // حسب شكل بياناتك
        option.textContent = type.name; // أو type.name
        deliverySelect.appendChild(option);
        });
    });

    // جلب طرق الدفع
    fetch('/payment-methods')
    .then(response => response.json())
    .then(data => {
        const paymentSelect = document.getElementById('paymentMethod');
        data.forEach(method => {
        const option = document.createElement('option');
        option.value = method.id;
        option.textContent = method.name;
        paymentSelect.appendChild(option);
        });
    });
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
            <form id="saleseditInvoiceForm">
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
                                <th>الضريبة</th>
                                <th>الكود</th>
                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="editinvoiceItemsBody">
                            <tr>
                                <td><input type="text" class="item-name" required></td>
                                <td><input type="number" class="item-quantity" min="1" value="1" required></td>
                                <td><input type="number" class="item-price" min="0" value="0" required></td>
                                <td><input type="text" class="item-tax_rate" required></td>
                                <td><input type="text" class="item-code" readonly required></td>
                                <td><input type="text" class="product_id"  readonly required></td>
                                <td class="item-total">0</td>
                                <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="addeditInvoiceItem" class="add-item-btn"><i class="fas fa-plus"></i> إضافة منتج</button>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="deliveryType">نوع التوصيل:</label>
                        <select id="deliveryType" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">طريقة الدفع:</label>
                        <select id="paymentMethod" required>
                        </select>
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
    salesFormContainer.id = 'saleseditFormContainer';
    salesFormContainer.innerHTML = salesFormHTML;
    // saleseditInvoiceForm
    billsContainer.appendChild(salesFormContainer);

    setTimeout(() => {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('invoiceDate').value = today;

        const salesInvoiceCount = bills.filter(b => b.type === 'مبيعات').length;
        document.getElementById('invoiceNumber').value = `INV-S-${String(salesInvoiceCount + 1).padStart(3, '0')}`;
    }, 0);
    // إضافة مستمعي الأحداث
    setupInvoiceItemListeners2();

    // إضافة حدث العودة للفواتير
    document.getElementById('backToBillsBtn').addEventListener('click', function() {
        document.getElementById('saleseditFormContainer').remove();
        document.getElementById('billsList').style.display = 'block';
        document.getElementById('billsButtons').style.display = 'flex';
        document.getElementById('billsSearchContainer').style.display = 'block';
    });



}

function updateSalesInvoice(billId) {
    const invoiceNumber = document.getElementById('invoiceNumber').value;
    const invoiceDate = document.getElementById('invoiceDate').value;
    const customerName = document.getElementById('customerName').value;
    const notes = document.getElementById('invoiceNotes').value;
    const deliveryType = document.getElementById('deliveryType').value;
    const paymentMethod = document.getElementById('paymentMethod').value;

    const items = [];
    let invoiceTotal = 0;

    document.querySelectorAll('#editinvoiceItemsBody tr').forEach(row => {
        const name = row.querySelector('.item-name').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value);
        const price = parseFloat(row.querySelector('.item-price').value);
        const code = row.querySelector('.item-code').value;
        const tax_rate = row.querySelector('.item-tax_rate').value;
        const product_id = row.querySelector('.product_id').value;
        const total = quantity * price;
//             <td><input type="text" class="item-code"  required></td>
{/* <td><input type="text" class="item-tax_rate"  required></td> */}

        items.push({ name, quantity, price, total ,tax_rate,code,product_id});
        invoiceTotal += total;
    });

    const tax = invoiceTotal * 0.15;
    const finalTotal = invoiceTotal + tax;

    fetch(`/bills/${billId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            number: invoiceNumber,
            type: 'مبيعات',
            date: invoiceDate,
            customer: customerName,
            amount: finalTotal,
            delivery_type_id: deliveryType,
            payment_method_id: paymentMethod,
            description: notes,
            items: items
        })
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message);
        // document.getElementById('saleseditFormContainer')?.remove();
        fetchBills();
    })
    .catch(err => {
        console.log(err);
        showToast('حدث خطأ أثناء تعديل الفاتورة','#d00000');
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
            (bill.type === 'مبيعات' ? bill.customer.toLowerCase().includes(searchText) : bill.customer.toLowerCase().includes(searchText)) ||
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
    table.className = 'bills-table vouchers-table';

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
            <td>${bill.type === 'مبيعات' ? bill.customer : bill.customer}</td>
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
                    <div class="document-row">
                        <div class="document-field">نوع التوصيل:</div>
                        <div class="document-value">${bill.delivery_type.name || 'محلي'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">طريقة الدفع:</div>
                        <div class="document-value">${bill.payment_method.name || 'نقدي'}</div>
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
                        <span>${formatCurrency(bill.items.total)}</span>
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

        document.getElementById('print-area').innerHTML = documentHTML;

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
                        <div class="document-value">${bill.customer}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">نوع التوصيل:</div>
                        <div class="document-value">${bill.delivery_type.name || 'محلي'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">طريقة الدفع:</div>
                        <div class="document-value">${bill.payment_method.name || 'نقدي'}</div>
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
                        <span>${formatCurrency(bill.items.total)}</span>
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
        document.getElementById('print-area').innerHTML = documentHTML;

    }
    previewContainer.innerHTML = documentHTML;
    document.getElementById('previewDocumentModal').style.display = 'block';
}

// إعداد أحداث معاينة المستندات
function setupPreviewActions() {
    document.getElementById('printDocumentBtn').addEventListener('click', function () {
        const printContent = document.getElementById('print-area').innerHTML;

        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>طباعة الفاتورة</title>
                    <link rel="stylesheet" href="/css/your-print-style.css"> <!-- تأكد من إضافة مسار CSS الخاص بالتصميم -->
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            direction: rtl;
                            padding: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        th, td {
                            border: 1px solid #000;
                            padding: 8px;
                            text-align: center;
                        }
                        .document-header, .invoice-totals-preview, .invoice-notes, .document-footer {
                            margin-top: 20px;
                        }
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
    });
}



// إعداد مستمعات الأحداث
function setupEventListeners() {
    // أزرار الفواتير
    document.getElementById('salesInvoiceBtn').addEventListener('click', function() {
        showSalesInvoiceForm();
    });

    document.getElementById('purchaseInvoiceBtn').addEventListener('click', function() {
        showPurchaseInvoiceForm();
    });

    // بحث وفلترة الفواتير
    document.getElementById('billSearchBtn').addEventListener('click', function() {
        searchBills();
    });

    document.getElementById('billSearchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchBills();
        }
    });

    document.getElementById('billTypeFilter').addEventListener('change', function() {
        searchBills();
    });

    document.getElementById('billDateFilter').addEventListener('change', function() {
        searchBills();
    });

    document.getElementById('resetBillFiltersBtn').addEventListener('click', function() {
        document.getElementById('billSearchInput').value = '';
        document.getElementById('billTypeFilter').value = 'all';
        document.getElementById('billDateFilter').value = '';
        renderBills();
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



// 💡
// إعداد أحداث بنود الفاتورة
function setupInvoiceItemListeners() {
    // حساب الإجمالي عند تغيير الكمية أو السعر
    document.querySelectorAll('.item-quantity, .item-price').forEach(input => {
        input.addEventListener('input', updateInvoiceTotals);
    });
    fetch('/delivery-types')
    .then(response => response.json())
    .then(data => {
        const deliverySelect = document.getElementById('deliveryType');
        data.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id; // حسب شكل بياناتك
        option.textContent = type.name; // أو type.name
        deliverySelect.appendChild(option);
        });
    });

    // جلب طرق الدفع
    fetch('/payment-methods')
    .then(response => response.json())
    .then(data => {
        const paymentSelect = document.getElementById('paymentMethod');
        data.forEach(method => {
        const option = document.createElement('option');
        option.value = method.id;
        option.textContent = method.name;
        paymentSelect.appendChild(option);
        });
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
               <td> <input type="text" class="item-name-sale item-name" required autocomplete="off">
                                <ul class="autocomplete-list"></ul></td>
            <td><input type="number" class="item-quantity" min="1" value="1" required></td>
            <td><input type="number" class="item-price" min="0" value="0" required></td>
            <td><input type="text" class="item-tax_rate"  required></td>
            <td><input type="text" class="item-code" readonly required></td>
            <td><input type="text" class="product_id"  readonly required></td>

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

        // ✅ تفعيل الإكمال التلقائي للبند الجديد
        const itemNameInput = newRow.querySelector('.item-name');
        setupAutocompletesale(itemNameInput);
    });


    document.querySelectorAll('.item-name-sale').forEach(input => {
        input.addEventListener('input', function () {
            const query = this.value;
            const list = this.nextElementSibling; // autocomplete-list

            if (query.length < 2) {
                list.innerHTML = '';
                return;
            }

            fetch(`/products/search?q=${query}`)
                .then(res => res.json())
                .then(products => {
                    list.innerHTML = '';
                    products.forEach(product => {
                        const item = document.createElement('li');
                        item.textContent = product.name;
                        item.dataset.price = product.selling_price;
                        item.dataset.stock = product.stock;
                        item.dataset.tax_rate = product.tax_rate;
                        item.dataset.code = product.code;
                        item.dataset.product_id = product.id;
                        //         const code = parseFloat(row.querySelector('.item-code').value);
        // const tax_rate = parseFloat(row.querySelector('.item-tax_rate').value);

                        item.style.cursor = 'pointer';
                        item.addEventListener('click', () => {
                            this.value = product.name;

                            // ملء السعر تلقائيًا من سعر البيع
                            const row = this.closest('tr');
                            row.querySelector('.item-price').value = product.selling_price;
                            row.querySelector('.item-quantity').value = product.stock;
                            row.querySelector('.item-code').value = product.code;
                            row.querySelector('.item-tax_rate').value = product.tax_rate;
                            row.querySelector('.product_id').value = product.id;

                            updateInvoiceTotals(row); // تحديث الإجمالي تلقائيًا

                            list.innerHTML = '';
                        });
                        list.appendChild(item);
                    });
                });
        });
    });

}
function setupInvoiceItemListeners2() {
   // زر إضافة بند جديد

       // حساب الإجمالي عند تغيير الكمية أو السعر
    document.querySelectorAll('.item-quantity, .item-price').forEach(input => {
        input.addEventListener('input', updateInvoiceTotals2);
    });

    // إزالة بند عند النقر على زر الحذف
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            if (document.querySelectorAll('#editinvoiceItemsBody tr').length > 1) {
                row.remove();
                updateInvoiceTotals2();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
    });
    document.getElementById('addeditInvoiceItem').addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
      <td><input type="text" class="item-name item-name-sale" required autocomplete="off">
                <ul class="autocomplete-list"></ul></td>
            <td><input type="number" class="item-quantity" min="1" value="1" required></td>
            <td><input type="number" class="item-price" min="0" value="0" required></td>
            <td><input type="text" class="item-tax_rate"  required></td>
            <td><input type="text" class="item-code" readonly required></td>
            <td><input type="text" class="product_id"  readonly required></td>
            <td class="item-total">0</td>
            <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
        `;
        document.getElementById('editinvoiceItemsBody').appendChild(newRow);

        // إضافة مستمعي الأحداث للبند الجديد
        newRow.querySelector('.item-quantity').addEventListener('input', updateInvoiceTotals2);
        newRow.querySelector('.item-price').addEventListener('input', updateInvoiceTotals2);
        newRow.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('#editinvoiceItemsBody tr').length > 1) {
                newRow.remove();
                updateInvoiceTotals2();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });

        // ✅ تفعيل الإكمال التلقائي للبند الجديد
        const itemNameInput = newRow.querySelector('.item-name-sale');
        setupsaleAutocompletesale(itemNameInput);
    });

    document.querySelectorAll('.item-name-sale').forEach(input => {
        input.addEventListener('input', function () {
            const query = this.value;
            const list = this.nextElementSibling; // autocomplete-list

            if (query.length < 2) {
                list.innerHTML = '';
                return;
            }

            fetch(`/products/search?q=${query}`)
                .then(res => res.json())
                .then(products => {
                    list.innerHTML = '';
                    products.forEach(product => {
                        const item = document.createElement('li');
                        item.textContent = product.name;
                        item.dataset.price = product.selling_price;
                        item.dataset.stock = product.stock;
                        item.dataset.tax_rate = product.tax_rate;
                        item.dataset.code = product.code;
                        item.dataset.product_id = product.id;
                        item.style.cursor = 'pointer';
                        item.addEventListener('click', () => {
                            this.value = product.name;

                            // ملء السعر تلقائيًا من سعر البيع
                            const row = this.closest('tr');
                            row.querySelector('.item-price').value = product.selling_price;
                            row.querySelector('.item-quantity').value = product.stock;
                            row.querySelector('.item-tax_rate').value = product.tax_rate;
                            row.querySelector('.item-code').value = product.code;
                            row.querySelector('.product_id').value = product.id;

                            updateInvoiceTotals2(row); // تحديث الإجمالي تلقائيًا

                            list.innerHTML = '';
                        });
                        list.appendChild(item);
                    });
                });
        });
    });


}

// تحديث إجماليات الفاتورة
function updateInvoiceTotals2() {
    let subtotal = 0;

    // حساب إجمالي كل بند وإضافته للإجمالي العام
    document.querySelectorAll('#editinvoiceItemsBody tr').forEach(row => {
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



// تغيير وظيفة فتح فاتورة المشتريات (عرض مباشر بدلاً من النافذة المنبثقة)
function showPurchaseInvoiceForm() {
    if (document.getElementById('purchaseFormContainer')) return; // ⛔️ لا تنشئ مرتين

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
                                <th>الضريبة</th>
                                <th>الكود</th>
                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchaseInvoiceItemsBody">
                            <tr>

                            <td> <input type="text" class="item-name" required autocomplete="off">
                                <ul class="autocomplete-list"></ul>
                                </td>
                                <td><input type="number" class="item-quantity" min="1" value="1" required></td>
                                <td><input type="number" class="item-price" min="0" value="0" required></td>
                                <td><input type="text" class="item-tax_rate" required></td>
                                <td><input type="text" class="item-code" readonly required></td>
                                <td><input type="text" class="product_id"  readonly required></td>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="deliveryType">نوع التوصيل:</label>
                        <select id="deliveryType" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">طريقة الدفع:</label>
                        <select id="paymentMethod" required>
                        </select>
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

    setTimeout(() => {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('purchaseInvoiceDate').value = today;

        const purchaseInvoiceCount = bills.filter(b => b.type === 'مشتريات').length;
        document.getElementById('purchaseInvoiceNumber').value = `INV-S-${String(purchaseInvoiceCount + 1).padStart(3, '0')}`;
    }, 0);
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
// فتح نافذة فاتورة المبيعات
function showSalesInvoiceForm() {
    if (document.getElementById('salesFormContainer')) return; // ⛔️ لا تنشئ مرتين

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
                                <th>الضريبة</th>
                                <th>الكود</th>

                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody">
                            <tr>
                               <td><input type="text" class="item-name-sale item-name" required autocomplete="off">
                                <ul class="autocomplete-list"></ul></td>
                                <td><input type="number" class="item-quantity" min="1" value="1" required></td>
                                <td><input type="number" class="item-price" min="0" value="0" required></td>
                                <td><input type="text" class="item-tax_rate"  required></td>
                                <td><input type="text" class="item-code" readonly required></td>
                                <td><input type="text" class="product_id"  readonly required></td>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="deliveryType">نوع التوصيل:</label>
                        <select id="deliveryType" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">طريقة الدفع:</label>
                        <select id="paymentMethod" required>
                        </select>
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

    setTimeout(() => {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('invoiceDate').value = today;

        const salesInvoiceCount = bills.filter(b => b.type === 'مبيعات').length;
        document.getElementById('invoiceNumber').value = `INV-S-${String(salesInvoiceCount + 1).padStart(3, '0')}`;
    }, 0);
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





function saveSalesInvoice() {
    const invoiceNumber = document.getElementById('invoiceNumber').value;
    const invoiceDate = document.getElementById('invoiceDate').value;
    const customerName = document.getElementById('customerName').value;
    const notes = document.getElementById('invoiceNotes').value;
    const deliveryType = document.getElementById('deliveryType').value;
    const paymentMethod = document.getElementById('paymentMethod').value;

    const items = [];
    let invoiceTotal = 0;

    document.querySelectorAll('#invoiceItemsBody tr').forEach(row => {
        const name = row.querySelector('.item-name').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value);
        const price = parseFloat(row.querySelector('.item-price').value);
        const tax_rate = row.querySelector('.item-tax_rate').value;
        const code = row.querySelector('.item-code').value;
        const product_id = row.querySelector('.product_id').value;
        const total = quantity * price;

        items.push({ name, quantity, price, total,tax_rate,code,product_id });
        invoiceTotal += total;
    });

    const tax = invoiceTotal * 0.15;
    const finalTotal = invoiceTotal + tax;

    // 🟢 إرسال البيانات إلى السيرفر
    fetch('/bills/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            number: invoiceNumber,
            type: 'مبيعات',
            date: invoiceDate,
            customer: customerName,
            amount: finalTotal,
            delivery_type_id: deliveryType,
            payment_method_id: paymentMethod,
            description: notes,
            items: items
        })
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message,);
        fetchBills(); // تحديث الواجهة بعد الحفظ
    })
    .catch(err => {
        console.log(err);
        showToast('حدث خطأ أثناء حفظ الفاتورة','#d00000');
    });
}



// إعداد أحداث بنود فاتورة المشتريات
function setupPurchaseInvoiceItemListeners() {
    // حساب الإجمالي عند تغيير الكمية أو السعر
    document.querySelectorAll('#purchaseInvoiceItemsBody .item-quantity, #purchaseInvoiceItemsBody .item-price').forEach(input => {
        input.addEventListener('input', updatePurchaseInvoiceTotals);
    });
    // جلب أنواع التوصيل
    fetch('/delivery-types')
    .then(response => response.json())
    .then(data => {
        const deliverySelect = document.getElementById('deliveryType');
        data.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id; // حسب شكل بياناتك
        option.textContent = type.name; // أو type.name
        deliverySelect.appendChild(option);
        });
    });

    // جلب طرق الدفع
    fetch('/payment-methods')
    .then(response => response.json())
    .then(data => {
        const paymentSelect = document.getElementById('paymentMethod');
        data.forEach(method => {
        const option = document.createElement('option');
        option.value = method.id;
        option.textContent = method.name;
        paymentSelect.appendChild(option);
        });
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
            <td><input type="text" class="item-name" required autocomplete="off">
                <ul class="autocomplete-list"></ul></td>
            <td><input type="number" class="item-quantity" min="1" value="1" required></td>
            <td><input type="number" class="item-price" min="0" value="0" required></td>
            <td><input type="text" class="item-tax_rate" min="0" value="0" required></td>
            <td><input type="text" class="item-code" readonly min="0" value="0" required></td>
            <td><input type="text" class="item-code" readonly min="0" value="0" required></td>
            <td><input type="text" class="product_id" readonly   required></td>
            <td class="item-total">0</td>
            <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
        `;
        document.getElementById('purchaseInvoiceItemsBody').appendChild(newRow);

        // تفعيل الأحداث
        newRow.querySelector('.item-quantity').addEventListener('input', updatePurchaseInvoiceTotals);
        newRow.querySelector('.item-price').addEventListener('input', updatePurchaseInvoiceTotals);
        newRow.querySelector('.remove-item').addEventListener('click', function () {
            if (document.querySelectorAll('#purchaseInvoiceItemsBody tr').length > 1) {
                newRow.remove();
                updatePurchaseInvoiceTotals();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });

        // ✅ تفعيل الإكمال التلقائي للبند الجديد
        const itemNameInput = newRow.querySelector('.item-name');
        setupAutocomplete(itemNameInput);
    });


    document.querySelectorAll('.item-name').forEach(input => {
        input.addEventListener('input', function () {
            const query = this.value;
            const list = this.nextElementSibling; // autocomplete-list

            if (query.length < 2) {
                list.innerHTML = '';
                return;
            }

            fetch(`/products/search?q=${query}`)
                .then(res => res.json())
                .then(products => {
                    list.innerHTML = '';
                    products.forEach(product => {
                        const item = document.createElement('li');
                        item.textContent = product.name;
                        item.dataset.price = product.purchase_price;
                        item.dataset.stock = product.stock;
                        item.dataset.tax_rate = product.tax_rate;
                        item.dataset.code = product.code;
                        item.dataset.product_id = product.id;
                        item.style.cursor = 'pointer';
                        item.addEventListener('click', () => {
                            this.value = product.name;

                            // ملء السعر تلقائيًا
                            const row = this.closest('tr');
                            row.querySelector('.item-price').value = product.purchase_price;
                            row.querySelector('.item-quantity').value = product.stock;
                            row.querySelector('.item-tax_rate').value = product.tax_rate;
                            row.querySelector('.item-code').value = product.code;
                            row.querySelector('.product_id').value = product.id;
                            updatePurchaseInvoiceTotals(row); // لتحديث الإجمالي مباشرةً

                            list.innerHTML = '';
                        });
                        list.appendChild(item);
                    });
                });
        });
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

function savePurchaseInvoice() {
    const invoiceNumber = document.getElementById('purchaseInvoiceNumber').value;
    const invoiceDate = document.getElementById('purchaseInvoiceDate').value;
    const supplierName = document.getElementById('supplierName').value;
    const notes = document.getElementById('purchaseInvoiceNotes').value;
    const deliveryType = document.getElementById('deliveryType').value;
    const paymentMethod = document.getElementById('paymentMethod').value;

    const items = [];
    let invoiceTotal = 0;

    document.querySelectorAll('#purchaseInvoiceItemsBody tr').forEach(row => {
        const name = row.querySelector('.item-name').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value);
        const price = parseFloat(row.querySelector('.item-price').value);
        const tax_rate = row.querySelector('.item-tax_rate').value;
        const code = row.querySelector('.item-code').value;
        const product_id = row.querySelector('.product_id').value;
        const total = quantity * price;

        items.push({ name, quantity, price, total,tax_rate,code,product_id });
        invoiceTotal += total;
    });

    const tax = invoiceTotal * 0.15;
    const finalTotal = invoiceTotal + tax;

    // 🟢 إرسال البيانات إلى السيرفر
    fetch('/bills/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            number: invoiceNumber,
            type: 'مشتريات',
            date: invoiceDate,
            customer: supplierName,
            amount: finalTotal,
            delivery_type_id: deliveryType,
            payment_method_id: paymentMethod,
            description: notes,
            items: items
        })
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        showToast(data.message);
        fetchBills(); // إعادة تحميل الفواتير
    })
    .catch(async err => {
        const errorText = await err.response?.text?.();
        console.log('Raw error response:', errorText);
        showToast('حدث خطأ أثناء حفظ الفاتورة', '#d00000');
    });

}



function setupAutocomplete(inputElement) {
    inputElement.addEventListener('input', function () {
        const query = this.value;
        const list = this.nextElementSibling; // autocomplete-list

        if (query.length < 2) {
            list.innerHTML = '';
            return;
        }

        fetch(`/products/search?q=${query}`)
            .then(res => res.json())
            .then(products => {
                list.innerHTML = '';
                products.forEach(product => {
                    const item = document.createElement('li');
                    item.textContent = product.name;
                    item.dataset.price = product.purchase_price;
                    item.dataset.stock = product.stock;
                    item.dataset.tax_rate = product.tax_rate;
                    item.dataset.code = product.code;
                    item.dataset.product_id = product.id;
                    item.style.cursor = 'pointer';
                    item.addEventListener('click', () => {
                        this.value = product.name;

                        const row = this.closest('tr');
                        row.querySelector('.item-price').value = product.purchase_price;
                        row.querySelector('.item-quantity').value = product.stock;
                        row.querySelector('.item-tax_rate').value = product.tax_rate;
                        row.querySelector('.item-code').value = product.code;
                        row.querySelector('.product_id').value = product.id;
                        updatePurchaseInvoiceTotals(row);

                        list.innerHTML = '';
                    });
                    list.appendChild(item);
                });
            });
    });
}
function setuppurchaseAutocomplete(inputElement) {
    inputElement.addEventListener('input', function () {
        const query = this.value;
        const list = this.nextElementSibling; // autocomplete-list

        if (query.length < 2) {
            list.innerHTML = '';
            return;
        }

        fetch(`/products/search?q=${query}`)
            .then(res => res.json())
            .then(products => {
                list.innerHTML = '';
                products.forEach(product => {
                    const item = document.createElement('li');
                    item.textContent = product.name;
                    item.dataset.price = product.purchase_price;
                    item.dataset.stock = product.stock;
                    item.dataset.tax_rate = product.tax_rate;
                    item.dataset.code = product.code;
                    item.dataset.product_id = product.id;
                    item.style.cursor = 'pointer';
                    item.addEventListener('click', () => {
                        this.value = product.name;

                        const row = this.closest('tr');
                        row.querySelector('.item-price').value = product.purchase_price;
                        row.querySelector('.item-quantity').value = product.stock;
                        row.querySelector('.item-tax_rate').value = product.tax_rate;
                        row.querySelector('.item-code').value = product.code;
                        row.querySelector('.product_id').value = product.id;
                        updatePurchaseInvoiceTotals2(row);

                        list.innerHTML = '';
                    });
                    list.appendChild(item);
                });
            });
    });
}

function setupAutocompletesale(inputElement) {
    inputElement.addEventListener('input', function () {
        const query = this.value;
        const list = this.nextElementSibling; // autocomplete-list

        if (query.length < 2) {
            list.innerHTML = '';
            return;
        }

        fetch(`/products/search?q=${query}`)
            .then(res => res.json())
            .then(products => {
                list.innerHTML = '';
                products.forEach(product => {
                    const item = document.createElement('li');
                    item.textContent = product.name;
                    item.dataset.price = product.selling_price;
                    item.dataset.stock = product.stock;
                    item.dataset.tax_rate = product.tax_rate;
                    item.dataset.code = product.code;
                    item.dataset.product_id = product.id;
                    item.style.cursor = 'pointer';
                    item.addEventListener('click', () => {
                        this.value = product.name;

                        const row = this.closest('tr');
                        row.querySelector('.item-price').value = product.selling_price;
                        row.querySelector('.item-quantity').value = product.stock;
                        row.querySelector('.item-tax_rate').value = product.tax_rate;
                        row.querySelector('.item-code').value = product.code;
                        row.querySelector('.product_id').value = product.id;
                        updateInvoiceTotals(row);
//
                        list.innerHTML = '';
                    });
                    list.appendChild(item);
                });
            });
    });
}
function setupsaleAutocompletesale(inputElement) {
    inputElement.addEventListener('input', function () {
        const query = this.value;
        const list = this.nextElementSibling; // autocomplete-list

        if (query.length < 2) {
            list.innerHTML = '';
            return;
        }

        fetch(`/products/search?q=${query}`)
            .then(res => res.json())
            .then(products => {
                list.innerHTML = '';
                products.forEach(product => {
                    const item = document.createElement('li');
                    item.textContent = product.name;
                    item.dataset.price = product.selling_price;
                    item.dataset.stock = product.stock;
                    item.dataset.tax_rate = product.tax_rate;
                    item.dataset.code = product.code;
                    item.dataset.product_id = product.id;
                    item.style.cursor = 'pointer';
                    item.addEventListener('click', () => {
                        this.value = product.name;

                        const row = this.closest('tr');
                        row.querySelector('.item-price').value = product.selling_price;
                        row.querySelector('.item-quantity').value = product.stock;
                        row.querySelector('.item-tax_rate').value = product.tax_rate;
                        row.querySelector('.item-code').value = product.code;
                        row.querySelector('.product_id').value = product.id;
                        updateInvoiceTotals2(row);

                        list.innerHTML = '';
                    });
                    list.appendChild(item);
                });
            });
    });
}




// ////
// تعديل المشتريات \\

function showEditPurchaseInvoiceForm(bill) {
    showeditPurchaseInvoiceForm(); // استخدم النموذج نفسه
    setTimeout(() => {
        document.getElementById('purchaseInvoiceNumber').value = bill.number;
        document.getElementById('purchaseInvoiceDate').value = bill.date;
        document.getElementById('supplierName').value = bill.customer;
        document.getElementById('purchaseInvoiceNotes').value = bill.description;
        document.getElementById('purchasepaymentMethod').value = bill.paymentMethod;
        document.getElementById('purchasedeliveryType').value = bill.deliveryType;

        // تعبئة البنود
        const tbody = document.getElementById('purchaseeditInvoiceItemsBody');
        tbody.innerHTML = ''; // مسح البنود القديمة

        bill.items.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="item-name" value="${item.name}" required></td>
                <td><input type="number" class="item-quantity" value="${item.quantity}" required></td>
                <td><input type="number" class="item-price" value="${item.price}" required></td>
                <td><input type="text" class="item-tax_rate" value="${item.tax_rate}" required></td>
                <td><input type="text" class="item-code" readonly value="${item.code}" required></td>
                <td><input type="text" class="product_id" readonly  value="${item.product_id}" required></td>
                <td class="item-total">${(item.price * item.quantity).toFixed(2)}</td>
                <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
            `;
            tbody.appendChild(row);
        });

        updatePurchaseInvoiceTotals2();

        // تعديل زر الإرسال
        const form = document.getElementById('purchaseeditInvoiceForm');
        form.onsubmit = null;
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            // updateSalesInvoice(bill.id);
            updatePurchaseInvoice(bill.id); // ✅ تمرير صحيح لـ billId
            document.getElementById('purchaseeditFormContainer').remove();
            document.getElementById('billsList').style.display = 'block';
            document.getElementById('billsButtons').style.display = 'flex';
            document.getElementById('billsSearchContainer').style.display = 'block';
            renderBills();
        });
    }, 0);
}
function showeditPurchaseInvoiceForm() {
    if (document.getElementById('purchaseeditFormContainer')) return; // ⛔️ لا تنشئ مرتين

    // إخفاء قائمة الفواتير
    document.getElementById('billsList').style.display = 'none';
    document.getElementById('billsButtons').style.display = 'none';
    document.getElementById('billsSearchContainer').style.display = 'none';
    // جلب أنواع التوصيل
    fetch('/delivery-types')
    .then(response => response.json())
    .then(data => {
        const deliverySelect = document.getElementById('purchasedeliveryType');
        data.forEach(type => {
        const option = document.createElement('option');
        option.value = type.id; // حسب شكل بياناتك
        option.textContent = type.name; // أو type.name
        deliverySelect.appendChild(option);
        });
    });

    // جلب طرق الدفع
    fetch('/payment-methods')
    .then(response => response.json())
    .then(data => {
        const paymentSelect = document.getElementById('purchasepaymentMethod');
        data.forEach(method => {
        const option = document.createElement('option');
        option.value = method.id;
        option.textContent = method.name;
        paymentSelect.appendChild(option);
        });
    });

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
            <form id="purchaseeditInvoiceForm">
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
                                <th>الضريبة</th>
                                <th>الكود</th>

                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="purchaseeditInvoiceItemsBody">
                            <tr>
                                <td><input type="text" class="item-name" required></td>
                                <td><input type="number" class="item-quantity" min="1" value="1" required></td>
                                <td><input type="number" class="item-price" min="0" value="0" required></td>
                                <td><input type="text" class="item-tax_rate"  required></td>
                                <td><input type="text" class="item-code" readonly  required></td>
                                <td><input type="text" class="product_id"  readonly  required></td>
                                <td class="item-total">0</td>
                                <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" id="editPurchaseInvoiceItem" class="add-item-btn"><i class="fas fa-plus"></i> إضافة منتج</button>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="deliveryType">نوع التوصيل:</label>
                        <select id="purchasedeliveryType" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">طريقة الدفع:</label>
                        <select id="purchasepaymentMethod" required>
                        </select>
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
    purchaseFormContainer.id = 'purchaseeditFormContainer';
    purchaseFormContainer.innerHTML = purchaseFormHTML;

    billsContainer.appendChild(purchaseFormContainer);

    setTimeout(() => {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('purchaseInvoiceDate').value = today;

        const purchaseInvoiceCount = bills.filter(b => b.type === 'مشتريات').length;
        document.getElementById('purchaseInvoiceNumber').value = `INV-S-${String(purchaseInvoiceCount + 1).padStart(3, '0')}`;
    }, 0);
    // إضافة مستمعي الأحداث
    setupPurchaseInvoiceItemListeners2();
    // setupPurchaseInvoiceItemListeners2();

    // إضافة حدث العودة للفواتير
    document.getElementById('backToBillsBtn').addEventListener('click', function() {
        document.getElementById('purchaseeditFormContainer').remove();
        document.getElementById('billsList').style.display = 'block';
        document.getElementById('billsButtons').style.display = 'flex';
        document.getElementById('billsSearchContainer').style.display = 'block';
    });




}

function updatePurchaseInvoice(billId) {
    const invoiceNumber = document.getElementById('purchaseInvoiceNumber').value;
    const invoiceDate = document.getElementById('purchaseInvoiceDate').value;
    const customerName = document.getElementById('supplierName').value;
    const notes = document.getElementById('purchaseInvoiceNotes').value;
    const deliveryType = document.getElementById('purchasedeliveryType').value;
    const paymentMethod = document.getElementById('purchasepaymentMethod').value;


    const items = [];
    let invoiceTotal = 0;

    document.querySelectorAll('#purchaseeditInvoiceItemsBody tr').forEach(row => {
        const name = row.querySelector('.item-name').value;
        const quantity = parseFloat(row.querySelector('.item-quantity').value);
        const price = parseFloat(row.querySelector('.item-price').value);
        const tax_rate = row.querySelector('.item-tax_rate').value;
        const code = row.querySelector('.item-code').value;
        const product_id = row.querySelector('.product_id').value;
        const total = quantity * price;

        items.push({ name, quantity, price, total,tax_rate,code,product_id });
        invoiceTotal += total;
    });

    const tax = invoiceTotal * 0.15;
    const finalTotal = invoiceTotal + tax;

    fetch(`/bills/${billId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            number: invoiceNumber,
            type: 'مشتريات',
            date: invoiceDate,
            customer: customerName,
            amount: finalTotal,
            delivery_type_id: deliveryType,
            payment_method_id: paymentMethod,
            description: notes,
            items: items
        })
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message);
        // document.getElementById('saleseditFormContainer')?.remove();
        fetchBills();
    })
    .catch(err => {
        console.log(err);
        showToast('حدث خطأ أثناء تعديل الفاتورة','#d00000');
    });
}

// تحديث إجماليات فاتورة المشتريات
function updatePurchaseInvoiceTotals2() {
    let subtotal = 0;

    // حساب إجمالي كل بند وإضافته للإجمالي العام
    document.querySelectorAll('#purchaseeditInvoiceItemsBody tr').forEach(row => {
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

// إعداد أحداث بنود فاتورة المشتريات
function setupPurchaseInvoiceItemListeners2() {
    // حساب الإجمالي عند تغيير الكمية أو السعر
    document.querySelectorAll('#purchaseeditInvoiceItemsBody .item-quantity, #purchaseeditInvoiceItemsBody .item-price').forEach(input => {
        input.addEventListener('input', updatePurchaseInvoiceTotals2);
    });

    // إزالة بند عند النقر على زر الحذف
    document.querySelectorAll('#purchaseeditInvoiceItemsBody .remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const row = this.closest('tr');
            if (document.querySelectorAll('#purchaseeditInvoiceItemsBody tr').length > 1) {
                row.remove();
                updatePurchaseInvoiceTotals2();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
    });

    // زر إضافة بند جديد
    document.getElementById('editPurchaseInvoiceItem').addEventListener('click', function() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" class="item-name" required autocomplete="off">
                <ul class="autocomplete-list"></ul></td>
            <td><input type="number" class="item-quantity" min="1" value="1" required></td>
            <td><input type="number" class="item-price" min="0" value="0" required></td>
            <td><input type="text" class="item-tax_rate"  required></td>
            <td><input type="text" class="item-code" readonly required></td>
            <td><input type="text" class="product_id"  readonly required></td>
            <td class="item-total">0</td>
            <td><button type="button" class="remove-item"><i class="fas fa-times"></i></button></td>
        `;
        document.getElementById('purchaseeditInvoiceItemsBody').appendChild(newRow);

        // إضافة مستمعي الأحداث للبند الجديد
        newRow.querySelector('.item-quantity').addEventListener('input', updatePurchaseInvoiceTotals2);
        newRow.querySelector('.item-price').addEventListener('input', updatePurchaseInvoiceTotals2);
        newRow.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('#purchaseeditInvoiceItemsBody tr').length > 1) {
                newRow.remove();
                updatePurchaseInvoiceTotals2();
            } else {
                alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
            }
        });
                // ✅ تفعيل الإكمال التلقائي للبند الجديد
        const itemNameInput = newRow.querySelector('.item-name');
        setuppurchaseAutocomplete(itemNameInput);

    });




    document.querySelectorAll('.item-name').forEach(input => {
        input.addEventListener('input', function () {
            const query = this.value;
            const list = this.nextElementSibling; // autocomplete-list

            if (query.length < 2) {
                list.innerHTML = '';
                return;
            }

            fetch(`/products/search?q=${query}`)
                .then(res => res.json())
                .then(products => {
                    list.innerHTML = '';
                    products.forEach(product => {
                        const item = document.createElement('li');
                        item.textContent = product.name;
                        item.dataset.price = product.purchase_price;
                        item.dataset.stock = product.stock;
                        item.dataset.tax_rate = product.tax_rate;
                        item.dataset.code = product.code;
                        item.dataset.product_id = product.id;
                        item.style.cursor = 'pointer';
                        item.addEventListener('click', () => {
                            this.value = product.name;

                            // ملء السعر تلقائيًا
                            const row = this.closest('tr');
                            row.querySelector('.item-price').value = product.purchase_price;
                            row.querySelector('.item-quantity').value = product.stock;
                            row.querySelector('.item-tax_rate').value = product.tax_rate;
                            row.querySelector('.item-code').value = product.code;
                            row.querySelector('.product_id').value = product.id;
                            updatePurchaseInvoiceTotals(row); // لتحديث الإجمالي مباشرةً

                            list.innerHTML = '';
                        });
                        list.appendChild(item);
                    });
                });
        });
    });

}
