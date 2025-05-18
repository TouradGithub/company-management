@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
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

            {{-- الحسابات العامة --}}
            @if(
               $user->can('company_create_journal_entry') ||
               $user->can('company_view_journal_entries')
           )
                <li class="menu-group {{ $segment == 'journal-entry' || $segment == 'cost-center' ? 'active' : '' }}" >
                    <a href="#financial-accounting">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>الحسابات العامة</span>
                    </a>
                    <ul class="submenu">
                        @can('company_create_journal_entry')
                            <li class="{{ $route == 'journal-entry.create' ? 'active' : '' }}">
                                <a href="{{route('branch.journal-entry.create')}}">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>إنشاء قيد يومية</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_view_journal_entries')
                            <li class="{{ $route == 'journal-entry.index' ? 'active' : '' }}">
                                <a href="{{route('branch.journal-entry.index')}}">
                                    <i class="fas fa-journal-whills"></i>
                                    <span>قيود اليومية</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            {{-- الفواتير والمنتجات --}}
            @can('company_view_invoices')
                <li class="menu-group  {{ $segment == 'invoices' ? 'active' : '' }}">
                    <a href="#invoices">
                        <i class="fas fa-file-invoice"></i>
                        <span>الفواتير والمنتجات</span>
                    </a>
                    <ul class="submenu">
                        @can('company_view_invoices')
                            <li>
                                <a href="{{route('branch.invoices.index')}}">
                                    <i class="fas fa-file-alt"></i>
                                    <span> الفواتير</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_create_sales_invoice')
                            <li>
                                <a href="{{route('branch.invoices.sales')}}">
                                    <i class="fas fa-file-alt"></i>
                                    <span>فاتورة المبيعات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_create_sales_return')
                            <li>
                                <a href="{{ route('branch.invoices.sales-returns') }}">
                                    <i class="fas fa-undo"></i>
                                    <span>مرتجع فاتورة مبيعات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_create_purchase_invoice')
                            <li>
                                <a href="{{route('branch.invoices.purchases')}}">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>فاتورة المشتريات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_create_purchase_return')
                            <li>
                                <a href="{{ route('branch.invoices.purchase-returns') }}">
                                    <i class="fas fa-reply"></i>
                                    <span>مرتجع فاتورة المشتريات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_products')
                            <li>
                                <a href="{{route('branch.products.index')}}">
                                    <i class="fas fa-box"></i>
                                    <span>المنتجات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_customers')
                            <li>
                                <a href="{{route('branch.customers.index')}}">
                                    <i class="fas fa-tags"></i>
                                    <span> العملاء</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            {{-- الحسابات الختامية --}}
            @can('company_view_trial_balance')
                <li class="menu-group  {{ $segment == 'trial-balance' || $segment == 'income-statement' ? 'active' : '' }}">
                    <a href="#final-accounts">
                        <i class="fas fa-balance-scale"></i>
                        <span>الحسابات الختامية</span>
                    </a>
                    <ul class="submenu">
                        @can('company_view_trial_balance')
                            <li>
                                <a href="{{route('branch.trial.balance')}}">
                                    <i class="fas fa-list-ol"></i>
                                    <span>ميزان المراجعة</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_view_income_statement')
                            <li>
                                <a href="{{route('branch.income.statement.index')}}">
                                    <i class="fas fa-chart-line"></i>
                                    <span>قائمة الدخل</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            {{-- الموارد البشرية --}}
            @can('company_manage_employees')
                <li class="menu-group">
                    <a href="#hr">
                        <i class="fas fa-users-cog"></i>
                        <span>الموارد البشرية</span>
                    </a>
                    <ul class="submenu">
                        @can('company_manage_employees')
                            <li>
                                <a href="{{route('branch.employees.create')}}">
                                    <i class="fas fa-user-plus"></i>
                                    <span>إضافة موظف</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_leaves')
                            <li>
                                <a href="{{route('branch.leaves.index')}}">
                                    <i class="fas fa-umbrella-beach"></i>
                                    <span>الإجازات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_overtimes')
                            <li>
                                <a href="{{route('branch.overtimes.index')}}">
                                    <i class="fas fa-clock"></i>
                                    <span>الإضافي</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_deductions')
                            <li>
                                <a href="{{route('branch.deductions.index')}}">
                                    <i class="fas fa-minus-circle"></i>
                                    <span>الخصومات</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_payrolls')
                            <li>
                                <a href="{{route('branch.payrolls.index')}}">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    <span>كشوفات الرواتب</span>
                                </a>
                            </li>
                        @endcan

                        @can('company_manage_loans')
                            <li>
                                <a href="{{route('branch.loans.index')}}">
                                    <i class="fas fa-hand-holding-usd"></i>
                                    <span>السلف</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

        </ul>
    </nav>
    <div class="sidebar-footer">
        TOURAD LEHCENE
    </div>
</aside>
