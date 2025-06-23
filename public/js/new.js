// بيانات الحسابات
let accounts = [
    {
        id: 1,
        bankName: "البنك الأهلي",
        accountNumber: "1234567890",
        balance: 50000,
        transactions: [
            { date: "2023-06-15", type: "تحويل", amount: 5000, description: "تحويل من حساب آخر" },
            { date: "2023-06-10", type: "عهدة", amount: -2000, description: "عهدة مصاريف تشغيلية" }
        ]
    },
    {
        id: 2,
        bankName: "بنك الراجحي",
        accountNumber: "0987654321",
        balance: 75000,
        transactions: [
            { date: "2023-06-20", type: "تحويل", amount: -10000, description: "تحويل إلى مورد" },
            { date: "2023-06-05", type: "تحويل", amount: 30000, description: "إيداع" }
        ]
    }
];

// بيانات الصناديق
let funds = [
    {
        id: 1,
        name: "الصندوق الرئيسي",
        balance: 120000,
        cashier: "محمد عبدالله",
        location: "المقر الرئيسي - الرياض",
        transactions: [
            { date: "2023-06-18", type: "إيداع", amount: 15000, description: "إيداع نقدي" },
            { date: "2023-06-12", type: "صرف", amount: -5000, description: "صرف مصاريف تشغيلية" }
        ]
    },
    {
        id: 2,
        name: "صندوق المبيعات",
        balance: 45000,
        cashier: "أحمد محمد",
        location: "فرع الدمام",
        transactions: [
            { date: "2023-06-20", type: "إيداع", amount: 8000, description: "إيداع مبيعات اليوم" },
            { date: "2023-06-15", type: "صرف", amount: -2000, description: "صرف مستحقات موظفين" }
        ]
    }
];

// بيانات السندات
let vouchers = [
    {
        id: 1,
        number: "REC-001",
        type: "قبض",
        date: "2023-06-20",
        amount: 15000,
        fromTo: "شركة الأفق للتجارة",
        description: "دفعة من عقد توريد",
        paymentMethod: "شيك"
    },
    {
        id: 2,
        number: "PAY-001",
        type: "صرف",
        date: "2023-06-15",
        amount: 8000,
        fromTo: "مؤسسة النور للصيانة",
        description: "دفعة صيانة المكاتب",
        paymentMethod: "نقدي"
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
    renderStatistics();
    renderAccounts();
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
        switchView('banks');
    });

    document.getElementById('fundsViewBtn').addEventListener('click', function() {
        switchView('funds');
    });

    document.getElementById('vouchersViewBtn').addEventListener('click', function() {
        switchView('vouchers');
    });
}

// التبديل بين شاشة البنوك وشاشة الصناديق وشاشة السندات
function switchView(view) {
    currentView = view;

    // إخفاء/إظهار العناصر المناسبة
    if (view === 'banks') {
        document.getElementById('banksContainer').style.display = 'block';
        document.getElementById('fundsContainer').style.display = 'none';
        document.getElementById('vouchersContainer').style.display = 'none';
        document.getElementById('bankViewBtn').classList.add('active');
        document.getElementById('fundsViewBtn').classList.remove('active');
        document.getElementById('vouchersViewBtn').classList.remove('active');
        renderStatistics();
        renderAccounts();
    } else if (view === 'funds') {
        document.getElementById('banksContainer').style.display = 'none';
        document.getElementById('fundsContainer').style.display = 'block';
        document.getElementById('vouchersContainer').style.display = 'none';
        document.getElementById('bankViewBtn').classList.remove('active');
        document.getElementById('fundsViewBtn').classList.add('active');
        document.getElementById('vouchersViewBtn').classList.remove('active');
        renderFundsStatistics();
        renderFunds();
    } else if (view === 'vouchers') {
        document.getElementById('banksContainer').style.display = 'none';
        document.getElementById('fundsContainer').style.display = 'none';
        document.getElementById('vouchersContainer').style.display = 'block';
        document.getElementById('bankViewBtn').classList.remove('active');
        document.getElementById('fundsViewBtn').classList.remove('active');
        document.getElementById('vouchersViewBtn').classList.add('active');
        renderVouchers();
    }

    // إغلاق الشريط الجانبي في الشاشات الصغيرة
    if (window.innerWidth <= 768) {
        document.getElementById('sidebar').classList.remove('active');
    }
}

// عرض إحصائيات البنوك
function renderStatistics() {
    const statisticsContainer = document.getElementById('statisticsDashboard');

    // حساب الإحصائيات
    const totalBalance = accounts.reduce((sum, account) => sum + account.balance, 0);
    const totalAccounts = accounts.length;
    const totalTransactions = accounts.reduce((sum, account) => sum + account.transactions.length, 0);

    // البنك صاحب أعلى رصيد
    let highestBalanceAccount = accounts[0];
    accounts.forEach(account => {
        if (account.balance > highestBalanceAccount.balance) {
            highestBalanceAccount = account;
        }
    });

    statisticsContainer.innerHTML = `
        <div class="stat-card stat-total-balance">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-value">${formatCurrency(totalBalance)}</div>
            <div class="stat-label">إجمالي الأرصدة</div>
        </div>

        <div class="stat-card stat-total-accounts">
            <div class="stat-icon">
                <i class="fas fa-university"></i>
            </div>
            <div class="stat-value">${totalAccounts}</div>
            <div class="stat-label">عدد الحسابات</div>
        </div>

        <div class="stat-card stat-total-transactions">
            <div class="stat-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-value">${totalTransactions}</div>
            <div class="stat-label">عدد المعاملات</div>
        </div>

        <div class="stat-card stat-highest-balance">
            <div class="stat-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-value">${formatCurrency(highestBalanceAccount.balance)}</div>
            <div class="stat-label">أعلى رصيد (${highestBalanceAccount.bankName})</div>
        </div>
    `;
}

