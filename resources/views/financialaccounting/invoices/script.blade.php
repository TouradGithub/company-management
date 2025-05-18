<script>
    const { jsPDF } = window.jspdf;
    const products = @json($products);
    const customers = @json($customers);

    const suppliers = @json($suppliers??[]);

    $(document).ready(function() {
        $('.customers, .suppliers').select2();
        addNewItem('#salesItemsTable');
        addNewItem('#purchaseItemsTable');
        addNewItem('#salesReturnItemsTable');
        addNewItem('#purchaseReturnItemsTable');
        updateCustomers();
        updateSuppliers();


        ['sales', 'purchase', 'salesReturn', 'purchaseReturn'].forEach(section => {
            const tableId = `#${section}ItemsTable`;
            $(`${tableId} #${section}DiscountPercent, ${tableId} #${section}TaxPercent`).on('input', function() {
                calculateGrandTotal(tableId);
            });
        });


        $('#originalSalesInvoiceNumber').on('change', function() {
            fetchInvoiceData('sales-return', $(this).val());
            console.log("OK SEL");
        });
        $('#originalPurchaseInvoiceNumber').on('change', function() {
            fetchInvoiceData('purchase-return', $(this).val());
            console.log("OK PAR");
        });
    });

    function updateCustomers() {
        $('.sales-customer-id, .sales-return-customer-id').empty();
        let defaultOption = `<option value="">اختر عميل</option>`;
        $('.sales-customer-id, .sales-return-customer-id').append(defaultOption);
        $.each(customers, function(index, customer) {
            let option = `<option data-credit="${customer.credit_limit}" value="${customer.id}">${customer.name} - ${customer.contact_info}</option>`;
            $('.sales-customer-id, .sales-return-customer-id').append(option);
        });
    }

    function updateSuppliers() {
        $('.purchase-supplier-id, .purchase-return-supplier-id').empty();
        let defaultOption = `<option value="">اختر مورد</option>`;
        $('.purchase-supplier-id, .purchase-return-supplier-id').append(defaultOption);
        $.each(suppliers, function(index, supplier) {
            console.log(supplier);
            let option = `<option value="${supplier.id}">${supplier.name} - ${supplier.contact_info}</option>`;
            $('.purchase-supplier-id, .purchase-return-supplier-id').append(option);
        });
    }

    function getSelectedItems(tableId) {
        const selectedIds = [];
        $(`${tableId} tbody tr`).each(function() {
            const selectedValue = $(this).find('.item-select').val();
            if (selectedValue) selectedIds.push(selectedValue);
        });
        return selectedIds;
    }

    function addNewItem(tableId) {
        const $tbody = $(`${tableId} tbody`);
        const rowCount = $tbody.children().length + 1;
        const selectedIds = getSelectedItems(tableId);
        const availableProducts = products.filter(product => !selectedIds.includes(product.id.toString()));

        const $newRow = $(`
            <tr>
                <td class="serial">${rowCount}</td>
                <td>
                    <select class="item-select form-select" style="width: 100%;">
                        <option value="">اختر صنف</option>
                        ${availableProducts.map(product =>
            `<option value="${product.id}" data-price="${product.price}">${product.id} - ${product.name}</option>`
        ).join('')}
                    </select>
                </td>
                <td>
                    <select class="item-unit">
                        <option value="حبة" selected>حبة</option>
                        <option value="كرتون">كرتون</option>
                        <option value="كيلو">كيلو</option>
                        <option value="نص كيلو">نص كيلو</option>
                    </select>
                </td>
                <td><input type="number" class="item-qty" min="1" value="1"></td>
                <td><input type="number" class="item-price" step="0.01" placeholder="0.00"></td>
                <td class="item-total">0.00</td>
                <td><button type="button" class="delete-btn">×</button></td>
            </tr>
        `);
        $tbody.append($newRow);

        const $select = $newRow.find('.item-select');
        $select.select2({
            placeholder: "اختر صنف",
            allowClear: true,
            dir: "rtl",
            minimumResultsForSearch: Infinity
        });

        addCalculationListeners($newRow, tableId);
        $select.focus();
    }

    function addCalculationListeners($row, tableId) {
        const $select = $row.find('.item-select');
        $select.on('change', function() {
            const selectedOption = $(this).find(':selected');
            const price = selectedOption.data('price') || 0;
            $row.find('.item-price').val(price);
            calculateRowTotal($row, tableId);
            updateAllSelectOptions(tableId);
        });

        $row.find('.item-qty, .item-price').on('input', function() {
            calculateRowTotal($row, tableId);
        });

        $row.find('.delete-btn').on('click', function() {
            const $tbody = $(`${tableId} tbody`);
            if ($tbody.children().length > 1) {
                $row.find('.item-select').select2('destroy');
                $row.remove();
                updateSerialNumbers(tableId);
                calculateGrandTotal(tableId);
                updateAllSelectOptions(tableId);
            }
        });
    }

    function updateSerialNumbers(tableId) {
        $(`${tableId} tbody tr`).each((index, row) => {
            $(row).find('.serial').text(index + 1);
        });
    }

    function calculateRowTotal($row, tableId) {
        const qty = parseFloat($row.find('.item-qty').val()) || 0;
        const price = parseFloat($row.find('.item-price').val()) || 0;
        const total = qty * price;
        $row.find('.item-total').text(total.toFixed(2));
        calculateGrandTotal(tableId);
    }

    function calculateGrandTotal(tableId) {
        if (!$(tableId).length) {
            console.error(`Table with ID ${tableId} not found in DOM`);
            return;
        }

        const section = tableId.replace('#', '').replace('ItemsTable', '');
        const subtotal = $(`${tableId} .item-total`)
            .map((i, el) => parseFloat($(el).text()) || 0)
            .get()
            .reduce((sum, val) => sum + val, 0);

        const $subtotalElement = $(`#${section}Subtotal`);
        if ($subtotalElement.length) $subtotalElement.text(subtotal.toFixed(2));

        const $discountPercentElement = $(`#${section}DiscountPercent`);
        const discountPercent = parseFloat($discountPercentElement.val()) || 0;
        const discountAmount = subtotal * (discountPercent / 100);
        const $discountAmountElement = $(`#${section}DiscountAmount`);
        if ($discountAmountElement.length) $discountAmountElement.text(discountAmount.toFixed(2));

        const taxableAmount = subtotal - discountAmount;
        const $taxPercentElement = $(`#${section}TaxPercent`);
        const taxPercent = parseFloat($taxPercentElement.val()) || 0;
        const taxAmount = taxableAmount * (taxPercent / 100);
        const $taxAmountElement = $(`#${section}TaxAmount`);
        if ($taxAmountElement.length) $taxAmountElement.text(taxAmount.toFixed(2));

        const grandTotal = taxableAmount + taxAmount;
        const $grandTotalElement = $(`#${section}GrandTotal`);
        if ($grandTotalElement.length) $grandTotalElement.text(grandTotal.toFixed(2));
    }
    function updateAllSelectOptions(tableId) {
        const selectedIds = getSelectedItems(tableId);
        $(`${tableId} tbody tr`).each(function() {
            const $select = $(this).find('.item-select');
            const currentValue = $select.val();
            $select.select2('destroy');

            const availableProducts = products.filter(product =>
                !selectedIds.includes(product.id.toString()) || product.id.toString() === currentValue
            );

            $select.empty().append('<option value="">اختر صنف</option>');
            availableProducts.forEach(product => {
                $select.append(`<option value="${product.id}" data-price="${product.price}">${product.id} - ${product.name}</option>`);
            });

            $select.val(currentValue);
            $select.select2({
                placeholder: "اختر صنف",
                allowClear: true,
                dir: "rtl",
                minimumResultsForSearch: Infinity
            });
        });
    }

    let currentSection = 'sales';
    function addNewClient(section) {
        currentSection = section;
        $('#modal-title').text(section.includes('purchase') ? 'إضافة مورد' : 'إضافة عميل');
        $('body').append('<div class="page-overlay"></div>');
        $('.page-overlay').fadeIn();
        $('.invoice-form-modal').fadeIn();
    }

    function closeModal() {
        $('.invoice-form-modal').fadeOut();
        $('.page-overlay').fadeOut(function() {
            $(this).remove();
        });
    }

    function saveCustomerSupplier() {
        const data = {
            name: $('#name_customer_supplier').val(),
            contact_info: $('#contact_customer_supplier').val(),
            branch_id: $('#branch_id_customer_supplier').val(),
            account_id: $('#account_id_customer_supplier').val(),
            credit_limit: $('#credit_limit').val(),
            tax_number: $('#tax_number').val(),
        };

        const url = currentSection.includes('purchase') ? '/branch/suppliers/storeSupplier' : '/branch/customers/storeCustomer';
        const targetArray = currentSection.includes('purchase') ? suppliers : customers;
        const targetSelector = currentSection.includes('purchase') ?
            `.${currentSection}-supplier-id` : `.${currentSection}-customer-id`;

        $.ajax({
            url: url,
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحفظ',
                    text: `تم حفظ ${currentSection.includes('purchase') ? 'المورد' : 'العميل'} بنجاح!`,
                }).then(() => {
                    targetArray.push(response[currentSection.includes('purchase') ? 'supplier' : 'customer']);
                    if (currentSection.includes('purchase')) {
                        updateSuppliers();
                        $(targetSelector).val(response.supplier.id).trigger('change');
                    } else {
                        updateCustomers();
                        $(targetSelector).val(response.customer.id).trigger('change');
                    }
                    closeModal();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: xhr.responseJSON?.message || 'غير معروف',
                });
            }
        });
    }

    function saveInvoice(section) {
        let sectionIds = section;

        if(section == 'sales-return'){
            sectionIds  =  'salesReturn';
        }

        if(section == 'purchase-return'){
            sectionIds = 'purchaseReturn';
        }
        const tableId = `#${sectionIds}ItemsTable`;
        const items = [];
        $(`${tableId} tbody tr`).each(function() {
            const $row = $(this);
            const item = {
                productId: $row.find('.item-select').val(),
                unit: $row.find('.item-unit').val(),
                quantity: parseFloat($row.find('.item-qty').val()) || 0,
                price: parseFloat($row.find('.item-price').val()) || 0,
                total: parseFloat($row.find('.item-total').text()) || 0
            };
            if (item.productId) items.push(item);
        });



        const invoiceData = {
            customer_id: section.includes('sales') ? $(`#${sectionIds}CustomerSelect`).val() : null,
            supplier_id: section.includes('purchase') ? $(`#${sectionIds}SupplierSelect`).val() : null, // تصحيح هنا
            invoice_date: $(`#${sectionIds}InvoiceDate`).val(),
            original_invoice_number: section.includes('return') ? $(`#original${section.includes('sales') ? 'Sales' : 'Purchase'}InvoiceNumber`).val() : null,
            subtotal: parseFloat($(`${tableId} #${section}Subtotal`).text()) || 0,
            discount: parseFloat($(`${tableId} #${section}DiscountPercent`).val()) || 0,
            discount_amount: parseFloat($(`${tableId} #${section}DiscountAmount`).text()) || 0,
            tax: parseFloat($(`${tableId} #${section}TaxPercent`).val()) || 0,
            tax_amount: parseFloat($(`${tableId} #${section}TaxAmount`).text()) || 0,
            total: parseFloat($(`${tableId} #${section}GrandTotal`).text()) || 0,
            items: items
        };
        console.log(section);

        const url = section === 'sales' ? '/branch/invoices/store' :
            section === 'purchase' ? '/branch/invoices/purchases/store' :
                section === 'sales-return' ? '/branch/invoices/sales-returns/store' :
                    '/branch/invoices/purchase-returns/store';

        $.ajax({
            url: url,
            method: 'POST',
            data: JSON.stringify(invoiceData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {

                Swal.fire({
                    icon: 'success',
                    title: 'تم الحفظ',
                    text: 'تم حفظ الفاتورة بنجاح!',
                }).then(() => {
                    const invoiceId = response.invoice_id;
                    window.location.href = `/branch/invoices/index`;
                });
            },
            error: function(xhr) {
                console.log(xhr.responseJSON);
                let message = xhr.responseJSON?.message || 'حدث خطأ غير معروف';

                if (xhr.responseJSON?.errors) {
                    const allErrors = xhr.responseJSON.errors;
                    const firstKey = Object.keys(allErrors)[0];
                    message = '';
                    message = allErrors[firstKey];
                }
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: message || 'غير معروف',
                });
            }
        });
    }

    function fetchInvoiceData(section, invoiceNumber) {
        const url = section === 'sales-return' ? `/branch/invoices/${invoiceNumber}` : `/branch/invoices/purchases/${invoiceNumber}`;
        const tableId = section === 'sales-return' ?  `#salesReturnItemsTable` : `#purchaseReturnItemsTable`;
        console.log(tableId);

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response && response.items) {
                    // مسح الجدول الحالي
                    $(`${tableId} tbody`).empty();
                    let forSection = '';

                    if (section === 'sales-return' && response.customer_id) {
                        forSection = 'purchaseReturn';
                        $(`#salesReturnCustomerSelect`).val(response.customer_id).trigger('change');

                    } else if (section === 'purchase-return' && response.supplier_id) {
                        forSection = 'salesReturn';
                        $(`#purchaseReturnSupplierSelect`).val(response.supplier_id).trigger('change');
                    }

                    $(`#${forSection}InvoiceDate`).val(response.invoice_date);
                    $(`#${forSection}BranchSelect`).val(response.branch_id);
                    response.items.forEach((item, index) => {
                        const $newRow = $(`
                            <tr>
                                <td class="serial">${index + 1}</td>
                                <td>
                                    <select class="item-select form-select" style="width: 100%;">
                                        <option value="${item.productId}" data-price="${item.price}" selected>${item.productId} - ${products.find(p => p.id == item.productId)?.name || 'غير معروف'}</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="item-unit">
                                        <option value="حبة" ${item.unit === 'حبة' ? 'selected' : ''}>حبة</option>
                                        <option value="كرتون" ${item.unit === 'كرتون' ? 'selected' : ''}>كرتون</option>
                                        <option value="كيلو" ${item.unit === 'كيلو' ? 'selected' : ''}>كيلو</option>
                                        <option value="نص كيلو" ${item.unit === 'نص كيلو' ? 'selected' : ''}>نص كيلو</option>
                                    </select>
                                </td>
                                <td><input type="number" class="item-qty" min="1" value="${item.quantity}"></td>
                                <td><input type="number" class="item-price" step="0.01" value="${item.price}"></td>
                                <td class="item-total">${(item.quantity * item.price).toFixed(2)}</td>
                                <td><button type="button" class="delete-btn">×</button></td>
                            </tr>
                        `);
                        $(`${tableId} tbody`).append($newRow);

                        const $select = $newRow.find('.item-select');
                        $select.select2({
                            placeholder: "اختر صنف",
                            allowClear: true,
                            dir: "rtl",
                            minimumResultsForSearch: Infinity
                        });

                        addCalculationListeners($newRow, tableId);
                    });

                    // تحديث الإجماليات
                    calculateGrandTotal(tableId);

                    Swal.fire({
                        icon: 'success',
                        title: 'تم الجلب',
                        text: 'تم جلب بيانات الفاتورة بنجاح!',
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد بيانات',
                        text: 'لم يتم العثور على الفاتورة المطلوبة.',
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: xhr.responseJSON?.message || 'حدث خطأ أثناء جلب الفاتورة.',
                });
            }
        });
    }

    $('#saveSalesInvoiceBtn').on('click', () => saveInvoice('sales'));
    $('#savePurchaseInvoiceBtn').on('click', () => saveInvoice('purchase'));
    $('#saveSalesReturnInvoiceBtn').on('click', () => saveInvoice('sales-return'));
    $('#savePurchaseReturnInvoiceBtn').on('click', () => saveInvoice('purchase-return'));

    $('.nav-tabs button').on('click', function() {
        const section = $(this).data('section');
        $('.invoice-container').addClass('hidden');
        $(`#${section}-invoice`).removeClass('hidden');
        $('.nav-tabs button').removeClass('active');
        $(this).addClass('active');
    });

    $('#salesCustomerSelect').on('change' , function (){

        let selectedOption = $(this).find('option:selected'); // العنصر المختار
        let creditLimit = selectedOption.data('credit'); // جلب قيمة data-credit
        let customerId = selectedOption.val(); // جلب قيمة العميل (id)

        if (creditLimit !== undefined) {
            $('.limit-credit').show();
            $('.limit-credit').text(`الحد الائتماني: ${creditLimit}`);
        } else {
            $('.limit-credit').hide();
        }
    });

    $('#salesReturnCustomerSelect').on('change' , function (){

        let selectedOption = $(this).find('option:selected'); // العنصر المختار
        let creditLimit = selectedOption.data('credit'); // جلب قيمة data-credit
        let customerId = selectedOption.val(); // جلب قيمة العميل (id)

        if (creditLimit !== undefined) {
            $('.limit-credit').show();
            $('.limit-credit').text(`الحد الائتماني: ${creditLimit}`);
        } else {
            $('.limit-credit').hide();
        }
    });


    function addNewSalesItem(section) {
        const tableId = `#${section}`;
        const $tbody = $(`${tableId} tbody`);
        const existingRows = $tbody.find('tr');

        // التحقق من السطر الأخير إذا كان موجودًا
        if (existingRows.length > 0) {
            const lastRow = existingRows.last();
            const itemSelect = lastRow.find('.item-select').val();

            if (!itemSelect) {
                Swal.fire({
                    icon: 'warning',
                    title: 'خطأ',
                    text: 'يرجى اختيار صنف في السطر الحالي قبل إضافة سطر جديد',
                });
                return;
            }
        }

        const rowCount = $tbody.children().length + 1;
        const selectedIds = getSelectedItems(tableId);
        const availableProducts = products.filter(product => !selectedIds.includes(product.id.toString()));

        const $newRow = $(`
            <tr>
                <td class="serial">${rowCount}</td>
                <td>
                    <select class="item-select form-select" style="width: 100%;">
                        <option value="">اختر صنف</option>
                        ${availableProducts.map(product =>
                `<option value="${product.id}" data-price="${product.price}">${product.id} - ${product.name}</option>`
                ).join('')}
                        </select>
                    </td>
                    <td>
                        <select class="item-unit">
                            <option value="حبة" selected>حبة</option>
                            <option value="كرتون">كرتون</option>
                            <option value="كيلو">كيلو</option>
                            <option value="نص كيلو">نص كيلو</option>
                        </select>
                    </td>
                    <td><input type="number" class="item-qty" min="1" value="1"></td>
                    <td><input type="number" class="item-price" step="0.01" placeholder="0.00"></td>
                    <td class="item-total">0.00</td>
                    <td><button type="button" class="delete-btn">×</button></td>
                </tr>
        `);
        $tbody.append($newRow);

        const $select = $newRow.find('.item-select');
        $select.select2({
            placeholder: "اختر صنف",
            allowClear: true,
            dir: "rtl",
            minimumResultsForSearch: Infinity
        });

        addCalculationListeners($newRow, tableId);
        $select.focus();
    }
</script>
