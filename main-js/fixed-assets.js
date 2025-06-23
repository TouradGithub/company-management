document.addEventListener('DOMContentLoaded', () => {
    // Initialize any required setup
    setupFixedAssetsHandlers();
});

function showFixedAssetsModule() {
    const mainContent = document.querySelector('.main-content');
    mainContent.innerHTML = `
        <div class="fixed-assets-dashboard">
            <div class="header-actions">
                <button class="back-to-home" onclick="backToHome()">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    العودة للرئيسية
                </button>
            </div>
            <h2>الأصول الثابتة</h2>
            <div class="fixed-assets-modules">
                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <h3>تسجيل أصل جديد</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 3h18v18H3z"/>
                        <path d="M15 9l-6 6M9 9l6 6"/>
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    </svg>
                    <h3>بيع أو التخلص من أصل</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                        <path d="M8 14h8"/>
                    </svg>
                    <h3>تسجيل الإهلاكات السنوية</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        <path d="M8 14h8"/>
                    </svg>
                    <h3>تسجيل الإهلاكات حسب الفئة</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <h3>إدارة فئات الأصول</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                        <path d="M14 2v6h6"/>
                        <path d="M16 13H8"/>
                        <path d="M16 17H8"/>
                        <path d="M10 9H8"/>
                    </svg>
                    <h3>جدول الإهلاكات العام</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/>
                        <path d="M3.27 6.96L12 12.01l8.73-5.05"/>
                        <path d="M12 22.08V12"/>
                    </svg>
                    <h3>الإهلاك السنوي العام</h3>
                </div>

                <div class="asset-module" onclick="navigate('assets')">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 3h18v18H3z"/>
                        <path d="M3 9h18"/>
                        <path d="M3 15h18"/>
                        <path d="M9 3v18"/>
                        <path d="M15 3v18"/>
                    </svg>
                    <h3>جدول الأصول</h3>
                </div>
            </div>
        </div>
    `;
}

function setupFixedAssetsHandlers() {
    // Handle asset registration form submission
    document.addEventListener('submit', (e) => {
        if (e.target.classList.contains('asset-form')) {
            e.preventDefault();
            registerNewAsset();
        }
    });

    // Handle depreciation registration form submission
    document.addEventListener('submit', (e) => {
        if (e.target.classList.contains('depreciation-form')) {
            e.preventDefault();
            registerDepreciation();
        }
    });
}

function registerNewAsset() {
    // Collect form data and save asset
    const assetData = {
        number: document.getElementById('assetNumber').value,
        name: document.getElementById('assetName').value,
        purchaseDate: document.getElementById('purchaseDate').value,
        cost: parseFloat(document.getElementById('assetCost').value),
        usefulLife: parseInt(document.getElementById('usefulLife').value),
        depreciationMethod: document.getElementById('depreciationMethod').value
    };

    // Save to localStorage for demo purposes
    const assets = JSON.parse(localStorage.getItem('fixedAssets') || '[]');
    assets.push(assetData);
    localStorage.setItem('fixedAssets', JSON.stringify(assets));

    alert('تم تسجيل الأصل بنجاح');
}

function registerDepreciation() {
    // Collect form data and save depreciation
    const depreciationData = {
        assetId: document.getElementById('assetSelect').value,
        date: document.getElementById('depreciationDate').value,
        amount: parseFloat(document.getElementById('depreciationAmount').value)
    };

    // Save to localStorage for demo purposes
    const depreciations = JSON.parse(localStorage.getItem('assetDepreciations') || '[]');
    depreciations.push(depreciationData);
    localStorage.setItem('assetDepreciations', JSON.stringify(depreciations));

    alert('تم تسجيل الإهلاك بنجاح');
}
