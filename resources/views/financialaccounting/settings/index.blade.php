
@extends('financialaccounting.layouts.master')

@section('content')

    <div id="settingsSection">
        <h1>الإعدادات</h1>
        <div class="settings-grid">
            <div class="settings-card">
                <i class="fas fa-building"></i>
                <h3>بيانات الشركة</h3>
                <p>تعديل المعلومات الأساسية للشركة</p>
                <div class="card-actions">
                    <a style="text-decoration: none;color: white" href="{{route('update.company.info.index')}}">
                    <button class="action-btn edit" title="تعديل"><i class="fas fa-edit"></i></button>
                    </a>
                </div>
            </div>
            <div class="settings-card">
                <i class="fas fa-database"></i>
                <h3>النسخ الاحتياطي</h3>
                <p>إدارة النسخ الاحتياطي للبيانات</p>
                <div class="card-actions">
                    <button class="action-btn backup" id="backupBtn" title="نسخ احتياطي"><i class="fas fa-download"></i></button>
                </div>
            </div>
            <div class="settings-card">

                <i class="fas fa-calendar-plus"></i>
                <h3>فتح سنة جديدة</h3>
                <p>إنشاء سنة مالية جديدة</p>
                <div class="card-actions">

                    <button class="action-btn new-year" title="سنة جديدة"><i class="fas fa-plus"></i></button>

                </div>

            </div>
            <div class="settings-card"> <a style="text-decoration: none;color: white" href="{{route('users-company.index')}}">


                <i class="fas fa-users-cog"></i>
                <h3>صلاحيات المستخدمين</h3>
                <p>إدارة صلاحيات المستخدمين</p>
                <div class="card-actions">

                    <button class="action-btn permissions"  title="الصلاحيات"><i class="fas fa-key"></i></button>
                </div>
                </a>
            </div>
        </div>
    </div>

@endsection

@section('css')

@endsection

@section('js')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#backupBtn').on('click', function() {
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم إنشاء نسخة احتياطية لقاعدة البيانات وسيبدأ التنزيل.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، نفذ النسخ',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showLoadingOverlay();

                        $.ajax({
                            url: '{{ route("backup.create") }}',
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            xhrFields: {
                                responseType: 'blob' // للتعامل مع الملف كـ Blob
                            },
                            success: function(data, status, xhr) {
                                hideLoadingOverlay();

                                // التحقق من نوع الاستجابة
                                const contentType = xhr.getResponseHeader('Content-Type');
                                if (contentType === 'application/json') {
                                    // تحويل Blob إلى JSON إذا كانت الاستجابة خطأ
                                    data.text().then(text => {
                                        const response = JSON.parse(text);
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'خطأ',
                                            text: response.message
                                        });
                                    });
                                } else {
                                    // التعامل مع الملف للتنزيل
                                    const disposition = xhr.getResponseHeader('Content-Disposition');
                                    let filename = 'backup.zip';
                                    if (disposition && disposition.indexOf('attachment') !== -1) {
                                        const matches = /filename="([^"]*)"/.exec(disposition);
                                        if (matches != null && matches[1]) filename = matches[1];
                                    }

                                    const url = window.URL.createObjectURL(new Blob([data]));
                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.setAttribute('download', filename);
                                    document.body.appendChild(link);
                                    link.click();
                                    link.remove();
                                    window.URL.revokeObjectURL(url);

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'تم النسخ',
                                        text: 'تم إنشاء النسخة الاحتياطية وتنزيلها بنجاح!'
                                    });
                                }
                            },
                            error: function(xhr) {
                                hideLoadingOverlay();
                                let errorMessage = 'حدث خطأ أثناء إنشاء النسخة الاحتياطية';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: errorMessage
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
