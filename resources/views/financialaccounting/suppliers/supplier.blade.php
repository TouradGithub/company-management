@extends('financialaccounting.layouts.master')
@section('content')

    <div id="salesInvoiceSection">
        <div class="invoices-container">
            <div class="invoices-header">
                <h1>الموردون</h1>
                <button class="add-invoice-btn">
                    <i class="fas fa-plus"></i>
                    إضافة مورد جديد
                </button>
            </div>

            <div class="invoice-search">
                <input type="search" id="supplier-search" placeholder="بحث في العملاء...">
                <select id="branch-filter">
                    <option value="all">جميع الفروع</option>
                    @foreach($branches as $item)
                        <option value="{{$item->id}}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="invoices-grid">
            </div>
        </div>
    </div>

    <div class="invoice-form-modal">
        <div class="modal-content">
            <h2 id="modal-title">إضافة مورد</h2>
            <form id="supplier-form">
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
                            <!-- حقل فارغ للحفاظ على التنسيق -->
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" class="save-btn" id="save-btn">حفظ</button>
                </div>
                <input type="hidden" id="supplier_id" name="supplier_id">
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
                openModal('إضافة مورد', 'حفظ');
            });

            // إغلاق النافذة
            $('.cancel-btn').on('click', closeModal);
            $('body').on('click', '.page-overlay', closeModal);

            // فلترة العملاء بناءً على الفرع
            $('#branch-filter').on('change', function () {
                let selectedBranch = $(this).val();
                let searchTerm = $('#supplier-search').val().toLowerCase();
                loadCustomers(selectedBranch, searchTerm);
            });

            $('#supplier-search').on('input', function () {
                let searchTerm = $(this).val().toLowerCase();
                let branchFilter = $('#branch-filter').val();
                loadCustomers(branchFilter, searchTerm);
            });
            function loadCustomers(branchId = 'all', searchTerm = '') {
                $.ajax({
                    url: "/suppliers-company/get",
                    type: "GET",
                    data: { branch_id: branchId },
                    dataType: "json",
                    success: function (response) {
                        let grid = $('.invoices-grid');
                        grid.empty();

                        // فلترة العملاء بناءً على البحث
                        let filteredCustomers = response.suppliers.filter(supplier =>
                            supplier.name.toLowerCase().includes(searchTerm) ||
                            supplier.contact_info.includes(searchTerm)
                        );

                        if (filteredCustomers.length === 0) {
                            grid.append('<p style="text-align: center;">لا يوجد موردين</p>');
                        } else {
                            $.each(filteredCustomers, function (index, item) {
                                let branch = item.branch_id === 'all' || !item.branch ? 'غير محدد' : item.branch.name;
                                let account = item.account ? item.account.name : 'غير محدد';
                                let card = `
                                    <div class="invoice-card">
                                        <span class="invoice-status paid">مورد</span>
                                        <div class="invoice-number">${item.name}</div>
                                        <div class="invoice-date">
                                            <i class="fas fa-phone"></i> ${item.contact_info}
                                        </div>
                                        <div class="invoice-details">
                                            <p><span><i class="fas fa-code-branch"></i> الفرع</span><strong>${branch}</strong></p>
                                            <p><span><i class="fas fa-wallet"></i> الحساب</span><strong>${account}</strong></p>
                                        </div>
                                        <div class="card-actions">
                                            <button class="action-btn edit-supplier" title="تعديل" data-id="${item.id}"><i class="fas fa-edit"></i></button>
                                            <button class="action-btn delete-supplier" title="حذف" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </div>`;
                                grid.append(card);
                            });
                        }
                    },
                    error: function () {
                        Swal.fire('خطأ', 'فشل تحميل بيانات الموردين.', 'error');
                    }
                });
            }
            $('#save-btn').on('click', function (e) {
                e.preventDefault();
                clearErrors();

                const supplierId = $('#supplier_id').val();
                const url = supplierId ? `/suppliers-company/update/${supplierId}` : '{{ route('suppliers-company.store') }}';
                const method = supplierId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        name: $('#name').val(),
                        contact_info: $('#contact_info').val(),
                        branch_id: $('#branch_id').val(),
                        account_id: $('#account_id').val(),
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
            $(document).on('click', '.edit-supplier', function (e) {
                e.preventDefault();
                const supplierId = $(this).data('id');

                $.ajax({
                    url: `/suppliers-company/edit/${supplierId}`,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            console.log(response);
                            $('#modal-title').text('تعديل عميل');
                            $('#save-btn').text('تحديث');
                            $('#name').val(response.supplier.name);
                            $('#contact_info').val(response.supplier.contact_info);
                            $('#branch_id').val(response.supplier.branch_id);
                            $('#account_id').val(response.supplier.account_id);
                            $('#supplier_id').val(response.supplier.id);
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
            $(document).on('click', '.delete-supplier', function (e) {
                e.preventDefault();
                const supplierId = $(this).data('id');

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
                            url: `/suppliers-company/delete/${supplierId}`,
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
            function openModal(title = 'إضافة مورد', buttonText = 'حفظ') {
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
                $('#supplier-form')[0].reset();
                $('#supplier_id').val('');
                clearErrors();
            }
            function clearErrors() {
                $('.error').text('').css('color', '');
                $('.form-group input, .form-group select').css('border', '');
            }
        });
    </script>
@endsection
