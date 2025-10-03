/**
 * Modern Invoice Functions
 * وظائف الفاتورة العصرية
 */

// وظائف مساعدة للوصول للمتغيرات العامة
function getInvoiceItems() {
    return window.invoiceItems || [];
}

function setInvoiceItems(items) {
    window.invoiceItems = items;
}

function getItemCounter() {
    return window.itemCounter || 0;
}

function setItemCounter(counter) {
    window.itemCounter = counter;
}

// الوصول للمتغيرات العامة
function getInvoiceItems() {
    return window.invoiceItems || [];
}

function setInvoiceItems(items) {
    window.invoiceItems = items;
}

function getItemCounter() {
    return window.itemCounter || 0;
}

function setItemCounter(counter) {
    window.itemCounter = counter;
}

// إصلاح z-index لنتائج البحث
function fixSearchResultsZIndex() {
    $('#searchResults').css({
        'position': 'absolute',
        'z-index': '999999',
        'background': 'white',
        'border': '2px solid #e5e7eb',
        'border-top': 'none',
        'border-radius': '0 0 8px 8px',
        'box-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
        'max-height': '300px',
        'overflow-y': 'auto',
        'width': '100%',
        'top': '100%',
        'left': '0',
        'right': '0'
    });

    $('.smart-search-container').css({
        'position': 'relative',
        'z-index': '1000'
    });

    $('.search-card').css({
        'position': 'relative',
        'z-index': '1000'
    });
}

// تحديث رقم الفاتورة تلقائياً
function updateInvoiceNumber() {
    const today = new Date();
    const dateStr = today.getFullYear() +
                   String(today.getMonth() + 1).padStart(2, '0') +
                   String(today.getDate()).padStart(2, '0');

    // يمكن تحسين هذا ليجلب آخر رقم من قاعدة البيانات
    const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    $('#invoiceNumber').text(`INV-${dateStr}-${randomNum}`);
}

// تحديث عداد الأصناف
function updateItemsCounter() {
    const items = getInvoiceItems();
    $('#itemsCount').text(items.length);

    // إظهار/إخفاء رسالة فارغة
    if (items.length === 0) {
        $('#emptyMessage').show();
    } else {
        $('#emptyMessage').hide();
    }
}

// حساب الإجماليات
function calculateTotals() {
    const items = getInvoiceItems();
    const subtotal = items.reduce((sum, item) => sum + item.total, 0);
    const discountPercent = parseFloat($('#discountPercent').val()) || 0;
    const taxPercent = parseFloat($('#taxPercent').val()) || 0;

    const discountAmount = subtotal * (discountPercent / 100);
    const taxableAmount = subtotal - discountAmount;
    const taxAmount = taxableAmount * (taxPercent / 100);
    const grandTotal = taxableAmount + taxAmount;

    // تحديث العرض
    $('#subtotal').text(subtotal.toFixed(2) + ' ر.س');
    $('#discountAmount').text(discountAmount.toFixed(2) + ' ر.س');
    $('#taxAmount').text(taxAmount.toFixed(2) + ' ر.س');
    $('#grandTotal').text(grandTotal.toFixed(2) + ' ر.س');
}

// تحديث كمية الصنف
function updateItemQuantity(itemId, quantity) {
    const items = getInvoiceItems();
    const item = items.find(i => i.id === itemId);
    if (item) {
        const newQuantity = parseFloat(quantity) || 1;
        item.quantity = newQuantity;
        item.total = item.quantity * item.price;

        // تحديث الإجمالي في الجدول
        $(`tr[data-item-id="${itemId}"] .item-total`).text(item.total.toFixed(2) + ' ر.س');

        // تحديث حالة أزرار الزيادة والنقصان
        updateQuantityButtons(itemId, newQuantity);

        calculateTotals();
        setInvoiceItems(items);
    }
}

