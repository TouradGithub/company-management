@php
    $segment = Request::segment(1);
    $route = Route::currentRouteName();
@endphp

<aside class="sidebar">
    <div class="logo">
        <a href="#" class="logo-link">
            <i class="fas fa-calculator"></i>
            <span class="logo-text">كونكت برو</span>
        </a>
    </div>
    <nav>
        <ul>
            <li >
                <a href="{{route('accounting.accountsTree.index')}}"  class="{{ $route == 'accounting.accountsTree.index' ? 'activesideli' : '' }}">
                    <i class="fas fa-sitemap"></i>
                    <span>شجرة الحسابات</span>
                </a>
            </li>
            <li>
                <a href="{{route('accounting.index.table')}}">
                    <i class="fas fa-sitemap"></i>
                    <span> الحسابات</span>
                </a>
            </li>


            <li class="menu-group {{ $segment == 'journal-entry' || $segment == 'cost-center' ? 'active' : '' }}" >
                <a href="#financial-accounting">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>الحسابات العامة</span>
                </a>
                <ul class="submenu">
                    <li class="{{ $route == 'journal-entry.create' ? 'active' : '' }}">
                        <a href="{{route('journal-entry.create')}}">
                            <i class="fas fa-plus-circle"></i>
                            <span>إنشاء قيد يومية</span>
                        </a>
                    </li>
                    <li class="{{ $route == 'journal-entry.index' ? 'active' : '' }}">
                        <a href="{{route('journal-entry.index')}}">
                            <i class="fas fa-journal-whills"></i>
                            <span>قيود اليومية</span>
                        </a>
                    </li>


                    <li>
                        <a href="{{route('cost-center.index')}}">
                            <i class="fas fa-plus-circle"></i>
                            <span>  مراكز التكلفه</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('journals.index')}}">
                            <i class="fas fa-plus-circle"></i>
                            <span>   الدفاتر  المحاسبيه</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('account.statement.index')}}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>كشف حساب</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-group  {{ $segment == 'invoices' ? 'active' : '' }}">
                <a href="#invoices">
                    <i class="fas fa-file-invoice"></i>
                    <span>الفواتير والمنتجات</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{route('invoices.index')}}">
                            <i class="fas fa-file-alt"></i>
                            <span> الفواتير</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('invoices.sales')}}">
                            <i class="fas fa-file-alt"></i>
                            <span>فاتورة المبيعات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invoices.sales-returns') }}">
                            <i class="fas fa-undo"></i>
                            <span>مرتجع فاتورة مبيعات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('invoices.purchases')}}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>فاتورة المشتريات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('invoices.purchase-returns') }}">
                            <i class="fas fa-reply"></i>
                            <span>مرتجع فاتورة المشتريات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('products.index')}}">
                            <i class="fas fa-box"></i>
                            <span>المنتجات</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('categorie-invoices.index')}}">
                            <i class="fas fa-tags"></i>
                            <span>تصنيفات المنتجات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('customers.index')}}">
                            <i class="fas fa-tags"></i>
                            <span> العملاء</span>
                        </a>
                    </li>



                </ul>
            </li>
            <li class="menu-group  {{ $segment == 'trial-balance' || $segment == 'income-statement' ? 'active' : '' }}">
                <a href="#final-accounts">
                    <i class="fas fa-balance-scale"></i>
                    <span>الحسابات الختامية</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{route('trial.balance')}}">
                            <i class="fas fa-list-ol"></i>
                            <span>ميزان المراجعة</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('income.statement.index')}}">
                            <i class="fas fa-chart-line"></i>
                            <span>قائمة الدخل</span>
                        </a>
                    </li>
                    <li>
                        <a href="#balance-sheet">
                            <i class="fas fa-file-contract"></i>
                            <span>الميزانية العمومية</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-group">
                <a href="#hr">
                    <i class="fas fa-users-cog"></i>
                    <span>الموارد البشرية</span>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{route('company.employees.create')}}">
                            <i class="fas fa-user-plus"></i>
                            <span>إضافة موظف</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('branch.leaves.index')}}">
                            <i class="fas fa-umbrella-beach"></i>
                            <span>الإجازات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('branch.overtimes.index')}}">
                            <i class="fas fa-clock"></i>
                            <span>الإضافي</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('deductions.index')}}">
                            <i class="fas fa-minus-circle"></i>
                            <span>الخصومات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('company.payrolls.index')}}">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>كشوفات الرواتب</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('categories.index')}}">
                            <i class="fas fa-layer-group"></i>
                            <span>الفئات</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{route('loans.index')}}">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span>السلف</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{route('suppliers.index')}}" id="additionsBtn">
                    <i class="fas fa-plus-circle"></i>
                    <span>الإضافات</span>
                </a>
            </li>
            <li>
                <a href="{{route('settings.index')}}" id="settingsBtn">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        TOURAD LEHCENE
    </div>
</aside>
