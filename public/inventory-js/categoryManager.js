document.addEventListener('DOMContentLoaded', function() {
    // Default categories with their Arabic translations
    const defaultCategories = [
        { name: 'المباني', code: 'building', lifespan: 25, rate: 4 },
        { name: 'السيارات', code: 'vehicle', lifespan: 5, rate: 20 },
        { name: 'المعدات والآلات', code: 'equipment', lifespan: 10, rate: 10 },
        { name: 'الأثاث', code: 'furniture', lifespan: 7, rate: 14.29 }
    ];
    
    // Load categories from localStorage or use defaults
    let categories = localStorage.getItem('assetCategories') 
        ? JSON.parse(localStorage.getItem('assetCategories')) 
        : defaultCategories;
    
    // Initialize dropdown in asset type select
    function initializeAssetTypeSelect() {
        const assetTypeSelect = document.getElementById('assetType');
        if (assetTypeSelect) {
            // Clear existing options except the first placeholder
            while (assetTypeSelect.options.length > 1) {
                assetTypeSelect.remove(1);
            }
            
            // Add category options
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.code;
                option.textContent = category.name;
                assetTypeSelect.appendChild(option);
            });
        }
    }
    
    // Initialize category management tab
    function initializeCategoryManagement() {
        const categoriesTable = document.getElementById('categoriesTable').querySelector('tbody');
        categoriesTable.innerHTML = '';
        
        // Populate categories table
        categories.forEach((category, index) => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${category.name}</td>
                <td>${category.code}</td>
                <td>${category.lifespan}</td>
                <td>${category.rate}%</td>
                <td>
                    <button class="icon-button edit-category" data-index="${index}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    ${category.isDefault ? '' : `
                    <button class="icon-button delete-category" data-index="${index}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </button>
                    `}
                </td>
            `;
            
            categoriesTable.appendChild(row);
        });
        
        // Add event listeners for edit and delete buttons
        document.querySelectorAll('.edit-category').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                editCategory(index);
            });
        });
        
        document.querySelectorAll('.delete-category').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                deleteCategory(index);
            });
        });
    }
    
    // Add new category
    function addCategory() {
        const nameInput = document.getElementById('newCategoryName');
        const codeInput = document.getElementById('newCategoryCode');
        const lifespanInput = document.getElementById('newCategoryLifespan');
        
        const name = nameInput.value.trim();
        const code = codeInput.value.trim();
        const lifespan = parseInt(lifespanInput.value);
        
        if (!name || !code) {
            alert('الرجاء إدخال اسم ورمز للفئة الجديدة');
            return;
        }
        
        if (categories.some(cat => cat.code === code)) {
            alert('رمز الفئة موجود بالفعل، الرجاء استخدام رمز آخر');
            return;
        }
        
        // Add new category
        categories.push({
            name,
            code,
            lifespan: lifespan || 5,
            rate: lifespan ? (100 / lifespan).toFixed(2) : 20,
            isDefault: false
        });
        
        // Save categories to localStorage
        saveCategories();
        
        // Refresh UI
        initializeCategoryManagement();
        initializeAssetTypeSelect();
        
        // Clear inputs
        nameInput.value = '';
        codeInput.value = '';
        lifespanInput.value = '5';
        
        alert('تمت إضافة الفئة الجديدة بنجاح');
    }
    
    // Edit category
    function editCategory(index) {
        const category = categories[index];
        
        // Simple prompt-based editing for now
        const newName = prompt('تعديل اسم الفئة:', category.name);
        if (newName === null) return; // User canceled
        
        const newLifespan = parseInt(prompt('تعديل العمر الإنتاجي (بالسنوات):', category.lifespan));
        if (isNaN(newLifespan) || newLifespan <= 0) return; // Invalid input
        
        // Update category
        categories[index].name = newName.trim();
        categories[index].lifespan = newLifespan;
        categories[index].rate = (100 / newLifespan).toFixed(2);
        
        // Save and refresh
        saveCategories();
        initializeCategoryManagement();
        initializeAssetTypeSelect();
    }
    
    // Delete category
    function deleteCategory(index) {
        // Check if category is in use
        const assets = window.getAssets ? window.getAssets() : [];
        const categoryCode = categories[index].code;
        
        if (assets.some(asset => asset.type === categoryCode)) {
            alert('لا يمكن حذف هذه الفئة لأنها مستخدمة من قبل أصول موجودة');
            return;
        }
        
        if (confirm('هل أنت متأكد من حذف هذه الفئة؟')) {
            categories.splice(index, 1);
            saveCategories();
            initializeCategoryManagement();
            initializeAssetTypeSelect();
        }
    }
    
    // Save categories to localStorage
    function saveCategories() {
        localStorage.setItem('assetCategories', JSON.stringify(categories));
    }
    
    // Export this function for use in other modules
    window.getAssetCategories = function() {
        return categories;
    };
    
    // Add event listener for the add category button
    document.getElementById('addCategoryBtn').addEventListener('click', addCategory);
    
    // Initialize UI
    initializeAssetTypeSelect();
    initializeCategoryManagement();
});