// زيادة الكمية
function increaseQuantity(itemId) {
    const currentQuantity = parseInt($(`tr[data-item-id="${itemId}"] .quantity-input`).val()) || 1;
    const newQuantity = currentQuantity + 1;
    $(`tr[data-item-id="${itemId}"] .quantity-input`).val(newQuantity);
    updateItemQuantity(itemId, newQuantity);
    showNotification('تم زيادة الكمية ➕', 'success');
}

// نقصان الكمية
function decreaseQuantity(itemId) {
    const currentQuantity = parseInt($(`tr[data-item-id="${itemId}"] .quantity-input`).val()) || 1;
    if (currentQuantity > 1) {
        const newQuantity = currentQuantity - 1;
        $(`tr[data-item-id="${itemId}"] .quantity-input`).val(newQuantity);
        updateItemQuantity(itemId, newQuantity);
        showNotification('تم تقليل الكمية ➖', 'info');
    }
}

// تحديث حالة أزرار الكمية
function updateQuantityButtons(itemId, quantity) {
    const minusBtn = $(`tr[data-item-id="${itemId}"] .quantity-btn.minus`);
    if (quantity <= 1) {
        minusBtn.prop('disabled', true);
    } else {
        minusBtn.prop('disabled', false);
    }
}

// تحديث سعر الصنف
function updateItemPrice(itemId, price) {
    const items = getInvoiceItems();
    const item = items.find(i => i.id === itemId);
    if (item) {
        item.price = parseFloat(price) || 0;
        item.total = item.quantity * item.price;

        // تحديث الإجمالي في الجدول
        $(`tr[data-item-id="${itemId}"] .item-total`).text(item.total.toFixed(2) + ' ر.س');

        calculateTotals();
    }
}

// حذف صنف
function removeItem(itemId) {
    // إزالة من المصفوفة
    const items = getInvoiceItems();
    const filteredItems = items.filter(item => item.id !== itemId);
    setInvoiceItems(filteredItems);

    // إزالة من الجدول مع أنيميشن
    $(`tr[data-item-id="${itemId}"]`).addClass('fadeOut').fadeOut(300, function() {
        $(this).remove();
        calculateTotals();
        updateItemsCounter();
    });

    showNotification('تم حذف الصنف ❌', 'error');
}

