@extends('financialaccounting.layouts.master')
@section('content')
    <div class="products-container">
        <div class="products-header">
            <h1>المنتجات</h1>
            <button class="add-product-btn">
                <i class="fas fa-plus"></i>
                إضافة منتج جديد
            </button>
        </div>
        <div class="products-grid"></div>
    </div>

    <div class="product-form-modal">
        <div class="modal-content">
            <h2 id="modal-title">إضافة منتج جديد</h2>
            <div class="tabs">
                <button class="tab-btn active" data-tab="basic">البيانات الأساسية</button>
                <button class="tab-btn" data-tab="pricing">الأسعار والتكاليف</button>
                <button class="tab-btn" data-tab="branch">الفرع</button>
                <button class="tab-btn" data-tab="images">الصور</button>
            </div>

            <form id="productForm">
                <div class="tab-content active" data-tab="basic">
                    <div class="form-group">
                        <label>اسم المنتج</label>
                        <input type="text" id="productName" name="productName" required>
                    </div>
                    <div class="form-group">
                        <label>التصنيف</label>
                        <select id="productCategory" name="productCategory" required>
                            <option value="">اختر التصنيف...</option>
                            @foreach($categories as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الكمية</label>
                        <input type="number" id="stock" name="stock" required>
                    </div>
                    <div class="form-group">
                        <label>الوصف</label>
                        <textarea id="productDescription" name="productDescription"></textarea>
                    </div>
                </div>

                <div class="tab-content" data-tab="pricing">
                    <div class="form-group">
                        <label>سعر البيع</label>
                        <input type="number" id="productPrice" name="productPrice" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>تكلفة الشراء</label>
                        <input type="number" id="productCost" name="productCost" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>الحد الأدنى للسعر</label>
                        <input type="number" id="productMinPrice" name="productMinPrice" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>نسبة الضريبة</label>
                        <input type="number" id="productTax" name="productTax" step="0.01">
                    </div>
                </div>

                <div class="tab-content" data-tab="branch">
                    <div class="form-group">
                        <label>الفرع</label>
                        <select id="branch_id" name="branch_id" required>
                            <option value="all">جميع الفروع</option>
                            @foreach($branches as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="tab-content" data-tab="images">
                    <div class="form-group">
                        <label>صور المنتج</label>
                        <div class="image-upload-container">
                            <div class="image-upload-zone">
                                <input type="file" id="productImages" name="images[]" multiple accept="image/*" class="image-input">
                                <div class="upload-placeholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>اسحب الصور هنا أو انقر للاختيار</p>
                                </div>
                            </div>
                            <div class="product-images-preview"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="submit" class="save-btn" id="save-btn">حفظ</button>
                </div>
                <input type="hidden" id="product_id" name="product_id">
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            fetchProducts();

            function fetchProducts() {
                showLoadingOverlay();
                $.ajax({
                    url: "{{ route('products.fetch') }}",
                    type: "GET",
                    dataType: "json",
                    success: function (response) {
                        $('.products-grid').empty();

                        if (response.length === 0) {
                            $('.products-grid').html('<p class="no-products">لا يوجد منتجات حاليا</p>');
                        }

                        response.forEach(product => {
                            let productHTML = `
                                <div class="product-card">
                                    <div class="product-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="product-info">
                                        <h3>${product.name}</h3>
                                        <p class="product-category"><i class="fas fa-tag"></i> ${product.category.name}</p>
                                        <p class="product-price"><i class="fas fa-money-bill-wave"></i> ${product.price} ريال</p>
                                    </div>
                                    <div class="product-actions">
                                        <button class="action-btn view" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="action-btn edit" title="تعديل" data-id="${product.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="action-btn delete" title="حذف" data-id="${product.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            $('.products-grid').append(productHTML);
                            hideLoadingOverlay();
                        });
                    },
                    error: function () {
                        Swal.fire('خطأ', 'حدث خطأ أثناء جلب المنتجات!', 'error');
                    }
                });
            }

            // Open modal for adding a new product
            $('.add-product-btn').on('click', function() {
                resetModal();
                openModal('إضافة منتج جديد', 'حفظ');
            });

            // Handle edit button click
            $(document).on('click', '.edit', function() {
                showLoadingOverlay();
                const productId = $(this).data('id');
                $.ajax({
                    url: `/products/edit/${productId}`,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Populate form fields
                            $('#productName').val(response.product.name);
                            $('#productCategory').val(response.product.category_id);
                            $('#stock').val(response.product.stock);
                            $('#productDescription').val(response.product.description || '');
                            $('#productPrice').val(response.product.price);
                            $('#productCost').val(response.product.cost);
                            $('#productMinPrice').val(response.product.min_price || '');
                            $('#productTax').val(response.product.tax || '');
                            $('#branch_id').val(response.product.branch_id || 'all');
                            $('#product_id').val(response.product.id);

                            // Open modal with "Edit" title
                            openModal('تعديل منتج', 'تحديث');
                            hideLoadingOverlay();
                        } else {
                            Swal.fire('خطأ', 'فشل تحميل بيانات المنتج.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('خطأ', 'حدث خطأ أثناء جلب بيانات المنتج.', 'error');
                    }
                });
            });

            // Handle delete button click
            $(document).on('click', '.delete', function() {
                const productId = $(this).data('id');
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من استرجاع هذا المنتج بعد الحذف!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/products/delete/${productId}`,
                            type: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('تم الحذف!', 'تم حذف المنتج بنجاح.', 'success').then(() => fetchProducts());
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

            $('#productForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                const productId = $('#product_id').val();
                const url = productId ? `/products/update/${productId}` : "{{ route('products.store') }}";
                const method = productId ? 'POST' : 'POST'; // Laravel uses POST with _method=PUT for updates

                // Fb
                console.log(method);

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status !== 201 && response.status !== 200) {
                            Swal.fire('خطأ', response.errors || 'حدث خطأ أثناء الحفظ.', 'error');
                        } else {
                            fetchProducts();
                            closeModal();
                            Swal.fire('تم!', productId ? 'تم تحديث المنتج بنجاح.' : 'تم إضافة المنتج بنجاح.', 'success');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('خطأ', 'حدث خطأ أثناء الحفظ!', 'error');
                    }
                });
            });

            // Utility Functions
            function openModal(title = 'إضافة منتج جديد', buttonText = 'حفظ') {
                $('body').append('<div class="page-overlay"></div>');
                $('.page-overlay').fadeIn();
                $('.product-form-modal').fadeIn().css({ 'display': 'flex' });

                $('#modal-title').text(title);
                $('#save-btn').text(buttonText);

                // Set first tab as active
                $('.tab-btn').removeClass('active');
                $('.tab-content').removeClass('active');
                $('.tab-btn:first').addClass('active');
                $('.tab-content:first').addClass('active');
            }

            function closeModal() {
                $('.product-form-modal, .page-overlay').fadeOut(function() {
                    $('.page-overlay').remove();
                });
            }

            function resetModal() {
                $('#productForm')[0].reset();
                $('#product_id').val('');
                $('.product-images-preview').empty();
            }

            $('.cancel-btn, .page-overlay').on('click', closeModal);

            // Tab Switching
            $('.tab-btn').on('click', function () {
                let tabName = $(this).data('tab');
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                $('.tab-content').removeClass('active');
                $('.tab-content[data-tab="' + tabName + '"]').addClass('active');
            });



        });
    </script>
@endsection
