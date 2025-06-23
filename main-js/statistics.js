document.addEventListener('DOMContentLoaded', () => {
    const mainContent = document.querySelector('.main-content');
    const header = mainContent.querySelector('header');
    
    // Create statistics tab
    const statisticsTab = document.createElement('div');
    statisticsTab.className = 'statistics-tab';
    statisticsTab.innerHTML = `
        <h2>إحصائيات النظام</h2>
        <div class="statistics-grid">
            <div class="statistic-card">
                <div class="statistic-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                </div>
                <div class="statistic-value">1,234</div>
                <div class="statistic-label">المعاملات اليومية</div>
                <div class="statistic-trend trend-up">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none">
                        <path d="M7 17l5-5 5 5"/>
                        <path d="M7 7l5 5 5-5"/>
                    </svg>
                    12.5%
                </div>
            </div>

            <div class="statistic-card">
                <div class="statistic-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <path d="M22 6l-10 7L2 6"/>
                    </svg>
                </div>
                <div class="statistic-value">458</div>
                <div class="statistic-label">الفواتير المصدرة</div>
                <div class="statistic-trend trend-up">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none">
                        <path d="M7 17l5-5 5 5"/>
                        <path d="M7 7l5 5 5-5"/>
                    </svg>
                    8.3%
                </div>
            </div>

            <div class="statistic-card">
                <div class="statistic-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                </div>
                <div class="statistic-value">₪ 52,869</div>
                <div class="statistic-label">إجمالي المبيعات</div>
                <div class="statistic-trend trend-up">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none">
                        <path d="M7 17l5-5 5 5"/>
                        <path d="M7 7l5 5 5-5"/>
                    </svg>
                    15.2%
                </div>
            </div>

            <div class="statistic-card">
                <div class="statistic-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div class="statistic-value">284</div>
                <div class="statistic-label">العملاء النشطين</div>
                <div class="statistic-trend trend-down">
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none">
                        <path d="M7 7l5 5 5-5"/>
                        <path d="M7 17l5-5 5 5"/>
                    </svg>
                    3.1%
                </div>
            </div>
        </div>
    `;

    // Insert statistics tab before quick access section
    const quickAccess = mainContent.querySelector('.quick-access');
    header.insertBefore(statisticsTab, quickAccess);
});
