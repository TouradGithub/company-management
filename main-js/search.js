// Search functionality
const searchableModules = [
    { id: 'sales-invoice', title: 'فاتورة مبيعات', description: 'إدارة فواتير المبيعات', category: 'محاسبة' },
    { id: 'purchase-invoice', title: 'فاتورة مشتريات', description: 'إدارة فواتير المشتريات', category: 'محاسبة' },
    { id: 'journal-entry', title: 'تسجيل قيد يومية', description: 'إدارة القيود المحاسبية', category: 'محاسبة' },
    { id: 'accounting', title: 'المحاسبة المالية', description: 'إدارة الحسابات والتقارير المالية', category: 'محاسبة' },
    { id: 'hr', title: 'الموارد البشرية', description: 'إدارة الموظفين والرواتب', category: 'إدارة' },
    { id: 'inventory', title: 'المخازن', description: 'إدارة المخزون والمستودعات', category: 'مخزون' },
    { id: 'pos', title: 'نقاط البيع', description: 'إدارة المبيعات والفواتير', category: 'مبيعات' },
    { id: 'production', title: 'تكاليف الإنتاج', description: 'إدارة وحساب تكاليف الإنتاج', category: 'إنتاج' },
    { id: 'customers', title: 'نظام العملاء', description: 'إدارة حسابات وعلاقات العملاء', category: 'عملاء' },
    { id: 'financial-statements', title: 'القوائم الختامية', description: 'عرض وإدارة القوائم المالية', category: 'تقارير' },
    { id: 'reports', title: 'التقارير العامة', description: 'عرض وطباعة التقارير', category: 'تقارير' },
    { id: 'account-statement', title: 'كشف حساب', description: 'عرض حركات وأرصدة الحسابات', category: 'تقارير' },
    { id: 'email', title: 'البريد الإلكتروني', description: 'إدارة الرسائل والمراسلات', category: 'أدوات' },
    { id: 'calculator', title: 'الآلة الحاسبة', description: 'حاسبة سريعة للعمليات الحسابية', category: 'أدوات' },
    { id: 'tasks', title: 'تسجيل المهام', description: 'إدارة وتتبع المهام اليومية', category: 'أدوات' },
    { id: 'agenda', title: 'الأجندة', description: 'تنظيم المواعيد والمهام', category: 'أدوات' },
    { id: 'chat', title: 'المحادثات', description: 'التواصل مع الفريق', category: 'أدوات' }
];

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.trim().toLowerCase();
        
        if (searchTerm.length < 2) {
            searchResults.classList.remove('active');
            return;
        }
        
        const filteredModules = searchableModules.filter(module => 
            module.title.toLowerCase().includes(searchTerm) ||
            module.description.toLowerCase().includes(searchTerm) ||
            module.category.toLowerCase().includes(searchTerm)
        );
        
        if (filteredModules.length > 0) {
            searchResults.innerHTML = filteredModules.map(module => `
                <div class="search-result-item" onclick="navigate('${module.id}')">
                    <div class="module-info">
                        <h3>${module.title}</h3>
                        <p>${module.description}</p>
                    </div>
                </div>
            `).join('');
            searchResults.classList.add('active');
        } else {
            searchResults.innerHTML = '<div class="search-result-item"><p>لا توجد نتائج</p></div>';
            searchResults.classList.add('active');
        }
    });
    
    // Close search results when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.remove('active');
        }
    });
});