// عرض إحصائيات الصناديق
function renderFundsStatistics() {
    const statisticsContainer = document.getElementById('fundsStatisticsDashboard');

    // حساب الإحصائيات
    const totalBalance = funds.reduce((sum, fund) => sum + fund.balance, 0);
    const totalFunds = funds.length;
    const totalTransactions = funds.reduce((sum, fund) => sum + fund.transactions.length, 0);

    // الصندوق صاحب أعلى رصيد
    let highestBalanceFund = funds[0];
    funds.forEach(fund => {
        if (fund.balance > highestBalanceFund.balance) {
            highestBalanceFund = fund;
        }
    });

    statisticsContainer.innerHTML = `
        <div class="stat-card stat-total-balance">
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-value">${formatCurrency(totalBalance)}</div>
            <div class="stat-label">إجمالي أرصدة الصناديق</div>
        </div>

        <div class="stat-card stat-total-accounts">
            <div class="stat-icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <div class="stat-value">${totalFunds}</div>
            <div class="stat-label">عدد الصناديق</div>
        </div>

        <div class="stat-card stat-total-transactions">
            <div class="stat-icon">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-value">${totalTransactions}</div>
            <div class="stat-label">عدد العمليات</div>
        </div>

        <div class="stat-card stat-highest-balance">
            <div class="stat-icon">
                <i class="fas fa-crown"></i>
            </div>
            <div class="stat-value">${formatCurrency(highestBalanceFund.balance)}</div>
            <div class="stat-label">أعلى رصيد (${highestBalanceFund.name})</div>
        </div>
    `;
}

// تحديث الإحصائيات بعد كل عملية تغيير في البيانات
function updateStatistics() {
    if (currentView === 'banks') {
        renderStatistics();
    } else if (currentView === 'funds') {
        renderFundsStatistics();
    }
}

// عرض الحسابات البنكية
function renderAccounts() {
    const accountsContainer = document.getElementById('bankAccounts');
    accountsContainer.innerHTML = '';

    accounts.forEach(account => {
        const accountCard = document.createElement('div');
        accountCard.className = 'account-card';
        accountCard.dataset.id = account.id;

        accountCard.innerHTML = `
            <div class="account-options">
                <button class="edit-account" data-id="${account.id}"><i class="fas fa-edit"></i></button>
                <button class="delete-account" data-id="${account.id}"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="account-name">${account.bankName}</div>
            <div class="account-number">رقم الحساب: ${account.accountNumber}</div>
            <div class="account-balance">${formatCurrency(account.balance)}</div>
            <div class="account-actions">
                <button class="action-btn add-transfer" data-id="${account.id}">
                    <i class="fas fa-exchange-alt"></i> سند تحويل
                </button>
                <button class="action-btn add-custody" data-id="${account.id}">
                    <i class="fas fa-hand-holding-usd"></i> سند عهدة
                </button>
                <button class="action-btn add-deposit" data-id="${account.id}">
                    <i class="fas fa-money-bill-wave"></i> سند إيداع
                </button>
            </div>
        `;

        accountsContainer.appendChild(accountCard);
    });
}

