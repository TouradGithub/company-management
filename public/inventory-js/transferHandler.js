class TransferHandler {
    constructor() {
        this.branches = [];
// جلب الفروع من API


        this.transferItems = [];
        this.products = [];
        this.categories = [];
        this.currentTransferNumber = 1001;
    }
    fetchTransfers() {
        fetch('/transfer/list')
            .then(res => res.json())
            .then(transfers => {
                console.log("قائمة التحويلات:", transfers);
                this.transferItems

                = Array.isArray(transfers) ? transfers : [];


            })
            .catch(err => {
                console.error("فشل في جلب التحويلات:", err);
            });
    }

    loadProductsData() {
        fetch('/transfer/products')
        .then(res => res.json())
        .then(data => {
            this.products = data.products;
            this.categories = data.categories;
            // console.log(this.products);

            this.updateUIAfterProductLoad();
        });
        fetch('/branches')
        .then(response => response.json())
        .then(data => {
            // تحديث المصفوفة بالفروع المسترجعة
            this.branches = data;
            // console.log(this.branches)

            // ملء الـ select بعد جلب الفروع
            this.populateBranchSelect('fromBranch');
            this.populateBranchSelect('toBranch');
                    })
        .catch(error => {
            console.error("حدث خطأ أثناء جلب الفروع:", error);
        });
    }

    updateUIAfterProductLoad() {
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter && categoryFilter.value) {
            this.filterByCategory(categoryFilter.value);
        }

        // إعادة تحميل الأزرار أو الواجهات الأخرى إن وجدت
        TransferUIHelper.initialize(this);
    }

    initialize() {
        const today = new Date();
        document.getElementById('transferDate').valueAsDate = today;

        this.populateBranchSelect('fromBranch');
        this.populateBranchSelect('toBranch');

        document.getElementById('productSearch').addEventListener('input', (e) => this.handleSearchInput(e.target.value));
        document.getElementById('searchProductBtn').addEventListener('click', () => this.searchProduct());
        document.getElementById('saveTransferBtn').addEventListener('click', () => this.saveTransfer());
        document.getElementById('printTransferBtn').addEventListener('click', () => this.printTransfer());

        document.getElementById('transferNumber').value = this.currentTransferNumber;

        this.addCategorySelector();

        this.loadProductsData();

        TransferUIHelper.initialize(this);
        // setInterval(() => {
        //     this.loadProductsData();
        // }, 10000); // كل 10 ثوانٍ

    }

    // دالة لملء select بالفروع
    populateBranchSelect(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.innerHTML = '';  // مسح الخيارات الحالية

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'اختر الفرع...';
        select.appendChild(defaultOption);
        // console.log(this.branches)
        // ملء القائمة بالفروع
        this.branches.forEach(branch => {
            const option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.name;
            select.appendChild(option);
        });
    }


    addCategorySelector() {
        const filterControls = document.querySelector('.transfer-filter-controls');
        if (!filterControls) return;

        const categoryField = document.createElement('div');
        categoryField.className = 'filter-field';
        categoryField.innerHTML = `
            <label for="categoryFilter">تصفية حسب الفئة:</label>
            <select id="categoryFilter">
                <option value="">جميع الفئات</option>
                ${this.categories.map(cat => `<option value="${cat}">${cat}</option>`).join('')}
            </select>
        `;

        filterControls.appendChild(categoryField);

        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => this.filterByCategory(e.target.value));
        }
    }

    filterByCategory(category) {
        if (!category) return;

        const filteredProducts = this.products.filter(p => p.category === category);
        const productSelect = document.getElementById('productSelect');

        if (productSelect) {
            productSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'اختر المنتج...';
            productSelect.appendChild(defaultOption);

            filteredProducts.forEach(product => {
                const option = document.createElement('option');
                option.value = product.code;
                option.textContent = `${product.name} (${product.code})`;
                productSelect.appendChild(option);
            });

            const productSelectContainer = document.querySelector('.product-select-container');
            if (productSelectContainer) {
                productSelectContainer.style.display = 'block';
            }
        }
    }

    handleSearchInput(searchTerm) {
        if (!searchTerm || searchTerm.length < 3) return;

        const filteredProducts = this.products.filter(p =>
            p.code.includes(searchTerm) ||
            p.name.includes(searchTerm)
        );

        const productSelect = document.getElementById('productSelect');

        if (productSelect) {
            productSelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'اختر المنتج...';
            productSelect.appendChild(defaultOption);

            filteredProducts.forEach(product => {
                const option = document.createElement('option');
                option.value = product.code;
                option.textContent = `${product.name} (${product.code})`;
                productSelect.appendChild(option);
            });

            const productSelectContainer = document.querySelector('.product-select-container');
            if (productSelectContainer) {
                productSelectContainer.style.display = 'block';
            }
        }
    }

    searchProduct() {
        const searchTerm = document.getElementById('productSearch').value;
        if (!searchTerm) return;

        this.handleSearchInput(searchTerm);
    }

    addItemToTransfer() {
        const productCode = document.getElementById('productSelect').value;
        const quantity = parseFloat(document.getElementById('quantity').value);

        if (!productCode) {
            alert('الرجاء اختيار منتج');
            return;
        }

        if (!quantity || quantity <= 0) {
            alert('الرجاء إدخال كمية صحيحة');
            return;
        }

        const product = this.products.find(p => p.code === productCode);

        if (!product) {
            alert('المنتج غير موجود');
            return;
        }

        if (quantity > product.quantity) {
            alert(`الكمية المتاحة للمنتج ${product.name} هي ${product.quantity} فقط`);
            return;
        }

        this.addItemManually(product, quantity);

        document.getElementById('productSelect').value = '';
        document.getElementById('quantity').value = '';
    }

    addItemManually(product, quantity) {
        const existingItemIndex = this.transferItems.findIndex(item => item.code === product.code);

        if (existingItemIndex >= 0) {
            this.transferItems[existingItemIndex].quantity += quantity;
            this.transferItems[existingItemIndex].totalCost = this.transferItems[existingItemIndex].quantity * this.transferItems[existingItemIndex].unitCost;
        } else {

            this.transferItems.push({
                id: product.id, // ← أضف هذا السطر
                code: product.code,
                name: product.name,
                category: product.category,
                quantity: quantity,
                unitCost: product.unitCost,
                totalCost: quantity * product.unitCost
            });
        }

        product.quantity -= quantity;

        this.renderTransferTable();
        this.updateSummary();
    }

    renderTransferTable() {
        // fetchTransfers();
        const tableBody = document.getElementById('transferItemsBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        this.transferItems.forEach((item, index) => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${item.code}</td>
                <td>${item.name}</td>
                <td>${item.category}</td>
                <td><input type="number" class="quantity-edit-input" value="${item.quantity.toFixed(2)}" min="0.01" step="0.01" data-index="${index}"></td>
                <td>${item.unitCost.toFixed(2)} ريال</td>
                <td>${item.totalCost.toFixed(2)} ريال</td>
                <td>
                    <button class="action-btn delete-btn" data-index="${index}">
                        <i class="fas fa-trash">حذف</i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        // Add event listeners for delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.getAttribute('data-index'));
                this.removeItem(index);
            });
        });

        // Add event listeners for quantity edit inputs
        const quantityInputs = document.querySelectorAll('.quantity-edit-input');
        quantityInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.getAttribute('data-index'));
                const newQuantity = parseFloat(e.target.value) || 0;
                this.updateItemQuantity(index, newQuantity);
            });
        });
    }

    updateItemQuantity(index, newQuantity) {
        const item = this.transferItems[index];
        const product = this.products.find(p => p.code === item.code);

        if (!product) return;

        // Calculate available quantity (current product quantity + current item quantity)
        const availableQuantity = product.quantity + item.quantity;

        if (newQuantity > availableQuantity) {
            alert(`الكمية المتاحة للمنتج ${product.name} هي ${availableQuantity.toFixed(2)} فقط`);
            // Reset the input value
            const input = document.querySelector(`.quantity-edit-input[data-index="${index}"]`);
            if (input) input.value = item.quantity.toFixed(2);
            return;
        }

        // Update product quantity
        product.quantity = availableQuantity - newQuantity;

        // Update transfer item
        item.quantity = newQuantity;
        item.totalCost = newQuantity * item.unitCost;

        // Update display of total cost cell
        const row = document.querySelector(`#transferItemsBody tr:nth-child(${index + 1})`);
        if (row) {
            row.cells[5].textContent = item.totalCost.toFixed(2) + ' ريال';
        }

        this.updateSummary();
    }

    removeItem(index) {
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            const item = this.transferItems[index];
            const product = this.products.find(p => p.code === item.code);

            if (product) {
                product.quantity += item.quantity;
            }

            this.transferItems.splice(index, 1);

            this.renderTransferTable();
            this.updateSummary();
        }
    }

    updateSummary() {
        const totalItems = this.transferItems.length;
        let totalQuantity = 0;
        let totalCost = 0;

        this.transferItems.forEach(item => {
            totalQuantity += item.quantity;
            totalCost += item.totalCost;
        });

        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalQuantity').textContent = totalQuantity.toFixed(2);
        document.getElementById('totalCost').textContent = totalCost.toFixed(2) + ' ريال';
    }

    validateTransfer() {
        const fromBranch = document.getElementById('fromBranch').value;
        const toBranch = document.getElementById('toBranch').value;

        if (!fromBranch) {
            alert('الرجاء اختيار فرع المصدر');
            return false;
        }

        if (!toBranch) {
            alert('الرجاء اختيار فرع الوجهة');
            return false;
        }

        if (fromBranch === toBranch) {
            alert('لا يمكن التحويل لنفس الفرع');
            return false;
        }

        if (this.transferItems.length === 0) {
            alert('الرجاء إضافة منتجات للتحويل');
            return false;
        }

        return true;
    }

    saveTransfer() {
        if (!this.validateTransfer()) return;

        const transferData = {
            transferNumber: document.getElementById('transferNumber').value,
            transferDate: document.getElementById('transferDate').value,
            fromBranch: parseInt(document.getElementById('fromBranch').value),
            toBranch: parseInt(document.getElementById('toBranch').value),
            notes: document.getElementById('transferNotes').value,
            items: this.transferItems.map(item => ({
                id: item.id,          // تأكد أن كل عنصر يحتوي على 'id'
                name: item.name,
                code: item.code,
                category: item.category,
                quantity: item.quantity,
                unitCost: item.unitCost,
                totalCost: item.totalCost
            })),            totalItems: this.transferItems.length,
            totalQuantity: this.transferItems.reduce((sum, item) => sum + item.quantity, 0),
            totalCost: this.transferItems.reduce((sum, item) => sum + item.totalCost, 0)
        };

        fetch('/transfer/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(transferData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                this.currentTransferNumber++;
                document.getElementById('transferNumber').value = this.currentTransferNumber;
                this.clearForm();
            } else {
                alert("فشل في الحفظ: " + (data.error || "خطأ غير معروف"));
            }
        })
        .catch(err => {
            console.error(err);
            alert('حدث خطأ أثناء الاتصال بالسيرفر');
        });
    }


    printTransfer() {
        const printContent = document.querySelector('.transfer-items-table-container').outerHTML +
                             document.querySelector('.transfer-summary').outerHTML;

        const printWindow = window.open('', '', 'width=800,height=600');
        printWindow.document.write(`
            <html>
                <head>
                    <title>نموذج التحويل</title>
                    <style>
                        body { font-family: Arial, sans-serif; direction: rtl; padding: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                        h3 { margin-top: 30px; }
                        th:nth-child(7), td:nth-child(7){
                        display:none;
                        }

                    </style>
                </head>
                <body>
                    <h2>نموذج تحويل مخزني</h2>
                    ${printContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }


    clearForm() {
        document.getElementById('fromBranch').value = '';
        document.getElementById('toBranch').value = '';
        document.getElementById('transferNotes').value = '';
        document.getElementById('productSearch').value = '';
        document.getElementById('quantity').value = '';

        const productSelectContainer = document.querySelector('.product-select-container');
        if (productSelectContainer) {
            productSelectContainer.style.display = 'none';
        }

        this.transferItems = [];

        this.products.forEach(product => {
            switch(product.code) {
                case "P1001": product.quantity = 100; break;
                case "P1002": product.quantity = 150; break;
                case "P1003": product.quantity = 120; break;
                case "P1004": product.quantity = 80; break;
                case "P1005": product.quantity = 95; break;
                case "P1006": product.quantity = 110; break;
                case "P1007": product.quantity = 30; break;
                case "P1008": product.quantity = 25; break;
                case "P1009": product.quantity = 45; break;
                case "P1010": product.quantity = 60; break;
                case "P1011": product.quantity = 20; break;
                case "P1012": product.quantity = 35; break;
                case "P1013": product.quantity = 200; break;
                case "P1014": product.quantity = 50; break;
                case "P1015": product.quantity = 180; break;
                case "P1016": product.quantity = 160; break;
                case "P1017": product.quantity = 130; break;
                case "P1018": product.quantity = 110; break;
                case "P1019": product.quantity = 300; break;
                case "P1020": product.quantity = 90; break;
                case "P1021": product.quantity = 40; break;
                case "P1022": product.quantity = 75; break;
                case "P1023": product.quantity = 55; break;
                case "P1024": product.quantity = 200; break;
                case "P1025": product.quantity = 80; break;
                case "P1026": product.quantity = 300; break;
                case "P1027": product.quantity = 150; break;
                case "P1028": product.quantity = 70; break;
                case "P1029": product.quantity = 60; break;
                case "P1030": product.quantity = 40; break;
            }
        });

        this.renderTransferTable();
        this.updateSummary();
    }

}
