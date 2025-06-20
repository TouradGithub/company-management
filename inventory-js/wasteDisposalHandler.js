class WasteDisposalHandler {
    constructor() {
        this.branches = [];

        this.wasteItems = [];
        this.products = [];
        this.categories = [];
        this.currentWasteNumber = 2001;
    }

    loadProductsData() {
        fetch('/transfer/products')
        .then(res => res.json())
        .then(data => {
            this.products = data.products;
            this.categories = data.categories;
            this.updateUIAfterProductLoad();
        });
        fetch('/branches')
        .then(response => response.json())
        .then(data => {
            // تحديث المصفوفة بالفروع المسترجعة
            this.branches = data;
            // console.log(this.branches)

            // ملء الـ select بعد جلب الفروع
            this.populateBranchSelect('wasteBranch');
            // this.populateBranchSelect('toBranch');
                    })
        .catch(error => {
            console.error("حدث خطأ أثناء جلب الفروع:", error);
        });
        const categoryFilter = document.getElementById('wasteCategoryFilter');
        if (categoryFilter) {
            // Populate category filter
            this.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categoryFilter.appendChild(option);
            });

            categoryFilter.addEventListener('change', (e) => this.filterByCategory(e.target.value));
        }
    }
    updateUIAfterProductLoad() {
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter && categoryFilter.value) {
            this.filterByCategory(categoryFilter.value);
        }

        // إعادة تحميل الأزرار أو الواجهات الأخرى إن وجدت
        // WasteDisposalHandler.initialize(this);
    }
    initialize() {
        const today = new Date();
        document.getElementById('wasteDate').valueAsDate = today;

        this.populateBranchSelect('wasteBranch');

        document.getElementById('wasteProductSearch').addEventListener('input', (e) => this.handleSearchInput(e.target.value));
        document.getElementById('wasteSearchProductBtn').addEventListener('click', () => this.searchProduct());
        document.getElementById('saveWasteBtn').addEventListener('click', () => this.saveWaste());
        // document.getElementById('printWasteBtn').addEventListener('click', () => this.printWaste());
        document.getElementById('wasteBrowseProductsBtn').addEventListener('click', () => this.showProductBrowser());

        document.getElementById('wasteNumber').value = this.currentWasteNumber;

        this.loadProductsData();
    }

    populateBranchSelect(selectId) {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.innerHTML = '';

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'اختر الفرع...';
        select.appendChild(defaultOption);

        this.branches.forEach(branch => {
            const option = document.createElement('option');
            option.value = branch.id;
            option.textContent = branch.name;
            select.appendChild(option);
        });
    }

    filterByCategory(category) {
        if (!category) return;

        const filteredProducts = this.products.filter(p => p.category === category);

        // Just showing the filtered products in the browser when selecting a category
        if (filteredProducts.length > 0) {
            this.showProductBrowser(filteredProducts);
        }
    }

    handleSearchInput(searchTerm) {
        if (!searchTerm || searchTerm.length < 3) return;

        const filteredProducts = this.products.filter(p =>
            p.code.includes(searchTerm) ||
            p.name.includes(searchTerm)
        );

        if (filteredProducts.length > 0) {
            this.showProductBrowser(filteredProducts);
        }
    }

    searchProduct() {
        const searchTerm = document.getElementById('wasteProductSearch').value;
        if (!searchTerm) return;

        this.handleSearchInput(searchTerm);
    }

    addItemManually(product, quantity) {
        const existingItemIndex = this.wasteItems.findIndex(item => item.code === product.code);

        if (existingItemIndex >= 0) {
            this.wasteItems[existingItemIndex].quantity += quantity;
            this.wasteItems[existingItemIndex].totalCost = this.wasteItems[existingItemIndex].quantity * this.wasteItems[existingItemIndex].unitCost;
        } else {
            this.wasteItems.push({
                id: product.id,
                code: product.code,
                name: product.name,
                category: product.category,
                quantity: quantity,
                unitCost: product.unitCost,
                totalCost: quantity * product.unitCost
            });
        }

        product.quantity -= quantity;

        this.renderWasteTable();
        this.updateSummary();
    }

    renderWasteTable() {
        const tableBody = document.getElementById('wasteItemsBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        this.wasteItems.forEach((item, index) => {
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
        const deleteButtons = document.querySelectorAll('#wasteItemsBody .delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.getAttribute('data-index'));
                this.removeItem(index);
            });
        });

        // Add event listeners for quantity edit inputs
        const quantityInputs = document.querySelectorAll('#wasteItemsBody .quantity-edit-input');
        quantityInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.getAttribute('data-index'));
                const newQuantity = parseFloat(e.target.value) || 0;
                this.updateItemQuantity(index, newQuantity);
            });
        });
    }

    updateItemQuantity(index, newQuantity) {
        const item = this.wasteItems[index];
        const product = this.products.find(p => p.code === item.code);

        if (!product) return;

        // Calculate available quantity (current product quantity + current item quantity)
        const availableQuantity = product.quantity + item.quantity;

        if (newQuantity > availableQuantity) {
            alert(`الكمية المتاحة للمنتج ${product.name} هي ${availableQuantity.toFixed(2)} فقط`);
            // Reset the input value
            const input = document.querySelector(`#wasteItemsBody .quantity-edit-input[data-index="${index}"]`);
            if (input) input.value = item.quantity.toFixed(2);
            return;
        }

        // Update product quantity
        product.quantity = availableQuantity - newQuantity;

        // Update waste item
        item.quantity = newQuantity;
        item.totalCost = newQuantity * item.unitCost;

        // Update display of total cost cell
        const row = document.querySelector(`#wasteItemsBody tr:nth-child(${index + 1})`);
        if (row) {
            row.cells[5].textContent = item.totalCost.toFixed(2) + ' ريال';
        }

        this.updateSummary();
    }

    removeItem(index) {
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            const item = this.wasteItems[index];
            const product = this.products.find(p => p.code === item.code);

            if (product) {
                product.quantity += item.quantity;
            }

            this.wasteItems.splice(index, 1);

            this.renderWasteTable();
            this.updateSummary();
        }
    }

    updateSummary() {
        const totalItems = this.wasteItems.length;
        let totalQuantity = 0;
        let totalCost = 0;

        this.wasteItems.forEach(item => {
            totalQuantity += item.quantity;
            totalCost += item.totalCost;
        });

        document.getElementById('wasteTotalItems').textContent = totalItems;
        document.getElementById('wasteTotalQuantity').textContent = totalQuantity.toFixed(2);
        document.getElementById('wasteTotalCost').textContent = totalCost.toFixed(2) + ' ريال';
    }

    validateWaste() {
        const branch = document.getElementById('wasteBranch').value;
        const reason = document.getElementById('wasteReason').value;

        if (!branch) {
            alert('الرجاء اختيار الفرع');
            return false;
        }

        if (!reason) {
            alert('الرجاء اختيار سبب الإتلاف');
            return false;
        }

        if (this.wasteItems.length === 0) {
            alert('الرجاء إضافة منتجات للإتلاف');
            return false;
        }

        return true;
    }

    saveWaste() {
        if (!this.validateWaste()) return;

        const wasteData = {
            wasteNumber: document.getElementById('wasteNumber').value,
            wasteDate: document.getElementById('wasteDate').value,
            branch: document.getElementById('wasteBranch').value,
            reason: document.getElementById('wasteReason').value,
            notes: document.getElementById('wasteNotes').value,
            items: this.wasteItems,
            totalItems: this.wasteItems.length,
            totalQuantity: this.wasteItems.reduce((sum, item) => sum + item.quantity, 0),
            totalCost: this.wasteItems.reduce((sum, item) => sum + item.totalCost, 0)
        };

        fetch('/wastes/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(wasteData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('تم حفظ عملية الإتلاف بنجاح');
                this.currentWasteNumber++;
                document.getElementById('wasteNumber').value = this.currentWasteNumber;
                this.clearForm();
            } else {
                alert('حدث خطأ أثناء الحفظ');
            }
        })
        .catch(error => {
            console.error('خطأ في الحفظ:', error);
            alert('فشل في الاتصال بالخادم');
        });
    }


    printWaste() {
        if (!this.validateWaste()) return;

        alert('جاري طباعة نموذج الإتلاف...');
    }

    clearForm() {
        document.getElementById('wasteBranch').value = '';
        document.getElementById('wasteReason').value = '';
        document.getElementById('wasteNotes').value = '';
        document.getElementById('wasteProductSearch').value = '';
        document.getElementById('wasteCategoryFilter').value = '';

        this.wasteItems = [];

        // Reset product quantities
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

        this.renderWasteTable();
        this.updateSummary();
    }

    showProductBrowser(filteredProducts = null) {
        // Create a modal to browse products
        const modal = document.createElement('div');
        modal.className = 'product-browser-overlay';

        const modalContent = document.createElement('div');
        modalContent.className = 'product-browser-content';

        // If no filtered products provided, show all or apply current category filter
        const productsToShow = filteredProducts || this.products;

        modalContent.innerHTML = `
            <div class="modal-header">
                <h3>تصفح المنتجات</h3>
                <div class="browser-search">
                    <input type="text" placeholder="بحث..." id="browserSearchInput">
                </div>
                <div class="browser-controls">
                    <button id="showAllProductsBtn" class="control-btn">عرض كل المنتجات</button>
                    <button id="addSelectedProductsBtn" class="control-btn add-selected">إضافة المحدد <span id="selectedCount">(0)</span></button>
                </div>
                <button class="close-modal-btn">×</button>
            </div>
            <div class="product-browser-table-container">
                <table class="product-browser-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllProducts"></th>
                            <th>رقم المنتج</th>
                            <th>اسم المنتج</th>
                            <th>الفئة</th>
                            <th>المخزون</th>
                            <th>السعر</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${productsToShow.map(product => `
                            <tr>
                                <td><input type="checkbox" class="product-checkbox" data-code="${product.code}"></td>
                                <td>${product.code}</td>
                                <td>${product.name}</td>
                                <td>${product.category}</td>
                                <td>${product.quantity.toFixed(2)}</td>
                                <td>${product.unitCost.toFixed(2)} ريال</td>
                                <td>
                                    <button class="select-product-btn" data-code="${product.code}">
                                        <i class="fas fa-plus-circle"></i> إضافة
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            <div class="multi-select-controls">
                <div class="quantity-control">
                    <label for="multi-quantity">الكمية للمحدد:</label>
                    <input type="number" id="multi-quantity" min="0.01" step="0.01" value="1.00">
                </div>
            </div>
        `;

        modal.appendChild(modalContent);
        document.body.appendChild(modal);

        // Add event listener for close button
        const closeButton = modal.querySelector('.close-modal-btn');
        if (closeButton) {
            closeButton.addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        }

        // Add event listeners for select buttons
        const selectButtons = modal.querySelectorAll('.select-product-btn');
        selectButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const code = e.currentTarget.getAttribute('data-code');
                const product = this.products.find(p => p.code === code);

                if (product) {
                    // Add to waste items with default quantity
                    this.addItemManually(product, 1);
                }

                document.body.removeChild(modal);
            });
        });

        // Add search functionality
        const searchInput = modal.querySelector('#browserSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const rows = modal.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const productCode = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const productName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                    const productCategory = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                    if (productCode.includes(searchTerm) ||
                        productName.includes(searchTerm) ||
                        productCategory.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Focus search input
            searchInput.focus();
        }

        // Handle select all checkbox
        const selectAllCheckbox = modal.querySelector('#selectAllProducts');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                const isChecked = e.target.checked;
                const productCheckboxes = modal.querySelectorAll('.product-checkbox');

                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });

                // Update selected count
                this.updateSelectedCount(modal);
            });
        }

        // Handle individual product checkboxes
        const productCheckboxes = modal.querySelectorAll('.product-checkbox');
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateSelectedCount(modal);
            });
        });

        // Show all products button
        const showAllProductsBtn = modal.querySelector('#showAllProductsBtn');
        if (showAllProductsBtn) {
            showAllProductsBtn.addEventListener('click', () => {
                this.showAllProducts(modal);
            });
        }

        // Add selected products button
        const addSelectedProductsBtn = modal.querySelector('#addSelectedProductsBtn');
        if (addSelectedProductsBtn) {
            addSelectedProductsBtn.addEventListener('click', () => {
                this.addSelectedProducts(modal);
            });
        }
    }

    updateSelectedCount(modal) {
        const selectedCheckboxes = modal.querySelectorAll('.product-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        const selectedCountElement = modal.querySelector('#selectedCount');

        if (selectedCountElement) {
            selectedCountElement.textContent = `(${selectedCount})`;
        }
    }

    showAllProducts(modal) {
        // Get the table body
        const tableBody = modal.querySelector('.product-browser-table tbody');
        if (!tableBody) return;

        // Clear existing rows
        tableBody.innerHTML = '';

        // Add all products
        this.products.forEach(product => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="checkbox" class="product-checkbox" data-code="${product.code}"></td>
                <td>${product.code}</td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td>${product.quantity.toFixed(2)}</td>
                <td>${product.unitCost.toFixed(2)} ريال</td>
                <td>
                    <button class="select-product-btn" data-code="${product.code}">
                        <i class="fas fa-plus-circle"></i> إضافة
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        // Re-add event listeners
        const selectButtons = modal.querySelectorAll('.select-product-btn');
        selectButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const code = e.currentTarget.getAttribute('data-code');
                const product = this.products.find(p => p.code === code);

                if (product) {
                    // Add to waste items with default quantity
                    this.addItemManually(product, 1);
                }

                document.body.removeChild(modal);
            });
        });

        // Re-add checkbox event listeners
        const productCheckboxes = modal.querySelectorAll('.product-checkbox');
        productCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateSelectedCount(modal);
            });
        });
    }

    addSelectedProducts(modal) {
        const selectedCheckboxes = modal.querySelectorAll('.product-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('الرجاء تحديد منتج واحد على الأقل');
            return;
        }

        // Get quantity
        const quantityInput = modal.querySelector('#multi-quantity');
        const quantity = parseFloat(quantityInput.value) || 1;

        if (quantity <= 0) {
            alert('الرجاء إدخال كمية صحيحة');
            return;
        }

        // Add each selected product
        selectedCheckboxes.forEach(checkbox => {
            const code = checkbox.getAttribute('data-code');
            const product = this.products.find(p => p.code === code);

            if (!product) return;

            // Check if there's enough inventory
            if (product.quantity < quantity) {
                alert(`الكمية المتاحة للمنتج ${product.name} هي ${product.quantity} فقط`);
                return;
            }

            // Add to waste directly
            this.addItemManually(product, quantity);
        });

        // Close modal
        document.body.removeChild(modal);
    }
}
