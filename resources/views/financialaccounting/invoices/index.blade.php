
@extends('financialaccounting.layouts.master')
@section('content')

    <div id="salesInvoiceSection" >
        <div class="invoices-container">
            <div class="invoices-header">
                <h1>الفواتير </h1>
            </div>

            <div class="invoice-search">
                <input type="search" id="searchKeyword" placeholder="بحث في الفواتير...">
                <select >
                    <option value="all">جميع الأنواع</option>
                    <option value="Purchases">مشتريات</option>
                    <option value="PurchasesReturn">مرتجع مشتريات</option>
                    <option value="Sales">مبيعات</option>
                    <option value="SalesReturn">مرتجع مبيعات</option>
                </select>
            </div>

            <div class="invoices-grid">


            </div>
        </div>
    </div>

    <div class="invoice-form-modal">
        <div class="modal-content">
            <h2>فاتورة مبيعات جديدة</h2>
            <form >
                <div class="invoice-header-section">
                    <div class="form-row">



                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>التاريخ</label>
                            <input type="date" id="invoice_date" required>
                            <span id="error-invoice_date" class="error"></span>
                        </div>
                        <div class="form-group">
                            <label>العميل</label>
                            <select id="customer_id" required>
                                <option value="">اختر العميل...</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الفرع</label>
                            <select id="branchSelect" required>
                                <option value="">اختر الفرع...</option>
                                @foreach($branches as $item)
                                    <option value="{{$item->id}}"> {{$item->name}}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                </div>

                <div class="invoice-items-section">
                    <h3>الأصناف</h3>
                    <div class="invoice-items-table">
                        <table>
                            <thead>
                            <tr>
                                <th>اسم الصنف</th>

                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="invoiceItemsBody">
                            </tbody>
                        </table>
                        <button type="button" class="add-item-btn">
                            <i class="fas fa-plus"></i>
                            إضافة صنف
                        </button>
                    </div>
                </div>

                <div class="invoice-totals-section">
                    <div class="totals-grid">
                        <div class="total-group">
                            <label>الإجمالي</label>
                            <input type="number" id="subtotal" readonly>
                        </div>
                        <div class="total-group">
                            <label>الخصم</label>
                            <input type="number" id="discount" step="0.01">
                        </div>
                        <div class="total-group">
                            <label>الضريبة (%15)</label>
                            <input type="number" id="tax" readonly>
                        </div>
                        <div class="total-group">
                            <label>الصافي بعد الضريبة</label>
                            <input type="number" id="total" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn">إلغاء</button>
                    <button type="button" class="save-btn" id="save-btn">حفظ الفاتورة</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script >
        function printObject(url) {
            showLoadingOverlay();
            let iframe = document.createElement("iframe");
            iframe.src = url;
            iframe.style.display = "none";
            document.body.appendChild(iframe);
            iframe.onload = function () {
                hideLoadingOverlay();
                iframe.contentWindow.print();
            };
        }
        $(document).ready(function () {

            getSelInvoices('all','');

            let products = @json($products);
            function getAvailableProducts() {
                let selectedProducts = [];
                $('.item-select').each(function () {
                    let selectedValue = $(this).val();
                    if (selectedValue) selectedProducts.push(selectedValue);
                });

                let options = '';
                products.forEach(product => {
                    if (!selectedProducts.includes(product.id.toString())) {
                        options += `<option value="${product.id}">${product.name}</option>`;
                    }
                });

                return options;
            }
            function getSelInvoices(status = '',search = '') {
                $.ajax({
                    url: '/getInvoices',
                    type: 'GET',
                    data: { status: status ,search:search }, // إرسال الحالة المحددة كمعامل
                    dataType: 'json',
                    success: function (data) {
                        var body = $('.invoices-grid');
                        body.empty();
                        data.forEach(function (invoice) {
                            let classStatus = '';
                            let invoiceType = '';
                            if (invoice.invoice_type == 'Purchases') {
                                classStatus = 'cancelled';
                                invoiceType = 'مشتريات';
                            } else if (invoice.invoice_type == 'PurchasesReturn') {
                                classStatus = 'pending';
                                invoiceType = 'مرتجع مشتريات';
                            } else if (invoice.invoice_type == 'SalesReturn') {
                                classStatus = 'paid';
                                invoiceType = 'مرتجع مبيعات';
                            } else if (invoice.invoice_type == 'Sales') {
                                classStatus = 'paid';
                                invoiceType = 'مبيعات';
                            }
                            // تحديد ما إذا كان العميل أو المورد
                            let entityType = invoice.customer ? 'العميل' : 'المورد';
                            let entityName = invoice.customer
                                ? (invoice.customer.name || 'غير محدد')
                                : (invoice.supplier ? invoice.supplier.name : 'غير محدد');
                            let  printUrl= `/invoices/print/${invoice.id}`;

                            var row = `
                                <div class="invoice-card">
                            <span class="invoice-status ${classStatus}">${invoiceType}</span>
                            <div class="invoice-number">فاتورة رقم: ${invoice.invoice_number}</div>
                            <div class="invoice-date">
                                <i class="far fa-calendar-alt"></i> ${invoice.invoice_date}
                            </div>
                            <div class="invoice-details">
                                <p><span><i class="fas fa-user"></i> ${entityType}</span><strong>${entityName}</strong></p>                                <p><span><i class="fas fa-code-branch"></i> الفرع</span><strong>${invoice.branch ? invoice.branch.name : 'غير محدد'}</strong></p>
                                <p><span><i class="fas fa-user-tie"></i> الموظف</span><strong>${invoice.employee_id}</strong></p>
                            </div>
                            <div class="invoice-total">${invoice.total} ريال</div>
                            <div class="card-actions">
                             <a href="" target="_blank" style="text-decoration: none">
                             <a  onclick="printObject('${printUrl}')" style="text-decoration: none;" target="_blank">
                                <button class="action-btn view" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a>

                            <a href="/invoices/edit/${invoice.id}" style="text-decoration: none;" target="_blank">
                                <button class="action-btn view" title="عرض">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </a>


                                <button class="action-btn delete" title="حذف"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>`;
                            body.append(row);
                        });
                    },
                    error: function () {
                        alert('حدث خطأ أثناء تحميل الفواتير');
                    }
                });
            }

            $('#searchKeyword').on('keypress', function(event) {
                if (event.which === 13) { // زر Enter
                    getSelInvoices($('.invoice-search select').val(),$('#searchKeyword').val());
                }
            });

            $('.invoice-search select').on('change', function () {
                let selectedStatus = $(this).val();
                getSelInvoices(selectedStatus);
            });


            $('.add-invoice-btn').on('click', function() {
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
                $('.invoice-form-modal').css({
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

            // Close modal when clicking on the overlay
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
                $('.invoice-form-modal').fadeOut();
                $('.page-overlay').fadeOut(function() {
                    $(this).remove(); // Remove the overlay from the DOM
                });
            }



            const addItemBtn = $('.add-item-btn');
            const itemsBodyModal = $('#invoiceItemsBody');
            const subtotalInput = $('#subtotal');
            const discountInput = $('#discount');
            const taxInput = $('#tax');
            const totalInput = $('#total');

            addItemBtn.on('click', function () {
                const availableProducts = getAvailableProducts(); // استرجاع المنتجات غير المختارة
                let itemIndex = 1;
                const newRow = $('<tr></tr>').html(`
                    <td>
                        <select name="items[${itemIndex}][productId]" class="item-select" required>
                            <option value="">اختر الصنف...</option>
                            ${availableProducts}
                        </select>
                        <span class="error error-item-${itemIndex}-productId"></span>
                    </td>

                    <td>
                        <input type="number" name="items[${itemIndex}][quantity]" class="quantity-input" min="1" value="1" required>
                        <span class="error error-item-${itemIndex}-quantity"></span>
                    </td>
                    <td>
                        <input type="number" name="items[${itemIndex}][price]" class="price-input" step="0.01" value="100.00" required>
                            <span class="error error-item-${itemIndex}-price"></span>
                    </td>
                    <td class="">
                        <input type="number" name="items[${itemIndex}][total]" class="item-total" value="0.00" readonly>
                        <span class="error error-item-${itemIndex}-item-total"></span>
                    </td>


                    <td>
                        <button type="button" class="remove-item-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `);
                let itemSelect = newRow.find('.item-select');
                itemSelect.on('change', function () {
                    // updateProductOptions();
                    let selectedProduct = products.find(p => p.id == $(this).val());
                    if (selectedProduct) {
                        newRow.find('.price-input').val(selectedProduct.price || 0);
                        calculateRowTotal(newRow);
                        calculateInvoiceTotals();
                    }
                });
                itemsBodyModal.append(newRow);
                itemIndex++;

                const quantityInput = newRow.find('.quantity-input');
                const priceInput = newRow.find('.price-input');
                const removeBtn = newRow.find('.remove-item-btn');

                // Update row total when quantity or price changes
                quantityInput.add(priceInput).on('input', function () {
                    calculateRowTotal(newRow);
                    calculateInvoiceTotals();
                });
                $(document).on('input', '.quantity-input', function () {
                    let row = $(this).closest('tr');
                    let productId = row.find('.item-select').val();
                    let selectedProduct = products.find(p => p.id == productId);
                    console.log(selectedProduct);
                    if (!selectedProduct) return;

                    let maxStock = selectedProduct.stock; // الكمية المتاحة في المخزون
                    let quantity = parseInt($(this).val()) || 0;
                    if (quantity <= 0) {

                        $(this).val(1); // تعيين الحد الأدنى إلى 1
                        $(this).css('border', '2px solid red');
                    } else if (quantity > maxStock) {
                        // إذا كانت الكمية أكبر من المخزون المتاح
                        alert(`الكمية المتاحة من هذا المنتج هي ${maxStock} فقط.`);
                        $(this).val(maxStock); // ضبط الكمية إلى الحد الأقصى المتاح
                        $(this).css('border', '2px solid red');
                    } else {
                        // إذا كانت الكمية صحيحة، إزالة التلوين الأحمر
                        $(this).css('border', '');
                    }
                    calculateRowTotal(row);
                    calculateInvoiceTotals()
                });

                // Remove item event
                removeBtn.on('click', function () {
                    newRow.remove();
                    calculateInvoiceTotals();
                });

                // Calculate initial row total
                calculateRowTotal(newRow);
                calculateInvoiceTotals();
            });

            // Function to calculate row total
            function calculateRowTotal(row) {
                const quantity = parseFloat(row.find('.quantity-input').val()) || 0;
                const price = parseFloat(row.find('.price-input').val()) || 0;
                const total = quantity * price;
                row.find('.item-total').val(total.toFixed(2));
            }

            // Function to calculate subtotal, tax, and total
            function calculateInvoiceTotals() {
                let subtotal = 0;

                // Sum all row totals
                $('.item-total').each(function () {
                    subtotal += parseFloat($(this).val()) || 0;
                });

                // Get discount value
                let discount = parseFloat(discountInput.val()) || 0;

                // Calculate tax (15%)
                let tax = (subtotal - discount) * 0.15;

                // Calculate final total
                let total = subtotal - discount + tax;

                // Update inputs
                subtotalInput.val(subtotal.toFixed(2));
                taxInput.val(tax.toFixed(2));
                totalInput.val(total.toFixed(2));
            }

            // Recalculate totals when discount changes
            discountInput.on('input', function () {
                calculateInvoiceTotals();
            });

            $('#save-btn').on('click', function(e) {
                e.preventDefault();

                let invoiceDate = $('#invoice_date').val();
                let customerId = $('#customer_id').val();
                let branchId = $('#branchSelect').val();
                let items = [];

                $('#invoiceItemsBody tr').each(function() {
                    let item = {
                        productId: $(this).find('.item-select').val(),
                        quantity: $(this).find('.quantity-input').val(),
                        price: $(this).find('.price-input').val(),
                        total: $(this).find('.item-total').val() // إضافة الإجمالي لكل منتج
                    };
                    items.push(item);
                });

                let subtotal = $('#subtotal').val();
                let discount = $('#discount').val();
                let tax = $('#tax').val();
                let total = $('#total').val();

                $.ajax({
                    url: '{{ route('invoices.store') }}',
                    method: 'POST',
                    data: {
                        invoice_date: invoiceDate,
                        customer_id: customerId,
                        branch_id: branchId,
                        items: items,
                        subtotal: subtotal,
                        discount: discount,
                        tax: tax,
                        total: total,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('تم حفظ الفاتورة بنجاح!');
                            location.reload();
                        } else {
                            Swal.fire('تم!', 'تم إضافة المنتج  بنجاح.', 'success');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = [];

                            $.each(errors, function(key, messages) {
                                let errorKey = key.replace(/\./g, '-'); // e.g., items.0.quantity -> items-0-quantity
                                let errorElement = $(`#error-${errorKey}`);
                                errorMessages.push(messages[0]);
                            });

                            // Show a single SweetAlert with all errors
                            if (errorMessages.length > 0) {
                                $('.invoice-form-modal').css({
                                    'pointer-events': 'none',
                                    'opacity': '0.7' // Optional: Visually indicate it’s disabled
                                });
                                $('.page-overlay').css('pointer-events', 'auto'); // Ensure overlay blocks clicks

                                Swal.fire({
                                    title: 'خطأ في الإدخال',
                                    html: errorMessages.join('<br>'),
                                    icon: 'error',
                                    confirmButtonText: 'موافق',
                                    allowOutsideClick: false, // Prevent closing by clicking outside
                                    allowEscapeKey: false // Prevent closing with Escape key
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Re-enable modal interaction
                                        $('.invoice-form-modal').css({
                                            'pointer-events': 'auto',
                                            'opacity': '1'
                                        });
                                    }
                                });
                            }
                        } else {
                            Swal.fire('خطأ!', 'حدث خطأ غير متوقع أثناء حفظ الفاتورة.', 'error');
                        }
                    }
                });
            });
            $(document).on('click', '.delete', function() {
                let invoiceCard = $(this).closest('.invoice-card');
                let invoiceNumber = invoiceCard.find('.invoice-number').text().replace('فاتورة رقم: ', '');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف الفاتورة رقم ${invoiceNumber} نهائيًا!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفها!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/sales-invoices/${invoiceNumber}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    invoiceCard.fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                    Swal.fire('تم الحذف!', 'تم حذف الفاتورة بنجاح.', 'success');
                                } else {
                                    Swal.fire('خطأ!', 'حدث خطأ أثناء حذف الفاتورة.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('خطأ!', 'حدث خطأ أثناء تنفيذ العملية.', 'error');
                            }
                        });
                    }
                });
            });



        });
    </script>
@endsection
