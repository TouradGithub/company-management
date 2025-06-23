class DashboardHandler {
    constructor() {
        this.dashboardItems = [
            {
                id: "sale-report",
                title: "تقرير فواتير المبيعات",
                description: "عرض وتحليل بيانات المبيعات والأرباح",
                icon: "💰",
                iconClass: "icon-sales"
            },
            {
                id: "purchase-report",
                title: "تقرير فواتير المشتريات",
                description: "عرض وتحليل بيانات المبيعات والأرباح",
                icon: "📦",
                iconClass: "icon-sales"
            },
            {
                id: "products-report",
                title: "تقرير المنتجات ",
                description: "تقرير المنتجات ",
                icon: "🧾",
                iconClass: "icon-sales"
            },
            {
                id: "sales-products",
                title: "تقرير ربح المنتجات ",
                description: "تقرير ربح المنتجات ",
                icon: "📈",
                iconClass: "icon-sales"
            },
            {
                id: "sales-bills",
                title: "تقرير ربح الفواتير  ",
                description: "تقرير ربح الفواتير ",
                icon: "💵",
                iconClass: "icon-sales"
            },
            {
                id: "add-product",
                title: "إضافة منتج  ",
                description: "إضافة منتج ",
                icon: "📦",
                iconClass: "icon-sales"
            },
            // {
            //     id: "sales-report",
            //     title: "تقرير ربح المنتجات ",
            //     description: "عرض وتحليل بيانات المبيعات والأرباح",
            //     icon: "📊",
            //     iconClass: "icon-sales"
            // },
            {
                id: "daily-sales-report",
                title: "تقرير المبيعات اليومي",
                description: "عرض المبيعات اليومية حسب طرق الدفع",
                icon: "📅",
                iconClass: "icon-daily-sales"
            },
            // {
            //     id: "monthly-summary-report",
            //     title: "التقرير الشهري العام",
            //     description: "ملخص شامل للمبيعات والمشتريات والأرباح",
            //     icon: "📝",
            //     iconClass: "icon-profit"
            // },
            {
                id: "cost-detail",
                title: "تقرير حركة المنتجات والرصيد النهائي ",
                description: "تقرير حركة المنتجات والرصيد النهائي ",
                icon: "💰",
                iconClass: "icon-cost"
            },
            // {
            //     id: "inventory-summary",
            //     title: "ملخص المخزون الفعلي",
            //     description: "تقرير المخزون الفعلي والفروقات",
            //     icon: "📦",
            //     iconClass: "icon-inventory"
            // },
            // {
            //     id: "profit-cost-report",
            //     title: "تقرير الربح والتكلفة",
            //     description: "تحليل الربحية وتكاليف المنتجات",
            //     icon: "📈",
            //     iconClass: "icon-profit"
            // },
            {
                id: "category-report",
                title: "تقرير الفئات",
                description: "مقارنة أداء الفئات المختلفة",
                icon: "🏷️",
                iconClass: "icon-category"
            },
            {
                id: "inventory-entry",
                title: "إدخال الجرد الشهري",
                description: "إدخال بيانات الجرد الشهري للمخزون",
                icon: "✏️",
                iconClass: "icon-entry"
            },
            {
                id: "material-transfer",
                title: "تحويل المواد",
                description: "إنشاء وإدارة تحويلات المواد بين الفروع",
                icon: "🔄",
                iconClass: "icon-transfer"
            },
            {
                id: "waste-disposal",
                title: "إتلاف المواد",
                description: "تسجيل إتلاف المواد والمخزون",
                icon: "🗑️",
                iconClass: "icon-waste"
            }
        ];
    }

    initialize() {
        this.renderDashboard();
        this.setupEventListeners();
    }

    renderDashboard() {
        const dashboardContainer = document.getElementById('dashboard-view');
        if (!dashboardContainer) return;

        dashboardContainer.innerHTML = '';

        this.dashboardItems.forEach(item => {
            const card = document.createElement('div');
            card.className = 'dashboard-card';
            card.dataset.tab = item.id;

            card.innerHTML = `
                <div class="dashboard-icon ${item.iconClass}">${item.icon}</div>
                <h3 class="dashboard-title">${item.title}</h3>
                <p class="dashboard-description">${item.description}</p>
            `;

            dashboardContainer.appendChild(card);
        });
    }

    setupEventListeners() {
        // Dashboard cards click event
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('click', (e) => {
                const tabId = card.dataset.tab;
                this.activateTab(tabId);
            });
        });

        // Back button click event
        const backButton = document.getElementById('back-to-dashboard');
        if (backButton) {
            backButton.addEventListener('click', () => {
                this.showDashboard();
            });
        }
    }

    activateTab(tabId) {
        // Hide dashboard view
        const dashboardView = document.getElementById('dashboard-view');
        if (dashboardView) {
            dashboardView.style.display = 'none';
        }

        // Show tab content
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        const selectedTab = document.getElementById(tabId);
        if (selectedTab) {
            selectedTab.classList.add('active');
        }

        // Show back button
        const backButton = document.getElementById('back-to-dashboard');
        if (backButton) {
            backButton.style.display = 'block';
        }

        // Find and display the title of the selected tab
        const tabInfo = this.dashboardItems.find(item => item.id === tabId);
        if (tabInfo) {
            // Update the page title with the tab title
            document.querySelector('h1').textContent = tabInfo.title;
        }

        // Trigger tab click (to initialize tab content if needed)
        const tabButton = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
        if (tabButton) {
            tabButton.click();
        }
    }

    showDashboard() {
        // Hide all tab content
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });

        // Show dashboard view
        const dashboardView = document.getElementById('dashboard-view');
        if (dashboardView) {
            dashboardView.style.display = 'grid';
        }

        // Hide back button
        const backButton = document.getElementById('back-to-dashboard');
        if (backButton) {
            backButton.style.display = 'none';
        }

        // Reset the main title to default
        document.querySelector('h1').textContent = 'تكاليف الوحدات المباعة';
    }
}
