

@extends('financialaccounting.layouts.master')

@section('content')

    <h2>إضافة مستخدم جديد</h2>
    <form class="user-form" action="{{route('users-company.store')}}" >
        <div class="form-group">
            <label>اسم المستخدم</label>
            <input type="text" id="username" required>
        </div>
        <div class="form-group">
            <label>كلمة المرور</label>
            <input type="password" id="password" required>
        </div>
        <div class="form-group">
            <label>تأكيد كلمة المرور</label>
            <input type="password" id="confirmPassword" required>
        </div>
        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input type="email" id="email" required>
        </div>


        <div class="form-group">
            <label> نوع المستخدم</label>
            <select id="user_type" name="user_type" required >
                <option value="">اختر نوع المستخدم</option>
                <option value="company">تابع للشركة</option>
                <option value="branch">تابع لفرع</option>
            </select>
        </div>
        <div class="form-group" id="branch_select" style="display: none;">
            <label>الفرع</label>
            <select id="branch_id" name="branch_id" class="select2">
                <option value="">اختر الفرع</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>



    </form>
    <div class="permissions-section">
        <h3>الصلاحيات</h3>
        <div class="permissions-grid" id="permissions_grid">




        </div>
    </div>

    <div class="user-form-buttons">
        <button type="button" class="cancel-user-btn">إلغاء</button>
        <button type="button" class="save-user-btn" id="saveUser">حفظ المستخدم</button>
    </div>


    <div class="users-list">
        <h3>المستخدمون الحاليون</h3>

        <div class="accounts-table-container">
            <table class="accounts-table" id="accountsTable">
                <thead>
                <tr>
                    <th>  لاسم </th>
                    <th>  الإيميل</th>
                    <th>  الفرع</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $item)
                    <tr >
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        @if($item->model_type == 'BRANCH')
                            <td>{{ $item->branch->name }}</td>
                        @else
                            <td>فرع رئيسي</td>
                        @endif

                        <td>
                            <a href="{{ route('users-company.edit', $item->id) }}" class="edit-cost-center" data-id="{{ $item->id }}" style="margin: 10px; font-size: 20px;">
                                <i class="fas fa-edit" style="color: green;"></i></a>
                            <a  data-id="{{ $item->id }}" class="delete-cost-center deleteUser" style="margin: 10px; font-size: 20px;">
                                <i class="fas fa-trash" style="color: red;"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


    </div>

@endsection

