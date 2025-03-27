@extends('financialaccounting.layouts.master')

@section('css')
    <style>
        .container-tabs {
            width: 50%;
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
        <button class="export-excel-btn">
            <i class="fas fa-file-excel"></i> تصدير Excel
        </button>
        <button class="export-pdf-btn">
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

    <!-- Modal (unchanged) -->
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
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Load all accounts by default
            fetchAccounts('all');

            // Handle level button clicks
            $('.level-btn').on('click', function() {
                let level = $(this).attr('id');
                $('.level-btn').removeClass('active');
                $(this).addClass('active');
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
                            let balance = account.balance || 0;
                            let debit = balance < 0 ? Math.abs(balance) : 0;
                            let credit = balance >= 0 ? balance : 0;

                            totalDebit += debit;
                            totalCredit += credit;
                            totalBalance += balance;

                            // الحساب الرئيسي (المستوى 1)
                            let row = `
                                <tr>
                                    <td>${account.account_number}</td>
                                    <td>${account.name}</td>
                                    <td>${account.account_type_name}</td>
                                    <td class="total-debit">${debit}</td>
                                    <td class="total-credit">${credit}</td>
                                    <td class="total-balance">${balance}</td>
                                    <td>
                                        <div>
                                            <a href="#" style="margin: 10px; font-size: 20px;">
                                                <i class="fas fa-edit edit-account-btn" id="${account.id}" style="color: green;"></i>
                                            </a>
                                            <a href="{{ route('accounting.delete', ':id') }}".replace(':id', account.id)"
                                               style="margin: 10px; font-size: 20px;"
                                               onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟');">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>`;

                            // الأبناء (المستوى 2 وما دون)
                            if (account.children && account.children.length > 0) {
                                account.children.forEach(function(child) {
                                    let childBalance = child.balance || 0;
                                    let childDebit = childBalance < 0 ? Math.abs(childBalance) : 0;
                                    let childCredit = childBalance >= 0 ? childBalance : 0;

                                    totalDebit += childDebit;
                                    totalCredit += childCredit;
                                    totalBalance += childBalance;

                                    row += `
                                        <tr class="child-level-1">
                                            <td class="child-level-1">${child.account_number}</td>
                                            <td class="child-level-1">${child.name}</td>
                                            <td>${child.account_type_name}</td>
                                            <td class="total-debit">${childDebit}</td>
                                            <td class="total-credit">${childCredit}</td>
                                            <td class="total-balance">${childBalance}</td>
                                            <td>
                                                <div>
                                                    <a href="#" style="margin: 10px; font-size: 20px;">
                                                        <i class="fas fa-edit edit-account-btn" id="${child.id}" style="color: green;"></i>
                                                    </a>
                                                    <a href="{{ route('accounting.delete', ':id') }}".replace(':id', child.id)"
                                                       style="margin: 10px; font-size: 20px;"
                                                       onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟');">
                                                        <i class="fas fa-trash" style="color: red;"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>`;

                                    // الأحفاد (المستوى 3)
                                    if (child.children && child.children.length > 0) {
                                        child.children.forEach(function(grandchild) {
                                            let grandBalance = grandchild.balance || 0;
                                            let grandDebit = grandBalance < 0 ? Math.abs(grandBalance) : 0;
                                            let grandCredit = grandBalance >= 0 ? grandBalance : 0;

                                            totalDebit += grandDebit;
                                            totalCredit += grandCredit;
                                            totalBalance += grandBalance;

                                            row += `
                                                <tr class="child-level-2">
                                                    <td class="child-level-2">${grandchild.account_number}</td>
                                                    <td class="child-level-2">${grandchild.name}</td>
                                                    <td>${grandchild.account_type_name}</td>
                                                    <td class="total-debit">${grandDebit}</td>
                                                    <td class="total-credit">${grandCredit}</td>
                                                    <td class="total-balance">${grandBalance}</td>
                                                    <td>
                                                        <div>
                                                            <a href="#" style="margin: 10px; font-size: 20px;">
                                                                <i class="fas fa-edit edit-account-btn" id="${grandchild.id}" style="color: green;"></i>
                                                            </a>
                                                            <a href="{{ route('accounting.delete', ':id') }}".replace(':id', grandchild.id)"
                                                               style="margin: 10px; font-size: 20px;"
                                                               onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟');">
                                                                <i class="fas fa-trash" style="color: red;"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>`;
                                        });
                                    }
                                });
                            }

                            tbody.append(row);
                        });
                    } else {
                        // عرض مسطح للمستويات الأخرى (1، 2، 3، 4)
                        accounts.forEach(function(account) {
                            let balance = account.balance || 0;
                            let debit = balance < 0 ? Math.abs(balance) : 0;
                            let credit = balance >= 0 ? balance : 0;

                            totalDebit += debit;
                            totalCredit += credit;
                            totalBalance += balance;

                            let row = `
                                <tr>
                                    <td>${account.account_number}</td>
                                    <td>${account.name}</td>
                                    <td>${account.account_type_name}</td>
                                    <td class="total-debit">${debit}</td>
                                    <td class="total-credit">${credit}</td>
                                    <td class="total-balance">${balance}</td>
                                    <td>
                                        <div>
                                            <a href="#" style="margin: 10px; font-size: 20px;">
                                                <i class="fas fa-edit edit-account-btn" id="${account.id}" style="color: green;"></i>
                                            </a>
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

                    // Update footer totals
                    tfoot.find('.total-debit').text(totalDebit);
                    tfoot.find('.total-credit').text(totalCredit);
                    tfoot.find('.total-balance').text(totalBalance);
                    tfoot.show();
                }

                // Rebind edit button events
                bindEditEvents();
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
                resetForm();
            });


            function resetForm() {
                $('#accountId').val('');
            }

        });
    </script>
@endsection
