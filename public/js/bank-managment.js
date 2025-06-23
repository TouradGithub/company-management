// بيانات الحسابات
let accounts = [];

function fetchAccounts() {
    fetch('/accounts/index')
        .then(res => res.json())
        .then(async data => {
            // تأكد أن البيانات مصفوفة
            accounts = Array.isArray(data) ? data : [];

            // جلب المعاملات لكل حساب
            for (let i = 0; i < accounts.length; i++) {
                const res = await fetch(`/accounts/${accounts[i].id}/transactions`);
                const fullData = await res.json();//accounts/{id}/transactions
                accounts[i].transactions = fullData.transactions || []; // ضمان وجود array
            }

            renderStatistics(accounts); // عرض البيانات في الواجهة
            renderAccounts(accounts); // عرض البيانات في الواجهة
        })
        .catch(error => {
            console.error("حدث خطأ أثناء جلب الحسابات:", error);
        });
}

// php artisan make:model Transaction_Acount -m

fetchAccounts();
// بيانات الصناديق
let funds = [];
function fetchFunds() {
    fetch('/funds/index')
        .then(res => res.json())
        .then(async data => {
            // تأكد أن البيانات مصفوفة
            funds = Array.isArray(data) ? data : [];

            // جلب المعاملات لكل حساب
            for (let i = 0; i < funds.length; i++) {
                const res = await fetch(`/funds/${funds[i].id}/fundtransactions`);
                const fullData = await res.json();//accounts/{id}/transactions
                funds[i].transactions = fullData.transactions || []; // ضمان وجود array
            }
            renderFunds(funds)
            renderFundsStatistics(funds)
            // renderAccounts(funds); // عرض البيانات في الواجهة
            // renderStatistics(funds); // عرض البيانات في الواجهة
        })
        .catch(error => {
            console.error("حدث خطأ أثناء جلب الحسابات:");
        });
}

// php artisan make:model Transaction_Acount -m

// fetchFunds();
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
    renderStatistics();
    renderAccounts();
    setupEventListeners();
    setupPreviewActions();
    // setupSidebar();
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