<!-- تضمين jQuery و SweetAlert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // صلاحيات دور الشركة والفرع
    const companyPermissions = @json($companyPermissions);
    const branchPermissions = @json($branchPermissions);

    const permissionsLabels = {
        'view_accounts': ' شجرة الحسابات',
        'add_accounts':  ' جدول الحسابات',
        'edit_accounts': 'تعديل قيد',
        'delete_accounts': 'حذف قيد',
        'view_purchases': 'عرض المشتريات',
        'add_purchases': 'إضافة فاتورة مشتريات',
        'edit_purchases': 'تعديل فاتورة مشتريات',
        'delete_purchases': 'حذف فاتورة مشتريات',
        'view_sales': 'عرض المبيعات',
        'add_sales': 'إضافة فاتورة مبيعات',
        'edit_sales': 'تعديل فاتورة مبيعات',
        'delete_sales': 'حذف فاتورة مبيعات',
        'view_hr': 'عرض بيانات الموظفين',
        'add_hr': 'إضافة موظف',
        'edit_hr': 'تعديل بيانات موظف',
        'manage_salaries': 'إدارة الرواتب'
    };

    const permissionGroups = {
        'الحسابات': ['view_accounts', 'add_accounts', 'edit_accounts', 'delete_accounts'],
        'الحسابات': ['view_accounts', 'add_accounts', 'edit_accounts', 'delete_accounts'],
        'الحسابات العامة': ['view_purchases', 'add_purchases', 'edit_purchases', 'delete_purchases'],
        'الفواتير والمنتجات': ['view_sales', 'add_sales', 'edit_sales', 'delete_sales'],
        ' الحسابات الختاميه': ['view_sales', 'add_sales', 'edit_sales', 'delete_sales'],
        'الموارد البشريه': ['view_sales', 'add_sales', 'edit_sales', 'delete_sales'],
        'الإضافات ': ['view_sales', 'add_sales', 'edit_sales', 'delete_sales'],
        ' الإعدادات': ['view_hr', 'add_hr', 'edit_hr', 'manage_salaries']
    };

    $(document).ready(function () {
        $('#user_type').on('change', function () {
            let userType = $(this).val();
            let $permissionsGrid = $('#permissions_grid');
            let $branchSelect = $('#branch_select');
            $permissionsGrid.empty();

            let availablePermissions = [];
            if (userType === 'company') {
                availablePermissions = companyPermissions;
                $branchSelect.hide();
                $('#branch_id').removeAttr('required');
            } else if (userType === 'branch') {
                availablePermissions = branchPermissions;
                $branchSelect.show();
                $('#branch_id').attr('required', 'required');
            }

            $.each(permissionGroups, function (groupName, permissions) {
                let groupHtml = `<div class="permission-group"><h3>${groupName}</h3>`;
                let hasPermissions = false;
                permissions.forEach(function (permission) {
                    console.log(permission)

                    // if (availablePermissions.includes(permission)) {
                        groupHtml += `
                                <div class="permission-item">
                                    <input type="checkbox" id="${permission}" name="permissions[]" value="${permission}">
                                    <label for="${permission}">${permissionsLabels[permission]}</label>
                                </div>
                            `;
                        hasPermissions = true;
                    // }
                });

                groupHtml += `</div>`;
                // if (hasPermissions) {
                    $permissionsGrid.append(groupHtml);
                // }
            });
        });

        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('نجاح', 'تم إضافة المستخدم بنجاح!', 'success');
                        $('#userForm')[0].reset();
                        $('#permissions_grid').empty();
                        window.location.reload();
                    } else {
                        Swal.fire('خطأ', response.message || 'حدث خطأ أثناء الحفظ!', 'error');
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'فشل الاتصال بالخادم!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('خطأ', errorMessage, 'error');
                }
            });
        });

        $('#saveUser').on('click', function () {
            let name = $('#username').val();
            let password = $('#password').val();
            let confirmPassword = $('#confirmPassword').val();
            let email = $('#email').val();
            let userType = $('#user_type').val();
            let companyId = $('#company_id').val();
            let branchId = $('#branch_id').val();
            let permissions = [];

            // جمع الصلاحيات المختارة
            $('#permissions_grid input[type="checkbox"]:checked').each(function () {
                permissions.push($(this).val());
            });

            // التحقق من الحقول المطلوبة
            if (!name || !password || !confirmPassword || !email || !userType) {
                Swal.fire('خطأ', 'يرجى ملء جميع الحقول المطلوبة!', 'error');
                return;
            }
            if (password !== confirmPassword) {
                Swal.fire('خطأ', 'كلمة المرور وتأكيدها غير متطابقتين!', 'error');
                return;
            }
            if (userType === 'branch' && !branchId) {
                Swal.fire('خطأ', 'يرجى اختيار فرع!', 'error');
                return;
            }

            // إعداد البيانات للإرسال
            let data = {
                name: name,
                password: password,
                password_confirmation: confirmPassword,
                email: email,
                user_type: userType,
                company_id: companyId,
                branch_id: userType === 'branch' ? branchId : null,
                permissions: permissions,
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: "{{ route('users-company.store') }}",
                method: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        Swal.fire('نجاح', 'تم إضافة المستخدم بنجاح!', 'success');
                        resetForm();
                        window.location.reload();
                    } else {
                        Swal.fire('خطأ', response.message || 'حدث خطأ أثناء الحفظ!', 'error');
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'فشل الاتصال بالخادم!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('خطأ', errorMessage, 'error');
                }
            });
        });

        $('.cancel-user-btn').on('click', function () {
            resetForm();
        });

        $('.edit-user-btn').on('click', function () {
            let userId = $(this).data('id');
            window.location.href = `/users/${userId}/edit`;
        });

        $('.deleteUser').on('click', function () {
            let userId = $(this).data('id');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف المستخدم نهائيًا!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/users-company/${userId}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('تم الحذف', 'تم حذف المستخدم بنجاح!', 'success');
                                window.location.reload();
                            } else {
                                Swal.fire('خطأ', response.message || 'حدث خطأ أثناء الحذف!', 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('خطأ', 'فشل الاتصال بالخادم!', 'error');
                        }
                    });
                }
            });
        });

        function resetForm() {
            $('#username').val('');
            $('#password').val('');
            $('#confirmPassword').val('');
            $('#email').val('');
            $('#user_type').val('');
            $('#branch_id').val('');
            $('#permissions_grid').empty();
            $('#branch_select').hide();
        }


    });
</script>
