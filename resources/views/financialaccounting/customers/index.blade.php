@extends('financialaccounting.layouts.master')
@section('content')

    <div id="salesInvoiceSection">
        <div class="invoices-container">
            <div class="invoices-header">
                <h1>العملاء</h1>
                <button class="add-invoice-btn">
                    <i class="fas fa-plus"></i>
                    إضافة عميل جديد
                </button>
            </div>

            <div class="invoice-search">
                <input type="search" id="customer-search" placeholder="بحث في العملاء...">
                <select id="branch-filter">
                    <option value="all">جميع الفروع</option>
                    @foreach($branches as $item)
                        <option value="{{$item->id}}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="invoices-grid">
                <!-- سيتم ملء هذا القسم ديناميكيًا بواسطة JavaScript -->
            </div>
        </div>
    </div>

    <div class="invoice-form-modal">
        <div class="modal-content">
            <h2 id="modal-title">إضافة عميل</h2>
            <form id="customer-form">
                <div class="invoice-header-section">
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>الاسم</label>
                            <input type="text" id="name" name="name" required>
                            <span id="error-name" class="error"></span>
                        </div>
                        <div class="form-group-model">
                            <label>رقم الهاتف</label>
                            <input type="number" id="contact_info" name="contact_info" required>
                            <span id="error-contact_info" class="error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-model">
                            <label>الفرع</label>
                            <select id="branch_id" name="branch_id" class="select2" required>
                                <option value="">اختر الفرع...</option>
                                @foreach($branches as $item)
                                    <option value="{{$item->id}}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <span id="error-branch_id" class="error"></span>
                        </div>
                        <div class="form-group-model">
                            <label>الحساب</label>
                            <select id="account_id" name="account_id" class="select2" required>
                                <option value="">اختر الحساب...</option>
                                @foreach($accounts as $item)
                                    <option value="{{$item->id}}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <span id="error-account_id" class="error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-model">
                            <label>الحد الائتماني</label>
                            <input type="number" id="credit_limit" name="credit_limit" step="0.01" value="0" required>
                            <span id="error-credit_limit" class="error"></span>
                        </div>
                        <div class="form-group-model">
                            <div class="form-group-model">
                                <label> الرقم الضريبي</label>
                                <input type="number" id="tax_number" name="tax_number" value="" required>
                                @error('tax_number')
                                <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" class="save-btn" id="save-btn">حفظ</button>
                </div>
                <input type="hidden" id="customer_id" name="customer_id">
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // تحميل العملاء عند تحميل الصفحة
            loadCustomers('all');

            // فتح نافذة إضافة عميل جديد
            $('.add-invoice-btn').on('click', function () {
                resetModal();
                openModal('إضافة عميل', 'حفظ');
            });

            // إغلاق النافذة
            $('.cancel-btn').on('click', closeModal);
            $('body').on('click', '.page-overlay', closeModal);

            // فلترة العملاء بناءً على الفرع
            $('#branch-filter').on('change', function () {
                let selectedBranch = $(this).val();
                let searchTerm = $('#customer-search').val().toLowerCase();
                loadCustomers(selectedBranch, searchTerm);
            });

            // البحث في العملاء
            $('#customer-search').on('input', function () {
                let searchTerm = $(this).val().toLowerCase();
                let branchFilter = $('#branch-filter').val();
                loadCustomers(branchFilter, searchTerm);
            });

            // دالة لتحميل العملاء باستخدام تصميم البطاقات
            function loadCustomers(branchId = 'all', searchTerm = '') {
                $.ajax({
                    url: "/customers/get",
                    type: "GET",
                    data: { branch_id: branchId },
                    dataType: "json",
                    success: function (response) {
                        let grid = $('.invoices-grid');
                        grid.empty();

                        // فلترة العملاء بناءً على البحث
                        let filteredCustomers = response.customers.filter(customer =>
                            customer.name.toLowerCase().includes(searchTerm) ||
                            customer.contact_info.includes(searchTerm)
                        );

                        if (filteredCustomers.length === 0) {
                            grid.append('<p style="text-align: center;">لا يوجد عملاء</p>');
                        } else {
                            $.each(filteredCustomers, function (index, item) {
                                let branch = item.branch_id === 'all' || !item.branch ? 'غير محدد' : item.branch.name;
                                let account = item.account ? item.account.name : 'غير محدد';
                                let card = `
                                    <div class="invoice-card">
                                        <span class="invoice-status paid">عميل</span>
                                        <div class="invoice-number">${item.name}</div>
                                        <div class="invoice-date">
                                            <i class="fas fa-phone"></i> ${item.contact_info}
                                        </div>
                                        <div class="invoice-details">
                                            <p><span><i class="fas fa-code-branch"></i> الفرع</span><strong>${branch}</strong></p>
                                            <p><span><i class="fas fa-wallet"></i> الحساب</span><strong>${account}</strong></p>
                                            <p><span><i class="fas fa-credit-card"></i> الحد الائتماني</span><strong>${item.credit_limit} ريال</strong></p>
                                            <p><span><i class="fas fa-credit-card"></i>  الرقم الضريبي</span><strong>${item.tax_number??''} </strong></p>
                                            <p><span><i class="fas fa-credit-card"></i>  لرصيد</span><strong>${item.balance} ريال</strong></p>
                                        </div>
                                        <div class="card-actions">
                                            <button class="action-btn edit-customer" title="تعديل" data-id="${item.id}"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete-customer" title="حذف" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>`;
                                grid.append(card);
                            });
                        }
                    },
                    error: function () {
                        Swal.fire('خطأ', 'فشل تحميل بيانات العملاء.', 'error');
                    }
                });
            }

            // حفظ أو تحديث العميل
            $('#save-btn').on('click', function (e) {
                e.preventDefault();
                clearErrors();

                const customerId = $('#customer_id').val();
                const url = customerId ? `/customers/update/${customerId}` : '{{ route('customers.store') }}';
                const method = customerId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: $('#name').val(),
                        contact_info: $('#contact_info').val(),
                        branch_id: $('#branch_id').val(),
                        account_id: $('#account_id').val(),
                        credit_limit: $('#credit_limit').val(),
                        tax_number: $('#tax_number').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        closeModal();
                        Swal.fire(response.status, response.message, response.success ? 'success' : 'error');
                        loadCustomers('all');
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, messages) {
                                let errorKey = key.replace(/\./g, "-");
                                $(`#error-${errorKey}`).text(messages[0]).css('color', 'red');
                                $(`#${errorKey}`).css('border', '1px solid red');
                            });
                        } else {
                            Swal.fire('خطأ', 'حدث خطأ غير متوقع.', 'error');
                        }
                    }
                });
            });

            // تعديل عميل
            $(document).on('click', '.edit-customer', function (e) {
                e.preventDefault();
                const customerId = $(this).data('id');

                $.ajax({
                    url: `/customers/edit/${customerId}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            $('#modal-title').text('تعديل عميل');
                            $('#save-btn').text('تحديث');
                            $('#name').val(response.customer.name);
                            $('#contact_info').val(response.customer.contact_info);
                            $('#branch_id').val(response.customer.branch_id);
                            $('#account_id').val(response.customer.account_id).trigger('change');
                            $('#credit_limit').val(response.customer.credit_limit);
                            $('#tax_number').val(response.customer.tax_number);
                            $('#customer_id').val(response.customer.id);
                            openModal('تعديل عميل', 'تحديث');
                        } else {
                            Swal.fire('خطأ', 'فشل تحميل بيانات العميل.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('خطأ', 'حدث خطأ أثناء جلب بيانات العميل.', 'error');
                    }
                });
            });

            // حذف عميل
            $(document).on('click', '.delete-customer', function (e) {
                e.preventDefault();
                const customerId = $(this).data('id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من استرجاع هذا العميل بعد الحذف!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/customers/delete/${customerId}`,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('تم الحذف!', 'تم حذف العميل بنجاح.', 'success').then(() => loadCustomers('all'));
                                } else {
                                    Swal.fire('فشل الحذف', response.message || 'حدث خطأ أثناء الحذف.', 'error');
                                }
                            },
                            error: function () {
                                Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
                            }
                        });
                    }
                });
            });

            // دوال مساعدة
            function openModal(title = 'إضافة عميل', buttonText = 'حفظ') {
                $('body').css({ 'pointer-events': 'none', 'overflow': 'hidden' });
                $('body').append('<div class="page-overlay"></div>');
                $('.page-overlay').css({
                    'position': 'fixed', 'top': '0', 'left': '0', 'width': '100%', 'height': '100%',
                    'background-color': 'rgba(0, 0, 0, 0.5)', 'z-index': '999'
                }).fadeIn();

                $('#modal-title').text(title);
                $('#save-btn').text(buttonText);
                $('.invoice-form-modal').css({
                    'display': 'none', 'position': 'fixed', 'top': '50%', 'left': '50%',
                    'transform': 'translate(-50%, -50%)', 'z-index': '1000', 'background-color': '#fff',
                    'padding': '20px', 'box-shadow': '0 2px 10px rgba(0, 0, 0, 0.2)', 'border-radius': '8px',
                    'pointer-events': 'auto'
                }).fadeIn();
            }

            function closeModal() {
                $('body').css({ 'pointer-events': 'auto', 'overflow': 'auto' });
                $('.invoice-form-modal').fadeOut();
                $('.page-overlay').fadeOut(function () { $(this).remove(); });
            }

            function resetModal() {
                $('#customer-form')[0].reset();
                $('#customer_id').val('');
                $('#credit_limit').val('0');
                clearErrors();
            }

            function clearErrors() {
                $('.error').text('').css('color', '');
                $('.form-group input, .form-group select').css('border', '');
            }
        });
    </script>
@endsection
