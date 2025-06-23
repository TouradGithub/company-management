document.addEventListener('DOMContentLoaded', function() {
    // Add Assets Table tab
    const tabsContainer = document.querySelector('.tabs');
    const tabContents = document.querySelector('.container');
    
    // Create and add the new tab button
    const assetsTableTab = document.createElement('div');
    assetsTableTab.className = 'tab';
    assetsTableTab.setAttribute('data-tab', 'assetsTable');
    assetsTableTab.textContent = 'جدول الأصول';
    tabsContainer.appendChild(assetsTableTab);
    
    // Create tab content
    const assetsTableContent = document.createElement('div');
    assetsTableContent.className = 'tab-content';
    assetsTableContent.id = 'assetsTable';
    
    // Build the assets table view with filter and search
    assetsTableContent.innerHTML = `
        <h2>جدول الأصول</h2>
        
        <div class="filter-controls">
            <div class="search-container">
                <input type="text" id="assetSearchInput" placeholder="البحث عن الأصول...">
                <button id="assetSearchBtn">بحث</button>
            </div>
            
            <div class="filter-options">
                <div class="form-group">
                    <label for="branchFilter">تصفية حسب الفرع</label>
                    <select id="branchFilter">
                        <option value="">جميع الفروع</option>
                        <option value="الرئيسي">الرئيسي</option>
                        <option value="فرع 1">فرع 1</option>
                        <option value="فرع 2">فرع 2</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="categoryFilter">تصفية حسب الفئة</label>
                    <select id="categoryFilter">
                        <option value="">جميع الفئات</option>
                        <!-- Will be populated dynamically from categories -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="sortBy">ترتيب حسب</label>
                    <select id="sortBy">
                        <option value="id">رقم الأصل</option>
                        <option value="name">اسم الأصل</option>
                        <option value="purchaseDate">تاريخ الشراء</option>
                        <option value="originalCost">قيمة الشراء</option>
                        <option value="bookValue">القيمة الدفترية</option>
                    </select>
                </div>
                
                <button id="resetFiltersBtn">إعادة تعيين الفلاتر</button>
            </div>
        </div>
        
        <div class="export-controls">
            <button id="exportExcelBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                    <path d="M10 12l4 4m0 -4l-4 4"></path>
                </svg>
                تصدير اكسل
            </button>
            <button id="exportPDFBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                    <path d="M9 9h1"></path><path d="M9 13h6"></path><path d="M9 17h6"></path>
                </svg>
                تصدير PDF
            </button>
            <button id="printTableBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 9v-3a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v3"></path>
                    <path d="M6 18h12a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2"></path>
                    <path d="M8 14h8"></path><path d="M8 18h8"></path>
                </svg>
                طباعة
            </button>
            <button id="previewReportBtn" class="export-button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="2"></circle>
                    <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                </svg>
                معاينة التقرير
            </button>
        </div>
        
        <div class="assets-table-container">
            <table id="assetsListTable">
                <thead>
                    <tr>
                        <th>رقم الأصل</th>
                        <th>اسم الأصل</th>
                        <th>الفرع</th>
                        <th>الفئة</th>
                        <th>تاريخ الشراء</th>
                        <th>قيمة الشراء</th>
                        <th>مجمع الإهلاك</th>
                        <th>القيمة الدفترية</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Assets will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
        
        <div id="assetDetailsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>تفاصيل الأصل</h2>
                <div id="assetDetailsContent">
                    <!-- Asset details will be loaded here -->
                </div>
            </div>
        </div>
    `;
    
    // Add new content to page
    tabContents.appendChild(assetsTableContent);
    
    // Add CSS for the new view
    const style = document.createElement('style');
    style.textContent = `
        .filter-controls {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .search-container {
            display: flex;
            margin-bottom: 15px;
        }
        
        .search-container input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        
        .search-container button {
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
        }
        
        .filter-options {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .filter-options .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        #resetFiltersBtn {
            background-color: #e74c3c;
        }
        
        #resetFiltersBtn:hover {
            background-color: #c0392b;
        }
        
        .assets-table-container {
            overflow-x: auto;
        }
        
        #assetsListTable {
            width: 100%;
            border-collapse: collapse;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        
        .asset-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .branch-badge {
            background-color: #e8f7ff;
            color: #3498db;
        }
        
        .category-badge {
            background-color: #f0f7e8;
            color: #27ae60;
        }
        
        .export-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .export-button {
            display: flex;
            align-items: center;
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        
        .export-button svg {
            margin-left: 8px;
        }
        
        .export-button:hover {
            background-color: #27ae60;
        }
        
        #exportPDFBtn {
            background-color: #e74c3c;
        }
        
        #exportPDFBtn:hover {
            background-color: #c0392b;
        }
        
        #printTableBtn {
            background-color: #3498db;
        }
        
        #printTableBtn:hover {
            background-color: #2980b9;
        }
        
        #previewReportBtn {
            background-color: #9b59b6;
        }
        
        #previewReportBtn:hover {
            background-color: #8e44ad;
        }
    `;
    document.head.appendChild(style);
    
    // Initialize the assets table functionality
    initAssetsTable();
    
    function initAssetsTable() {
        // References to DOM elements
        const assetSearchInput = document.getElementById('assetSearchInput');
        const assetSearchBtn = document.getElementById('assetSearchBtn');
        const branchFilter = document.getElementById('branchFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const sortBySelect = document.getElementById('sortBy');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');
        const assetsTableBody = document.querySelector('#assetsListTable tbody');
        const assetDetailsModal = document.getElementById('assetDetailsModal');
        
        // Export buttons
        const exportExcelBtn = document.getElementById('exportExcelBtn');
        const exportPDFBtn = document.getElementById('exportPDFBtn');
        const printTableBtn = document.getElementById('printTableBtn');
        const previewReportBtn = document.getElementById('previewReportBtn');
        
        // Populate category filter from available categories
        populateCategoryFilter();
        
        // Update the assets table with any existing assets
        updateAssetsTable();
        
        // Add event listeners
        assetSearchBtn.addEventListener('click', updateAssetsTable);
        assetSearchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') updateAssetsTable();
        });
        branchFilter.addEventListener('change', updateAssetsTable);
        categoryFilter.addEventListener('change', updateAssetsTable);
        sortBySelect.addEventListener('change', updateAssetsTable);
        resetFiltersBtn.addEventListener('click', resetFilters);
        
        // Add event listeners for export buttons
        if (exportExcelBtn) {
            exportExcelBtn.addEventListener('click', function() {
                if (window.exportToExcel) {
                    window.exportToExcel('assetsListTable', 'assets-report');
                } else {
                    alert('تعذر تحميل مكتبة التصدير. الرجاء المحاولة مرة أخرى لاحقًا.');
                }
            });
        }
        
        if (exportPDFBtn) {
            exportPDFBtn.addEventListener('click', function() {
                if (window.exportToPDF) {
                    window.exportToPDF('assetsListTable', 'assets-report');
                } else {
                    alert('تعذر تحميل مكتبة التصدير. الرجاء المحاولة مرة أخرى لاحقًا.');
                }
            });
        }
        
        if (printTableBtn) {
            printTableBtn.addEventListener('click', function() {
                if (window.printTable) {
                    window.printTable('assetsListTable');
                } else {
                    alert('تعذر تحميل مكتبة الطباعة. الرجاء المحاولة مرة أخرى لاحقًا.');
                }
            });
        }
        
        if (previewReportBtn) {
            previewReportBtn.addEventListener('click', function() {
                if (window.previewReport) {
                    window.previewReport('assetsListTable');
                } else {
                    alert('تعذر تحميل مكتبة المعاينة. الرجاء المحاولة مرة أخرى لاحقًا.');
                }
            });
        }
        
        // Close modal when clicking on X
        const closeModalBtns = document.querySelectorAll('#assetDetailsModal .close');
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                assetDetailsModal.style.display = 'none';
            });
        });
        
        // Close modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target == assetDetailsModal) {
                assetDetailsModal.style.display = 'none';
            }
        });
        
        // Function to populate the category filter
        function populateCategoryFilter() {
            const categories = window.getAssetCategories ? window.getAssetCategories() : [];
            const select = document.getElementById('categoryFilter');
            
            // Keep the default "All Categories" option
            while (select.options.length > 1) {
                select.remove(1);
            }
            
            // Add options for each category
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.code;
                option.textContent = category.name;
                select.appendChild(option);
            });
        }
        
        // Function to reset all filters
        function resetFilters() {
            assetSearchInput.value = '';
            branchFilter.value = '';
            categoryFilter.value = '';
            sortBySelect.value = 'id';
            updateAssetsTable();
        }
        
        // Function to update the assets table based on filters
        function updateAssetsTable() {
            const assets = window.getAssets ? window.getAssets() : [];
            
            // If we have access to the assets, enhance them with branch info if not already present
            assets.forEach((asset, index) => {
                if (!asset.id) asset.id = index + 1;
                if (!asset.branch) asset.branch = 'الرئيسي';  // Default branch
            });
            
            // Get filter values
            const searchText = assetSearchInput.value.toLowerCase();
            const branchValue = branchFilter.value;
            const categoryValue = categoryFilter.value;
            const sortBy = sortBySelect.value;
            
            // Filter assets
            let filteredAssets = assets.filter(asset => {
                const matchesSearch = !searchText || 
                    asset.name.toLowerCase().includes(searchText) || 
                    (asset.id.toString().includes(searchText));
                
                const matchesBranch = !branchValue || asset.branch === branchValue;
                const matchesCategory = !categoryValue || asset.type === categoryValue;
                
                return matchesSearch && matchesBranch && matchesCategory;
            });
            
            // Sort assets
            filteredAssets.sort((a, b) => {
                switch(sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'purchaseDate':
                        return new Date(a.purchaseDate) - new Date(b.purchaseDate);
                    case 'originalCost':
                        return a.originalCost - b.originalCost;
                    case 'bookValue':
                        const aDepreciation = a.depreciations.reduce((sum, d) => sum + d.value, 0);
                        const bDepreciation = b.depreciations.reduce((sum, d) => sum + d.value, 0);
                        return (a.originalCost - aDepreciation) - (b.originalCost - bDepreciation);
                    default: // id
                        return a.id - b.id;
                }
            });
            
            // Clear existing rows
            assetsTableBody.innerHTML = '';
            
            // Get category names mapping
            const categories = window.getAssetCategories ? window.getAssetCategories() : [];
            const categoryNames = {};
            categories.forEach(cat => {
                categoryNames[cat.code] = cat.name;
            });
            
            // Add rows for filtered assets
            if (filteredAssets.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = '<td colspan="9" style="text-align: center;">لا توجد أصول تطابق معايير البحث</td>';
                assetsTableBody.appendChild(emptyRow);
            } else {
                filteredAssets.forEach(asset => {
                    const totalDepreciation = asset.depreciations.reduce((sum, d) => sum + d.value, 0);
                    const bookValue = asset.originalCost - totalDepreciation;
                    const categoryName = categoryNames[asset.type] || asset.type;
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${asset.id}</td>
                        <td>${asset.name}</td>
                        <td><span class="asset-badge branch-badge">${asset.branch}</span></td>
                        <td><span class="asset-badge category-badge">${categoryName}</span></td>
                        <td>${asset.purchaseDate}</td>
                        <td>${asset.originalCost.toLocaleString()}</td>
                        <td>${totalDepreciation.toLocaleString()}</td>
                        <td>${bookValue.toLocaleString()}</td>
                        <td class="action-buttons">
                            <button class="icon-button view-asset" data-id="${asset.id}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            ${!asset.sold ? `
                            <button class="icon-button edit-asset" data-id="${asset.id}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </button>
                            ` : ''}
                        </td>
                    `;
                    
                    assetsTableBody.appendChild(row);
                });
                
                // Add event listeners for view and edit buttons
                document.querySelectorAll('.view-asset').forEach(button => {
                    button.addEventListener('click', function() {
                        const assetId = parseInt(this.getAttribute('data-id'));
                        showAssetDetails(filteredAssets.find(a => a.id === assetId));
                    });
                });
                
                document.querySelectorAll('.edit-asset').forEach(button => {
                    button.addEventListener('click', function() {
                        const assetId = parseInt(this.getAttribute('data-id'));
                        const asset = filteredAssets.find(a => a.id === assetId);
                        
                        // Switch to sell tab if the asset is not sold
                        if (!asset.sold) {
                            // Find the sell tab and click it
                            const sellTab = document.querySelector('.tab[data-tab="sell"]');
                            if (sellTab) {
                                sellTab.click();
                                
                                // Select this asset in the dropdown
                                setTimeout(() => {
                                    const sellAssetSelect = document.getElementById('sellAssetSelect');
                                    const assetIndex = assets.findIndex(a => a.id === assetId);
                                    if (sellAssetSelect && assetIndex >= 0) {
                                        sellAssetSelect.value = assetIndex;
                                        sellAssetSelect.dispatchEvent(new Event('change'));
                                    }
                                }, 100);
                            }
                        }
                    });
                });
            }
        }
        
        // Function to show asset details
        function showAssetDetails(asset) {
            if (!asset) return;
            
            const modalContent = document.getElementById('assetDetailsContent');
            const categories = window.getAssetCategories ? window.getAssetCategories() : [];
            const categoryNames = {};
            categories.forEach(cat => {
                categoryNames[cat.code] = cat.name;
            });
            
            const totalDepreciation = asset.depreciations.reduce((sum, d) => sum + d.value, 0);
            const bookValue = asset.originalCost - totalDepreciation;
            const categoryName = categoryNames[asset.type] || asset.type;
            
            // Format depreciation history
            let depreciationHistory = '';
            if (asset.depreciations.length > 0) {
                depreciationHistory = '<h3>سجل الإهلاك</h3><table class="details-table">';
                depreciationHistory += '<tr><th>السنة</th><th>قيمة الإهلاك</th></tr>';
                
                asset.depreciations.forEach(dep => {
                    depreciationHistory += `<tr><td>${dep.year}</td><td>${dep.value.toLocaleString()}</td></tr>`;
                });
                
                depreciationHistory += '</table>';
            } else {
                depreciationHistory = '<p>لا يوجد سجل إهلاك لهذا الأصل</p>';
            }
            
            // Add sale information if the asset was sold
            let saleInfo = '';
            if (asset.sold && asset.saleDetails) {
                saleInfo = `
                    <h3>معلومات البيع</h3>
                    <div class="sale-info">
                        <p><strong>تاريخ البيع:</strong> ${asset.saleDetails.date}</p>
                        <p><strong>قيمة البيع:</strong> ${asset.saleDetails.amount.toLocaleString()}</p>
                        <p><strong>الربح/الخسارة:</strong> ${Math.abs(asset.saleDetails.gainOrLoss).toLocaleString()} 
                            ${asset.saleDetails.gainOrLoss >= 0 ? '(ربح)' : '(خسارة)'}</p>
                    </div>
                `;
            }
            
            modalContent.innerHTML = `
                <div class="asset-details">
                    <div class="asset-header">
                        <h3>${asset.name}</h3>
                        <span class="asset-id">رقم الأصل: ${asset.id}</span>
                    </div>
                    
                    <div class="details-section">
                        <div class="details-row">
                            <div class="detail-item">
                                <span class="detail-label">الفرع:</span>
                                <span class="detail-value">${asset.branch}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">الفئة:</span>
                                <span class="detail-value">${categoryName}</span>
                            </div>
                        </div>
                        
                        <div class="details-row">
                            <div class="detail-item">
                                <span class="detail-label">تاريخ الشراء:</span>
                                <span class="detail-value">${asset.purchaseDate}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">قيمة الشراء:</span>
                                <span class="detail-value">${asset.originalCost.toLocaleString()}</span>
                            </div>
                        </div>
                        
                        <div class="details-row">
                            <div class="detail-item">
                                <span class="detail-label">القيمة التخريدية:</span>
                                <span class="detail-value">${asset.salvageValue.toLocaleString()}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">العمر الإنتاجي:</span>
                                <span class="detail-value">${asset.usefulLife} سنوات</span>
                            </div>
                        </div>
                        
                        <div class="details-row">
                            <div class="detail-item">
                                <span class="detail-label">مجمع الإهلاك:</span>
                                <span class="detail-value">${totalDepreciation.toLocaleString()}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">القيمة الدفترية:</span>
                                <span class="detail-value">${bookValue.toLocaleString()}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="depreciation-history">
                        ${depreciationHistory}
                    </div>
                    
                    ${saleInfo}
                </div>
            `;
            
            const detailsStyle = document.createElement('style');
            detailsStyle.textContent = `
                .asset-details {
                    padding: 10px;
                }
                
                .asset-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 10px;
                }
                
                .asset-id {
                    background-color: #f8f9fa;
                    padding: 5px 10px;
                    border-radius: 4px;
                    font-size: 14px;
                }
                
                .details-section {
                    margin-bottom: 20px;
                }
                
                .details-row {
                    display: flex;
                    margin-bottom: 10px;
                }
                
                .detail-item {
                    flex: 1;
                    padding: 8px;
                }
                
                .detail-label {
                    font-weight: bold;
                    color: #666;
                    display: block;
                    margin-bottom: 5px;
                }
                
                .detail-value {
                    font-size: 16px;
                }
                
                .depreciation-history {
                    margin-top: 20px;
                }
                
                .details-table {
                    width: 100%;
                    border-collapse: collapse;
                }
                
                .details-table th, .details-table td {
                    padding: 8px;
                    text-align: center;
                    border: 1px solid #ddd;
                }
                
                .details-table th {
                    background-color: #f2f2f2;
                }
                
                .sale-info {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 5px;
                    margin-top: 10px;
                }
            `;
            document.head.appendChild(detailsStyle);
            
            assetDetailsModal.style.display = 'block';
        }
        
        // Make sure to update the assets table when assets change
        const originalAddTransaction = window.addTransaction;
        if (originalAddTransaction) {
            // Remove transaction observer code
        }
        
        // Update asset form to include branch
        const assetForm = document.getElementById('assetForm');
        if (assetForm) {
            // Create branch field if it doesn't exist
            if (!document.getElementById('assetBranch')) {
                const purchaseDateField = document.getElementById('purchaseDate').closest('.form-group');
                
                const branchGroup = document.createElement('div');
                branchGroup.className = 'form-group';
                
                const branchLabel = document.createElement('label');
                branchLabel.setAttribute('for', 'assetBranch');
                branchLabel.textContent = 'الفرع';
                
                const branchSelect = document.createElement('select');
                branchSelect.id = 'assetBranch';
                branchSelect.required = true;
                
                branchSelect.innerHTML = `
                    <option value="الرئيسي">الرئيسي</option>
                    <option value="فرع 1">فرع 1</option>
                    <option value="فرع 2">فرع 2</option>
                `;
                
                branchGroup.appendChild(branchLabel);
                branchGroup.appendChild(branchSelect);
                
                // Add it after purchase date
                purchaseDateField.parentNode.insertBefore(branchGroup, purchaseDateField.nextSibling);
                
                // Modify the form submit handler to include the branch
                const originalSubmitHandler = assetForm.onsubmit;
                assetForm.onsubmit = function(e) {
                    // Remove the existing handler to avoid double processing
                    this.onsubmit = null;
                    
                    // Get the branch value
                    const branch = document.getElementById('assetBranch').value;
                    
                    // Call the original handler
                    if (originalSubmitHandler) {
                        originalSubmitHandler.call(this, e);
                    }
                    
                    // Add branch to the last asset (assuming it was just added)
                    const assets = window.getAssets ? window.getAssets() : [];
                    if (assets.length > 0) {
                        const lastAsset = assets[assets.length - 1];
                        lastAsset.branch = branch;
                        lastAsset.id = assets.length; // Add ID if not present
                    }
                    
                    // Restore the submit handler
                    this.onsubmit = originalSubmitHandler;
                };
            }
        }
    }
    
    // Add event handler for tab
    assetsTableTab.addEventListener('click', function() {
        const allTabs = document.querySelectorAll('.tab');
        allTabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const allContents = document.querySelectorAll('.tab-content');
        allContents.forEach(c => c.classList.remove('active'));
        document.getElementById('assetsTable').classList.add('active');
    });
});