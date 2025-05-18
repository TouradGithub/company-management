@extends('financialaccounting.layouts.master')
@section('content')
    <div id="accountsTreeSection">
        <div class="accounts-tree-container">
            @if($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="accounts-header">
                <h1>مراكز التكلفة</h1>
                <button class="add-account-btn">
                    <i class="fas fa-plus"></i>
                    إضافة مركز تكلفة جديد
                </button>
            </div>

            <div class="table-actions">
                <button class="export-excel-btn">
                    <i class="fas fa-file-excel"></i>
                    تصدير Excel
                </button>
                <button class="export-pdf-btn">
                    <i class="fas fa-file-pdf"></i>
                    تصدير PDF
                </button>
            </div>

            <div class="accounts-table-container">
                <table class="accounts-table" id="accountsTable">
                    <thead>
                    <tr>
                        <th>إسم مركز التكلفة</th>
                        <th>رمز مركز التكلفة</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($costcenters as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->code }}</td>
                            <td>
                                <a href="#" class="edit-cost-center" data-id="{{ $item->id }}" style="margin: 10px; font-size: 20px;">
                                    <i class="fas fa-edit" style="color: green;"></i></a>
                                <a href="#" class="delete-cost-center" data-id="{{ $item->id }}" style="margin: 10px; font-size: 20px;">
                                    <i class="fas fa-trash" style="color: red;"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="account-form-modal">
            <div class="modal-content">
                <h2 id="modal-title">إضافة مركز تكلفة جديد</h2>
                <form id="cost-center-form">
                    @csrf
                    <input type="hidden" id="cost_center_id" name="cost_center_id">
                    <div class="form-group">
                        <label>إسم المركز</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label>رمز المركز</label>
                        <input type="text" name="code" id="code" required>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="cancel-btn">إلغاء</button>
                        <button type="button" class="save-btn">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-overlay"></div> <!-- Added overlay for modal -->
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Open modal for adding a new cost center
            $('.add-account-btn').on('click', function() {
                resetModal();
                $('#modal-title').text('إضافة مركز تكلفة جديد');
                $('.account-form-modal').addClass('active');
                $('.modal-overlay').addClass('active');
            });

            // Handle edit button click
            $(document).on('click', '.edit-cost-center', function(e) {
                e.preventDefault();
                const costCenterId = $(this).data('id');

                showLoadingOverlay();

                $.ajax({
                    url: `/cost-centers/edit/${costCenterId}`,
                    type: 'GET',
                    success: function(response) {
                        $('#cost_center_id').val(response.id);
                        $('#name').val(response.name);
                        $('#code').val(response.code);
                        $('#modal-title').text('تعديل مركز التكلفة: ' + response.name);
                        $('.save-btn').text('تحديث');
                        $('.account-form-modal').addClass('active');
                        $('.modal-overlay').addClass('active');
                        hideLoadingOverlay();
                    },
                    error: function(xhr) {
                        hideLoadingOverlay();
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'فشل في جلب بيانات مركز التكلفة',
                        });
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete-cost-center', function(e) {
                e.preventDefault();
                const costCenterId = $(this).data('id');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من استرجاع مركز التكلفة بعد الحذف!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoadingOverlay();

                        $.ajax({
                            url: `/cost-centers/delete/${costCenterId}`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                hideLoadingOverlay();


                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.message || 'تم حذف مركز التكلفة بنجاح',
                                }).then(() => {
                                    location.reload(); // Reload page to refresh table
                                });
                            },
                            error: function(xhr) {
                                hideLoadingOverlay();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: xhr.responseJSON?.message || 'فشل في حذف مركز التكلفة',
                                });
                            }
                        });
                    }
                });
            });

            // Handle save button (Add/Edit)
            $('.save-btn').on('click', function(e) {
                e.preventDefault();

                const costCenterId = $('#cost_center_id').val();
                const url = costCenterId ? `/cost-centers/update/${costCenterId}` : '{{ route("cost-center.store") }}';
                const method = costCenterId ? 'PUT' : 'POST';

                showLoadingOverlay();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        name: $('#name').val(),
                        code: $('#code').val(),
                        _token: '{{ csrf_token() }}',
                        _method: method
                    },
                    success: function(response) {
                        hideLoadingOverlay();
                        $('.account-form-modal').removeClass('active');
                        $('.modal-overlay').removeClass('active');
                        Swal.fire({
                            icon: 'success',
                            title: 'تم',
                            text: costCenterId ? 'تم تحديث مركز التكلفة بنجاح' : 'تم إضافة مركز التكلفة بنجاح',
                        }).then(() => {
                            location.reload(); // Reload page to refresh table
                        });
                    },
                    error: function(xhr) {
                        hideLoadingOverlay();
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'فشل في حفظ مركز التكلفة',
                        });
                    }
                });
            });

            // Cancel button
            $('.cancel-btn').on('click', function() {
                $('.account-form-modal').removeClass('active');
                $('.modal-overlay').removeClass('active');
                resetModal();
            });

            // Utility Functions
            function resetModal() {
                $('#cost-center-form')[0].reset();
                $('#cost_center_id').val('');
                $('#modal-title').text('إضافة مركز تكلفة جديد');
                $('.save-btn').text('حفظ');
            }


        });
    </script>
@endsection
