class BulkInventoryHandler {
    constructor() {
        this.products = [
            // { code: "P1001", name: "خس طازج", category: "خضار", unitCost: 5.50, currentCount: 100 },
            // { code: "P1002", name: "طماطم", category: "خضار", unitCost: 6.75, currentCount: 150 },
            // { code: "P1003", name: "خيار", category: "خضار", unitCost: 4.25, currentCount: 120 },
            // { code: "P1004", name: "تفاح أحمر", category: "فواكه", unitCost: 12.50, currentCount: 80 },
            // { code: "P1005", name: "موز", category: "فواكه", unitCost: 9.75, currentCount: 95 },
            // { code: "P1006", name: "برتقال", category: "فواكه", unitCost: 8.25, currentCount: 110 },
            // { code: "P1007", name: "لحم بقري", category: "لحوم", unitCost: 85.00, currentCount: 30 },
            // { code: "P1008", name: "لحم ضأن", category: "لحوم", unitCost: 95.00, currentCount: 25 },
            // { code: "P1009", name: "دجاج كامل", category: "دواجن", unitCost: 25.50, currentCount: 45 },
            // { code: "P1010", name: "صدور دجاج", category: "دواجن", unitCost: 32.75, currentCount: 60 },
            // { code: "P1011", name: "سمك سلمون", category: "أسماك", unitCost: 75.25, currentCount: 20 },
            // { code: "P1012", name: "سمك تونة", category: "أسماك", unitCost: 60.50, currentCount: 35 },
            // { code: "P1013", name: "حليب طازج", category: "ألبان", unitCost: 7.50, currentCount: 200 },
            // { code: "P1014", name: "جبن أبيض", category: "ألبان", unitCost: 18.25, currentCount: 50 },
            // { code: "P1015", name: "زبادي", category: "ألبان", unitCost: 5.25, currentCount: 180 },
            // { code: "P1016", name: "فول معلب", category: "معلبات", unitCost: 4.25, currentCount: 160 },
            // { code: "P1017", name: "ذرة معلبة", category: "معلبات", unitCost: 5.00, currentCount: 130 },
            // { code: "P1018", name: "عصير برتقال", category: "مشروبات", unitCost: 8.75, currentCount: 110 },
            // { code: "P1019", name: "مياه معدنية", category: "مشروبات", unitCost: 2.00, currentCount: 300 },
            // { code: "P1020", name: "خبز طازج", category: "مخبوزات", unitCost: 3.50, currentCount: 90 },
            // { code: "P1021", name: "كعك", category: "مخبوزات", unitCost: 12.00, currentCount: 40 },
            // { code: "P1022", name: "بسكويت", category: "حلويات", unitCost: 7.25, currentCount: 75 },
            // { code: "P1023", name: "شوكولاتة", category: "حلويات", unitCost: 15.50, currentCount: 55 }
        ];
        // نؤخر عرض البيانات حتى يتم جلبها
        this.categories = [];
       fetch('/products-data')
       .then(res => res.json())
       .then(data => {
           this.products = data.products;
           this.categories = data.categories;
           this.loadAllProducts(); // هنا فقط نعرض التقرير بعد استلام البيانات
       });

        this.entryItems = [];
        this.filteredProducts = [];
    }

    loadproducts() {
        fetch('/products-data')
        .then(res => res.json())
        .then(data => {
            this.products = data.products;
            this.categories = data.categories;
            this.loadAllProducts(); // هنا فقط نعرض التقرير بعد استلام البيانات
        });
    }
    initialize() {
        // Set current date
        const today = new Date();
        document.getElementById('entryDate').valueAsDate = today;

        // Add category selector
        this.addCategorySelector();

        // Add event listeners
        document.getElementById('saveInventoryBtn').addEventListener('click', () => this.saveInventory());
        document.getElementById('exportInventoryBtn').addEventListener('click', () => this.exportToExcel());
        document.getElementById('searchEntryBtn').addEventListener('click', () => this.searchProduct());

        // Load all products initially
        this.loadAllProducts();
    }

    addCategorySelector() {
        const entryControls = document.querySelector('.entry-controls');
        if (!entryControls) return;

        const categoryField = document.createElement('div');
        categoryField.className = 'category-field';
        categoryField.innerHTML = `
            <label for="categoryFilter">تصفية حسب الفئة:</label>
            <select id="categoryFilter">
                <option value="">جميع الفئات</option>
                ${this.categories.map(cat => `<option value="${cat.name}">${cat.name}</option>`).join('')}
            </select>
        `;

        entryControls.appendChild(categoryField);

        // Add event listener to the category selector
        const categoryFilter = document.getElementById('categoryFilter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => this.filterByCategory(e.target.value));
        }
    }

    loadAllProducts() {
        this.filteredProducts = [...this.products];
        this.renderEntryTable();
        this.updateSummary();
    }

    filterByCategory(category) {
        if (!category) {
            this.loadAllProducts();
            return;
        }

        // Filter products by the selected category
        this.filteredProducts = this.products.filter(p => p.category === category);
        this.renderEntryTable();
        this.updateSummary();
    }

