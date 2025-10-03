/**
 * 🔍 نظام البحث الذكي للفواتير
 * Smart Search System for Modern Invoices
 */

class SmartInvoiceSearch {
    constructor(options = {}) {
        this.searchInput = options.searchInput || '#smartSearch';
        this.resultsContainer = options.resultsContainer || '#searchResults';
        this.apiEndpoint = options.apiEndpoint || '/api/products/search';
        this.minQueryLength = options.minQueryLength || 2;
        this.searchDelay = options.searchDelay || 300;
        this.maxResults = options.maxResults || 10;

        this.searchTimeout = null;
        this.cache = new Map();

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupKeyboardNavigation();
    }

    setupEventListeners() {
        // البحث عند الكتابة
        $(this.searchInput).on('input', (e) => {
            this.handleSearch(e.target.value);
        });

        // إخفاء النتائج عند النقر خارجها
        $(document).on('click', (e) => {
            if (!$(e.target).closest('.smart-search-container').length) {
                this.hideResults();
            }
        });

        // إظهار النتائج عند التركيز
        $(this.searchInput).on('focus', (e) => {
            if (e.target.value.length >= this.minQueryLength) {
                this.showResults();
            }
        });
    }

    setupKeyboardNavigation() {
        let selectedIndex = -1;

        $(this.searchInput).on('keydown', (e) => {
            const results = $(this.resultsContainer + ' .search-result-item');

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, results.length - 1);
                    this.highlightResult(selectedIndex);
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    this.highlightResult(selectedIndex);
                    break;

                case 'Enter':
                    e.preventDefault();
                    if (selectedIndex >= 0) {
                        results.eq(selectedIndex).click();
                    }
                    break;

                case 'Escape':
                    this.hideResults();
                    selectedIndex = -1;
                    break;
            }
        });
    }

    handleSearch(query) {
        // مسح التايمر السابق
        if (this.searchTimeout) {
            clearTimeout(this.searchTimeout);
        }

        query = query.trim();

        if (query.length < this.minQueryLength) {
            this.hideResults();
            return;
        }

        // تأخير البحث للتحسين
        this.searchTimeout = setTimeout(() => {
            this.performSearch(query);
        }, this.searchDelay);
    }

    async performSearch(query) {
        // التحقق من الكاش أولاً
        if (this.cache.has(query)) {
            this.displayResults(this.cache.get(query), query);
            return;
        }

        // إظهار مؤشر التحميل
        this.showLoading();

        try {
            // البحث الفعلي (يمكن استبداله بـ API حقيقي)
            const results = await this.searchProducts(query);

            // حفظ في الكاش
            this.cache.set(query, results);

            // عرض النتائج
            this.displayResults(results, query);

        } catch (error) {
            console.error('خطأ في البحث:', error);
            this.showError('حدث خطأ أثناء البحث');
        }
    }

    async searchProducts(query) {
        // محاكاة البحث في قاعدة البيانات
        // في التطبيق الفعلي، استبدل هذا بطلب AJAX حقيقي

        const mockDatabase = [
            { id: 1, name: 'لابتوب HP Pavilion', price: 2500, unit: 'جهاز', category: 'إلكترونيات', stock: 15, image: '/images/laptop-hp.jpg', description: 'لابتوب عالي الأداء مع معالج Intel Core i5' },
            { id: 2, name: 'لابتوب Dell Inspiron', price: 2200, unit: 'جهاز', category: 'إلكترونيات', stock: 8, image: '/images/laptop-dell.jpg', description: 'لابتوب موثوق للاستخدام اليومي' },
            { id: 3, name: 'جوال Samsung Galaxy S21', price: 1200, unit: 'جهاز', category: 'هواتف', stock: 25, image: '/images/samsung-s21.jpg', description: 'هاتف ذكي متقدم مع كاميرا عالية الدقة' },
            { id: 4, name: 'جوال iPhone 13', price: 1800, unit: 'جهاز', category: 'هواتف', stock: 12, image: '/images/iphone-13.jpg', description: 'أحدث هواتف آبل بتقنيات متطورة' },
            { id: 5, name: 'كتاب البرمجة بـ JavaScript', price: 45, unit: 'نسخة', category: 'كتب', stock: 50, image: '/images/js-book.jpg', description: 'دليل شامل لتعلم البرمجة بـ JavaScript' },
            { id: 6, name: 'كتاب تصميم قواعد البيانات', price: 38, unit: 'نسخة', category: 'كتب', stock: 30, image: '/images/db-book.jpg', description: 'أساسيات تصميم وإدارة قواعد البيانات' },
            { id: 7, name: 'ماوس لاسلكي Logitech', price: 85, unit: 'قطعة', category: 'إكسسوارات', stock: 40, image: '/images/mouse.jpg', description: 'ماوس لاسلكي عالي الدقة' },
            { id: 8, name: 'لوحة مفاتيح ميكانيكية', price: 150, unit: 'قطعة', category: 'إكسسوارات', stock: 20, image: '/images/keyboard.jpg', description: 'لوحة مفاتيح ميكانيكية للألعاب والبرمجة' },
            { id: 9, name: 'شاشة Samsung 24 بوصة', price: 450, unit: 'جهاز', category: 'شاشات', stock: 18, image: '/images/monitor.jpg', description: 'شاشة عالية الدقة للعمل والترفيه' },
            { id: 10, name: 'سماعة Bluetooth Sony', price: 95, unit: 'قطعة', category: 'صوتيات', stock: 35, image: '/images/headphones.jpg', description: 'سماعة لاسلكية عالية الجودة' }
        ];

        // محاكاة تأخير الشبكة
        await new Promise(resolve => setTimeout(resolve, 200));

        // البحث الذكي
        const results = mockDatabase.filter(item => {
            const searchTerms = query.toLowerCase().split(' ');
            const itemText = (item.name + ' ' + item.category + ' ' + item.description).toLowerCase();

            return searchTerms.some(term => itemText.includes(term));
        });

        return results.slice(0, this.maxResults);
    }

    displayResults(results, query) {
        if (results.length === 0) {
            this.showNoResults(query);
            return;
        }

        let html = '';
        results.forEach((item, index) => {
            const stockStatus = item.stock > 10 ? 'متوفر' : item.stock > 0 ? 'قليل' : 'نفذت الكمية';
            const stockClass = item.stock > 10 ? 'success' : item.stock > 0 ? 'warning' : 'danger';

            html += `
                <div class="search-result-item" data-item-id="${item.id}" onclick="addItemFromSearch(${item.id}, '${item.name}', ${item.price}, '${item.unit}')">
                    <div class="result-content">
                        <div class="result-image">
                            ${this.getItemIcon(item.category)}
                        </div>
                        <div class="result-info">
                            <div class="result-name">${this.highlightMatch(item.name, query)}</div>
                            <div class="result-details">
                                <span class="result-price">${item.price} ر.س</span>
                                <span class="result-unit">/ ${item.unit}</span>
                                <span class="result-category">${item.category}</span>
                            </div>
                            <div class="result-stock ${stockClass}">
                                المخزون: ${item.stock} • ${stockStatus}
                            </div>
                        </div>
                        <div class="result-action">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
            `;
        });

        $(this.resultsContainer).html(html).show();
    }

    showLoading() {
        const html = `
            <div class="search-result-item loading">
                <div class="result-content">
                    <div class="loading-spinner"></div>
                    <span>جاري البحث...</span>
                </div>
            </div>
        `;
        $(this.resultsContainer).html(html).show();
    }

    showNoResults(query) {
        const html = `
            <div class="search-result-item no-results">
                <div class="result-content">
                    <i class="fas fa-search" style="font-size: 2rem; color: #64748b; margin-bottom: 1rem;"></i>
                    <div>لم يتم العثور على نتائج لـ "<strong>${query}</strong>"</div>
                    <div style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        جرب استخدام كلمات مختلفة أو أقل تحديداً
                    </div>
                </div>
            </div>
        `;
        $(this.resultsContainer).html(html).show();
    }

    showError(message) {
        const html = `
            <div class="search-result-item error">
                <div class="result-content">
                    <i class="fas fa-exclamation-triangle" style="color: var(--danger);"></i>
                    <span>${message}</span>
                </div>
            </div>
        `;
        $(this.resultsContainer).html(html).show();
    }

    highlightMatch(text, query) {
        const regex = new RegExp(`(${query.split(' ').join('|')})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    getItemIcon(category) {
        const icons = {
            'إلكترونيات': '💻',
            'هواتف': '📱',
            'كتب': '📚',
            'إكسسوارات': '🖱️',
            'شاشات': '🖥️',
            'صوتيات': '🎧'
        };

        return `<div class="item-icon">${icons[category] || '📦'}</div>`;
    }

    highlightResult(index) {
        const results = $(this.resultsContainer + ' .search-result-item');
        results.removeClass('highlighted');

        if (index >= 0) {
            results.eq(index).addClass('highlighted');
        }
    }

    showResults() {
        $(this.resultsContainer).show();
    }

    hideResults() {
        $(this.resultsContainer).hide();
    }

    clearCache() {
        this.cache.clear();
    }
}

// إضافة الستايل للنتائج
const searchStyles = `
    <style>
        .search-result-item {
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border);
        }

        .search-result-item:hover,
        .search-result-item.highlighted {
            background: var(--light);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        .result-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
        }

        .result-image {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--light);
            border-radius: 8px;
            flex-shrink: 0;
        }

        .item-icon {
            font-size: 1.5rem;
        }

        .result-info {
            flex: 1;
        }

        .result-name {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .result-name mark {
            background: #fef3c7;
            color: #92400e;
            padding: 0.1em 0.2em;
            border-radius: 3px;
        }

        .result-details {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .result-price {
            font-weight: 600;
            color: var(--success);
        }

        .result-stock {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            display: inline-block;
        }

        .result-stock.success {
            background: #dcfce7;
            color: #166534;
        }

        .result-stock.warning {
            background: #fef3c7;
            color: #92400e;
        }

        .result-stock.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .result-action {
            color: var(--success);
            font-size: 1.25rem;
        }

        .search-result-item.loading,
        .search-result-item.no-results,
        .search-result-item.error {
            cursor: default;
        }

        .search-result-item.loading:hover,
        .search-result-item.no-results:hover,
        .search-result-item.error:hover {
            background: white;
        }

        .search-result-item.no-results .result-content,
        .search-result-item.error .result-content {
            justify-content: center;
            text-align: center;
            flex-direction: column;
            padding: 2rem 1rem;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-solid);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 0.5rem;
        }
    </style>
`;

// إضافة الستايل إلى الصفحة
$('head').append(searchStyles);

// تصدير الكلاس للاستخدام العام
window.SmartInvoiceSearch = SmartInvoiceSearch;
