@extends('financialaccounting.layouts.master')

@section('css')
    <style>

        .container-tabs {
            width: 80%;
            margin-right: 0;
            margin-left: auto;
        }

        .row {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
        }

        .row div {
            flex: 1;
            min-width: 0;
        }

        .row button {
            width: 100%;
            padding: 10px 20px;
            color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .row .search-container button {
            width: 100%;
            padding: 10px 20px;
            color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }


        .row .search-input {
            width: 100%;
            padding: 10px 20px;
        }

        .row button:hover {
            background-color: #0056b3;
            color: white;
        }

        .row button.active {
            background-color: #007bff;
            color: white;
        }

        .no-data-message {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .child-level-1 {
            padding-right: 20px; /* مسافة بادئة للمستوى 2 */
        }

        .child-level-2 {
            padding-right: 40px; /* مسافة بادئة للمستوى 3 */
        }

        .child-level-3 {
            padding-right: 60px; /* مسافة بادئة للمستوى 4 */
        }
    </style>
@endsection

@section('content')
    <h2>جدول الحسابات</h2>

    <div class="table-actions">
        <button class="export-excel-btn" id="export-excel-btn-account">
            <i class="fas fa-file-excel"></i> تصدير Excel
        </button>
        <button class="export-pdf-btn" id="export-pdf-btn-account">
            <i class="fas fa-file-pdf"></i> تصدير PDF
        </button>
    </div>

    <div class="container-tabs">
        <div class="row">
            <div>
                <button type="button" id="all" class="level-btn active">الكل</button>
            </div>
            <div>
                <button type="button" id="1" class="level-btn">المستوى 1</button>
            </div>
            <div>
                <button type="button" id="2" class="level-btn">المستوى 2</button>
            </div>
            <div>
                <button type="button" id="3" class="level-btn">المستوى 3</button>
            </div>
            <div>
                <button type="button" id="4" class="level-btn">المستوى 4</button>
            </div>

            <div >
                <input type="text" id="search-account" placeholder="ابحث عن الحساب..." class="search-input">
            </div>

            <div >
                <button id="search-btn" class="search-button">بحث</button>
            </div>



        </div>

    </div>

    <div class="accounts-table-container">
        <table class="accounts-table" id="accountsTable">
            <thead>
            <tr>
                <th>رقم الحساب</th>
                <th>اسم الحساب</th>
                <th>نوع الحساب</th>
                <th>رصيد مدين</th>
                <th>رصيد دائن</th>
                <th>الرصيد</th>
                <th>الإجراءات</th>
            </tr>
            </thead>
            <tbody id="accountsTableBody">
            <!-- Data will be populated here via AJAX -->
            </tbody>
            <tfoot id="accountsTableFooter" style="display: none;">
            <tr>
                <td colspan="3">الإجمالي</td>
                <td class="total-debit">0</td>
                <td class="total-credit">0</td>
                <td class="total-balance">0</td>
            </tr>
            </tfoot>
        </table>
    </div>


    <div class="account-form-modal" style="display: none;">
        <div class="modal-content">
            <h2></h2>
            <form id="accountForm">
                @csrf
                <div class="form-row">
                    <div class="form-group-model">
                        <label>رقم الحساب</label>
                        <input type="text" name="account_number" id="accountNumber" required>
                    </div>
                    <div class="form-group-model">
                        <label>اسم الحساب</label>
                        <input type="text" name="name" id="accountName" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model">
                        <label>نوع الحساب</label>
                        <select name="account_type_id" id="accountType" required>
                            @foreach($accounttypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-model">
                        <label>الحساب الرئيسي</label>
                        <select name="parent_id" id="parentAccount" required>
                            <option value="">اختر الحساب الرئيسي...</option>
                            <option value="0">حساب رئيسي</option>
                            @foreach($accounts as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model">
                        <label>الرصيد الافتتاحي</label>
                        <input type="number" name="opening_balance" id="openingBalance" step="0.01" value="0" required>
                    </div>
                    <div class="form-group-model">
                        <label>القائمة الختامية</label>
                        <select name="closing_list_type" id="closingListType">
                            <option value="">اختر نوع</option>
                            <option value="1">قائمة الدخل</option>
                            <option value="2">الميزانيه العموميه</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="accountId">
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" class="save-btn">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <div class="account-form-modal-show">
        <div class="modal-content">
            <h2>  </h2>
            <form action="{#" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group-model">
                        <label>رقم الحساب</label>
                        <input type="text" name="account_number" id="accountNumberShow" required>
                    </div>
                    <div class="form-group-model">
                        <label>اسم الحساب</label>
                        <input type="text" name="name" id="accountNameShow" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model">
                        <label>نوع الحساب</label>
                        <select name="account_type_id" id="accountTypeShow" required>
                            @foreach($accounttypes as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-model">
                        <label>الحساب الرئيسي</label>
                        <select name="parent_id" id="parentAccountShow" required>
                            <option value="">اختر الحساب الرئيسي...</option>
                            <option value="0">حساب رئيسي</option>
                            @foreach($accounts as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model" id="openingBalanceRowShow" style="display: none;">
                        <label>الرصيد الافتتاحي</label>
                        <input type="number"  name="opening_balance" id="openingBalance" step="0.01" value="0" required>
                    </div>
                    <div class="form-group-model">
                        <label> القائمة الختامية</label>
                        <select name="closing_list_type" id="closingListTypeShow">
                            <option value="">اختر نوع</option>
                            <option value="1">قائمة الدخل</option>
                            <option value="2">الميزانيه العموميه</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-model">
                        <label>
                            هل هو حساب فرعي (نهائي)؟ </label>
                        <input type="checkbox"  id="isLastCheckboxShow" name="islast" value="1">

                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Load all accounts by default
            let currentLevel = 'all';
            fetchAccounts('all');

            // Handle level button clicks
            $('.level-btn').on('click', function() {
                $('#search-account').val('');
                let level = $(this).attr('id');
                $('.level-btn').removeClass('active');
                $(this).addClass('active');
                currentLevel = level;
                fetchAccounts(level);
            });

            // Fetch accounts via AJAX
            function fetchAccounts(level) {
                showLoadingOverlay();
                $.ajax({
                    url: '{{ route("accounting.accountsTree.filter") }}',
                    method: 'GET',
                    data: { level: level },
                    success: function(response) {
                        updateTable(response.accounts, level);
                        hideLoadingOverlay();
                    },
                    error: function(xhr) {
                        hideLoadingOverlay();
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'فشل في جلب البيانات',
                        });
                    }
                });
            }

            // Update table with fetched data
            function updateTable(accounts, level) {
                let tbody = $('#accountsTableBody');
                let tfoot = $('#accountsTableFooter');
                tbody.empty();

                if (accounts.length === 0) {
                    tbody.append('<tr><td colspan="7" class="no-data-message">لا توجد بيانات</td></tr>');
                    tfoot.hide();
                } else {
                    let totalDebit = 0;
                    let totalCredit = 0;
                    let totalBalance = 0;

                    if (level === 'all') {
                        // عرض الشجرة عند اختيار "الكل"
                        accounts.forEach(function(account) {
                            console.log(account);
                            let balance = account.balance || 0;
                            let debit = balance < 0 ? Math.abs(balance) : 0;
                            let credit = balance >= 0 ? balance : 0;

                            totalDebit += debit;
                            totalCredit += credit;
                            totalBalance += balance;
                            let balanceDisplay = account.islast == "1" ? balance : '';
                                console.log(account.islast);
                            let row = `
                                <tr>
                                    <td>${account.account_number}</td>
                                    <td>${account.name}</td>
                                    <td>${account.account_type_name}</td>
                                    <td class="total-debit">${debit}</td>
                                    <td class="total-credit">${credit}</td>
                                    <td class="total-balance">${balanceDisplay}</td>
                                    <td>
                                        <div>
                                            <a href="#" style="margin: 10px; font-size: 20px;" ><i class="fas fa-edit edit-account-btn" id="${account.id}" style="color: green;"></i></a>
                                             <a href="#" style="margin: 10px; font-size: 20px;" class="view-account"><i class="fas fa-eye show-account-btn" id="${account.id}" style="color: green;"></i></a>
                                            <a href="{{ route('accounting.delete', ':id') }}".replace(':id', account.id)"
                                               style="margin: 10px; font-size: 20px;"
                                               onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟');">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                            tbody.append(row);
                        });
                    } else {
                        accounts.forEach(function(account) {
                            let balance = account.balance || 0;
                            let debit = balance < 0 ? Math.abs(balance) : 0;
                            let credit = balance >= 0 ? balance : 0;

                            totalDebit += debit;
                            totalCredit += credit;
                            totalBalance += balance;
                            let balanceDisplay = account.islast == "1" ? balance : '';

                            let row = `
                                <tr>
                                    <td>${account.account_number}</td>
                                    <td>${account.name}</td>
                                    <td>${account.account_type_name}</td>
                                    <td class="total-debit">${debit}</td>
                                    <td class="total-credit">${credit}</td>
                                    <td class="total-balance">${balanceDisplay}</td>
                                    <td>
                                        <div>
                                              <a href="#" style="margin: 10px; font-size: 20px;" ><i class="fas fa-edit edit-account-btn" id="${account.id}" style="color: green;"></i></a>

                                             <a href="#" style="margin: 10px; font-size: 20px;" class="view-account"><i class="fas fa-eye show-account-btn" id="${account.id}" style="color: green;"></i></a>

                                            <a href="{{ route('accounting.delete', ':id') }}".replace(':id', account.id)"
                                               style="margin: 10px; font-size: 20px;"
                                               onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟');">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;
                            tbody.append(row);
                        });
                    }
                    tfoot.find('.total-debit').text(totalDebit);
                    tfoot.find('.total-credit').text(totalCredit);
                    tfoot.find('.total-balance').text(totalBalance);
                    tfoot.show();
                }

                // Rebind edit button events
                bindEditEvents();
                bindShowEvents();
            }

            // Edit button click handler
            function bindEditEvents() {
                $('.edit-account-btn').on('click', function() {
                    let accountId = $(this).attr('id');
                    showLoadingOverlay();
                    $.ajax({
                        url: `/Acounting/edit/${accountId}`,
                        method: 'GET',
                        success: function(response) {
                            $('#accountId').val(response.id);
                            $('.account-form-modal h2').text(`تعديل حساب ${response.name}`);
                            $('#accountNumber').val(response.account_number);
                            $('#accountName').val(response.name);
                            $('#accountType').val(response.account_type_id);
                            $('#parentAccount').val(response.parent_id);
                            $('#openingBalance').val(response.opening_balance);
                            $('#closingListType').val(response.closing_list_type);
                            $('.account-form-modal').show();
                            hideLoadingOverlay();
                        },
                        error: function(xhr) {
                            hideLoadingOverlay();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.responseJSON?.message || 'فشل في جلب بيانات الحساب',
                            });
                        }
                    });
                });
            }
            function bindShowEvents() {
                $('.show-account-btn').on('click', function() {
                    let accountId = $(this).attr('id');
                    showLoadingOverlay();
                    $.ajax({
                        url: `/Acounting/edit/${accountId}`,
                        method: 'GET',
                        success: function(response) {
                            $('#accountId').val(response.id);
                            $('.account-form-modal-show h2').text(`عرض حساب ${response.name}`);

                            $('#accountNumberShow').val(response.account_number).prop('readonly', true);
                            $('#accountNameShow').val(response.name).prop('readonly', true);
                            $('#accountTypeShow').val(response.account_type_id).prop('disabled', true);
                            $('#parentAccountShow').val(response.parent_id).prop('disabled', true);
                            $('#openingBalanceShow').val(response.opening_balance).prop('readonly', true);
                            $('#closingListTypeShow').val(response.closing_list_type).prop('disabled', true);
                            $('#isLastCheckboxShow').prop('checked', response.islast == 1).prop('disabled', true);

                            if(response.islast == 1){
                                $('#openingBalanceRowShow').show();
                            } else {
                                $('#openingBalanceRowShow').hide();
                            }
                            $('#isLastCheckboxShow').prop('checked', response.islast == 1);

                            $('.account-form-modal-show').show();
                            hideLoadingOverlay();
                        },
                        error: function(xhr) {
                            hideLoadingOverlay();
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.responseJSON?.message || 'فشل في جلب بيانات الحساب',
                            });
                        }
                    });
                });
            }

            // Save button handler
            $('.save-btn').on('click', function() {
                let data = {
                    id: $('#accountId').val(),
                    account_number: $('#accountNumber').val(),
                    name: $('#accountName').val(),
                    account_type_id: $('#accountType').val(),
                    parent_id: $('#parentAccount').val(),
                    opening_balance: $('#openingBalance').val(),
                    closing_list_type: $('#closingListType').val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                };

                if ($('#accountId').val()) {
                    $.ajax({
                        url: '{{ route("accounting.accountsTree.update") }}',
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.status == 201) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: response.message || 'غير معروف',
                                });
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم',
                                    text: response.message || 'غير معروف',
                                }).then(() => {
                                    location.reload();
                                });
                            }
                            hideLoadingOverlay();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.responseJSON?.message || 'غير معروف',
                            });
                            hideLoadingOverlay();
                        }
                    });
                }
            });

            // Cancel button
            $('.cancel-btn').on('click', function() {
                $('.account-form-modal').hide();
                $('.account-form-modal-show').hide();
                resetForm();
            });

            // Export to Excel
            $('#export-excel-btn-account').on('click', function() {
                window.location.href = '{{ route("accounting.export.excel") }}?level=' + currentLevel;
            });

            // Export to PDF
            $('#export-pdf-btn-account').on('click', function() {
                window.location.href = '{{ route("accounting.export.pdf") }}?level=' + currentLevel;
            });

            function resetForm() {
                $('#accountId').val('');
            }


            $('#search-btn').on('click', function() {
                searchAccounts();
            });

            $('#search-account').on('keypress', function(event) {
                if (event.which === 13) { // زر Enter
                    searchAccounts();
                }
            });

            function searchAccounts() {
                let query = $('#search-account').val().trim();

                if (query === '') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'تنبيه',
                        text: 'يرجى إدخال كلمة للبحث!',
                    });
                    return;
                }

                showLoadingOverlay();
                $.ajax({
                    url: '{{ route("accounting.accountsTree.search") }}',
                    method: 'GET',
                    data: { query: query },
                    success: function(response) {
                        updateTable(response.accounts);
                        hideLoadingOverlay();
                    },
                    error: function(xhr) {
                        hideLoadingOverlay();
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'حدث خطأ أثناء البحث!',
                        });
                    }
                });
            }

        });
    </script>
@endsection
