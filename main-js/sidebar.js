document.addEventListener('DOMContentLoaded', () => {
    // Add sidebar to the page
    const sidebar = document.createElement('div');
    sidebar.className = 'sidebar collapsed';
    sidebar.innerHTML = `
        <div class="sidebar-header">
            <div style="cursor: pointer" onclick="navigate('home')" class="sidebar-logo">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
            </div>
            <span style="cursor: pointer" onclick="navigate('home')" class="sidebar-title">نظام المحاسبة</span>
        </div>

        <div class="sidebar-menu">
            <div class="menu-category">إدارة الفروع والحسابات</div>
            <div class="menu-item" onclick="navigate('branches')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                <span>إدارة الفروع</span>
            </div>
            <div class="menu-item" onclick="navigate('bank-managment')">
                <svg class="icon" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="18" rx="2"/>
                    <path d="M12 7v10M7 12h10"/>
                </svg>
                <span>إدارة الحسابات</span>
            </div>

            <div class="menu-category">العملاء والموردين</div>
            <div class="menu-item" onclick="navigate('customers')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>إدارة العملاء</span>
            </div>
            <div class="menu-item" onclick="navigate('suppliers')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M15 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span>إدارة الموردين</span>
            </div>

            <div class="menu-category">مراكز التكلفة والخزينة</div>
            <div class="menu-item" onclick="navigate('cost-centers')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                </svg>
                <span>مراكز التكلفة</span>
            </div>
            <div class="menu-item" onclick="navigate('funds')">
                <svg class="icon" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 9v6M9 12h6"/>
                </svg>
                <span>الصناديق</span>
            </div>
            <div class="menu-item" onclick="navigate('bank-managment')">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"/>
                </svg>
                <span>البنوك</span>
            </div>
        </div>

        <div class="sidebar-footer">
            <button class="settings-btn" onclick="navigate('settings')">
                <svg class="icon" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <span>الإعدادات</span>
            </button>
        </div>

        <div class="sidebar-toggle">
            <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </div>
    `;

    document.body.insertBefore(sidebar, document.body.firstChild);

    // Wrap existing content in main container
    const mainContent = document.querySelector('.container');
    const mainContainer = document.createElement('div');
    mainContainer.className = 'main-container';
    mainContent.parentNode.insertBefore(mainContainer, mainContent);
    mainContainer.appendChild(mainContent);

    // Setup sidebar toggle functionality
    const sidebarToggle = sidebar.querySelector('.sidebar-toggle');
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

    // Setup mobile menu toggle
    const mediaQuery = window.matchMedia('(max-width: 768px)');
    function handleMobileMenu(e) {
        if (e.matches) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.remove('active');
        }
    }
    mediaQuery.addListener(handleMobileMenu);
    handleMobileMenu(mediaQuery);
});
