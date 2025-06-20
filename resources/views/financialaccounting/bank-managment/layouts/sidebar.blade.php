<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>القائمة الرئيسية</h3>
    </div>
    <div class="sidebar-menu">
        <div class="menu-item {{ request()->is('bank-managment') ? 'active' : '' }}" id="bankViewBtn">
            <i class="fas fa-university"></i>
            <span>الحسابات البنكية</span>
        </div>
        <div class="menu-item {{ request()->is('fund/index') ? 'active' : '' }}" id="fundsViewBtn">
            <i class="fas fa-cash-register"></i>
            <span>الصناديق</span>
        </div>

        <div class="menu-item" id="vouchersViewBtn">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>السندات</span>
        </div>
        <div class="menu-item" id="billsViewBtn">
            <i class="fas fa-file-invoice"></i>
            <span>الفواتير</span>
        </div>
    </div>
</div>