    searchProduct() {
        const searchTerm = document.getElementById('productSearch').value.trim();
        if (!searchTerm) {
            this.loadAllProducts();
            return;
        }

        this.filteredProducts = this.products.filter(p =>
            p.code.includes(searchTerm) ||
            p.name.includes(searchTerm)
        );

        this.renderEntryTable();
        this.updateSummary();
    }

    renderEntryTable() {
        const tableBody = document.getElementById('inventoryEntryBody');
        if (!tableBody) return;

        tableBody.innerHTML = '';

        this.filteredProducts.forEach((product, index) => {
            // Set up initial values or use existing entries
            const existingEntry = this.entryItems.find(item => item.code === product.code);
            const actualCount = existingEntry ? existingEntry.actualCount : 0;
            const countVariance = actualCount - product.currentCount;
            const valueVariance = countVariance * product.unitCost;

            // Save or update entry
            if (existingEntry) {
                existingEntry.actualCount = actualCount;
                existingEntry.countVariance = countVariance;
                existingEntry.valueVariance = valueVariance;
            } else {
                this.entryItems.push({
                    code: product.code,
                    name: product.name,
                    category: product.category,
                    currentCount: product.currentCount,
                    actualCount: actualCount,
                    countVariance: countVariance,
                    valueVariance: valueVariance,
                    unitCost: product.unitCost
                });
            }

            const row = document.createElement('tr');

            // Add class based on variance
            const varianceClass = countVariance >= 0 ? 'positive' : 'negative';
            const valueVarianceClass = valueVariance >= 0 ? 'positive' : 'negative';

            row.innerHTML = `
                <td>${product.code}</td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td class="current-count-cell">${product.currentCount.toFixed(2)}</td>
                <td><input type="number" class="actual-count-input" value="${actualCount.toFixed(2)}" data-code="${product.code}" min="0" step="0.01"></td>
                <td class="${varianceClass}">${countVariance.toFixed(2)}</td>
                <td class="${valueVarianceClass}">${valueVariance.toFixed(2)} ريال</td>
                <td>
                    <button class="action-btn delete-btn" data-code="${product.code}">
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
                const code = e.currentTarget.getAttribute('data-code');
                this.deleteEntry(code);
            });
        });

        // Add event listeners for actual count inputs
        const actualCountInputs = document.querySelectorAll('.actual-count-input');
        actualCountInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                const code = e.target.getAttribute('data-code');
                const actualCount = parseFloat(e.target.value) || 0;
                this.updateItemActualCount(code, actualCount);
            });
        });
    }

    updateItemActualCount(code, actualCount) {
        const product = this.products.find(p => p.code === code);
        if (!product) return;

        const entry = this.entryItems.find(item => item.code === code);
        if (!entry) return;

        entry.actualCount = actualCount;
        entry.countVariance = actualCount - product.currentCount;
        entry.valueVariance = entry.countVariance * product.unitCost;

        // Update display without re-rendering the entire table
        const row = document.querySelector(`#inventoryEntryBody tr td:first-child:contains('${code}')`).closest('tr');
        if (row) {
            const varianceCell = row.cells[5];
            const valueVarianceCell = row.cells[6];

            varianceCell.textContent = entry.countVariance.toFixed(2);
            varianceCell.className = entry.countVariance >= 0 ? 'positive' : 'negative';

            valueVarianceCell.textContent = entry.valueVariance.toFixed(2) + ' ريال';
            valueVarianceCell.className = entry.valueVariance >= 0 ? 'positive' : 'negative';
        }

        this.updateSummary();
    }

    deleteEntry(code) {
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            // Remove from entry items
            this.entryItems = this.entryItems.filter(item => item.code !== code);

            // Remove from filtered products display
            this.filteredProducts = this.filteredProducts.filter(product => product.code !== code);

            this.renderEntryTable();
            this.updateSummary();
        }
    }

    updateSummary() {
        const totalItems = this.filteredProducts.length;
        let totalVarianceValue = 0;

        // Calculate total variance from entry items that are currently filtered/displayed
        this.filteredProducts.forEach(product => {
            const entry = this.entryItems.find(item => item.code === product.code);
            if (entry) {
                totalVarianceValue += entry.valueVariance;
            }
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

        const date = document.getElementById('entryDate').value;

        fetch('/inventory/update-stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                date: date,
                items: this.entryItems
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(`تم حفظ بيانات الجرد بتاريخ ${date} بنجاح!`);
            console.log('Inventory updated:', data);
            this.loadproducts();
        })
        .catch(error => {
            console.error('خطأ أثناء الحفظ:', error);
            alert('حدث خطأ أثناء حفظ بيانات الجرد.');
            this.loadproducts();
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

// Helper method to support finding elements by text content
jQuery.expr[':'].contains = function(a, i, m) {
    return jQuery(a).text().indexOf(m[3]) >= 0;
};
