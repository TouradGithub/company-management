class InventoryEntryHandler {
    constructor() {
        this.entryItems = [];
        this.currentEditingRow = null;
        this.products = [
            { code: "P1001", name: "خس طازج", category: "خضار", unitCost: 5.50, currentCount: 100 },
            { code: "P1002", name: "طماطم", category: "خضار", unitCost: 6.75, currentCount: 150 },
            { code: "P1003", name: "خيار", category: "خضار", unitCost: 4.25, currentCount: 120 },
            { code: "P1004", name: "تفاح أحمر", category: "فواكه", unitCost: 12.50, currentCount: 80 },
            { code: "P1005", name: "موز", category: "فواكه", unitCost: 9.75, currentCount: 95 },
            { code: "P1006", name: "برتقال", category: "فواكه", unitCost: 8.25, currentCount: 110 },
            { code: "P1007", name: "لحم بقري", category: "لحوم", unitCost: 85.00, currentCount: 30 },
            { code: "P1008", name: "لحم ضأن", category: "لحوم", unitCost: 95.00, currentCount: 25 },
            { code: "P1009", name: "دجاج كامل", category: "دواجن", unitCost: 25.50, currentCount: 45 },
            { code: "P1010", name: "صدور دجاج", category: "دواجن", unitCost: 32.75, currentCount: 60 },
            { code: "P1011", name: "سمك سلمون", category: "أسماك", unitCost: 75.25, currentCount: 20 },
            { code: "P1012", name: "سمك تونة", category: "أسماك", unitCost: 60.50, currentCount: 35 },
            { code: "P1013", name: "حليب طازج", category: "ألبان", unitCost: 7.50, currentCount: 200 },
            { code: "P1014", name: "جبن أبيض", category: "ألبان", unitCost: 18.25, currentCount: 50 },
            { code: "P1015", name: "زبادي", category: "ألبان", unitCost: 5.25, currentCount: 180 },
            { code: "P1016", name: "فول معلب", category: "معلبات", unitCost: 4.25, currentCount: 160 },
            { code: "P1017", name: "ذرة معلبة", category: "معلبات", unitCost: 5.00, currentCount: 130 },
            { code: "P1018", name: "عصير برتقال", category: "مشروبات", unitCost: 8.75, currentCount: 110 },
            { code: "P1019", name: "مياه معدنية", category: "مشروبات", unitCost: 2.00, currentCount: 300 },
            { code: "P1020", name: "خبز طازج", category: "مخبوزات", unitCost: 3.50, currentCount: 90 },
            { code: "P1021", name: "كعك", category: "مخبوزات", unitCost: 12.00, currentCount: 40 },
            { code: "P1022", name: "بسكويت", category: "حلويات", unitCost: 7.25, currentCount: 75 },
            { code: "P1023", name: "شوكولاتة", category: "حلويات", unitCost: 15.50, currentCount: 55 }
        ];
        this.categories = ["خضار", "فواكه", "لحوم", "دواجن", "أسماك", "ألبان", "معلبات", "مشروبات", "مخبوزات", "حلويات"];
    }

    initialize() {
        // Set current date
        const today = new Date();
        document.getElementById('entryDate').valueAsDate = today;

        // Add event listeners
        document.getElementById('searchEntryBtn').addEventListener('click', () => this.searchProduct());
        document.getElementById('saveInventoryBtn').addEventListener('click', () => this.saveInventory());
        document.getElementById('exportInventoryBtn').addEventListener('click', () => this.exportToExcel());
        document.getElementById('addNewRowBtn').addEventListener('click', () => this.addNewRow());

        // Set up autocomplete for product search
        const productSearch = document.getElementById('productSearch');
        productSearch.addEventListener('input', (e) => this.handleSearchInput(e.target.value));
        
        // Initialize the empty table
        this.renderEntryTable();
        
        // Add category selector to inventory entry tab
        this.addCategorySelector();
    }
    
    addCategorySelector() {
        // Create category selector container
        const entryControls = document.querySelector('.entry-controls');
        if (!entryControls) return;
        
        const categoryField = document.createElement('div');
        categoryField.className = 'category-field';
        categoryField.innerHTML = `
            <label for="categoryFilter">تصفية حسب الفئة:</label>
            <select id="categoryFilter">
                <option value="">جميع الفئات</option>
                ${this.categories.map(cat => `<option value="${cat}">${cat}</option>`).join('')}
            </select>
        `;
        
        entryControls.appendChild(categoryField);
        
        // Add event listener to the category selector
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => this.filterByCategory(e.target.value));
        }
    }
    
    filterByCategory(category) {
        if (!category) {
            // If no category selected, clear the entries
            this.entryItems = [];
            this.renderEntryTable();
            return;
        }
        
        // Filter products by the selected category
        const filteredProducts = this.products.filter(p => p.category === category);
        
        // Clear current entries and add filtered products
        this.entryItems = [];
        filteredProducts.forEach(product => {
            this.entryItems.push({
                code: product.code,
                name: product.name,
                category: product.category,
                currentCount: product.currentCount,
                actualCount: 0,
                countVariance: -product.currentCount,
                valueVariance: -product.currentCount * product.unitCost,
                unitCost: product.unitCost
            });
        });
        
        // Update the table and summary
        this.renderEntryTable();
        this.updateSummary();
    }

    handleSearchInput(searchTerm) {
        if (!searchTerm) return;
        
        // In a real app, this would filter products dynamically
        const foundProducts = this.products.filter(p => 
            p.code.includes(searchTerm) || 
            p.name.includes(searchTerm)
        );
        
        if (foundProducts.length > 0) {
            // Clear existing items and add the found ones
            this.entryItems = [];
            foundProducts.forEach(product => {
                this.entryItems.push({
                    code: product.code,
                    name: product.name,
                    category: product.category,
                    currentCount: product.currentCount,
                    actualCount: 0,
                    countVariance: -product.currentCount,
                    valueVariance: -product.currentCount * product.unitCost,
                    unitCost: product.unitCost
                });
            });
            this.renderEntryTable();
            this.updateSummary();
        }
    }

    searchProduct() {
        const searchTerm = document.getElementById('productSearch').value;
        if (!searchTerm) return;
        
        this.handleSearchInput(searchTerm);
    }

    addNewRow() {
        // Add an empty row for new data
        this.entryItems.push({
            code: "",
            name: "",
            category: "",
            currentCount: 0,
            actualCount: 0,
            countVariance: 0,
            valueVariance: 0,
            unitCost: 0
        });
        this.renderEntryTable();
        
        // Start editing the new row
        const lastIndex = this.entryItems.length - 1;
        const lastRow = document.querySelector(`#inventoryEntryBody tr:nth-child(${lastIndex + 1})`);
        if (lastRow) {
            this.startEditing(lastRow, lastIndex);
        }
    }

    renderEntryTable() {
        const tableBody = document.getElementById('inventoryEntryBody');
        tableBody.innerHTML = '';

        this.entryItems.forEach((item, index) => {
            const row = document.createElement('tr');
            row.dataset.index = index;
            
            // Add class based on variance
            const varianceClass = item.countVariance >= 0 ? 'positive' : 'negative';
            const valueVarianceClass = item.valueVariance >= 0 ? 'positive' : 'negative';

            row.innerHTML = `
                <td>${item.code}</td>
                <td>${item.name}</td>
                <td>${item.category}</td>
                <td class="current-count-cell">${item.currentCount.toFixed(2)}</td>
                <td><input type="number" class="actual-count-input" value="${item.actualCount.toFixed(2)}" data-index="${index}" min="0" step="0.01"></td>
                <td class="${varianceClass}">${item.countVariance.toFixed(2)}</td>
                <td class="${valueVarianceClass}">${item.valueVariance.toFixed(2)} ريال</td>
                <td>
                    <button class="action-btn edit-btn" data-index="${index}">
                        <i class="fas fa-edit">تعديل</i>
                    </button>
                    <button class="action-btn delete-btn" data-index="${index}">
                        <i class="fas fa-trash">حذف</i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        // Add event listeners for edit and delete buttons
        const editButtons = document.querySelectorAll('.edit-btn');
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const actualCountInputs = document.querySelectorAll('.actual-count-input');

        editButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.getAttribute('data-index'));
                const row = e.currentTarget.closest('tr');
                this.startEditing(row, index);
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const index = parseInt(e.currentTarget.getAttribute('data-index'));
                this.deleteEntry(index);
            });
        });
        
        // Add event listeners for actual count inputs
        actualCountInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                const index = parseInt(e.target.getAttribute('data-index'));
                const actualCount = parseFloat(e.target.value) || 0;
                this.updateItemActualCount(index, actualCount);
            });
        });
    }

    updateItemActualCount(index, actualCount) {
        const item = this.entryItems[index];
        item.actualCount = actualCount;
        item.countVariance = actualCount - item.currentCount;
        item.valueVariance = item.countVariance * item.unitCost;
        
        // Update display without re-rendering the entire table
        const row = document.querySelector(`#inventoryEntryBody tr[data-index="${index}"]`);
        if (row) {
            const varianceCell = row.cells[5];
            const valueVarianceCell = row.cells[6];
            
            varianceCell.textContent = item.countVariance.toFixed(2);
            varianceCell.className = item.countVariance >= 0 ? 'positive' : 'negative';
            
            valueVarianceCell.textContent = item.valueVariance.toFixed(2) + ' ريال';
            valueVarianceCell.className = item.valueVariance >= 0 ? 'positive' : 'negative';
            
            this.updateSummary();
        }
    }
    
    startEditing(row, index) {
        // If already editing a row, finish that edit first
        if (this.currentEditingRow) {
            this.finishEditing();
        }
        
        this.currentEditingRow = row;
        row.classList.add('editing-row');
        
        const item = this.entryItems[index];
        
        // Replace cells with editable inputs
        const cells = row.querySelectorAll('td');
        
        // Product code cell
        cells[0].innerHTML = `<div class="editable-cell"><input type="text" value="${item.code}" placeholder="رقم الصنف"></div>`;
        const codeInput = cells[0].querySelector('input');
        codeInput.addEventListener('change', (e) => {
            const code = e.target.value;
            const product = this.products.find(p => p.code === code);
            if (product) {
                cells[1].querySelector('input').value = product.name;
                cells[2].querySelector('select').value = product.category;
                cells[3].innerHTML = product.currentCount.toFixed(2);
                cells[3].classList.add('current-count-cell');
                cells[6].querySelector('input').value = product.unitCost;
                
                // Update item data
                item.name = product.name;
                item.category = product.category;
                item.currentCount = product.currentCount;
                item.unitCost = product.unitCost;
                
                // Calculate variance
                this.calculateVariance(index);
            }
        });
        
        // Product name cell
        cells[1].innerHTML = `<div class="editable-cell"><input type="text" value="${item.name}" placeholder="اسم الصنف"></div>`;
        
        // Category cell
        let categoryOptions = '';
        this.categories.forEach(category => {
            categoryOptions += `<option value="${category}" ${item.category === category ? 'selected' : ''}>${category}</option>`;
        });
        cells[2].innerHTML = `<div class="editable-cell"><select>${categoryOptions}</select></div>`;
        
        // Current count cell - readonly
        cells[3].classList.add('current-count-cell');
        
        // Actual count cell
        cells[4].innerHTML = `<div class="editable-cell"><input type="number" value="${item.actualCount}" min="0" step="0.01" placeholder="العدد الفعلي"></div>`;
        const actualCountInput = cells[4].querySelector('input');
        actualCountInput.addEventListener('input', () => {
            this.calculateVariance(index);
        });
        
        // Unit cost (hidden in the variance value cell)
        cells[6].innerHTML = `<div class="editable-cell">
            <input type="number" value="${item.unitCost}" min="0" step="0.01" placeholder="تكلفة الوحدة">
        </div>`;
        const unitCostInput = cells[6].querySelector('input');
        unitCostInput.addEventListener('input', () => {
            this.calculateVariance(index);
        });
        
        // Actions cell - replace with Save/Cancel
        cells[7].innerHTML = `
            <button class="action-btn save-edit-btn">
                <i class="fas fa-save">حفظ</i>
            </button>
            <button class="action-btn cancel-edit-btn">
                <i class="fas fa-times">إلغاء</i>
            </button>
        `;
        
        cells[7].querySelector('.save-edit-btn').addEventListener('click', () => {
            this.finishEditing();
        });
        
        cells[7].querySelector('.cancel-edit-btn').addEventListener('click', () => {
            this.cancelEditing();
        });
    }
    
    calculateVariance(index) {
        const row = this.currentEditingRow;
        const cells = row.querySelectorAll('td');
        
        const currentCount = parseFloat(cells[3].textContent);
        const actualCount = parseFloat(cells[4].querySelector('input').value) || 0;
        const unitCost = parseFloat(cells[6].querySelector('input').value) || 0;
        
        const countVariance = actualCount - currentCount;
        const valueVariance = countVariance * unitCost;
        
        // Update display
        cells[5].textContent = countVariance.toFixed(2);
        cells[5].className = countVariance >= 0 ? 'positive' : 'negative';
        
        // Store in item
        this.entryItems[index].actualCount = actualCount;
        this.entryItems[index].unitCost = unitCost;
        this.entryItems[index].countVariance = countVariance;
        this.entryItems[index].valueVariance = valueVariance;
    }
    
    finishEditing() {
        if (!this.currentEditingRow) return;
        
        const index = parseInt(this.currentEditingRow.dataset.index);
        const cells = this.currentEditingRow.querySelectorAll('td');
        
        // Get values from inputs
        const code = cells[0].querySelector('input').value;
        const name = cells[1].querySelector('input').value;
        const category = cells[2].querySelector('select').value;
        const currentCount = parseFloat(cells[3].textContent);
        const actualCount = parseFloat(cells[4].querySelector('input').value) || 0;
        const unitCost = parseFloat(cells[6].querySelector('input').value) || 0;
        
        // Calculate variance
        const countVariance = actualCount - currentCount;
        const valueVariance = countVariance * unitCost;
        
        // Update item
        this.entryItems[index] = {
            code,
            name,
            category,
            currentCount,
            actualCount,
            countVariance,
            valueVariance,
            unitCost
        };
        
        // Reset editing state
        this.currentEditingRow = null;
        
        // Re-render table
        this.renderEntryTable();
        this.updateSummary();
    }
    
    cancelEditing() {
        this.currentEditingRow = null;
        this.renderEntryTable();
    }

    deleteEntry(index) {
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            this.entryItems.splice(index, 1);
            this.renderEntryTable();
            this.updateSummary();
        }
    }

    updateSummary() {
        const totalItems = this.entryItems.length;
        let totalVarianceValue = 0;

        this.entryItems.forEach(item => {
            totalVarianceValue += item.valueVariance;
        });

        document.getElementById('totalItems').textContent = totalItems;
        document.getElementById('totalVarianceValue').textContent = 
            totalVarianceValue.toFixed(2) + ' ريال';
        
        // Add class based on total variance
        const varianceElement = document.getElementById('totalVarianceValue');
        varianceElement.className = 'summary-value ' + 
            (totalVarianceValue >= 0 ? 'positive' : 'negative');
    }

    saveInventory() {
        if (this.entryItems.length === 0) {
            alert('لا توجد بيانات جرد للحفظ!');
            return;
        }

        // Finish any ongoing editing
        if (this.currentEditingRow) {
            this.finishEditing();
        }

        const date = document.getElementById('entryDate').value;
        
        // In a real app, this would save data to a database
        alert(`تم حفظ بيانات الجرد بتاريخ ${date} بنجاح!`);
        console.log('Inventory data saved:', {
            date,
            items: this.entryItems
        });
    }

    exportToExcel() {
        if (this.entryItems.length === 0) {
            alert('لا توجد بيانات جرد للتصدير!');
            return;
        }

        // In a real app, this would generate an Excel file
        alert('تم تصدير البيانات إلى ملف Excel بنجاح!');
    }
}