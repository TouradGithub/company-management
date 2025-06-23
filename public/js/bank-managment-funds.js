
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
            console.error("حدث خطأ أثناء جلب الحسابات:", error);
        });
}

// php artisan make:model Transaction_Acount -m

fetchFunds();


// تهيئة التطبيق
document.addEventListener('DOMContentLoaded', function() {
    // renderStatistics();
    setupEventListeners();
    // setupPreviewActions();
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
        // switchView('funds');
      });



    document.getElementById('vouchersViewBtn').addEventListener('click', function() {
        // switchView('vouchers');
        window.location.href = '/vouchers';

    });

    document.getElementById('billsViewBtn').addEventListener('click', function() {
        window.location.href = '/bills';
    });
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


        renderFundsStatistics();

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
                <button class="action-btn add-income" id="addIncome" data-id="${fund.id}">
                    <i class="fas fa-plus-circle"></i> سند قبض
                </button>
                <button class="action-btn add-expense" id="addExpense" data-id="${fund.id}">
                    <i class="fas fa-minus-circle"></i> سند صرف
                </button>
            </div>
        `;
        // اربط الأحداث بعد ما تضيف الكروت
        const incomeBtn = fundCard.querySelector('.add-income');
        const expenseBtn = fundCard.querySelector('.add-expense');

        incomeBtn.addEventListener('click', function () {
            document.getElementById('addIncomeModal').style.display = 'block';
            // ممكن تحفظ معرف الصندوق داخل المودال باستخدام data-* لو حبيت ترسله
            document.getElementById('newIncomeForm').dataset.fundId = fund.id;
        });

        expenseBtn.addEventListener('click', function () {
            document.getElementById('addExpenseModal').style.display = 'block';
            document.getElementById('newExpenseForm').dataset.fundId = fund.id;
        });
        fundsContainer.appendChild(fundCard);
    });

}



// إعداد مستمعات الأحداث
function setupEventListeners() {





    document.getElementById('fundsList').addEventListener('click', function(e) {
        if (e.target.closest('.edit-fund')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.edit-fund').dataset.id);
            editFund(accountId);
        } else if (e.target.closest('.delete-fund')) {
            e.stopPropagation();
            const accountId = parseInt(e.target.closest('.delete-fund').dataset.id);
            deleteFund(accountId);
        }
    });





    document.getElementById('addFundBtn').addEventListener('click', function() {
        // console.log('ok')
        document.getElementById('addFundModal').style.display = 'block';
    });

    // إضافة حساب جديد
    // document.getElementById('newAccountForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
    //     // addNewAccount();
    // });



    // إضافة سند إيداع جديد
    // document.getElementById('newDepositForm').addEventListener('submit', function(e) {
    //     e.preventDefault();
    //     openTransactionModal('إيداع', currentAccountId);
    // });

    // أزرار كشف الحساب
    // document.getElementById('printStatementBtn').addEventListener('click', function() {
        // printAccountStatement();
    // });

    // document.getElementById('exportStatementBtn').addEventListener('click', function() {
    //     // exportAccountStatement();
    // });


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