// التبديل بين شاشة البنوك وشاشة الصناديق وشاشة السندات وشاشة الفواتير
function switchView(view) {
    currentView = view;

    // إخفاء/إظهار العناصر المناسبة
    if (view === 'banks') {
        document.getElementById('banksContainer').style.display = 'block';
        document.getElementById('fundsContainer').style.display = 'none';
        document.getElementById('vouchersContainer').style.display = 'none';
        document.getElementById('billsContainer').style.display = 'none';
        document.getElementById('bankViewBtn').classList.add('active');
        document.getElementById('fundsViewBtn').classList.remove('active');
        document.getElementById('vouchersViewBtn').classList.remove('active');
        document.getElementById('billsViewBtn').classList.remove('active');
        renderStatistics();
        renderAccounts();
    } else if (view === 'funds') {
        document.getElementById('banksContainer').style.display = 'none';
        document.getElementById('fundsContainer').style.display = 'block';
        document.getElementById('vouchersContainer').style.display = 'none';
        document.getElementById('billsContainer').style.display = 'none';
        document.getElementById('bankViewBtn').classList.remove('active');
        document.getElementById('fundsViewBtn').classList.add('active');
        document.getElementById('vouchersViewBtn').classList.remove('active');
        document.getElementById('billsViewBtn').classList.remove('active');
        renderFundsStatistics();
        renderFunds();
    } else if (view === 'vouchers') {
        document.getElementById('banksContainer').style.display = 'none';
        document.getElementById('fundsContainer').style.display = 'none';
        document.getElementById('vouchersContainer').style.display = 'block';
        document.getElementById('billsContainer').style.display = 'none';
        document.getElementById('bankViewBtn').classList.remove('active');
        document.getElementById('fundsViewBtn').classList.remove('active');
        document.getElementById('vouchersViewBtn').classList.add('active');
        document.getElementById('billsViewBtn').classList.remove('active');
        renderVouchers();
    } else if (view === 'bills') {
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

// عرض إحصائيات البنوك
function renderStatistics() {
    const statisticsContainer = document.getElementById('statisticsDashboard');

    // تأكد من أن جميع الأرصدة أرقام
    const totalBalance = accounts.reduce((sum, account) => sum + Number(account.balance || 0), 0);

    // إيجاد الحساب صاحب أعلى رصيد
    let highestBalanceAccount = accounts[0];
    accounts.forEach(account => {
        if (Number(account.balance) > Number(highestBalanceAccount.balance)) {
            highestBalanceAccount = account;
        }
    });

    // عدد الحسابات
    const totalAccounts = accounts.length;

    // عدد المعاملات في كل الحسابات
    const totalTransactions = accounts.reduce((sum, account) => {
        return sum + (Array.isArray(account.transactions) ? account.transactions.length : 0);
    }, 0);


    if(highestBalanceAccount) {
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
    } else {
        return '';
    }
}

// عرض إحصائيات الصناديق
function renderFundsStatistics() {
    const statisticsContainer = document.getElementById('fundsStatisticsDashboard');
    // تأكد من أن جميع الأرصدة أرقام
    const totalBalance = funds.reduce((sum, fund) => sum + Number(fund.balance || 0), 0);

    // إيجاد الحساب صاحب أعلى رصيد
    let highestBalanceFund = funds[0];
    funds.forEach(fund => {
        if (Number(fund.balance) > Number(highestBalanceFund.balance)) {
            highestBalanceFund = fund;
        }
    });


    // عدد المعاملات في كل الحسابات
    const totalTransactions = funds.reduce((sum, fund) => {
        return sum + (Array.isArray(fund.transactions) ? fund.transactions.length : 0);
    }, 0);
    const totalFunds = funds.length;



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
                <button class="delete-account"  data-id="${account.id}"><i class="fas fa-trash-alt"></i></button>
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
    funds = funds;
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
    document.getElementById('editDocumentBtn').addEventListener('click', function() {
        document.getElementById('previewDocumentModal').style.display = 'none';
        // تنفيذ العملية بناء على نوع المستند الحالي
        if (currentTransaction) {
            editTransaction(currentTransaction);
        }
    });

    document.getElementById('deleteDocumentBtn').addEventListener('click', function() {
        if (confirm('هل أنت متأكد من حذف هذا المستند؟')) {
            document.getElementById('previewDocumentModal').style.display = 'none';
            // تنفيذ عملية الحذف بناء على نوع المستند الحالي
            if (currentTransaction) {
                deleteTransaction(currentTransaction);
            }
        }
    });

    document.getElementById('printDocumentBtn').addEventListener('click', function() {
        window.print();
    });
}

// إعداد مستمعات الأحداث
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
            openTransactionModal('إيداع', currentAccountId);
            // openTransactionModal
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
        // console.log('ok')
        document.getElementById('addAccountModal').style.display = 'block';
    });


    // إضافة معاملة جديدة
    document.getElementById('newTransactionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const data = {
            date: document.getElementById('transactionDate').value,
            type: document.getElementById('transactionType').value,
            amount: parseFloat(document.getElementById('transactionAmount').value),
            description: document.getElementById('transactionDescription').value,
            beneficiary_name: document.getElementById('beneficiaryName').value,
        };

        fetch(`/accounts/${currentAccountId}/transactions`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(data),
        })
        .then(async response => {
            const responseData = await response.json();

            if (!response.ok) {
                // في حالة وجود خطأ من السيرفر مثل الرصيد غير كافٍ
                showToast(responseData.message || 'حدث خطأ أثناء الإضافة', '#d00000');
                return;
            }
            // const accounts = responseData.accounts;
            const transaction = responseData.transaction;

            // نجاح العملية
            showToast('تمت إضافة السند بنجاح');
            // location.reload();
            fetchAccounts();
            // renderAccounts(accounts); // عرض البيانات في الواجهة
            // renderStatistics(accounts); // عرض البيانات في الواجهة
            // console.log(accounts)
            // openAccountStatement(currentAccountId)

        })
        .catch(error => {
            console.error(error);
            showToast('حدث خطأ غير متوقع أثناء الإضافة', '#d00000');
        });

    });





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

let isDeleting = false;

function deleteAccount(accountId) {
    if (isDeleting) return;
    if (!confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟')) return;

    isDeleting = true;

    fetch(`/accounts/${accountId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showToast('تم حذف الحساب بنجاح');
            fetchAccounts();
            document.getElementById('editAccountModal').style.display = 'none';
        } else {
            showToast('حدث خطأ أثناء الحذف', '#d00000');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('فشل في تنفيذ الحذف', '#d00000');
    })
    .finally(() => {
        isDeleting = false;
    });
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
