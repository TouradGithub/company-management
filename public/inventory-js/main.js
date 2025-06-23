document.addEventListener('DOMContentLoaded', function() {
    // إدارة التبويبات
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            const tabId = tab.getAttribute('data-tab');
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');

            // تحديث جدول الإهلاك السنوي العام عندما يتم فتح هذا التبويب
            if (tabId === 'annualDepreciation') {
                updateAnnualDepreciationTable();
            }
            
            // تحديث جدول الإهلاكات عندما يتم فتح هذا التبويب
            if (tabId === 'depreciationDetails') {
                updateDepreciationDetailsTable();
            }
        });
    });
    
    // تخزين الأصول (سيتم استبداله بمخزن بيانات فعلي)
    let assets = [];
    
    // نموذج تسجيل الأصل
    document.getElementById('assetForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const asset = {
            name: document.getElementById('assetName').value,
            type: document.getElementById('assetType').value,
            purchaseDate: document.getElementById('purchaseDate').value,
            originalCost: parseFloat(document.getElementById('originalCost').value),
            salvageValue: parseFloat(document.getElementById('salvageValue').value),
            usefulLife: parseInt(document.getElementById('usefulLife').value),
            depreciations: [],
            sold: false,
            saleDetails: null
        };
        
        assets.push(asset);
        
        alert('تم حفظ الأصل بنجاح!');
        this.reset();
        updateDepreciationTable();
        updateAssetSelectList();
    });
    
    // نموذج تسجيل الإهلاكات
    document.getElementById('depreciationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // في تطبيق حقيقي، يجب اختيار الأصل المراد تسجيل الإهلاك له
        if (assets.length === 0) {
            alert('لا توجد أصول مسجلة بعد');
            return;
        }
        
        const year = parseInt(document.getElementById('year').value);
        const depreciationValue = parseFloat(document.getElementById('depreciationValue').value);
        
        // إضافة الإهلاك للأصل الأول (في تطبيق حقيقي، يجب اختيار الأصل)
        assets[0].depreciations.push({
            year,
            value: depreciationValue
        });
        
        alert('تم حفظ الإهلاك بنجاح!');
        this.reset();
        updateDepreciationTable();
        updateAnnualDepreciationTable();
    });
    
    // تحديث جدول الإهلاكات
    function updateDepreciationTable() {
        const tableBody = document.querySelector('#depreciationTable tbody');
        tableBody.innerHTML = '';
        
        assets.forEach(asset => {
            const totalDepreciation = asset.depreciations.reduce((sum, d) => sum + d.value, 0);
            const bookValue = asset.originalCost - totalDepreciation;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${asset.name}</td>
                <td>${asset.type}</td>
                <td>${asset.purchaseDate}</td>
                <td>${asset.originalCost.toLocaleString()}</td>
                <td>${asset.salvageValue.toLocaleString()}</td>
                <td>${asset.usefulLife}</td>
                <td>${(asset.originalCost / asset.usefulLife).toLocaleString()}</td>
                <td>${totalDepreciation.toLocaleString()}</td>
                <td>${bookValue.toLocaleString()}</td>
                <td><button class="icon-button" onclick="showJournalEntries('${asset.name}', ${asset.originalCost / asset.usefulLife})"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></button></td>
            `;
            tableBody.appendChild(row);
        });
    }
    
    // تحديث جدول الإهلاك السنوي العام
    function updateAnnualDepreciationTable() {
        const container = document.getElementById('annualDepreciationTableContainer');
        container.innerHTML = '';
        
        // إذا لم تكن هناك أصول، عرض رسالة
        if (assets.length === 0) {
            container.innerHTML = '<p>لا توجد أصول مسجلة بعد</p>';
            return;
        }
        
        // تحديد الفترة الزمنية التي سيتم عرضها في الجدول
        let minYear = Infinity;
        let maxYear = -Infinity;
        let maxUsefulLife = 0;
        
        assets.forEach(asset => {
            const purchaseYear = new Date(asset.purchaseDate).getFullYear();
            minYear = Math.min(minYear, purchaseYear);
            maxYear = Math.max(maxYear, purchaseYear);
            maxUsefulLife = Math.max(maxUsefulLife, asset.usefulLife);
        });
        
        maxYear = Math.max(maxYear, minYear + maxUsefulLife);
        
        // إنشاء جدول الإهلاك السنوي
        const table = document.createElement('table');
        table.className = 'annual-depreciation-table';
        
        // إنشاء رأس الجدول
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        
        // إضافة خلية فارغة في أعلى اليمين
        headerRow.appendChild(document.createElement('th'));
        
        // إضافة السنوات كرؤوس أعمدة
        for (let year = minYear; year <= maxYear; year++) {
            const th = document.createElement('th');
            th.textContent = year;
            headerRow.appendChild(th);
        }
        
        // إضافة عمود المجموع
        const totalTh = document.createElement('th');
        totalTh.textContent = 'المجموع';
        headerRow.appendChild(totalTh);
        
        thead.appendChild(headerRow);
        table.appendChild(thead);
        
        // إنشاء جسم الجدول
        const tbody = document.createElement('tbody');
        
        // إضافة صف لكل أصل
        assets.forEach(asset => {
            if (asset.sold) return;
            const row = document.createElement('tr');
            
            // إضافة اسم الأصل في العمود الأول
            const nameCell = document.createElement('td');
            nameCell.className = 'year-column';
            nameCell.textContent = asset.name;
            row.appendChild(nameCell);
            
            // حساب الإهلاك السنوي
            const annualDepreciation = (asset.originalCost - asset.salvageValue) / asset.usefulLife;
            const purchaseYear = new Date(asset.purchaseDate).getFullYear();
            let totalAssetDepreciation = 0;
            
            // إضافة خلايا لكل سنة
            for (let year = minYear; year <= maxYear; year++) {
                const cell = document.createElement('td');
                
                // حساب الإهلاك لهذه السنة إذا كان الأصل موجودًا
                if (year >= purchaseYear && year < purchaseYear + asset.usefulLife) {
                    cell.textContent = annualDepreciation.toLocaleString();
                    totalAssetDepreciation += annualDepreciation;
                } else {
                    cell.textContent = '-';
                }
                
                row.appendChild(cell);
            }
            
            // إضافة خلية المجموع
            const totalCell = document.createElement('td');
            totalCell.textContent = totalAssetDepreciation.toLocaleString();
            row.appendChild(totalCell);
            
            tbody.appendChild(row);
        });
        
        // إضافة صف المجموع
        const totalRow = document.createElement('tr');
        totalRow.className = 'total-row';
        
        // إضافة عنوان المجموع
        const totalLabelCell = document.createElement('td');
        totalLabelCell.className = 'year-column';
        totalLabelCell.textContent = 'مجموع الإهلاك';
        totalRow.appendChild(totalLabelCell);
        
        // حساب مجموع الإهلاك لكل سنة
        let grandTotal = 0;
        
        for (let year = minYear; year <= maxYear; year++) {
            const cell = document.createElement('td');
            let yearTotal = 0;
            
            assets.forEach(asset => {
                if (asset.sold) return;
                const purchaseYear = new Date(asset.purchaseDate).getFullYear();
                const annualDepreciation = (asset.originalCost - asset.salvageValue) / asset.usefulLife;
                
                if (year >= purchaseYear && year < purchaseYear + asset.usefulLife) {
                    yearTotal += annualDepreciation;
                }
            });
            
            if (yearTotal > 0) {
                cell.textContent = yearTotal.toLocaleString();
                grandTotal += yearTotal;
            } else {
                cell.textContent = '-';
            }
            
            totalRow.appendChild(cell);
        }
        
        // إضافة خلية المجموع الكلي
        const grandTotalCell = document.createElement('td');
        grandTotalCell.textContent = grandTotal.toLocaleString();
        totalRow.appendChild(grandTotalCell);
        
        tbody.appendChild(totalRow);
        table.appendChild(tbody);
        
        // أضف عنوانًا فرعيًا للتوضيح
        const tableTitle = document.createElement('h3');
        tableTitle.textContent = 'جدول الإهلاك السنوي حسب الأصول';
        container.appendChild(tableTitle);
        
        container.appendChild(table);
    }
    
    // تحديث جدول تفاصيل الإهلاكات
    function updateDepreciationDetailsTable() {
        const tableBody = document.querySelector('#depreciationDetailsTable tbody');
        tableBody.innerHTML = '';
        
        // Fill category filter dropdown if needed
        const categoryFilter = document.getElementById('depreciationCategoryFilter');
        if (categoryFilter && categoryFilter.options.length <= 1) {
            const categories = window.getAssetCategories ? window.getAssetCategories() : [];
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.code;
                option.textContent = category.name;
                categoryFilter.appendChild(option);
            });
        }
        
        // Get search and filter values
        const searchInput = document.getElementById('deprecationSearchInput');
        const searchText = searchInput ? searchInput.value.toLowerCase() : '';
        
        const categoryValue = categoryFilter ? categoryFilter.value : '';
        
        // Filter assets
        let filteredAssets = assets.filter(asset => {
            const matchesSearch = !searchText || 
                asset.name.toLowerCase().includes(searchText) || 
                (asset.id && asset.id.toString().includes(searchText));
            
            const matchesCategory = !categoryValue || asset.type === categoryValue;
            
            return matchesSearch && matchesCategory;
        });
        
        if (filteredAssets.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8" style="text-align: center;">لا توجد أصول تطابق معايير البحث</td></tr>';
            return;
        }
        
        filteredAssets.forEach(asset => {
            // حساب مجمع الإهلاك والقيمة الدفترية
            const totalDepreciation = asset.depreciations.reduce((sum, d) => sum + d.value, 0);
            const bookValue = asset.originalCost - totalDepreciation;
            
            // حساب نسبة الإهلاك
            const depreciationRate = (100 / asset.usefulLife).toFixed(2);
            
            // حساب قيمة الإهلاك السنوي
            const annualDepreciation = (asset.originalCost - asset.salvageValue) / asset.usefulLife;
            
            const row = document.createElement('tr');
            row.dataset.assetId = asset.id || '';
            row.innerHTML = `
                <td>${asset.id || '-'}</td>
                <td class="editable-cell" data-field="name">
                    <input type="text" value="${asset.name}" class="editable-field" data-original="${asset.name}">
                </td>
                <td class="editable-cell" data-field="annualDepreciation">
                    <input type="number" value="${annualDepreciation.toFixed(2)}" class="editable-field" data-original="${annualDepreciation.toFixed(2)}">
                </td>
                <td class="editable-cell" data-field="depreciationRate">
                    <input type="number" value="${depreciationRate}" class="editable-field" data-original="${depreciationRate}">
                </td>
                <td>${bookValue.toLocaleString()}</td>
                <td>${totalDepreciation.toLocaleString()}</td>
                <td class="editable-cell" data-field="originalCost">
                    <input type="number" value="${asset.originalCost}" class="editable-field" data-original="${asset.originalCost}">
                </td>
                <td>
                    <button class="icon-button view-depreciation" data-id="${asset.id || ''}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        // Add event listeners for view buttons
        document.querySelectorAll('.view-depreciation').forEach(button => {
            button.addEventListener('click', function() {
                const assetId = this.getAttribute('data-id');
                const asset = assets.find(a => a.id == assetId);
                if (asset) {
                    showAssetDepreciationDetails(asset);
                }
            });
        });
        
        // Add event listeners for editable fields
        document.querySelectorAll('.editable-field').forEach(input => {
            input.addEventListener('change', function() {
                const row = this.closest('tr');
                const originalValue = this.getAttribute('data-original');
                
                if (this.value !== originalValue) {
                    row.classList.add('asset-row-changed');
                } else {
                    // Check if any other field in this row is changed
                    const changedFields = row.querySelectorAll('.editable-field');
                    let hasChanges = false;
                    
                    changedFields.forEach(field => {
                        if (field.value !== field.getAttribute('data-original')) {
                            hasChanges = true;
                        }
                    });
                    
                    if (!hasChanges) {
                        row.classList.remove('asset-row-changed');
                    }
                }
            });
        });
    }

    // Add event listener for the search button
    document.getElementById('depreciationSearchBtn')?.addEventListener('click', updateDepreciationDetailsTable);

    // Add event listener for the category filter
    document.getElementById('depreciationCategoryFilter')?.addEventListener('change', updateDepreciationDetailsTable);

    // Add event listener for the reset filters button
    document.getElementById('resetDepreciationFiltersBtn')?.addEventListener('click', function() {
        const searchInput = document.getElementById('deprecationSearchInput');
        const categoryFilter = document.getElementById('depreciationCategoryFilter');
        
        if (searchInput) searchInput.value = '';
        if (categoryFilter) categoryFilter.value = '';
        
        updateDepreciationDetailsTable();
    });

    // Add event listener for the save changes button
    document.getElementById('saveDepreciationChangesBtn')?.addEventListener('click', function() {
        const changedRows = document.querySelectorAll('#depreciationDetailsTable tbody tr.asset-row-changed');
        
        changedRows.forEach(row => {
            const assetId = row.dataset.assetId;
            const asset = assets.find(a => a.id == assetId);
            
            if (asset) {
                // Get the updated values
                const nameInput = row.querySelector('.editable-field[data-field="name"]');
                const annualDepreciationInput = row.querySelector('.editable-field[data-field="annualDepreciation"]');
                const depreciationRateInput = row.querySelector('.editable-field[data-field="depreciationRate"]');
                const originalCostInput = row.querySelector('.editable-field[data-field="originalCost"]');
                
                // Update the asset
                if (nameInput) {
                    asset.name = nameInput.value;
                    nameInput.setAttribute('data-original', nameInput.value);
                }
                
                if (originalCostInput) {
                    asset.originalCost = parseFloat(originalCostInput.value);
                    originalCostInput.setAttribute('data-original', originalCostInput.value);
                }
                
                if (depreciationRateInput) {
                    const newRate = parseFloat(depreciationRateInput.value);
                    asset.usefulLife = Math.round(100 / newRate);
                    depreciationRateInput.setAttribute('data-original', depreciationRateInput.value);
                }
                
                // Reset the row's visual indicator
                row.classList.remove('asset-row-changed');
            }
        });
        
        // Update all the tables that display assets
        updateDepreciationTable();
        updateAnnualDepreciationTable();
        updateDepreciationDetailsTable();
        
        alert('تم حفظ التغييرات بنجاح');
    });
    
    // عرض تفاصيل إهلاكات الأصل
    function showAssetDepreciationDetails(asset) {
        const modal = document.getElementById('assetDepreciationModal');
        const modalContent = document.getElementById('assetDepreciationContent');
        
        // حساب المعلومات المتعلقة بالإهلاك
        const totalDepreciation = asset.depreciations.reduce((sum, d) => sum + d.value, 0);
        const bookValue = asset.originalCost - totalDepreciation;
        const depreciationRate = (100 / asset.usefulLife).toFixed(2);
        const annualDepreciation = (asset.originalCost - asset.salvageValue) / asset.usefulLife;
        
        modalContent.innerHTML = `
            <div class="asset-depreciation-details">
                <h3>${asset.name}</h3>
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">رقم الأصل:</span>
                        <span class="detail-value">${asset.id || '-'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">تاريخ الشراء:</span>
                        <span class="detail-value">${asset.purchaseDate}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">قيمة الشراء:</span>
                        <span class="detail-value">${asset.originalCost.toLocaleString()}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">العمر الإنتاجي:</span>
                        <span class="detail-value">${asset.usefulLife} سنوات</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">القيمة التخريدية:</span>
                        <span class="detail-value">${asset.salvageValue.toLocaleString()}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">نسبة الإهلاك:</span>
                        <span class="detail-value">${depreciationRate}%</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">الإهلاك السنوي:</span>
                        <span class="detail-value">${annualDepreciation.toLocaleString()}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">مجمع الإهلاك:</span>
                        <span class="detail-value">${totalDepreciation.toLocaleString()}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">القيمة الدفترية:</span>
                        <span class="detail-value">${bookValue.toLocaleString()}</span>
                    </div>
                </div>
                
                <h4>سجل الإهلاكات</h4>
                <table class="depreciation-history-table">
                    <thead>
                        <tr>
                            <th>السنة</th>
                            <th>قيمة الإهلاك</th>
                            <th>مجمع الإهلاك</th>
                            <th>القيمة الدفترية</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${generateDepreciationHistoryRows(asset)}
                    </tbody>
                </table>
            </div>
        `;
        
        modal.style.display = 'block';
    }
    
    // إنشاء صفوف جدول سجل الإهلاكات
    function generateDepreciationHistoryRows(asset) {
        if (asset.depreciations.length === 0) {
            return '<tr><td colspan="4">لا يوجد سجل إهلاكات لهذا الأصل</td></tr>';
        }
        
        let rows = '';
        let cumulativeDepreciation = 0;
        const sortedDepreciations = [...asset.depreciations].sort((a, b) => a.year - b.year);
        
        sortedDepreciations.forEach(dep => {
            cumulativeDepreciation += dep.value;
            const bookValue = asset.originalCost - cumulativeDepreciation;
            
            rows += `
                <tr>
                    <td>${dep.year}</td>
                    <td>${dep.value.toLocaleString()}</td>
                    <td>${cumulativeDepreciation.toLocaleString()}</td>
                    <td>${bookValue.toLocaleString()}</td>
                </tr>
            `;
        });
        
        return rows;
    }
    
    // تحديث قائمة الأصول في نموذج البيع
    function updateAssetSelectList() {
        const sellAssetSelect = document.getElementById('sellAssetSelect');
        sellAssetSelect.innerHTML = '<option value="">-- اختر الأصل --</option>';
        
        assets.forEach((asset, index) => {
            // تحقق من أن الأصل لم يتم بيعه بعد
            if (!asset.sold) {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = asset.name;
                sellAssetSelect.appendChild(option);
            }
        });
    }
    
    window.updateAssetSelectList = updateAssetSelectList;
    
    // استمع لتغيير الأصل في نموذج البيع
    document.getElementById('sellAssetSelect').addEventListener('change', function() {
        const selectedIndex = this.value;
        
        if (selectedIndex === '') {
            // إعادة تعيين الحقول إذا لم يتم اختيار أصل
            document.getElementById('originalCostDisplay').value = '';
            document.getElementById('purchaseDateDisplay').value = '';
            document.getElementById('accumulatedDepreciation').value = '';
            document.getElementById('currentBookValue').value = '';
            return;
        }
        
        const selectedAsset = assets[selectedIndex];
        
        // حساب مجمع الإهلاك
        const totalDepreciation = selectedAsset.depreciations.reduce((sum, d) => sum + d.value, 0);
        
        // حساب القيمة الدفترية الحالية
        const bookValue = selectedAsset.originalCost - totalDepreciation;
        
        // عرض بيانات الأصل
        document.getElementById('originalCostDisplay').value = selectedAsset.originalCost;
        document.getElementById('purchaseDateDisplay').value = selectedAsset.purchaseDate;
        document.getElementById('accumulatedDepreciation').value = totalDepreciation;
        document.getElementById('currentBookValue').value = bookValue;
    });
    
    // نموذج بيع الأصل
    document.getElementById('sellAssetForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedIndex = document.getElementById('sellAssetSelect').value;
        
        if (selectedIndex === '') {
            alert('الرجاء اختيار أصل للبيع');
            return;
        }
        
        const selectedAsset = assets[selectedIndex];
        const saleDate = document.getElementById('saleDate').value;
        const saleAmount = parseFloat(document.getElementById('saleAmount').value);
        
        // حساب مجمع الإهلاك والقيمة الدفترية
        const totalDepreciation = selectedAsset.depreciations.reduce((sum, d) => sum + d.value, 0);
        const bookValue = selectedAsset.originalCost - totalDepreciation;
        
        // حساب الربح أو الخسارة
        const gainOrLoss = saleAmount - bookValue;
        
        // وضع علامة على الأصل كمباع
        selectedAsset.sold = true;
        selectedAsset.saleDetails = {
            date: saleDate,
            amount: saleAmount,
            gainOrLoss: gainOrLoss
        };
        
        // عرض نتيجة البيع
        const saleResult = document.getElementById('saleResult');
        saleResult.innerHTML = `
            <h3>نتيجة عملية البيع</h3>
            <p>الأصل: ${selectedAsset.name}</p>
            <p>القيمة الدفترية: ${bookValue.toLocaleString()}</p>
            <p>قيمة البيع: ${saleAmount.toLocaleString()}</p>
            <p>الربح/الخسارة: ${Math.abs(gainOrLoss).toLocaleString()} ${gainOrLoss >= 0 ? '(ربح)' : '(خسارة)'}</p>
        `;
        
        if (gainOrLoss >= 0) {
            saleResult.style.backgroundColor = '#e8f8e8';
            saleResult.style.color = '#27ae60';
        } else {
            saleResult.style.backgroundColor = '#f8e8e8';
            saleResult.style.color = '#c0392b';
        }
        
        saleResult.style.display = 'block';
        
        // تحديث جداول وقوائم الأصول
        updateAssetSelectList();
        updateDepreciationTable();
        
        // إعادة تعيين النموذج
        this.reset();
        document.getElementById('originalCostDisplay').value = '';
        document.getElementById('purchaseDateDisplay').value = '';
        document.getElementById('accumulatedDepreciation').value = '';
        document.getElementById('currentBookValue').value = '';
    });
    
    // إضافة دالة للحصول على الأصول (للاستخدام في الملفات الأخرى)
    window.getAssets = function() {
        return assets;
    };

    // Remove references to initializeCategoryDepreciationForm
    function saveCategories() {
        localStorage.setItem('assetCategories', JSON.stringify(categories));
    }
    
    // Initialize UI
    function initializeAssetTypeSelect() {
        // Code for initializing asset type select
    }

    function initializeCategoryManagement() {
        // Code for initializing category management
    }
    
    initializeAssetTypeSelect();
    initializeCategoryManagement();
    
    // إضافة المتغير modal للنافذة المنبثقة
    const modal = document.getElementById('journalEntriesModal');
    const closeBtn = document.querySelector('.close');
    
    // إغلاق النافذة المنبثقة عند النقر على زر الإغلاق
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }
    
    // إغلاق النافذة المنبثقة عند النقر خارجها
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    
    // عرض القيود اليومية للإهلاكات
    window.showJournalEntries = function(assetName, annualDepreciation) {
        const modal = document.getElementById('journalEntriesModal');
        const tableBody = document.querySelector('#journalEntriesTable tbody');
        tableBody.innerHTML = '';
        
        const currentDate = new Date().toLocaleDateString('ar-EG');
        
        // إنشاء صفوف القيود اليومية
        const debitRow = document.createElement('tr');
        debitRow.innerHTML = `
            <td>${currentDate}</td>
            <td>مصروف الإهلاك - ${assetName}</td>
            <td>${annualDepreciation.toLocaleString()}</td>
            <td></td>
        `;
        
        const creditRow = document.createElement('tr');
        creditRow.innerHTML = `
            <td>${currentDate}</td>
            <td>مجمع الإهلاك - ${assetName}</td>
            <td></td>
            <td>${annualDepreciation.toLocaleString()}</td>
        `;
        
        tableBody.appendChild(debitRow);
        tableBody.appendChild(creditRow);
        
        modal.style.display = "block";
    }
    
    // تحديث تبويب الإهلاك السنوي عند بدء التحميل
    updateAnnualDepreciationTable();
    updateAssetSelectList();
    updateDepreciationDetailsTable();
    
    // Calculate depreciation rate automatically when lifespan changes
    const lifespanInput = document.getElementById('newCategoryLifespan');
    const rateInput = document.getElementById('newCategoryRate');
    
    if (lifespanInput && rateInput) {
        lifespanInput.addEventListener('input', function() {
            const lifespan = parseInt(this.value);
            if (lifespan > 0) {
                rateInput.value = (100 / lifespan).toFixed(2);
            } else {
                rateInput.value = '';
            }
        });
    }
});