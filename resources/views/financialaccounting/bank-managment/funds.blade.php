@section('css')
    <link rel="stylesheet" href="{{asset('css/bank-managment.css')}}">
@endsection
@extends('financialaccounting.layouts.master')
@section('content')
    <div id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;"></div>

    <!-- قسم الصناديق -->
    <div style="display: block" class="container" id="fundsContainer" >
        <h1>نظام إدارة الصناديق</h1>

        <div class="statistics-dashboard" id="fundsStatisticsDashboard">
            <!-- سيتم إضافة إحصائيات الصناديق هنا عن طريق JavaScript -->
        </div>

        <div class="bank-accounts" id="fundsList">
            <!-- سيتم إضافة الصناديق هنا عن طريق JavaScript -->
        </div>

        <div class="add-account">
            <button id="addFundBtn"><i class="fas fa-plus"></i> إضافة صندوق جديد</button>
        </div>
    </div>


    <!-- نافذة منبثقة لتعديل الصبدوق -->
    <div class="modal" id="editFundModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>تعديل الحساب البنكي</h2>
            <form id="editFundForm">
                <div class="form-group">
                    <label for="name">اسم الصندوق:</label>
                    <input type="text" id="name" required>
                </div>
                <div class="form-group">
                    <label for="cashier">مدير الصندوق:</label>
                    <input type="text" id="cashier" required>
                </div>
                <div class="form-group">
                    <label for="location">الموقع:</label>
                    <input type="text" id="location" required>
                </div>
                <div class="form-group">
                    <label for="editInitialBalance">الرصيد الافتتاحي:</label>
                    <input type="number" id="editInitialBalance" required>
                </div>
                <button type="submit">تحديث</button>
            </form>
        </div>
    </div>


    <!-- نافذة منبثقة لمعاينة المستند -->
    <div class="modal" id="previewDocumentModal">
        <div class="modal-content preview-modal">
            <span class="close">&times;</span>
            <div class="preview-actions">
                <button id="printDocumentBtn"><i class="fas fa-print"></i> طباعة</button>
                <button id="editDocumentBtn"><i class="fas fa-edit"></i> تعديل</button>
                <button id="deleteDocumentBtn"><i class="fas fa-trash-alt"></i> حذف</button>
            </div>
            <div id="printableDocument">
                <div class="document-preview" id="documentPreview">
                    <!-- محتوى المستند للمعاينة -->
                </div>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لإضافة صندوق جديد -->
    <div class="modal" id="addFundModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>إضافة صندوق جديد</h2>
            <form id="newFundForm">
                <div class="form-group">
                    <label for="fundName">اسم الصندوق:</label>
                    <input type="text" id="fundName" required>
                </div>
                <div class="form-group">
                    <label for="fundCashier">أمين الصندوق:</label>
                    <input type="text" id="fundCashier" required>
                </div>
                <div class="form-group">
                    <label for="fundLocation">الموقع:</label>
                    <input type="text" id="fundLocation" required>
                </div>
                <div class="form-group">
                    <label for="fundBalance">الرصيد الافتتاحي:</label>
                    <input type="number" id="fundBalance" required>
                </div>
                <button type="submit">إضافة</button>
            </form>
        </div>
    </div>

    <!-- نافذة منبثقة لإضافة سند قبض (إيداع) -->
    <div class="modal" id="addIncomeModal">
        <div class="modal-content deposit-modal">
            <span class="close">&times;</span>
            <h2>إضافة سند قبض</h2>
            <div class="deposit-receipt">
                <div class="receipt-header">
                    <div class="receipt-logo">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="receipt-title">سند قبض</div>
                </div>
                <form id="newIncomeForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="incomeReceiptNumber">رقم السند:</label>
                            <input type="text" id="incomeReceiptNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="incomeDate">التاريخ:</label>
                            <input type="date" id="incomeDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="incomeAmount">المبلغ:</label>
                        <input type="number" id="incomeAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="incomeDescription">الوصف:</label>
                        <textarea id="incomeDescription" required></textarea>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لإضافة سند صرف -->
    <div class="modal" id="addExpenseModal">
        <div class="modal-content transaction-modal">
            <span class="close">&times;</span>
            <h2>إضافة سند صرف</h2>
            <div class="transaction-voucher">
                <div class="voucher-header">
                    <div class="voucher-logo">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="voucher-title">سند صرف</div>
                </div>
                <form id="newExpenseForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expenseVoucherNumber">رقم السند:</label>
                            <input type="text" id="expenseVoucherNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="expenseDate">التاريخ:</label>
                            <input type="date" id="expenseDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expenseAmount">المبلغ:</label>
                        <input type="number" id="expenseAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="expenseDescription">الوصف:</label>
                        <textarea id="expenseDescription" required></textarea>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لسند قبض عام -->
    <div class="modal" id="generalReceiptModal">
        <div class="modal-content deposit-modal">
            <span class="close">&times;</span>
            <h2>سند قبض عام</h2>
            <div class="deposit-receipt">
                <div class="receipt-header">
                    <div class="receipt-logo">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="receipt-title">سند قبض عام</div>
                </div>
                <form id="generalReceiptForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="generalReceiptNumber">رقم السند:</label>
                            <input type="text" id="generalReceiptNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="generalReceiptDate">التاريخ:</label>
                            <input type="date" id="generalReceiptDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="receiptFromName">استلمنا من:</label>
                        <input type="text" id="receiptFromName" required>
                    </div>
                    <div class="form-group">
                        <label for="generalReceiptAmount">المبلغ:</label>
                        <input type="number" id="generalReceiptAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethod">طريقة الدفع:</label>
                        <select id="paymentMethod" required>
                            <option value="نقدي">نقدي</option>
                            <option value="شيك">شيك</option>
                            <option value="تحويل بنكي">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="generalReceiptDescription">البيان:</label>
                        <textarea id="generalReceiptDescription" required></textarea>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لسند صرف عام -->
    <div class="modal" id="generalPaymentModal">
        <div class="modal-content transaction-modal">
            <span class="close">&times;</span>
            <h2>سند صرف عام</h2>
            <div class="transaction-voucher">
                <div class="voucher-header">
                    <div class="voucher-logo">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="voucher-title">سند صرف عام</div>
                </div>
                <form id="generalPaymentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="generalPaymentNumber">رقم السند:</label>
                            <input type="text" id="generalPaymentNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="generalPaymentDate">التاريخ:</label>
                            <input type="date" id="generalPaymentDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paidToName">صرفنا إلى:</label>
                        <input type="text" id="paidToName" required>
                    </div>
                    <div class="form-group">
                        <label for="generalPaymentAmount">المبلغ:</label>
                        <input type="number" id="generalPaymentAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="paymentMethodOut">طريقة الدفع:</label>
                        <select id="paymentMethodOut" required>
                            <option value="نقدي">نقدي</option>
                            <option value="شيك">شيك</option>
                            <option value="تحويل بنكي">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="generalPaymentDescription">البيان:</label>
                        <textarea id="generalPaymentDescription" required></textarea>
                    </div>
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('js/bank-managment-funds.js')}}"></script>

    <script>

        function showToast(message, color = '#38b000') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.backgroundColor = color;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }



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
    </script>

    <script>
        document.getElementById('newFundForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const fundName = document.getElementById('fundName').value;
            const fundCashier = document.getElementById('fundCashier').value;
            const fundLocation = document.getElementById('fundLocation').value;
            const fundBalance = document.getElementById('fundBalance').value;

            fetch('/funds/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    name: fundName,
                    cashier: fundCashier,
                    location: fundLocation,
                    balance: fundBalance,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.errors) {
                    showToast('تم إضافة الحساب بنجاح');
                    document.getElementById('newFundForm').reset();
                    fetchFunds();

                    // إغلاق المودال بعد إضافة الحساب بنجاح
                    document.getElementById('addFundModal').style.display = 'none';

                } else {
                    showToast('حدث خطأ: تحقق من البيانات', '#d00000');
                    console.log(data.errors);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('فشل في إرسال البيانات', '#d00000');
            });
        });


        // دالة لفتح المودال وملء البيانات
        function editFund(fundId) {
            // إرسال طلب لاسترجاع بيانات الحساب
            fetch(`/funds/${fundId}`)
                .then(response => response.json())
                .then(data => {
                    // ملء الحقول بالقيم الموجودة في الحساب
                    document.getElementById('name').value = data.name;
                    document.getElementById('cashier').value = data.cashier;
                    document.getElementById('location').value = data.location;
                    document.getElementById('editInitialBalance').value = data.balance;

                    // إظهار المودال
                    document.getElementById('editFundModal').style.display = 'block';

                    // إضافة حدث لإرسال التعديلات
                    document.getElementById('editFundForm').onsubmit = function(e) {
                        e.preventDefault();
                        updateFund(fundId);
                    };
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('فشل في تحميل بيانات الحساب', '#d00000');
                });
        }


        // دالة لتحديث الحساب
        function updateFund(fundId) {
            const name = document.getElementById('name').value;
            const cashier = document.getElementById('cashier').value;
            const location = document.getElementById('location').value;
            const initialBalance = document.getElementById('editInitialBalance').value;

            fetch(`/funds/${fundId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    name: name,
                    cashier: cashier,
                    location: location,
                    balance: initialBalance,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    showToast('تم تحديث الصندوق بنجاح');
                    fetchFunds(); // تحديث الحسابات في واجهة المستخدم
                    // إغلاق المودال بعد إضافة الحساب بنجاح
                    document.getElementById('editFundModal').style.display = 'none';
                } else {
                    showToast('حدث خطأ في التحديث', '#d00000');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('فشل في إرسال البيانات', '#d00000');
            });
        }
        function deleteFund(fundId) {
            if (!confirm('هل أنت متأكد أنك تريد حذف هذا الصندوق')) return;

            fetch(`/funds/${fundId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showToast('تم حذف الصندوق بنجاح');
                    fetchFunds(); // تحديث قائمة الحسابات
                    // document.getElementById('editAccountModal').style.display = 'none'; // إغلاق المودال
                } else {
                    showToast('حدث خطأ أثناء الحذف', '#d00000');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('فشل في تنفيذ الحذف', '#d00000');
            });
        }
    </script>

    <script>

        // حفظ سند قبض
        document.getElementById('newIncomeForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fundId = this.dataset.fundId || document.getElementById('newIncomeForm').dataset.fundId;
            const data = {
                voucher_number: document.getElementById('incomeReceiptNumber').value,
                date: document.getElementById('incomeDate').value,
                amount: document.getElementById('incomeAmount').value,
                description: document.getElementById('incomeDescription').value,
                funds_id: fundId
            };

            fetch('/transactions/income', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                showToast(response.message);
                document.getElementById('newIncomeForm').reset();

                document.getElementById('addIncomeModal').style.display = 'none';
                fetchFunds();
            });
        });

        // حفظ سند صرف
        document.getElementById('newExpenseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const fundIdexpense = document.getElementById('newExpenseForm').dataset.fundId;
            const data = {
                voucher_number: document.getElementById('expenseVoucherNumber').value,
                date: document.getElementById('expenseDate').value,
                amount: document.getElementById('expenseAmount').value,
                description: document.getElementById('expenseDescription').value,
                funds_id: fundIdexpense
            };

            fetch('/transactions/expense', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json().then(data => ({ status: res.status, ok: res.ok, body: data })))
            .then(({ ok, body }) => {
                const color = ok ? '#38b000' : '#d00000'; // أخضر أو أحمر حسب الحالة
                showToast(body.message, color);

                document.getElementById('newExpenseForm').reset();
                document.getElementById('addExpenseModal').style.display = 'none';
                fetchFunds();
            })
            .catch(error => {
                showToast('حدث خطأ غير متوقع', '#d00000');
                console.error(error);
            });
        });

    </script>

@endsection