// إضافة صنف إلى الجدول
function addItemToTable(item) {
    const row = `
        <tr data-item-id="${item.id}" style="animation: slideInRight 0.3s ease;">
            <td>${item.id}</td>
            <td style="font-weight: 500;">📦 ${item.name}</td>
            <td class="quantity-cell">
                <div class="quantity-controls">
                    <button type="button" class="quantity-btn minus" onclick="decreaseQuantity(${item.id})" ${item.quantity <= 1 ? 'disabled' : ''}>
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" class="quantity-input" value="${item.quantity}"
                           min="1" onchange="updateItemQuantity(${item.id}, this.value)">
                    <button type="button" class="quantity-btn plus" onclick="increaseQuantity(${item.id})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </td>
            <td>
                <input type="number" class="table-input price-input" value="${item.price}"
                       step="0.01" onchange="updateItemPrice(${item.id}, this.value)">
            </td>
            <td class="item-total">${item.total.toFixed(2)} ر.س</td>
            <td class="actions-cell">
                <button type="button" class="delete-btn" onclick="removeItem(${item.id})" title="حذف المنتج">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#itemsTableBody').append(row);
    updateItemsCounter();
}

// إضافة صنف من البحث
function addItemFromSearch(id, name, price) {
    try {
        const items = getInvoiceItems();
        // التحقق من عدم وجود الصنف مسبقاً
        const existingItem = items.find(item => item.product_id === id);
        if (existingItem) {
            // زيادة الكمية بدلاً من إضافة صف جديد
            const newQuantity = existingItem.quantity + 1;
            existingItem.quantity = newQuantity;
            existingItem.total = existingItem.quantity * existingItem.price;

            // تحديث الجدول
            $(`tr[data-item-id="${existingItem.id}"] .quantity-input`).val(newQuantity);
            $(`tr[data-item-id="${existingItem.id}"] .item-total`).text(existingItem.total.toFixed(2) + ' ر.س');

            // تحديث أزرار الكمية
            updateQuantityButtons(existingItem.id, newQuantity);

            showNotification(`تم زيادة كمية ${name} ✅`, 'success');
            setInvoiceItems(items);
        } else {
            // إضافة صنف جديد
            const counter = getItemCounter() + 1;
            setItemCounter(counter);

            const item = {
                id: counter,
                product_id: id,
                name: name,
                quantity: 1,
                price: parseFloat(price) || 0,
                total: parseFloat(price) || 0
            };

            items.push(item);
            setInvoiceItems(items);
            addItemToTable(item);
            showNotification(`تم إضافة ${name} بنجاح! ✅`, 'success');
        }

        calculateTotals();

        // مسح البحث وإخفاء النتائج
        $('#smartSearch').val('');
        $('#searchResults').removeClass('show').hide();

    } catch (error) {
        console.error('خطأ في إضافة الصنف:', error);
        showNotification('حدث خطأ في إضافة الصنف ❌', 'error');
    }
}

// إظهار الإشعارات
function showNotification(message, type = 'info') {
    const notification = $('#notification');

    // إزالة الكلاسات السابقة وإضافة الجديدة
    notification.removeClass('success error info warning').addClass(type);
    notification.text(message);
    notification.addClass('show');

    // إخفاء الإشعار تلقائياً
    setTimeout(() => {
        notification.removeClass('show');
    }, 3000);
}

// إعادة تعيين النموذج
function resetForm() {
    if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع البيانات المدخلة.')) {
        setInvoiceItems([]);
        setItemCounter(0);
        $('#itemsTableBody').empty();
        $('#smartSearch').val('');
        $('#searchResults').hide();
        $('#customerSelect').val('').trigger('change');
        $('#discountPercent').val(0);
        $('#taxPercent').val(15);
        calculateTotals();
        updateItemsCounter();
        updateInvoiceNumber();
        showNotification('تم إعادة تعيين النموذج بنجاح ✨', 'success');
    }
}

// حفظ الفاتورة
function saveInvoice() {
    const items = getInvoiceItems();
    // التحقق الأساسي
    if (items.length === 0) {
        showNotification('يجب إضافة صنف واحد على الأقل! ⚠️', 'error');
        return;
    }

    if (!$('#customerSelect').val()) {
        showNotification('يجب اختيار العميل! ⚠️', 'error');
        $('#customerSelect').focus();
        return;
    }

    // إعداد البيانات للإرسال
    const invoiceData = {
        invoice_date: $('#invoiceDate').val(),
        customer_id: $('#customerSelect').val(),
        branch_id: $('#branchSelect').val(),
        items: items.map(item => ({
            productId: item.product_id,
            quantity: item.quantity,
            price: item.price,
            total: item.total
        })),
        subtotal: parseFloat($('#subtotal').text().replace(' ر.س', '')),
        discount: parseFloat($('#discountAmount').text().replace(' ر.س', '')),
        tax: parseFloat($('#taxAmount').text().replace(' ر.س', '')),
        total: parseFloat($('#grandTotal').text().replace(' ر.س', ''))
    };

    // إظهار مؤشر التحميل
    const btn = $('#saveInvoiceBtn');
    const originalText = btn.html();
    btn.prop('disabled', true);
    btn.html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...');

    // إرسال البيانات
    $.ajax({
        url: '/invoices/store',
        method: 'POST',
        data: invoiceData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status) {
                showNotification('تم حفظ الفاتورة بنجاح! 🎉', 'success');

                // يمكن إعادة توجيه أو طباعة الفاتورة
                setTimeout(() => {
                    if (confirm('هل تريد طباعة الفاتورة؟')) {
                        window.open(`/invoices/print/${response.invoice_id}`, '_blank');
                    }
                    resetForm();
                }, 1500);
            } else {
                showNotification('حدث خطأ في حفظ الفاتورة ❌', 'error');
            }
        },
        error: function(xhr) {
            console.error('خطأ في الحفظ:', xhr);
            let errorMessage = 'حدث خطأ في حفظ الفاتورة';

            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = Object.values(xhr.responseJSON.errors).flat();
                errorMessage = errors[0] || errorMessage;
            }

            showNotification(errorMessage + ' ❌', 'error');
        },
        complete: function() {
            // إعادة تعيين الزر
            btn.prop('disabled', false);
            btn.html(originalText);
        }
    });
}

// تحميل الإحصائيات الحقيقية
function loadStats() {
    // استخدام البيانات الممررة من الـ Controller
    const stats = window.statsData || {};

    console.log('Loading stats:', stats); // للتشخيص
    console.log('Stats keys:', Object.keys(stats)); // للتشخيص

    // إظهار الإحصائيات مباشرة
    setTimeout(() => {
        // تحديث الإحصائيات مع تأثير العد
        animateCounter('#todayStats', parseInt(stats.today_invoices) || 0);
        animateCounter('#monthStats', parseInt(stats.month_invoices) || 0);
        animateCounter('#customersStats', parseInt(stats.customers_count) || 0);

        // تحديث المبلغ الإجمالي
        const totalAmount = parseFloat(stats.total_amount) || 0;
        animateCounter('#totalAmountStats', totalAmount, true);
    }, 500);
}

// تأثير العد المتحرك للأرقام
function animateCounter(element, targetValue, isCurrency = false) {
    const $element = $(element);
    const startValue = 0;
    const duration = 1500;
    const startTime = Date.now();

    function updateCounter() {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // استخدام easing function للحصول على تأثير أفضل
        const easedProgress = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.floor(startValue + (targetValue - startValue) * easedProgress);

        if (isCurrency) {
            $element.text(currentValue.toLocaleString('ar-SA') + ' ر.س');
        } else {
            $element.text(currentValue.toLocaleString('ar-SA'));
        }

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    updateCounter();
}

// إضافة أنيميشن CSS وإصلاح z-index
if (!document.getElementById('modern-invoice-animations')) {
    const style = document.createElement('style');
    style.id = 'modern-invoice-animations';
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .fadeOut {
            animation: fadeOut 0.3s ease;
        }

        .notification.info {
            background: var(--info);
        }

        .notification.warning {
            background: var(--warning);
        }

        /* إصلاح z-index لنتائج البحث */
        .search-results {
            position: absolute !important;
            z-index: 999999 !important;
            background: white !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 0 0 8px 8px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            max-height: 300px !important;
            overflow-y: auto !important;
            width: 100% !important;
        }

        .search-results.show {
            display: block !important;
        }

        .smart-search-container {
            position: relative !important;
            z-index: 1000 !important;
        }

        .search-card {
            position: relative !important;
            z-index: 1000 !important;
        }

        .search-result-item {
            padding: 15px !important;
            border-bottom: 1px solid #e5e7eb !important;
            cursor: pointer !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            transition: background-color 0.2s ease !important;
            background: white !important;
        }

        .search-result-item:hover {
            background-color: #f8fafc !important;
        }

        .item-name {
            font-weight: 500 !important;
            color: #1f2937 !important;
        }

        .item-details {
            display: flex !important;
            gap: 15px !important;
            margin-top: 5px !important;
        }

        .item-price {
            color: #10b981 !important;
            font-weight: 600 !important;
        }

        .add-btn {
            background: #667eea !important;
            color: white !important;
            width: 30px !important;
            height: 30px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
    `;
    document.head.appendChild(style);
}
