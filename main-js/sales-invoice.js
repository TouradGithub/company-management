function showSalesInvoiceModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="sales-invoice-dashboard">
            <div class="header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>فاتورة مبيعات</h2>
            
            <div class="sales-invoice-form">
                <div class="invoice-header">
                    <div class="form-group">
                        <label>رقم الفاتورة</label>
                        <input type="text" id="invoiceNumber" readonly value="SI-2024-001">
                    </div>
                    <div class="form-group">
                        <label>التاريخ</label>
                        <input type="date" id="invoiceDate" value="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div class="form-group">
                        <label>العميل</label>
                        <select id="customerSelect">
                            <option value="">اختر العميل</option>
                            <option value="1">محمد أحمد</option>
                            <option value="2">سارة محسن</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>طريقة الدفع</label>
                        <select id="paymentMethod">
                            <option value="cash">نقدي</option>
                            <option value="credit">آجل</option>
                            <option value="bank">تحويل بنكي</option>
                        </select>
                    </div>
                </div>
                
                <div class="invoice-items">
                    <table class="invoice-items-table">
                        <thead>
                            <tr>
                                <th>الصنف</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الإجمالي</th>
                                <th>
                                    <button onclick="addInvoiceItem()">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody">
                            <tr>
                                <td>
                                    <select class="product-select">
                                        <option value="">اختر المنتج</option>
                                        <option value="1">منتج 1</option>
                                        <option value="2">منتج 2</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="quantity" value="1" min="1" onchange="calculateItemTotal(this)">
                                </td>
                                <td>
                                    <input type="number" class="unit-price" step="0.01" onchange="calculateItemTotal(this)">
                                </td>
                                <td>
                                    <input type="number" class="item-total" readonly>
                                </td>
                                <td>
                                    <button onclick="removeInvoiceItem(this)">-</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="invoice-total">
                <div class="total-group">
                    <label>المجموع</label>
                    <div class="value" id="subtotalValue">0.00</div>
                </div>
                <div class="total-group">
                    <label>الضريبة (15%)</label>
                    <div class="value" id="taxValue">0.00</div>
                </div>
                <div class="total-group">
                    <label>الإجمالي</label>
                    <div class="value" id="totalValue">0.00</div>
                </div>
            </div>
            
            <div class="invoice-actions">
                <button class="invoice-btn save-invoice-btn" onclick="saveSalesInvoice()">حفظ الفاتورة</button>
                <button class="invoice-btn cancel-invoice-btn" onclick="backToHome()">إلغاء</button>
            </div>
        </div>
    `;

    setupSalesInvoiceListeners();
}

function setupSalesInvoiceListeners() {
    const firstRow = document.querySelector('#invoiceItemsBody tr');
    setupInvoiceItemListeners(firstRow);
}

function setupInvoiceItemListeners(row) {
    const productSelect = row.querySelector('.product-select');
    const quantityInput = row.querySelector('.quantity');
    const unitPriceInput = row.querySelector('.unit-price');

    productSelect.addEventListener('change', () => {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const defaultPrice = selectedOption.getAttribute('data-price') || '0';
        unitPriceInput.value = defaultPrice;
        calculateItemTotal(unitPriceInput);
    });

    // Add other necessary listeners
}

function addInvoiceItem() {
    const itemsBody = document.getElementById('invoiceItemsBody');
    const newRow = itemsBody.querySelector('tr').cloneNode(true);
    
    // Reset values
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    newRow.querySelector('.product-select').selectedIndex = 0;
    
    itemsBody.appendChild(newRow);
    setupInvoiceItemListeners(newRow);
}

function removeInvoiceItem(button) {
    const row = button.closest('tr');
    const itemsBody = document.getElementById('invoiceItemsBody');
    
    if (itemsBody.children.length > 1) {
        row.remove();
        calculateInvoiceTotal();
    }
}

function calculateItemTotal(input) {
    const row = input.closest('tr');
    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
    const itemTotal = row.querySelector('.item-total');
    
    itemTotal.value = (quantity * unitPrice).toFixed(2);
    calculateInvoiceTotal();
}

function calculateInvoiceTotal() {
    const itemTotals = Array.from(document.querySelectorAll('.item-total'))
        .map(el => parseFloat(el.value) || 0);
    
    const subtotal = itemTotals.reduce((a, b) => a + b, 0);
    const tax = subtotal * 0.15;
    const total = subtotal + tax;
    
    document.getElementById('subtotalValue').textContent = subtotal.toFixed(2);
    document.getElementById('taxValue').textContent = tax.toFixed(2);
    document.getElementById('totalValue').textContent = total.toFixed(2);
}

function saveSalesInvoice() {
    const invoiceData = {
        number: document.getElementById('invoiceNumber').value,
        date: document.getElementById('invoiceDate').value,
        customer: document.getElementById('customerSelect').value,
        paymentMethod: document.getElementById('paymentMethod').value,
        items: [],
        subtotal: parseFloat(document.getElementById('subtotalValue').textContent),
        tax: parseFloat(document.getElementById('taxValue').textContent),
        total: parseFloat(document.getElementById('totalValue').textContent)
    };

    const itemRows = document.querySelectorAll('#invoiceItemsBody tr');
    itemRows.forEach(row => {
        const product = row.querySelector('.product-select').value;
        const quantity = parseFloat(row.querySelector('.quantity').value);
        const unitPrice = parseFloat(row.querySelector('.unit-price').value);
        const itemTotal = parseFloat(row.querySelector('.item-total').value);

        if (product && quantity && unitPrice) {
            invoiceData.items.push({
                product,
                quantity,
                unitPrice,
                itemTotal
            });
        }
    });

    // Save to localStorage or send to backend
    const invoices = JSON.parse(localStorage.getItem('salesInvoices') || '[]');
    invoices.push(invoiceData);
    localStorage.setItem('salesInvoices', JSON.stringify(invoices));

    alert('تم حفظ الفاتورة بنجاح');
    backToHome();
}

function backToHome() {
    location.reload();
}