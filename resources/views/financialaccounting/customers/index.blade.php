

@extends('financialaccounting.layouts.master')
@section('content')

    <div id="salesInvoiceSection" >
        <div class="invoices-container">
            <div class="invoices-header">
                <h1> العملاء</h1>
                <button class="add-invoice-btn">
                    <i class="fas fa-plus"></i>
                     إضافة عميل جديد
                </button>
            </div>

            <div class="invoices-grid">

                <div class="accounts-table-container">
                    <table class="accounts-table" id="accountsTable">
                        <thead>
                        <tr>
                            <th> الإسم </th>
                            <th> رقم الهاتف</th>
                            <th>  الفرع</th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>


                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-form-modal">
        <div class="modal-content">
            <h2 id="modal-title">إضافة عميل</h2>
            <form id="customer-form">
                <div class="invoice-header-section">
                    <div class="form-row">
                        <div class="form-group">
                            <label>الاسم</label>
                            <input type="text" id="name" name="name" required>
                            <span id="error-name" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="number" id="contact_info" name="contact_info" required>
                            <span id="error-contact_info" class="error"></span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>الفرع</label>
                            <select id="branch_id" name="branch_id" required>
                                <option value="all">اختر الفرع...</option>
                                @foreach($branches as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الحساب</label>
                            <select id="account_id" name="account_id" required>
                                <option value="all">اخترالحساب...</option>
                                @foreach($accounts as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
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
            // Load customers on page load
            loadCustomers();

            // Open modal for adding a new customer
            $('.add-invoice-btn').on('click', function() {
                resetModal();
                openModal('إضافة عميل', 'حفظ');
            });

            // Close modal when clicking "Cancel" or overlay
            $('.cancel-btn').on('click', closeModal);
            $('body').on('click', '.page-overlay', closeModal);

            // Handle form submission (Add/Edit)
            $('#save-btn').on('click', function(e) {
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
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        closeModal();
                        Swal.fire(response.status, response.message, response.success ? 'success' : 'error');
                        loadCustomers();
                    },
                    error: function(xhr) {
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

            // Handle edit button click
            $(document).on('click', '.edit-customer', function(e) {
                e.preventDefault();
                showLoadingOverlay();
                const customerId = $(this).data('id');

                $.ajax({
                    url: `/customers/edit/${customerId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#modal-title').text('تعديل عميل');
                            $('#save-btn').text('تحديث');
                            $('#name').val(response.customer.name);
                            $('#contact_info').val(response.customer.contact_info);
                            $('#branch_id').val(response.customer.branch_id);
                            $('#customer_id').val(response.customer.id);
                            openModal('تعديل عميل', 'تحديث');
                            hideLoadingOverlay();
                        } else {
                            Swal.fire('خطأ', 'فشل تحميل بيانات العميل.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('خطأ', 'حدث خطأ أثناء جلب بيانات العميل.', 'error');
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-customer', function(e) {
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
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('تم الحذف!', 'تم حذف العميل بنجاح.', 'success').then(() => loadCustomers());
                                } else {
                                    Swal.fire('فشل الحذف', response.message || 'حدث خطأ أثناء الحذف.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
                            }
                        });
                    }
                });
            });

            // Utility Functions
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
                $('.page-overlay').fadeOut(function() { $(this).remove(); });
            }

            function resetModal() {
                // Use [0] to access the native DOM element and call reset()
                $('#customer-form')[0].reset();
                $('#customer_id').val('');
                clearErrors();
            }

            function clearErrors() {
                $('.error').text('').css('color', '');
                $('.form-group input, .form-group select').css('border', '');
            }

            function loadCustomers() {
                $.ajax({
                    url: "/customers/get",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        let tableBody = $("#accountsTable tbody");
                        tableBody.empty();

                        if (response.customers.length === 0) {
                            tableBody.append("<tr><td colspan='4' style='text-align: center'>لا يوجد عملاء</td></tr>");
                        } else {
                            $.each(response.customers, function(index, item) {
                                let branch = item.branch_id === 'all' ? 'غير محدد' : (item.branch ? item.branch.name : 'غير محدد');
                                let row = `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td>${item.contact_info}</td>
                                        <td>${branch}</td>
                                        <td>
                                            <a href="#" class="edit-customer" data-id="${item.id}" style="margin: 10px; font-size: 20px;">
                                                <i class="fas fa-edit" style="color: green;"></i>
                                            </a>
                                            <a href="#" class="delete-customer" data-id="${item.id}" style="margin: 10px; font-size: 20px;">
                                                <i class="fas fa-trash" style="color: red;"></i>
                                            </a>
                                        </td>
                                    </tr>`;
                                tableBody.append(row);
                            });
                        }
                    },
                    error: function() {
                        console.error("فشل تحميل البيانات.");
                    }
                });
            }
        });
    </script>
@endsection
