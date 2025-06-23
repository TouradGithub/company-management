document.addEventListener('DOMContentLoaded', () => {
    // Remove bottom tools container
    const bottomTools = document.querySelector('.bottom-tools');
    if (bottomTools) {
        bottomTools.remove();
    }

    // Create sliding tools panel
    const slideTools = document.createElement('div');
    slideTools.className = 'slide-tools';
    slideTools.innerHTML = `
        <div class="slide-tools-toggle">
            <svg class="icon" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>
        <div class="slide-tools-content">
            <div class="slide-tool-item" onclick="navigate('agenda')">
                <div class="slide-tool-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div class="slide-tool-info">
                    <h3>الأجندة الذكية</h3>
                    <p>إدارة المواعيد والمهام بكفاءة</p>
                </div>
                <div class="notification-indicator">2</div>
            </div>

            <div class="slide-tool-item" onclick="navigate('calculator')">
                <div class="slide-tool-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <rect x="4" y="2" width="16" height="20" rx="2"/>
                        <line x1="8" y1="6" x2="16" y2="6"/>
                        <line x1="8" y1="10" x2="16" y2="10"/>
                        <line x1="8" y1="14" x2="16" y2="14"/>
                        <line x1="8" y1="18" x2="16" y2="18"/>
                    </svg>
                </div>
                <div class="slide-tool-info">
                    <h3>الآلة الحاسبة</h3>
                    <p>حاسبة متقدمة للعمليات المالية</p>
                </div>
            </div>

            <div class="slide-tool-item" onclick="navigate('chat')">
                <div class="slide-tool-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
                    </svg>
                </div>
                <div class="slide-tool-info">
                    <h3>المحادثات</h3>
                    <p>تواصل مباشر مع الفريق</p>
                </div>
                <div class="notification-indicator">3</div>
            </div>

            <div class="slide-tool-item" onclick="navigate('email')">
                <div class="slide-tool-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </div>
                <div class="slide-tool-info">
                    <h3>البريد الإلكتروني</h3>
                    <p>إدارة المراسلات والمخاطبات</p>
                </div>
                <div class="notification-indicator">2</div>
            </div>

            <div class="slide-tool-item" onclick="navigate('notifications')">
                <div class="slide-tool-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <div class="slide-tool-info">
                    <h3>الإشعارات</h3>
                    <p>متابعة التنبيهات والتحديثات</p>
                </div>
                <div class="notification-indicator">5</div>
            </div>
        </div>
    `;

    document.body.appendChild(slideTools);

    // Setup toggle functionality
    const toggle = slideTools.querySelector('.slide-tools-toggle');
    toggle.addEventListener('click', () => {
        slideTools.classList.toggle('active');
    });
});