@section('css')
    <link rel="stylesheet" href="{{asset('css/bank-managment.css')}}">

@endsection
@extends('financialaccounting.layouts.master')
@section('content')
    <div id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;"></div>


            <div style="display: block"  class="container" id="banksContainer">
                <h1>نظام إدارة الحسابات البنكية</h1>

                <div class="statistics-dashboard" id="statisticsDashboard">
                    <!-- سيتم إضافة الإحصائيات هنا عن طريق JavaScript -->
                </div>

                <div class="bank-accounts" id="bankAccounts">
                    <!-- سيتم إضافة الحسابات البنكية هنا عن طريق JavaScript -->
                </div>

                <div class="add-account">
                    <button id="addAccountBtn"><i class="fas fa-plus"></i> إضافة حساب بنكي جديد</button>
                </div>
            </div>



            <!-- قسم السندات -->
            <div  class="container" id="vouchersContainer" style="display: none;">
                <h1>نظام إدارة السندات</h1>

                <div class="voucher-buttons">
                    <button class="voucher-btn" id="generalReceiptBtn">
                        <i class="fas fa-plus-circle"></i>
                        <span>سند قبض عام</span>
                    </button>
                    <button class="voucher-btn" id="generalPaymentBtn">
                        <i class="fas fa-minus-circle"></i>
                        <span>سند صرف عام</span>
                    </button>
                </div>

                <div class="search-filter-container">
                    <div class="search-box">
                        <input type="text" id="voucherSearchInput" placeholder="بحث في السندات...">
                        <button id="voucherSearchBtn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="filter-options">
                        <select id="voucherTypeFilter">
                            <option value="all">جميع السندات</option>
                            <option value="قبض">سندات القبض</option>
                            <option value="صرف">سندات الصرف</option>
                        </select>
                        <input type="date" id="voucherDateFilter">
                        <button id="resetFiltersBtn"><i class="fas fa-undo"></i> إعادة ضبط</button>
                    </div>
                </div>

                <div class="vouchers-list" id="vouchersList">
                    <!-- سيتم إضافة قائمة السندات هنا عن طريق JavaScript -->
                </div>
            </div>

            <!-- قسم الفواتير -->
            <div class="container" id="billsContainer" style="display: none;">
                <h1>نظام إدارة الفواتير</h1>

                <div class="bills-buttons" id="billsButtons">
                    <button class="bill-btn" id="salesInvoiceBtn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>فاتورة مبيعات</span>
                    </button>
                    <button class="bill-btn" id="purchaseInvoiceBtn">
                        <i class="fas fa-shopping-basket"></i>
                        <span>فاتورة مشتريات</span>
                    </button>
                </div>

                <div class="search-filter-container" id="billsSearchContainer">
                    <div class="search-box">
                        <input type="text" id="billSearchInput" placeholder="بحث في الفواتير...">
                        <button id="billSearchBtn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="filter-options">
                        <select id="billTypeFilter">
                            <option value="all">جميع الفواتير</option>
                            <option value="مبيعات">فواتير المبيعات</option>
                            <option value="مشتريات">فواتير المشتريات</option>
                        </select>
                        <input type="date" id="billDateFilter">
                        <button id="resetBillFiltersBtn"><i class="fas fa-undo"></i> إعادة ضبط</button>
                    </div>
                </div>

                <div class="bills-list" id="billsList">
                    <!-- سيتم إضافة قائمة الفواتير هنا عن طريق JavaScript -->
                </div>
            </div>
        </div>
    {{-- </div> --}}

    <!-- نافذة منبثقة لكشف الحساب -->
    <div class="modal" id="accountStatementModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="accountTitle">كشف حساب</h2>
            <div class="statement-buttons">
                <button id="addTransferBtn"><i class="fas fa-exchange-alt"></i> إضافة سند تحويل</button>
                <button id="addCustodyBtn"><i class="fas fa-hand-holding-usd"></i> إضافة سند عهدة</button>
                <button id="printStatementBtn"><i class="fas fa-print"></i> طباعة الكشف</button>
                <button id="exportStatementBtn"><i class="fas fa-file-export"></i> تصدير الكشف</button>
            </div>
            <div class="statement-table-container">
                <table class="statement-table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>النوع</th>
                            <th>المبلغ</th>
                            <th>الوصف</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="statementTableBody">
                        <!-- سيتم إضافة الحركات المالية هنا -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- نافذة منبثقة لإضافة حساب جديد -->
    <div class="modal" id="addAccountModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>إضافة حساب بنكي جديد</h2>
            <form id="newAccountForm">
                <div class="form-group">
                    <label for="bankName">اسم البنك:</label>
                    <input type="text" id="bankName" required>
                </div>
                <div class="form-group">
                    <label for="accountNumber">رقم الحساب:</label>
                    <input type="text" id="accountNumber" required>
                </div>
                <div class="form-group">
                    <label for="initialBalance">الرصيد الافتتاحي:</label>
                    <input type="number" id="initialBalance" required>
                </div>
                <button type="submit">إضافة</button>
            </form>
        </div>
    </div>

    <!-- نافذة منبثقة لتعديل الحساب -->
    <div class="modal" id="editAccountModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>تعديل الحساب البنكي</h2>
            <form id="editAccountForm">
                <div class="form-group">
                    <label for="editBankName">اسم البنك:</label>
                    <input type="text" id="editBankName" required>
                </div>
                <div class="form-group">
                    <label for="editAccountNumber">رقم الحساب:</label>
                    <input type="text" id="editAccountNumber" required>
                </div>
                <div class="form-group">
                    <label for="editInitialBalance">الرصيد الافتتاحي:</label>
                    <input type="number" id="editInitialBalance" required>
                </div>
                <button type="submit">تحديث</button>
            </form>
        </div>
    </div>

    <!-- نافذة منبثقة لإضافة سند تحويل/عهدة -->
    <div class="modal" id="addTransactionModal">
        <div class="modal-content transaction-modal">
            <span class="close">&times;</span>
            <h2 id="transactionTitle">إضافة سند</h2>
            <div class="transaction-voucher">
                <div class="voucher-header">
                    <div class="voucher-logo">
                        <i class="fas fa-exchange-alt" id="transactionIcon"></i>
                    </div>
                    <div class="voucher-title" id="voucherTypeTitle">سند تحويل بنكي</div>
                </div>
                <form id="newTransactionForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="transactionVoucherNumber">رقم السند:</label>
                            <input type="text" id="transactionVoucherNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="transactionDate">التاريخ:</label>
                            <input type="date" id="transactionDate" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="beneficiaryName">اسم المستفيد:</label>
                        <input type="text" id="beneficiaryName" required>
                    </div>
                    <div class="form-group">
                        <label for="transactionAmount">المبلغ:</label>
                        <input type="number" id="transactionAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="transactionDescription">الوصف:</label>
                        <textarea id="transactionDescription" required></textarea>
                    </div>
                    <input type="hidden" id="transactionType">
                    <button type="submit" class="save-btn"><i class="fas fa-save"></i> حفظ</button>
                </form>
            </div>
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

