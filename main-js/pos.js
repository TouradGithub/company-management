// Update the showPOSModule function to inject content into main area instead of modal
function showPOSModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="pos-dashboard">
            <div class="pos-header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>نقاط البيع</h2>
            <div class="pos-terminals">
                <div class="pos-card">
                    <div class="pos-header">
                        <div class="pos-branch">الفرع الرئيسي</div>
                        <div class="pos-status active">مفتوح</div>
                    </div>
                    <div class="pos-info">
                        <div class="pos-row">
                            <span class="label">المسؤول:</span>
                            <span class="value">أحمد محمد</span>
                        </div>
                        <div class="pos-row">
                            <span class="label">الوردية:</span>
                            <span class="value">الصباحية (8:00 - 4:00)</span>
                        </div>
                        <div class="pos-amount">
                            <span class="amount-label">المبلغ الحالي</span>
                            <span class="amount-value">₪ 5,280.50</span>
                        </div>
                    </div>
                    <div class="pos-actions">
                        <button class="pos-action-btn" onclick="openPOS(1)">
                            <svg class="icon" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M7 7h10M7 12h10M7 17h10"/>
                            </svg>
                            فتح النقطة
                        </button>
                    </div>
                </div>

                <div class="pos-card">
                    <div class="pos-header">
                        <div class="pos-branch">الفرع الثاني</div>
                        <div class="pos-status active">مفتوح</div>
                    </div>
                    <div class="pos-info">
                        <div class="pos-row">
                            <span class="label">المسؤول:</span>
                            <span class="value">سارة خالد</span>
                        </div>
                        <div class="pos-row">
                            <span class="label">الوردية:</span>
                            <span class="value">المسائية (4:00 - 12:00)</span>
                        </div>
                        <div class="pos-amount">
                            <span class="amount-label">المبلغ الحالي</span>
                            <span class="amount-value">₪ 3,150.75</span>
                        </div>
                    </div>
                    <div class="pos-actions">
                        <button class="pos-action-btn" onclick="openPOS(2)">
                            <svg class="icon" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M7 7h10M7 12h10M7 17h10"/>
                            </svg>
                            فتح النقطة
                        </button>
                    </div>
                </div>

                <div class="pos-card">
                    <div class="pos-header">
                        <div class="pos-branch">الفرع الثالث</div>
                        <div class="pos-status inactive">مغلق</div>
                    </div>
                    <div class="pos-info">
                        <div class="pos-row">
                            <span class="label">المسؤول:</span>
                            <span class="value">محمد أحمد</span>
                        </div>
                        <div class="pos-row">
                            <span class="label">الوردية:</span>
                            <span class="value">الصباحية (8:00 - 4:00)</span>
                        </div>
                        <div class="pos-amount">
                            <span class="amount-label">المبلغ الحالي</span>
                            <span class="amount-value">₪ 0.00</span>
                        </div>
                    </div>
                    <div class="pos-actions">
                        <button class="pos-action-btn" onclick="openPOS(3)">
                            <svg class="icon" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <path d="M7 7h10M7 12h10M7 17h10"/>
                            </svg>
                            فتح النقطة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

window.backToHome = function() {
    location.reload();
};

window.openPOS = function(terminalId) {
    alert(`جاري فتح نقطة البيع رقم ${terminalId}`);
};