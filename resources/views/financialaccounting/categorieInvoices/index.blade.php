@extends('financialaccounting.layouts.master')

@section('content')
    <div class="categories-container">
        <div class="categories-header">
            <h1>تصنيفات المنتجات</h1>
            <button class="add-category-btn">
                <i class="fas fa-plus"></i>
                إضافة تصنيف جديد
            </button>
        </div>

        <div class="categories-grid">



        </div>
    </div>
    <div class="categories-form-modal">
        <div class="modal-content">
            <h2>إضافة تصنيف جديد</h2>
            <form >


                <div class="form-group">
                    <label>اسم التصنيف</label>
                    <input type="text" id="categoryName" required>
                </div>

                <div class="form-group">
                    <label>    الوصف</label>
                    <textarea type="text" id="categoryDescription" ></textarea>
                </div>


                <div class="form-group">
                    <label>نوع التصنيف</label>
                    <select id="categoryType" required>
                        <option value="">اختر النوع...</option>
                        <option value="0">رئيسي</option>
                        @foreach($categorie_invoices as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" class="save-btn" id="save-btn">حفظ</button>
                </div>
            </form>
        </div>
    </div>

    <div class="categories-form-modal-edit" id="categories-form-modal-edit">
        <div class="modal-content">
            <h2>تعديل  التصنيف</h2>
            <form >


                <div class="form-group">
                    <label>اسم التصنيف</label>
                    <input type="text" id="categoryNameEdit" required>
                </div>

                <div class="form-group">
                    <label>    الوصف</label>
                    <textarea type="text" id="categoryDescriptionEdit" ></textarea>
                </div>


                <div class="form-group">
                    <label>نوع التصنيف</label>
                    <select id="categoryTypeEdit" required>
                        <option value="">اختر النوع...</option>
                        <option value="0">رئيسي</option>
                        @foreach($categorie_invoices as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" data-id="" class="save-btnEdit" id="save-btn-edit">حفظ</button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.add-category-btn').on('click', function() {
                // Disable interaction with the rest of the page
                $('body').css({
                    'pointer-events': 'none', // Disable interaction with the content
                    'overflow': 'hidden'      // Disable scrolling on the page
                });

                // Create the overlay and apply styles directly using jQuery
                $('body').append('<div class="page-overlay"></div>');
                $('.page-overlay').css({
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%',
                    'background-color': 'rgba(0, 0, 0, 0.5)', // Black background with transparency
                    'z-index': '999', // Make sure it's above other content
                    'display': 'none' // Initially hidden
                }).fadeIn(); // Fade in the overlay

                // Show the modal
                $('.categories-form-modal').css({
                    'display': 'none',
                    'position': 'fixed',
                    'top': '50%',
                    'left': '50%',
                    'transform': 'translate(-50%, -50%)',
                    'z-index': '1000', // Make sure it's above the overlay
                    'background-color': '#fff',
                    'padding': '20px',
                    'box-shadow': '0 2px 10px rgba(0, 0, 0, 0.2)',
                    'border-radius': '8px',
                    'pointer-events': 'auto' // Allow interaction with the modal content
                }).fadeIn(); // Fade in the modal
            });

            // Close modal when clicking on "Cancel"
            $('.cancel-btn').on('click', function() {
                closeModal();
            });

            $('body').on('click', '.page-overlay', function() {
                closeModal();
            });

            function closeModal() {
                // Re-enable interaction with the page
                $('body').css({
                    'pointer-events': 'auto', // Enable interaction with the content
                    'overflow': 'auto'        // Enable scrolling
                });

                // Hide the modal and overlay
                $('.categories-form-modal').fadeOut();
                $('.categories-form-modal-edit').fadeOut();
                $('.page-overlay').fadeOut(function() {
                    $(this).remove(); // Remove the overlay from the DOM
                });
            }

            $('#save-btn').on('click', function(e) {
                e.preventDefault();

                let name = $('#categoryName').val();
                let parentId = $('#categoryType').val();
                let description = $('#categoryDescription').val();


                $.ajax({
                    url: '{{ route('categorie-invoices.store') }}',
                    method: 'POST',
                    data: {
                        name: name,
                        parent_id: parentId,
                        description: description,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            fetchCategories();
                           closeModal();

                        } else {
                            alert('حدث خطأ أثناء الحفظ');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('حدث خطأ. الرجاء المحاولة مرة أخرى.');
                    }
                });
            });
            fetchCategories();
            function fetchCategories() {
                showLoadingOverlay();
                $.ajax({
                    url: "{{route('categorie-invoices.getCategories')}}",
                    method: "GET",
                    success: function (data) {
                        let categoriesHtml = "";

                        data.forEach(category => {
                            let categoryType = category.parent_id ? "فرعي" : "رئيسي";

                            categoriesHtml += `
                            <div class="category-card">
                                <div class="category-info">
                                    <h3>${category.name}</h3>
                                    <p class="category-type ${category.parent_id != '0' ? 'sub' : 'main'}">
                                        ${category.parent_id != '0' ? 'فرعي' : 'رئيسي'}
                                    </p>
                                </div>
                                <div class="category-actions">
                                    <button class="action-btn edit" title="تعديل" data-id="${category.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete" title="حذف" data-id="${category.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        });

                        $(".categories-grid").html(categoriesHtml);
                        hideLoadingOverlay();
                    },
                    error: function () {
                        alert("حدث خطأ أثناء جلب التصنيفات.");
                    }
                });
            }
            $(document).on('click', '.action-btn.edit', function () {
                let categoryId = $(this).data('id');

                // جلب بيانات التصنيف من الخادم
                $.ajax({
                    url: '/categorie-invoices/categories/' + categoryId + '/edit',
                    method: 'GET',
                    success: function (data) {
                        if (data.success) {
                            $('#categoryNameEdit').val(data.category.name);
                            $('#categoryDescriptionEdit').val(data.category.description);
                            $('#categoryTypeEdit').val(data.category.parent_id);
                            $('#save-btn-edit').attr('data-id', categoryId);

                            $('body').css({
                                'pointer-events': 'none',
                                'overflow': 'hidden'
                            });
                            $('body').append('<div class="page-overlay"></div>');
                            $('.page-overlay').css({
                                'position': 'fixed',
                                'top': '0',
                                'left': '0',
                                'width': '100%',
                                'height': '100%',
                                'background-color': 'rgba(0, 0, 0, 0.5)', // Black background with transparency
                                'z-index': '999', // Make sure it's above other content
                                'display': 'none' // Initially hidden
                            }).fadeIn();


                            $('#categories-form-modal-edit').css({
                                'display': 'none',
                                'position': 'fixed',
                                'top': '50%',
                                'left': '50%',
                                'transform': 'translate(-50%, -50%)',
                                'z-index': '1000', // Make sure it's above the overlay
                                'background-color': '#fff',
                                'padding': '20px',
                                'box-shadow': '0 2px 10px rgba(0, 0, 0, 0.2)',
                                'border-radius': '8px',
                                'pointer-events': 'auto' // Allow interaction with the modal content
                            }).fadeIn();
                        } else {
                            Swal.fire('خطأ', 'تعذر جلب بيانات التصنيف.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('خطأ', 'حدث خطأ أثناء جلب البيانات.', 'error');
                    }
                });
            });
            $(document).on('click', '.action-btn.delete', function () {
                var categoryId = $(this).data('id');

                // التأكيد باستخدام SweetAlert
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من استرجاع هذا التصنيف بعد الحذف.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'نعم، احذفها!',
                    cancelButtonText: 'إلغاء',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // إرسال طلب الحذف عبر AJAX
                        $.ajax({
                            url: '/categorie-invoices/categories/' + categoryId,
                            method: 'GET',
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        'تم!',
                                        response.message,
                                        'success'
                                    );
                                    fetchCategories(); // إعادة تحميل التصنيفات بعد الحذف
                                } else {
                                    Swal.fire(
                                        'خطأ!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function () {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'حدث خطأ',
                                    text: 'حدث خطأ أثناء الحذف.',
                                });
                            }
                        });
                    }
                });
            });

            $('#save-btn-edit').on('click', function (e) {
                e.preventDefault();
                let categoryId = $(this).attr('data-id');
                let name = $('#categoryNameEdit').val();
                let parentId = $('#categoryTypeEdit').val();
                let description = $('#categoryDescriptionEdit').val();
                let url = '/categorie-invoices/categories/' + categoryId;
                let method = 'PUT';
                console.log(name);
                console.log(parentId);
                console.log(description);
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _method: method,
                        name: name,
                        parent_id: parentId,
                        description: description,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {

                        closeModal();
                        fetchCategories();

                        Swal.fire('تم!', 'تم تحديث التصنيف بنجاح.', 'success');


                    },
                    error: function () {
                        Swal.fire('خطأ', 'حدث خطأ أثناء التحديث.', 'error');
                    }
                });
            });
        });

    </script>
@endsection