// عرض الصناديق
function renderFunds() {
    const fundsContainer = document.getElementById('fundsList');
    fundsContainer.innerHTML = '';

    funds.forEach(fund => {
        const fundCard = document.createElement('div');
        fundCard.className = 'fund-card';
        fundCard.dataset.id = fund.id;

        fundCard.innerHTML = `
            <div class="fund-options">
                <button class="edit-fund" data-id="${fund.id}"><i class="fas fa-edit"></i></button>
                <button class="delete-fund" data-id="${fund.id}"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="fund-name">${fund.name}</div>
            <div class="fund-cashier"><i class="fas fa-user"></i> أمين الصندوق: ${fund.cashier}</div>
            <div class="fund-location"><i class="fas fa-map-marker-alt"></i> الموقع: ${fund.location}</div>
            <div class="fund-balance">${formatCurrency(fund.balance)}</div>
            <div class="fund-actions">
                <button class="action-btn add-income" data-id="${fund.id}">
                    <i class="fas fa-plus-circle"></i> سند قبض
                </button>
                <button class="action-btn add-expense" data-id="${fund.id}">
                    <i class="fas fa-minus-circle"></i> سند صرف
                </button>
            </div>
        `;

        fundsContainer.appendChild(fundCard);
    });
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

// وظيفة تعديل السند
function editVoucher(voucherId) {
    const voucher = vouchers.find(v => v.id === voucherId);
    if (!voucher) return;

    if (voucher.type === 'قبض') {
        openGeneralReceiptModal();

        document.getElementById('generalReceiptNumber').value = voucher.number;
        document.getElementById('generalReceiptDate').value = voucher.date;
        document.getElementById('receiptFromName').value = voucher.fromTo;
        document.getElementById('generalReceiptAmount').value = voucher.amount;
        document.getElementById('paymentMethod').value = voucher.paymentMethod || 'نقدي';
        document.getElementById('generalReceiptDescription').value = voucher.description;

        // تغيير وظيفة إرسال النموذج للتحديث بدلاً من الإضافة
        const form = document.getElementById('generalReceiptForm');
        form.dataset.editId = voucherId;

        form.onsubmit = function(e) {
            e.preventDefault();
            updateVoucher(voucherId, 'قبض');
        };
    } else if (voucher.type === 'صرف') {
        openGeneralPaymentModal();

        document.getElementById('generalPaymentNumber').value = voucher.number;
        document.getElementById('generalPaymentDate').value = voucher.date;
        document.getElementById('paidToName').value = voucher.fromTo;
        document.getElementById('generalPaymentAmount').value = voucher.amount;
        document.getElementById('paymentMethodOut').value = voucher.paymentMethod || 'نقدي';
        document.getElementById('generalPaymentDescription').value = voucher.description;

        // تغيير وظيفة إرسال النموذج للتحديث بدلاً من الإضافة
        const form = document.getElementById('generalPaymentForm');
        form.dataset.editId = voucherId;

        form.onsubmit = function(e) {
            e.preventDefault();
            updateVoucher(voucherId, 'صرف');
        };
    }
}

// وظيفة تحديث السند
function updateVoucher(voucherId, type) {
    const voucherIndex = vouchers.findIndex(v => v.id === voucherId);
    if (voucherIndex === -1) return;

    if (type === 'قبض') {
        const number = document.getElementById('generalReceiptNumber').value;
        const date = document.getElementById('generalReceiptDate').value;
        const fromName = document.getElementById('receiptFromName').value;
        const amount = parseFloat(document.getElementById('generalReceiptAmount').value);
        const paymentMethod = document.getElementById('paymentMethod').value;
        const description = document.getElementById('generalReceiptDescription').value;

        vouchers[voucherIndex] = {
            ...vouchers[voucherIndex],
            number: number,
            date: date,
            fromTo: fromName,
            amount: amount,
            description: description,
            paymentMethod: paymentMethod
        };

        document.getElementById('generalReceiptModal').style.display = 'none';
        document.getElementById('generalReceiptForm').reset();
        // إعادة تعيين وظيفة النموذج
        document.getElementById('generalReceiptForm').onsubmit = function(e) {
            e.preventDefault();
            addGeneralReceipt();
        };
    } else if (type === 'صرف') {
        const number = document.getElementById('generalPaymentNumber').value;
        const date = document.getElementById('generalPaymentDate').value;
        const toName = document.getElementById('paidToName').value;
        const amount = parseFloat(document.getElementById('generalPaymentAmount').value);
        const paymentMethod = document.getElementById('paymentMethodOut').value;
        const description = document.getElementById('generalPaymentDescription').value;

        vouchers[voucherIndex] = {
            ...vouchers[voucherIndex],
            number: number,
            date: date,
            fromTo: toName,
            amount: amount,
            description: description,
            paymentMethod: paymentMethod
        };

        document.getElementById('generalPaymentModal').style.display = 'none';
        document.getElementById('generalPaymentForm').reset();
        // إعادة تعيين وظيفة النموذج
        document.getElementById('generalPaymentForm').onsubmit = function(e) {
            e.preventDefault();
            addGeneralPayment();
        };
    }

    renderVouchers();
}

// وظيفة حذف السند
function deleteVoucher(voucherId) {
    if (confirm('هل أنت متأكد من حذف هذا السند؟')) {
        vouchers = vouchers.filter(v => v.id !== voucherId);
        renderVouchers();
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

// إعداد مستمعي الأحداث
function setupEventListeners() {
    // فتح كشف الحساب عند النقر على البطاقة
    document.getElementById('bankAccounts').addEventListener('click', function(e) {
        const card = e.target.closest('.account-card');
        if (card) {
            const accountId = parseInt(card.dataset.id);
            openAccountStatement(accountId);
        }
    });

    // أزرار إضافة التحويل/العهدة/الإيداع داخل البطاقة
    document.getElementById('bankAccounts').addEventListener('click', function(e) {
        if (e.target.closest('.add-transfer')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.add-transfer').dataset.id);
            openTransactionModal('تحويل', accountId);
        } else if (e.target.closest('.add-custody')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.add-custody').dataset.id);
            openTransactionModal('عهدة', accountId);
        } else if (e.target.closest('.add-deposit')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.add-deposit').dataset.id);
            openDepositModal(accountId);
        }
    });

    // أزرار تعديل/حذف الحساب
    document.getElementById('bankAccounts').addEventListener('click', function(e) {
        if (e.target.closest('.edit-account')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.edit-account').dataset.id);
            editAccount(accountId);
        } else if (e.target.closest('.delete-account')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.delete-account').dataset.id);
            deleteAccount(accountId);
        }
    });

    // أزرار إضافة التحويل/العهدة داخل كشف الحساب
    document.getElementById('addTransferBtn').addEventListener('click', function() {
        openTransactionModal('تحويل', currentAccountId);
    });

    document.getElementById('addCustodyBtn').addEventListener('click', function() {
        openTransactionModal('عهدة', currentAccountId);
    });

    // زر إضافة حساب جديد
    document.getElementById('addAccountBtn').addEventListener('click', function() {
        document.getElementById('addAccountModal').style.display = 'block';
    });

    // إضافة حساب جديد
    document.getElementById('newAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewAccount();
    });

    // إضافة معاملة جديدة
    document.getElementById('newTransactionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewTransaction();
    });

    // إضافة سند إيداع جديد
    document.getElementById('newDepositForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewDeposit();
    });

    // أزرار كشف الحساب
    document.getElementById('printStatementBtn').addEventListener('click', function() {
        printAccountStatement();
    });

    document.getElementById('exportStatementBtn').addEventListener('click', function() {
        exportAccountStatement();
    });

    // إغلاق النوافذ المنبثقة
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // إغلاق النوافذ المنبثقة عند النقر خارجها
    window.addEventListener('click', function(e) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });

    // إضافة مستمع للأزرار داخل قائمة الصناديق
    document.getElementById('fundsList').addEventListener('click', function(e) {
        if (e.target.closest('.edit-fund')) {
            const fundId = parseInt(e.target.closest('.edit-fund').dataset.id);
            editFund(fundId);
        } else if (e.target.closest('.delete-fund')) {
            const fundId = parseInt(e.target.closest('.delete-fund').dataset.id);
            deleteFund(fundId);
        } else if (e.target.closest('.add-income')) {
            const fundId = parseInt(e.target.closest('.add-income').dataset.id);
            addIncome(fundId);
        } else if (e.target.closest('.add-expense')) {
            const fundId = parseInt(e.target.closest('.add-expense').dataset.id);
            addExpense(fundId);
        }
    });

    document.getElementById('addFundBtn').addEventListener('click', function() {
        document.getElementById('addFundModal').style.display = 'block';
    });

    // إضافة صندوق جديد
    document.getElementById('newFundForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewFund();
    });

    // إضافة سند قبض جديد
    document.getElementById('newIncomeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewIncome();
    });

    // إضافة سند صرف جديد
    document.getElementById('newExpenseForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addNewExpense();
    });

    // أزرار إنشاء السندات
    document.getElementById('generalReceiptBtn').addEventListener('click', function() {
        openGeneralReceiptModal();
    });

    document.getElementById('generalPaymentBtn').addEventListener('click', function() {
        openGeneralPaymentModal();
    });

    // نماذج السندات
    document.getElementById('generalReceiptForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addGeneralReceipt();
    });

    document.getElementById('generalPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addGeneralPayment();
    });

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