@endsection

@section('js')
    <script src="{{asset('js/bank-managment.js')}}"></script>
    {{-- <script src="{{asset('main-js/script.js')}}"></script> --}}
    <script>
        document.getElementById('newAccountForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const bankName = document.getElementById('bankName').value;
            const accountNumber = document.getElementById('accountNumber').value;
            const initialBalance = document.getElementById('initialBalance').value;

            fetch('/accounts/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // ضروري
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    bankName: bankName,
                    accountNumber: accountNumber,
                    balance: initialBalance,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.errors) {
                    showToast('تم إضافة الحساب بنجاح');
                    document.getElementById('newAccountForm').reset();
                    fetchAccounts();

                    // إغلاق المودال بعد إضافة الحساب بنجاح
                    document.getElementById('addAccountModal').style.display = 'none';

                } else {
                    showToast('حدث خطأ: تحقق من البيانات', '#d00000');
                    console.log(data.errors);
                }
            })
            .catch(error => {
                console.error(error);
                showToast('فشل في إرسال البيانات', '#d00000');
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

        document.addEventListener('DOMContentLoaded', function() {
            // renderStatistics();
            // renderAccounts();
            // setupEventListeners();
            setupPreviewActions();
            // setupSidebar();
        });


        // دالة لفتح المودال وملء البيانات
        function editAccount(accountId) {
            // إرسال طلب لاسترجاع بيانات الحساب
            fetch(`/accounts/${accountId}`)
                .then(response => response.json())
                .then(data => {
                    // ملء الحقول بالقيم الموجودة في الحساب
                    document.getElementById('editBankName').value = data.bankName;
                    document.getElementById('editAccountNumber').value = data.accountNumber;
                    document.getElementById('editInitialBalance').value = data.balance;

                    // إظهار المودال
                    document.getElementById('editAccountModal').style.display = 'block';

                    // إضافة حدث لإرسال التعديلات
                    document.getElementById('editAccountForm').onsubmit = function(e) {
                        e.preventDefault();
                        updateAccount(accountId);
                    };
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('فشل في تحميل بيانات الحساب', '#d00000');
                });
        }


        // دالة لتحديث الحساب
        function updateAccount(accountId) {
            const bankName = document.getElementById('editBankName').value;
            const accountNumber = document.getElementById('editAccountNumber').value;
            const initialBalance = document.getElementById('editInitialBalance').value;

            fetch(`/accounts/${accountId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    bankName: bankName,
                    accountNumber: accountNumber,
                    balance: initialBalance,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    showToast('تم تحديث الحساب بنجاح');
                    fetchAccounts(); // تحديث الحسابات في واجهة المستخدم
                    // إغلاق المودال بعد إضافة الحساب بنجاح
                    document.getElementById('editAccountModal').style.display = 'none';
                } else {
                    showToast('حدث خطأ في التحديث', '#d00000');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('فشل في إرسال البيانات', '#d00000');
            });
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

@endsection
