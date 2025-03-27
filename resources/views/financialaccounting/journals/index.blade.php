@extends('financialaccounting.layouts.master')

@section('content')
    <div id="journalsSection">
        <div class="invoices-container">
            <div class="invoices-header">
                <h1>الدفاتر المحاسبية</h1>
                <button class="add-invoice-btn">
                    <i class="fas fa-plus"></i>
                    إضافة دفتر جديد
                </button>
            </div>

            <div class="invoices-grid">
                <div class="accounts-table-container">
                    <table class="accounts-table" id="journalsTable">
                        <thead>
                        <tr>
                            <th>الكود</th>
                            <th>الاسم</th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- سيتم ملء الجدول عبر AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-form-modal">
        <div class="modal-content">
            <h2 id="modal-title">إضافة دفتر</h2>
            <form id="journal-form">
                <div class="invoice-header-section">
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>كود الدفتر</label>
                            <input type="text" id="code" name="code" required>
                            <span id="error-code" class="error"></span>
                        </div>
                        <div class="form-group-model">
                            <label>اسم الدفتر</label>
                            <input type="text" id="name" name="name" required>
                            <span id="error-name" class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" class="save-btn" id="save-btn">حفظ</button>
                </div>
                <input type="hidden" id="journal_id" name="journal_id">
                <input type="hidden" id="company_id" name="company_id" value="{{ auth()->user()->company_id ?? 1 }}">
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Load journals on page load
            loadJournals();

            // Open modal for adding a new journal
            $('.add-invoice-btn').on('click', function() {
                resetModal();
                openModal('إضافة دفتر', 'حفظ');
            });

            // Close modal when clicking "Cancel" or overlay
            $('.cancel-btn').on('click', closeModal);
            $('body').on('click', '.page-overlay', closeModal);

            // Handle form submission (Add/Edit)
            $('#save-btn').on('click', function(e) {
                e.preventDefault();
                clearErrors();

                const journalId = $('#journal_id').val();
                const url = journalId ? `/journals/update/${journalId}` : '/journals/store';
                const method = journalId ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        code: $('#code').val(),
                        name: $('#name').val(), // إرسال company_id
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        closeModal();
                        Swal.fire(response.status, response.message, response.success ? 'success' : 'error');
                        loadJournals();
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
            $(document).on('click', '.edit-journal', function(e) {
                e.preventDefault();
                showLoadingOverlay();
                const journalId = $(this).data('id');

                $.ajax({
                    url: `/journals/edit/${journalId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#modal-title').text('تعديل دفتر');
                            $('#save-btn').text('تحديث');
                            $('#code').val(response.journal.code);
                            $('#name').val(response.journal.name);
                            $('#journal_id').val(response.journal.id);
                            openModal('تعديل دفتر', 'تحديث');
                            hideLoadingOverlay();
                        } else {
                            Swal.fire('خطأ', 'فشل تحميل بيانات الدفتر.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('خطأ', 'حدث خطأ أثناء جلب بيانات الدفتر.', 'error');
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-journal', function(e) {
                e.preventDefault();
                const journalId = $(this).data('id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من استرجاع هذا الدفتر بعد الحذف!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/journals/delete/${journalId}`,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('تم الحذف!', 'تم حذف الدفتر بنجاح.', 'success').then(() => loadJournals());
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
            function openModal(title = 'إضافة دفتر', buttonText = 'حفظ') {
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
                $('#journal-form')[0].reset();
                $('#journal_id').val('');
                $('#company_id').val('{{ auth()->user()->company_id ?? 1 }}'); // إعادة تعيين company_id
                clearErrors();
            }

            function clearErrors() {
                $('.error').text('').css('color', '');
                $('.form-group input, .form-group select').css('border', '');
            }




            function loadJournals() {
                $.ajax({
                    url: "/journals/get",
                    type: "GET",
                    dataType: "json",
                    data: {
                        company_id: '{{ auth()->user()->company_id ?? 1 }}' // فلترة حسب الشركة
                    },
                    success: function(response) {
                        let tableBody = $("#journalsTable tbody");
                        tableBody.empty();

                        if (response.journals.length === 0) {
                            tableBody.append("<tr><td colspan='3' style='text-align: center'>لا يوجد دفاتر</td></tr>");
                        } else {
                            $.each(response.journals, function(index, item) {
                                let row = `
                                    <tr>
                                        <td>${item.code}</td>
                                        <td>${item.name}</td>
                                        <td>
                                            <a href="#" class="edit-journal" data-id="${item.id}" style="margin: 10px; font-size: 20px;">
                                                <i class="fas fa-edit" style="color: green;"></i>
                                            </a>
                                            <a href="#" class="delete-journal" data-id="${item.id}" style="margin: 10px; font-size: 20px;">
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