// إعداد مستمعي الأحداث للمعاينة والطباعة والتعديل والحذف
function setupPreviewActions() {
    // زر معاينة المستند
    document.getElementById('statementTableBody').addEventListener('click', function(e) {
        if (e.target.closest('.preview-btn')) {
            const transactionIndex = parseInt(e.target.closest('.preview-btn').dataset.index);
            previewDocument(transactionIndex);
        }
    });

    // زر طباعة المستند
    document.getElementById('printDocumentBtn').addEventListener('click', function() {
        printDocument();
    });

    // زر تعديل المستند
    document.getElementById('editDocumentBtn').addEventListener('click', function() {
        editDocument();
    });

    // زر حذف المستند
    document.getElementById('deleteDocumentBtn').addEventListener('click', function() {
        deleteDocument();
    });
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

// فتح نافذة إضافة معاملة
function openTransactionModal(type, accountId) {
    currentAccountId = accountId;
    transactionType = type;

    document.getElementById('transactionTitle').textContent = `إضافة سند ${type}`;
    document.getElementById('transactionType').value = type;

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('transactionDate').value = today;

    const account = accounts.find(acc => acc.id === accountId);
    const transactionCount = account.transactions.filter(t => t.type === type).length;
    const typePrefix = type === 'تحويل' ? 'TRN' : 'CST';
    document.getElementById('transactionVoucherNumber').value = `${typePrefix}-${accountId}-${transactionCount + 1}`;

    document.getElementById('beneficiaryName').value = '';
    document.getElementById('transactionAmount').value = '';
    document.getElementById('transactionDescription').value = '';

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

    document.getElementById('addDepositModal').style.display = 'block';
}

// إضافة حساب جديد
function addNewAccount() {
    const bankName = document.getElementById('bankName').value;
    const accountNumber = document.getElementById('accountNumber').value;
    const initialBalance = parseFloat(document.getElementById('initialBalance').value);

    const newAccount = {
        id: accounts.length > 0 ? Math.max(...accounts.map(acc => acc.id)) + 1 : 1,
        bankName: bankName,
        accountNumber: accountNumber,
        balance: initialBalance,
        transactions: []
    };

    accounts.push(newAccount);
    renderAccounts();
    updateStatistics();

    document.getElementById('addAccountModal').style.display = 'none';
    document.getElementById('newAccountForm').reset();
}

// إضافة معاملة جديدة
function addNewTransaction() {
    const voucherNumber = document.getElementById('transactionVoucherNumber').value;
    const transactionDate = document.getElementById('transactionDate').value;
    const beneficiaryName = document.getElementById('beneficiaryName').value;
    const amount = parseFloat(document.getElementById('transactionAmount').value);
    const description = document.getElementById('transactionDescription').value;
    const type = document.getElementById('transactionType').value;

    let finalAmount = amount;
    if (type === 'عهدة') {
        finalAmount = -Math.abs(amount);
    }

    const transaction = {
        date: transactionDate,
        type: type,
        amount: finalAmount,
        description: `${type} ${type === 'تحويل' ? 'إلى' : 'لـ'} ${beneficiaryName}: ${description} (رقم السند: ${voucherNumber})`,
        voucherNumber: voucherNumber,
        beneficiaryName: beneficiaryName
    };

    const accountIndex = accounts.findIndex(acc => acc.id === currentAccountId);
    if (accountIndex !== -1) {
        accounts[accountIndex].transactions.push(transaction);
        accounts[accountIndex].balance += finalAmount;

        renderAccounts();
        updateStatistics();

        if (document.getElementById('accountStatementModal').style.display === 'block') {
            openAccountStatement(currentAccountId);
        }
    }

    document.getElementById('addTransactionModal').style.display = 'none';
    document.getElementById('newTransactionForm').reset();
}

// إضافة سند إيداع جديد
function addNewDeposit() {
    const receiptNumber = document.getElementById('receiptNumber').value;
    const depositDate = document.getElementById('depositDate').value;
    const depositorName = document.getElementById('depositorName').value;
    const amount = parseFloat(document.getElementById('depositAmount').value);
    const description = document.getElementById('depositDescription').value;

    const transaction = {
        date: depositDate,
        type: 'إيداع',
        amount: amount,
        description: `إيداع من ${depositorName}: ${description} (رقم السند: ${receiptNumber})`,
        receiptNumber: receiptNumber,
        depositorName: depositorName
    };

    const accountIndex = accounts.findIndex(acc => acc.id === currentAccountId);
    if (accountIndex !== -1) {
        accounts[accountIndex].transactions.push(transaction);
        accounts[accountIndex].balance += amount;

        renderAccounts();
        updateStatistics();

        if (document.getElementById('accountStatementModal').style.display === 'block') {
            openAccountStatement(currentAccountId);
        }
    }

    document.getElementById('addDepositModal').style.display = 'none';
}

// معاينة المستند
function previewDocument(transactionIndex) {
    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    const transaction = account.transactions[transactionIndex];
    currentTransaction = transaction;
    currentTransactionIndex = transactionIndex;

    const previewContainer = document.getElementById('documentPreview');
    previewContainer.innerHTML = '';

    let documentHTML = '';

    if (transaction.type === 'تحويل') {
        documentHTML = `
            <div class="preview-document transfer-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="document-title">سند تحويل بنكي</div>
                </div>
                <div class="document-body">
                    <div class="document-row">
                        <div class="document-field">رقم السند:</div>
                        <div class="document-value">${transaction.voucherNumber || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(transaction.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المستفيد:</div>
                        <div class="document-value">${transaction.beneficiaryName || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المبلغ:</div>
                        <div class="document-value ${transaction.amount >= 0 ? 'amount-positive' : 'amount-negative'}">${formatCurrency(Math.abs(transaction.amount))}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">الوصف:</div>
                        <div class="document-value document-description">${transaction.description}</div>
                    </div>
                </div>
                <div class="document-footer">
                    <div class="document-bank">
                        <div class="document-field">البنك:</div>
                        <div class="document-value">${account.bankName}</div>
                    </div>
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>توقيع المستلم</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>توقيع المدير المالي</div>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (transaction.type === 'عهدة') {
        documentHTML = `
            <div class="preview-document custody-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <div class="document-title">سند عهدة</div>
                </div>
                <div class="document-body">
                    <div class="document-row">
                        <div class="document-field">رقم السند:</div>
                        <div class="document-value">${transaction.voucherNumber || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(transaction.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المستفيد:</div>
                        <div class="document-value">${transaction.beneficiaryName || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المبلغ:</div>
                        <div class="document-value amount-negative">${formatCurrency(Math.abs(transaction.amount))}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">الوصف:</div>
                        <div class="document-value document-description">${transaction.description}</div>
                    </div>
                </div>
                <div class="document-footer">
                    <div class="document-bank">
                        <div class="document-field">البنك:</div>
                        <div class="document-value">${account.bankName}</div>
                    </div>
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>توقيع المستلم</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>توقيع المدير المالي</div>
                            <div class="signature-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else if (transaction.type === 'إيداع') {
        documentHTML = `
            <div class="preview-document deposit-document">
                <div class="document-header">
                    <div class="document-logo">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="document-title">سند إيداع بنكي</div>
                </div>
                <div class="document-body">
                    <div class="document-row">
                        <div class="document-field">رقم السند:</div>
                        <div class="document-value">${transaction.receiptNumber || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">التاريخ:</div>
                        <div class="document-value">${formatDate(transaction.date)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">اسم المودع:</div>
                        <div class="document-value">${transaction.depositorName || 'غير محدد'}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">المبلغ:</div>
                        <div class="document-value amount-positive">${formatCurrency(transaction.amount)}</div>
                    </div>
                    <div class="document-row">
                        <div class="document-field">الوصف:</div>
                        <div class="document-value document-description">${transaction.description}</div>
                    </div>
                </div>
                <div class="document-footer">
                    <div class="document-bank">
                        <div class="document-field">البنك:</div>
                        <div class="document-value">${account.bankName}</div>
                    </div>
                    <div class="document-signatures">
                        <div class="document-signature">
                            <div>توقيع المستلم</div>
                            <div class="signature-line"></div>
                        </div>
                        <div class="document-signature">
                            <div>توقيع المدير المالي</div>
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

// طباعة المستند
function printDocument() {
    const printContent = document.getElementById('printableDocument').innerHTML;
    const originalContent = document.body.innerHTML;

    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;

    renderAccounts();
    setupEventListeners();
    setupPreviewActions();
}

// تعديل المستند
function editDocument() {
    if (!currentTransaction) return;

    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    document.getElementById('previewDocumentModal').style.display = 'none';

    if (currentTransaction.type === 'تحويل' || currentTransaction.type === 'عهدة') {
        openTransactionModal(currentTransaction.type, currentAccountId);

        document.getElementById('transactionVoucherNumber').value = currentTransaction.voucherNumber || '';
        document.getElementById('transactionDate').value = currentTransaction.date;
        document.getElementById('beneficiaryName').value = currentTransaction.beneficiaryName || '';
        document.getElementById('transactionAmount').value = Math.abs(currentTransaction.amount);

        let description = currentTransaction.description;
        document.getElementById('transactionDescription').value = description;

        document.getElementById('newTransactionForm').onsubmit = function(e) {
            e.preventDefault();
            updateTransaction();
        };
    } else if (currentTransaction.type === 'إيداع') {
        openDepositModal(currentAccountId);

        document.getElementById('receiptNumber').value = currentTransaction.receiptNumber || '';
        document.getElementById('depositDate').value = currentTransaction.date;
        document.getElementById('depositorName').value = currentTransaction.depositorName || '';
        document.getElementById('depositAmount').value = currentTransaction.amount;

        let description = currentTransaction.description;
        document.getElementById('depositDescription').value = description;

        document.getElementById('newDepositForm').onsubmit = function(e) {
            e.preventDefault();
            updateDeposit();
        };
    }
}

// حذف المستند
function deleteDocument() {
    if (!currentTransaction || currentTransactionIndex === null) return;

    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    if (confirm('هل أنت متأكد من حذف هذا المستند؟')) {
        const oldAmount = account.transactions[currentTransactionIndex].amount;

        account.transactions.splice(currentTransactionIndex, 1);

        account.balance -= oldAmount;

        renderAccounts();
        updateStatistics();
        openAccountStatement(currentAccountId);
        document.getElementById('previewDocumentModal').style.display = 'none';
    }
}

// تحديث معاملة قائمة بعد التعديل
function updateTransaction() {
    if (currentTransactionIndex === null) return;

    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    const voucherNumber = document.getElementById('transactionVoucherNumber').value;
    const transactionDate = document.getElementById('transactionDate').value;
    const beneficiaryName = document.getElementById('beneficiaryName').value;
    const amount = parseFloat(document.getElementById('transactionAmount').value);
    const description = document.getElementById('transactionDescription').value;
    const type = document.getElementById('transactionType').value;

    let finalAmount = amount;
    if (type === 'عهدة') {
        finalAmount = -Math.abs(amount);
    }

    const updatedTransaction = {
        date: transactionDate,
        type: type,
        amount: finalAmount,
        description: `${type} ${type === 'تحويل' ? 'إلى' : 'لـ'} ${beneficiaryName}: ${description} (رقم السند: ${voucherNumber})`,
        voucherNumber: voucherNumber,
        beneficiaryName: beneficiaryName
    };

    account.transactions[currentTransactionIndex] = updatedTransaction;

    account.balance = account.balance - account.transactions[currentTransactionIndex - 1].amount + finalAmount;

    renderAccounts();
    updateStatistics();
    openAccountStatement(currentAccountId);

    document.getElementById('newTransactionForm').reset();
    document.getElementById('newTransactionForm').onsubmit = function(e) {
        e.preventDefault();
        addNewTransaction();
    };
}

// تحديث سند إيداع قائم بعد التعديل
function updateDeposit() {
    if (currentTransactionIndex === null) return;

    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    const receiptNumber = document.getElementById('receiptNumber').value;
    const depositDate = document.getElementById('depositDate').value;
    const depositorName = document.getElementById('depositorName').value;
    const amount = parseFloat(document.getElementById('depositAmount').value);
    const description = document.getElementById('depositDescription').value;

    const updatedTransaction = {
        date: depositDate,
        type: 'إيداع',
        amount: amount,
        description: `إيداع من ${depositorName}: ${description} (رقم السند: ${receiptNumber})`,
        receiptNumber: receiptNumber,
        depositorName: depositorName
    };

    account.transactions[currentTransactionIndex] = updatedTransaction;

    account.balance = account.balance - account.transactions[currentTransactionIndex - 1].amount + amount;

    renderAccounts();
    updateStatistics();
    openAccountStatement(currentAccountId);

    document.getElementById('newDepositForm').reset();
    document.getElementById('newDepositForm').onsubmit = function(e) {
        e.preventDefault();
        addNewDeposit();
    };
}

// تنسيق المبلغ كعملة
function formatCurrency(amount) {
    return amount.toLocaleString('ar-SA', {
        style: 'currency',
        currency: 'SAR'
    });
}

// تنسيق التاريخ
function formatDate(dateString) {
    const date = new Date(dateString);
    return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;
}

// تعديل حساب بنكي
function editAccount(accountId) {
    const account = accounts.find(acc => acc.id === accountId);
    if (!account) return;

    document.getElementById('bankName').value = account.bankName;
    document.getElementById('accountNumber').value = account.accountNumber;
    document.getElementById('initialBalance').value = account.balance;

    const modalTitle = document.querySelector('#addAccountModal h2');
    modalTitle.textContent = 'تعديل حساب بنكي';

    const form = document.getElementById('newAccountForm');
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.textContent = 'تحديث';

    form.dataset.editId = accountId;

    form.onsubmit = function(e) {
        e.preventDefault();
        updateAccount(accountId);
    };

    document.getElementById('addAccountModal').style.display = 'block';
}

// تحديث بيانات الحساب
function updateAccount(accountId) {
    const accountIndex = accounts.findIndex(acc => acc.id === accountId);
    if (accountIndex === -1) return;

    const bankName = document.getElementById('bankName').value;
    const accountNumber = document.getElementById('accountNumber').value;
    const newBalance = parseFloat(document.getElementById('initialBalance').value);

    const oldBalance = accounts[accountIndex].balance;
    const balanceDifference = newBalance - oldBalance;

    accounts[accountIndex].bankName = bankName;
    accounts[accountIndex].accountNumber = accountNumber;
    accounts[accountIndex].balance = newBalance;

    if (balanceDifference !== 0) {
        const today = new Date().toISOString().split('T')[0];
        accounts[accountIndex].transactions.push({
            date: today,
            type: 'تعديل رصيد',
            amount: balanceDifference,
            description: `تعديل الرصيد من ${formatCurrency(oldBalance)} إلى ${formatCurrency(newBalance)}`
        });
    }

    renderAccounts();
    updateStatistics();

    document.getElementById('newAccountForm').reset();
    document.getElementById('newAccountForm').onsubmit = function(e) {
        e.preventDefault();
        addNewAccount();
    };

    const modalTitle = document.querySelector('#addAccountModal h2');
    modalTitle.textContent = 'إضافة حساب بنكي جديد';

    const submitButton = document.getElementById('newAccountForm').querySelector('button[type="submit"]');
    submitButton.textContent = 'إضافة';

    document.getElementById('addAccountModal').style.display = 'none';
}

// حذف حساب بنكي
function deleteAccount(accountId) {
    if (confirm('هل أنت متأكد من حذف هذا الحساب البنكي؟ سيتم حذف جميع المعاملات المرتبطة به.')) {
        accounts = accounts.filter(account => account.id !== accountId);
        renderAccounts();
        updateStatistics();
    }
}

// طباعة كشف الحساب
function printAccountStatement() {
    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    const printWindow = window.open('', '_blank', 'height=600,width=800');

    const styleContent = `
        <style>
            @media print {
                @page { size: A4; margin: 1cm; }
            }
            body {
                font-family: 'Tajawal', Arial, sans-serif;
                direction: rtl;
                padding: 20px;
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
            }
            .account-info {
                margin-bottom: 20px;
                border-bottom: 1px solid #ddd;
                padding-bottom: 10px;
            }
            .account-detail {
                margin-bottom: 5px;
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
                background-color: #f2f2f2;
            }
            .amount-positive {
                color: green;
            }
            .amount-negative {
                color: red;
            }
            .print-footer {
                margin-top: 30px;
                text-align: center;
                font-size: 0.8em;
                color: #666;
            }
        </style>
    `;

    const currentDate = new Date();
    const formattedDate = `${currentDate.getDate()}/${currentDate.getMonth() + 1}/${currentDate.getFullYear()}`;

    const sortedTransactions = [...account.transactions].sort((a, b) =>
        new Date(b.date) - new Date(a.date)
    );

    let transactionsHtml = '';
    sortedTransactions.forEach(transaction => {
        const dateObj = new Date(transaction.date);
        const formattedTransDate = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;
        const amountClass = transaction.amount >= 0 ? 'amount-positive' : 'amount-negative';

        transactionsHtml += `
            <tr>
                <td>${formattedTransDate}</td>
                <td>${transaction.type}</td>
                <td class="${amountClass}">${formatCurrency(transaction.amount)}</td>
                <td>${transaction.description}</td>
            </tr>
        `;
    });

    const printContent = `
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>كشف حساب - ${account.bankName}</title>
            ${styleContent}
        </head>
        <body>
            <h1>كشف حساب - ${account.bankName}</h1>

            <div class="account-info">
                <div class="account-detail"><strong>اسم البنك:</strong> ${account.bankName}</div>
                <div class="account-detail"><strong>رقم الحساب:</strong> ${account.accountNumber}</div>
                <div class="account-detail"><strong>الرصيد الحالي:</strong> ${formatCurrency(account.balance)}</div>
                <div class="account-detail"><strong>تاريخ الطباعة:</strong> ${formattedDate}</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>الوصف</th>
                    </tr>
                </thead>
                <tbody>
                    ${transactionsHtml}
                </tbody>
            </table>

            <div class="print-footer">
                تم إصدار هذا الكشف بتاريخ ${formattedDate} - نظام إدارة الحسابات البنكية
            </div>
        </body>
        </html>
    `;

    printWindow.document.write(printContent);
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.print();
    };
}

// تصدير كشف الحساب
function exportAccountStatement() {
    const account = accounts.find(acc => acc.id === currentAccountId);
    if (!account) return;

    const sortedTransactions = [...account.transactions].sort((a, b) =>
        new Date(b.date) - new Date(a.date)
    );

    let csvContent = "التاريخ,النوع,المبلغ,الوصف\n";

    sortedTransactions.forEach(transaction => {
        const dateObj = new Date(transaction.date);
        const formattedDate = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;

        const cleanDescription = transaction.description.replace(/,/g, ";");

        csvContent += `${formattedDate},${transaction.type},${transaction.amount},${cleanDescription}\n`;
    });

    const encodedUri = encodeURI("data:text/csv;charset=utf-8," + csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `كشف_حساب_${account.bankName}_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);
}

// تعديل صندوق
function editFund(fundId) {
    const fund = funds.find(f => f.id === fundId);
    if (!fund) return;

    document.getElementById('fundName').value = fund.name;
    document.getElementById('fundCashier').value = fund.cashier;
    document.getElementById('fundLocation').value = fund.location;
    document.getElementById('fundBalance').value = fund.balance;

    const modalTitle = document.querySelector('#addFundModal h2');
    modalTitle.textContent = 'تعديل صندوق';

    const form = document.getElementById('newFundForm');
    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.textContent = 'تحديث';

    form.dataset.editId = fundId;

    form.onsubmit = function(e) {
        e.preventDefault();
        updateFund(fundId);
    };

    document.getElementById('addFundModal').style.display = 'block';
}

// تحديث بيانات الصندوق
function updateFund(fundId) {
    const fundIndex = funds.findIndex(f => f.id === fundId);
    if (fundIndex === -1) return;

    const name = document.getElementById('fundName').value;
    const cashier = document.getElementById('fundCashier').value;
    const location = document.getElementById('fundLocation').value;
    const newBalance = parseFloat(document.getElementById('fundBalance').value);

    const oldBalance = funds[fundIndex].balance;
    const balanceDifference = newBalance - oldBalance;

    funds[fundIndex].name = name;
    funds[fundIndex].cashier = cashier;
    funds[fundIndex].location = location;
    funds[fundIndex].balance = newBalance;

    if (balanceDifference !== 0) {
        const today = new Date().toISOString().split('T')[0];
        funds[fundIndex].transactions.push({
            date: today,
            type: 'تعديل رصيد',
            amount: balanceDifference,
            description: `تعديل الرصيد من ${formatCurrency(oldBalance)} إلى ${formatCurrency(newBalance)}`
        });
    }

    renderFunds();
    renderFundsStatistics();

    document.getElementById('newFundForm').reset();
    document.getElementById('newFundForm').onsubmit = function(e) {
        e.preventDefault();
        addNewFund();
    };

    const modalTitle = document.querySelector('#addFundModal h2');
    modalTitle.textContent = 'إضافة صندوق جديد';

    const submitButton = document.getElementById('newFundForm').querySelector('button[type="submit"]');
    submitButton.textContent = 'إضافة';

    document.getElementById('addFundModal').style.display = 'none';
}

// حذف صندوق
function deleteFund(fundId) {
    if (confirm('هل أنت متأكد من حذف هذا الصندوق؟ سيتم حذف جميع المعاملات المرتبطة به.')) {
        funds = funds.filter(fund => fund.id !== fundId);
        renderFunds();
        renderFundsStatistics();
    }
}

// إضافة صندوق جديد
function addNewFund() {
    const name = document.getElementById('fundName').value;
    const cashier = document.getElementById('fundCashier').value;
    const location = document.getElementById('fundLocation').value;
    const initialBalance = parseFloat(document.getElementById('fundBalance').value);

    const newFund = {
        id: funds.length > 0 ? Math.max(...funds.map(f => f.id)) + 1 : 1,
        name: name,
        cashier: cashier,
        location: location,
        balance: initialBalance,
        transactions: []
    };

    funds.push(newFund);
    renderFunds();
    renderFundsStatistics();

    document.getElementById('addFundModal').style.display = 'none';
    document.getElementById('newFundForm').reset();
}

// إضافة سند قبض
function addIncome(fundId) {
    const fund = funds.find(f => f.id === fundId);
    if (!fund) return;

    document.getElementById('newIncomeForm').dataset.fundId = fundId;

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('incomeDate').value = today;

    const incomeCount = fund.transactions.filter(t => t.type === 'إيداع').length;
    document.getElementById('incomeReceiptNumber').value = `INC-${fundId}-${incomeCount + 1}`;

    document.getElementById('incomeAmount').value = '';
    document.getElementById('incomeDescription').value = '';

    document.getElementById('addIncomeModal').style.display = 'block';
}

// إضافة سند صرف
function addExpense(fundId) {
    const fund = funds.find(f => f.id === fundId);
    if (!fund) return;

    document.getElementById('newExpenseForm').dataset.fundId = fundId;

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('expenseDate').value = today;

    const expenseCount = fund.transactions.filter(t => t.type === 'صرف').length;
    document.getElementById('expenseVoucherNumber').value = `EXP-${fundId}-${expenseCount + 1}`;

    document.getElementById('expenseAmount').value = '';
    document.getElementById('expenseDescription').value = '';

    document.getElementById('addExpenseModal').style.display = 'block';
}

// إضافة معاملة جديدة إلى صندوق
function addNewIncome() {
    const fundId = parseInt(document.getElementById('newIncomeForm').dataset.fundId);
    const fund = funds.find(f => f.id === fundId);
    if (!fund) return;

    const receiptNumber = document.getElementById('incomeReceiptNumber').value;
    const incomeDate = document.getElementById('incomeDate').value;
    const amount = parseFloat(document.getElementById('incomeAmount').value);
    const description = document.getElementById('incomeDescription').value;

    const transaction = {
        date: incomeDate,
        type: 'إيداع',
        amount: amount,
        description: `إيداع: ${description} (رقم السند: ${receiptNumber})`,
        receiptNumber: receiptNumber
    };

    fund.transactions.push(transaction);
    fund.balance += amount;

    renderFunds();
    renderFundsStatistics();

    document.getElementById('addIncomeModal').style.display = 'none';
}

// إضافة معاملة جديدة إلى صندوق
function addNewExpense() {
    const fundId = parseInt(document.getElementById('newExpenseForm').dataset.fundId);
    const fund = funds.find(f => f.id === fundId);
    if (!fund) return;

    const voucherNumber = document.getElementById('expenseVoucherNumber').value;
    const expenseDate = document.getElementById('expenseDate').value;
    const amount = parseFloat(document.getElementById('expenseAmount').value);
    const description = document.getElementById('expenseDescription').value;

    const transaction = {
        date: expenseDate,
        type: 'صرف',
        amount: -amount,
        description: `صرف: ${description} (رقم السند: ${voucherNumber})`,
        voucherNumber: voucherNumber
    };

    fund.transactions.push(transaction);
    fund.balance -= amount;

    renderFunds();
    renderFundsStatistics();

    document.getElementById('addExpenseModal').style.display = 'none';
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

// فتح نافذة سند قبض عام
function openGeneralReceiptModal() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('generalReceiptDate').value = today;

    const receiptCount = vouchers.filter(v => v.type === 'قبض').length;
    document.getElementById('generalReceiptNumber').value = `REC-${String(receiptCount + 1).padStart(3, '0')}`;

    document.getElementById('receiptFromName').value = '';
    document.getElementById('generalReceiptAmount').value = '';
    document.getElementById('generalReceiptDescription').value = '';

    document.getElementById('generalReceiptModal').style.display = 'block';
}

// فتح نافذة سند صرف عام
function openGeneralPaymentModal() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('generalPaymentDate').value = today;

    const paymentCount = vouchers.filter(v => v.type === 'صرف').length;
    document.getElementById('generalPaymentNumber').value = `PAY-${String(paymentCount + 1).padStart(3, '0')}`;

    document.getElementById('paidToName').value = '';
    document.getElementById('generalPaymentAmount').value = '';
    document.getElementById('generalPaymentDescription').value = '';

    document.getElementById('generalPaymentModal').style.display = 'block';
}

// إضافة سند قبض عام
function addGeneralReceipt() {
    const number = document.getElementById('generalReceiptNumber').value;
    const date = document.getElementById('generalReceiptDate').value;
    const fromName = document.getElementById('receiptFromName').value;
    const amount = parseFloat(document.getElementById('generalReceiptAmount').value);
    const paymentMethod = document.getElementById('paymentMethod').value;
    const description = document.getElementById('generalReceiptDescription').value;

    const newVoucher = {
        id: vouchers.length > 0 ? Math.max(...vouchers.map(v => v.id)) + 1 : 1,
        number: number,
        type: 'قبض',
        date: date,
        amount: amount,
        fromTo: fromName,
        description: description,
        paymentMethod: paymentMethod
    };

    vouchers.push(newVoucher);

    if (currentView === 'vouchers') {
        renderVouchers();
        // renderVouchersStatistics();
    }

    document.getElementById('generalReceiptModal').style.display = 'none';
    document.getElementById('generalReceiptForm').reset();
}

// إضافة سند صرف عام
function addGeneralPayment() {
    const number = document.getElementById('generalPaymentNumber').value;
    const date = document.getElementById('generalPaymentDate').value;
    const toName = document.getElementById('paidToName').value;
    const amount = parseFloat(document.getElementById('generalPaymentAmount').value);
    const paymentMethod = document.getElementById('paymentMethodOut').value;
    const description = document.getElementById('generalPaymentDescription').value;

    const newVoucher = {
        id: vouchers.length > 0 ? Math.max(...vouchers.map(v => v.id)) + 1 : 1,
        number: number,
        type: 'صرف',
        date: date,
        amount: amount,
        fromTo: toName,
        description: description,
        paymentMethod: paymentMethod
    };

    vouchers.push(newVoucher);

    if (currentView === 'vouchers') {
        renderVouchers();
        // renderVouchersStatistics();
    }

    document.getElementById('generalPaymentModal').style.display = 'none';
    document.getElementById('generalPaymentForm').reset();
}
