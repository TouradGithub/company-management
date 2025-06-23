class TransferUIHelper {
    static initialize(transferHandler) {
        this.transferHandler = transferHandler;
        
        // Add quick add buttons for each category
        this.addQuickAddButtons();
        
        // Add product list functionality
        this.setupProductList();
    }
    
    static addQuickAddButtons() {
        const filterControls = document.querySelector('.transfer-filter-controls');
        if (!filterControls) return;
        
        const quickAddDiv = document.createElement('div');
        quickAddDiv.className = 'quick-add-buttons';
        quickAddDiv.innerHTML = `
            <label>إضافة سريعة:</label>
            <div class="button-container">
                ${this.transferHandler.categories.slice(0, 5).map(cat => 
                    `<button class="quick-add-btn" data-category="${cat}">
                        <i class="fas fa-plus-circle"></i> ${cat}
                    </button>`
                ).join('')}
                <button class="quick-add-btn more-btn">
                    <i class="fas fa-ellipsis-h"></i> المزيد
                </button>
            </div>
        `;
        
        filterControls.appendChild(quickAddDiv);
        
        // Add event listeners for quick add buttons
        const quickAddButtons = document.querySelectorAll('.quick-add-btn:not(.more-btn)');
        quickAddButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const category = e.currentTarget.getAttribute('data-category');
                this.showProductsForCategory(category);
            });
        });
        
        // Add event listener for more button
        const moreButton = document.querySelector('.quick-add-btn.more-btn');
        if (moreButton) {
            moreButton.addEventListener('click', () => this.showMoreCategories());
        }
    }
    
    static showProductsForCategory(category) {
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.value = category;
            
            // Trigger the category filter
            const event = new Event('change');
            categoryFilter.dispatchEvent(event);
        }
    }
    
    static showMoreCategories() {
        // Create a modal with the remaining categories
        const modal = document.createElement('div');
        modal.className = 'category-modal-overlay';
        
        const modalContent = document.createElement('div');
        modalContent.className = 'category-modal-content';
        
        modalContent.innerHTML = `
            <div class="modal-header">
                <h3>اختر الفئة</h3>
                <button class="close-modal-btn">×</button>
            </div>
            <div class="category-grid">
                ${this.transferHandler.categories.map(cat => 
                    `<button class="category-grid-btn" data-category="${cat}">
                        <i class="fas fa-folder"></i>
                        <span>${cat}</span>
                    </button>`
                ).join('')}
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
        
        // Add event listeners for category buttons
        const categoryButtons = modal.querySelectorAll('.category-grid-btn');
        categoryButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const category = e.currentTarget.getAttribute('data-category');
                this.showProductsForCategory(category);
                document.body.removeChild(modal);
            });
        });
    }
    
    static setupProductList() {
        const productSelectContainer = document.querySelector('.product-select-container');
        if (!productSelectContainer) return;
        
        // Add floating product browser button
        const browseButton = document.createElement('button');
        browseButton.className = 'browse-products-btn';
        browseButton.innerHTML = '<i class="fas fa-search"></i> تصفح المنتجات';
        
        // Add button directly to the filter controls instead of product select container
        const filterControls = document.querySelector('.transfer-filter-controls');
        if (filterControls) {
            filterControls.appendChild(browseButton);
        }
        
        // Add event listener
        browseButton.addEventListener('click', () => this.showProductBrowser());
    }
    
    static showProductBrowser() {
        // Get filtered products (or all if no filter)
        const categoryFilter = document.getElementById('categoryFilter');
        let filteredProducts = this.transferHandler.products;
        
        if (categoryFilter && categoryFilter.value) {
            filteredProducts = this.transferHandler.products.filter(p => p.category === categoryFilter.value);
        }
        
        // Create a modal to browse products
        const modal = document.createElement('div');
        modal.className = 'product-browser-overlay';
        
        const modalContent = document.createElement('div');
        modalContent.className = 'product-browser-content';
        
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
                        ${filteredProducts.map(product => `
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
                const productSelect = document.getElementById('productSelect');
                if (productSelect) {
                    productSelect.value = code;
                    
                    // Set focus to quantity input
                    const quantityInput = document.getElementById('quantity');
                    if (quantityInput) {
                        quantityInput.value = "1.00";
                        quantityInput.focus();
                        quantityInput.select();
                    }
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
    
    static updateSelectedCount(modal) {
        const selectedCheckboxes = modal.querySelectorAll('.product-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        const selectedCountElement = modal.querySelector('#selectedCount');
        
        if (selectedCountElement) {
            selectedCountElement.textContent = `(${selectedCount})`;
        }
    }
    
    static showAllProducts(modal) {
        // Get all products from the handler
        const allProducts = this.transferHandler.products;
        
        // Get the table body
        const tableBody = modal.querySelector('.product-browser-table tbody');
        if (!tableBody) return;
        
        // Clear existing rows
        tableBody.innerHTML = '';
        
        // Add all products
        allProducts.forEach(product => {
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
                const productSelect = document.getElementById('productSelect');
                if (productSelect) {
                    productSelect.value = code;
                    
                    // Set focus to quantity input
                    const quantityInput = document.getElementById('quantity');
                    if (quantityInput) {
                        quantityInput.value = "1.00";
                        quantityInput.focus();
                        quantityInput.select();
                    }
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
    
    static addSelectedProducts(modal) {
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
            const product = this.transferHandler.products.find(p => p.code === code);
            
            if (!product) return;
            
            // Check if there's enough inventory
            if (product.quantity < quantity) {
                alert(`الكمية المتاحة للمنتج ${product.name} هي ${product.quantity} فقط`);
                return;
            }
            
            // Add to transfer directly without going through the removed UI elements
            this.transferHandler.addItemManually(product, quantity);
        });
        
        // Close modal
        document.body.removeChild(modal);
    }
}