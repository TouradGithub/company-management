@extends('financialaccounting.layouts.master')
@section('content')
    <div class="session-years-header">
        <h1>السنوات </h1>
        <button class="add-invoice-btn">
            <i class="fas fa-plus"></i> إضافة سنة
        </button>
    </div>

    <div class="accounts-table-container">
        <table class="accounts-table" id="accountsTable">
            <thead>
            <tr>
                <th>رقم </th>
                <th> السنة</th>
                <th> الحاله</th>
                <th>الإجراءات</th>
            </tr>
            </thead>
            <tbody id="accountsTableBody">

            </tbody>


        </table>
    </div>



    <div class="invoice-form-modal">
        <div class="modal-content">
            <h2 id="invoice-modal-title">إضافة سنة</h2>
            <form id="invoice-form">
                <div class="form-group-model">
                    <label>اسم السنة</label>
                    <input type="text" id="session_name" name="name" required>
                    <span id="error-session-name" class="error"></span>
                </div>
                <div class="form-group-model">
                    <label>
                        <input type="checkbox" id="is_current" name="is_current">  تفعيل
                    </label>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-invoice-btn">إلغاء</button>
                    <button type="button" class="save-invoice-btn">حفظ</button>
                </div>
                <input type="hidden" id="session_id" name="session_id">
            </form>
        </div>
    </div>
@endsection


@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('.add-invoice-btn').on('click', function () {
            $('#invoice-form')[0].reset();
            $('#session_id').val('');
            $('#invoice-modal-title').text('إضافة سنة');
            $('.save-invoice-btn').text('حفظ');
            openSessionModal();
        });
        loadSessionYears();
        function loadSessionYears() {
            showLoadingOverlay();
            $.get("/session-years/get", function (response) {
                let tbody = $('#accountsTableBody');
                tbody.empty();
                if (!response.session_years || response.session_years.length === 0) {
                    tbody.append('<tr><td colspan="4" style="text-align: center;">لا توجد سنوات دراسية</td></tr>');
                    return;
                }
                $.each(response.session_years, function (i, item) {
                    console.log(item);
                    let status = item.is_current
                        ? '<span class="badge badge-success">مفعله</span>'
                        : '<span class="badge badge-secondary">غير مفعلة</span>';

                    let row = `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${escapeHtml(item.name)}</td>
                            <td>${status}</td>
                            <td>
                                <a href="#" style="margin: 10px; font-size: 20px;" class="edit-invoice" data-id="${item.id}">
                                    <i class="fas fa-edit edit-account-btn" style="color: green;"></i>
                                </a>
                                <a style="margin: 10px; font-size: 20px;" class="delete-invoice" data-id="${item.id}">
                                    <i class="fas fa-trash" style="color: red;"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
                hideLoadingOverlay();
            }).fail(function () {
                hideLoadingOverlay();
                $('#accountsTableBody').html('<tr><td colspan="4" style="text-align: center; color: red;">فشل تحميل البيانات</td></tr>');
            });
        }

        function escapeHtml(text) {
            return $('<div>').text(text).html();
        }


        $('.save-invoice-btn').on('click', function () {
            const sessionId = $('#session_id').val();
            const method = sessionId ? 'PUT' : 'POST';
            const url = sessionId ? `/session-years/update/${sessionId}` : '{{ route("session-years.store") }}';

            $.ajax({
                url: url,
                method: method,
                data: {
                    name: $('#session_name').val(),
                    is_current: $('#is_current').is(':checked') ? 1 : 0,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire('تم', response.message, 'success');
                    closeSessionModal();
                    loadSessionYears();
                },
                error: function (xhr) {
                    Swal.fire('خطأ', 'فشل الحفظ', 'error');
                }
            });
        });

        $(document).on('click', '.edit-invoice', function () {
            showLoadingOverlay()

            const id = $(this).data('id');
            $.get(`/session-years/edit/${id}`, function (response) {
                $('#session_name').val(response.session_year.name);
                $('#is_current').prop('checked', response.session_year.is_current);
                $('#session_id').val(response.session_year.id);
                $('#session-modal-title').text('تعديل سنة');
                $('.save-session-btn').text('تحديث');
                openSessionModal();
                hideLoadingOverlay()
            });
            hideLoadingOverlay()
        });

        $(document).on('click', '.delete-invoice', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'لن تتمكن من استرجاع هذه السنة بعد الحذف!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذفها!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/session-years/delete/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (response) {
                            if(response.success){
                                Swal.fire('تم الحذف!', '', 'success');
                                loadSessionYears();
                            }
                            if(!response.success){
                                Swal.fire(' خطأ!',response.message, 'error');
                            }
                        }
                    });
                }
            });
        });

        function openSessionModal() {
            $('.invoice-form-modal').fadeIn();
            $('body').append('<div class="page-overlay"></div>');
        }

        function closeSessionModal() {
            $('.invoice-form-modal').fadeOut();
            $('.page-overlay').remove();
        }

        $('.cancel-invoice-btn').on('click', closeSessionModal);

    </script>
@endsection
