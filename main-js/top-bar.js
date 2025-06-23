document.addEventListener('DOMContentLoaded', () => {
    // Update the top bar HTML structure
    const topBar = document.querySelector('.top-bar');
    const userInfo = topBar.querySelector('.user-info');
    const dateDisplay = topBar.querySelector('.date-display');
    
    // Create actions container if it doesn't exist
    let actionsContainer = topBar.querySelector('.top-bar-actions');
    if (!actionsContainer) {
        actionsContainer = document.createElement('div');
        actionsContainer.className = 'top-bar-actions';
    }
    
    // Add agenda icon
    const agendaIcon = document.createElement('div');
    agendaIcon.className = 'action-icon agenda-icon';
    agendaIcon.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
        <div class="agenda-dropdown">
            <div class="dropdown-header">
                <h3>الأجندة</h3>
                <span>مواعيد اليوم</span>
            </div>
            <div class="agenda-preview">
                <div class="today-appointments">
                    <div class="appointment-preview">
                        <div class="appointment-time">10:00</div>
                        <div class="appointment-details">
                            <div class="appointment-title">اجتماع فريق العمل</div>
                        </div>
                    </div>
                    <div class="appointment-preview">
                        <div class="appointment-time">14:30</div>
                        <div class="appointment-details">
                            <div class="appointment-title">مراجعة التقارير</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown-footer">
                <a href="#" onclick="navigate('agenda')">عرض الأجندة كاملة</a>
            </div>
        </div>
    `;
    
    // Add email icon
    const emailIcon = document.createElement('div');
    emailIcon.className = 'action-icon email-icon';
    emailIcon.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
        <div class="notification-badge">2</div>
        <div class="email-dropdown">
            <div class="dropdown-header">
                <h3>البريد الإلكتروني</h3>
                <span>2 رسائل جديدة</span>
            </div>
            <div class="dropdown-content">
                <div class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12" y2="8"/>
                    </svg>
                    <div class="item-content">
                        <div class="item-title">تأكيد الطلب #1234</div>
                        <div class="item-description">تم تأكيد طلبك وسيتم معالجته قريباً</div>
                        <div class="item-time">منذ 5 دقائق</div>
                    </div>
                </div>
                <div class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <div class="item-content">
                        <div class="item-title">تقرير المبيعات اليومي</div>
                        <div class="item-description">تم إرسال تقرير المبيعات لهذا اليوم</div>
                        <div class="item-time">منذ ساعة</div>
                    </div>
                </div>
            </div>
            <div class="dropdown-footer">
                <a href="#">عرض كل الرسائل</a>
            </div>
        </div>
    `;
    
    // Add notifications icon
    const notificationsIcon = document.createElement('div');
    notificationsIcon.className = 'action-icon notifications-icon';
    notificationsIcon.innerHTML = `
        <svg class="icon" viewBox="0 0 24 24">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        <div class="notification-badge">3</div>
        <div class="notification-dropdown">
            <div class="dropdown-header">
                <h3>الإشعارات</h3>
                <span>3 إشعارات جديدة</span>
            </div>
            <div class="dropdown-content">
                <div class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <div class="item-content">
                        <div class="item-title">تحديث النظام</div>
                        <div class="item-description">تم تحديث النظام إلى الإصدار الجديد</div>
                        <div class="item-time">منذ 10 دقائق</div>
                    </div>
                </div>
                <div class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <div class="item-content">
                        <div class="item-title">تذكير بالموعد</div>
                        <div class="item-description">لديك اجتماع بعد ساعة</div>
                        <div class="item-time">منذ ساعة</div>
                    </div>
                </div>
                <div class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    <div class="item-content">
                        <div class="item-title">تحديث البيانات</div>
                        <div class="item-description">تم تحديث بيانات العملاء بنجاح</div>
                        <div class="item-time">منذ يومين</div>
                    </div>
                </div>
            </div>
            <div class="dropdown-footer">
                <a href="#">عرض كل الإشعارات</a>
            </div>
        </div>
    `;
    
    // Add icons to actions container in correct order
    actionsContainer.appendChild(agendaIcon);
    actionsContainer.appendChild(emailIcon);
    actionsContainer.appendChild(notificationsIcon);
    
    // Add actions container next to date display if not already there
    if (!topBar.querySelector('.top-bar-actions')) {
        dateDisplay.parentNode.insertBefore(actionsContainer, dateDisplay.nextSibling);
    }
    
    // Setup dropdown functionality
    setupDropdowns();
});

function setupDropdowns() {
    const actionIcons = document.querySelectorAll('.action-icon');
    
    actionIcons.forEach(icon => {
        icon.addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdown = icon.querySelector('.notification-dropdown, .email-dropdown, .agenda-dropdown');
            
            // Close other dropdowns
            document.querySelectorAll('.notification-dropdown.active, .email-dropdown.active, .agenda-dropdown.active').forEach(d => {
                if (d !== dropdown) d.classList.remove('active');
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('active');
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.notification-dropdown.active, .email-dropdown.active, .agenda-dropdown.active').forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    });
}