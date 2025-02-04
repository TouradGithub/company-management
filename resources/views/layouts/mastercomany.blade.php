<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>برنامج المحاسبة كونكت برو</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;500;700&display=swap" rel="stylesheet">
  <!-- Add Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /><link rel="stylesheet" href="{{asset('newDesign/styles.css')}}">
  {{-- <link rel="stylesheet" href="{{asset('payroll/style.css')}}"> --}}
  <style>
     .hidden {
        display: none;
    }
    .add-deduction-btn a{
        display: inline-block;
        color: white;
    text-decoration: none;
    }

  </style>
</head>
<body>
  <header class="main-header">
    <div class="header-content">
      <h1 class="logo">برنامج المحاسبة كونكت برو</h1>
      <div class="user-profile">
        <i class="fas fa-user-circle"></i>
      </div>
    </div>
  </header>

  <nav class="sidebar">
    <ul class="menu-items">
      <li class="menu-item  {{ Request::routeIs('company.employees.index') || Request::routeIs('company.employees.index') ? 'active' : '' }}">
        <a href="{{route('company.employees.index')}}">
          <i class="fas fa-users"></i>
          <span>الموظفين</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item {{ Request::routeIs('branch.loans.create') || Request::routeIs('branch.loans.index') ? 'active' : '' }}">
        <a href="{{route('loans.index')}}">
          <i class="fas fa-hand-holding-usd"></i>
          <span>السلف</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item {{ Request::routeIs('company.overtimes.create') || Request::routeIs('company.overtimes.index') ? 'active' : '' }}">
        <a href="{{route('company.overtimes.index')}}">
          <i class="fas fa-coins"></i>
          <span>الاضافي</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item  {{ Request::routeIs('deductions.create') || Request::routeIs('deductions.index') ? 'active' : '' }}"">
        <a href="{{route('deductions.index')}}">
          <i class="fas fa-percentage"></i>
          <span>الخصومات</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item  {{ Request::routeIs('company.leaves.create') || Request::routeIs('company.leaves.index') ? 'active' : '' }}">
        <a href="{{route('company.leaves.index')}}">
          <i class="fas fa-calendar-alt"></i>
          <span>الاجازات</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item  {{ Request::routeIs('company.payrolls.create') ||Request::routeIs('company.payrolls.index')  ? 'active' : '' }}">
        <a href="{{route('company.payrolls.index')}}">
          <i class="fas fa-file-invoice-dollar"></i>
          <span>كشوفات الرواتب</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item {{ Request::routeIs('categories.create') ||Request::routeIs('categories.index')  ? 'active' : '' }}">
        <a href="{{route('categories.index')}}">
          <i class="fas fa-tags"></i>
          <span>الفئات</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item {{ Request::routeIs('branches.create') || Request::routeIs('branches.index') ? 'active' : '' }}">
        <a href="{{route('branches.index')}}">
          <i class="fas fa-code-branch"></i>
          <span>الفروع</span>
          <div class="hover-effect"></div>
        </a>
      </li>
      <li class="menu-item has-submenu">
        <a href="#">
          <i class="fas fa-calculator"></i>
          <span>المحاسبة المالية</span>
          <i class="fas fa-chevron-down submenu-arrow"></i>
          <div class="hover-effect"></div>
        </a>
        <ul class="submenu">
          <li>
            <a href="#">
              <i class="fas fa-book"></i>
              <span>قيد يومية</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fas fa-list"></i>
              <span>عرض قيود اليومية</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fas fa-file-invoice"></i>
              <span>كشف حساب</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fas fa-balance-scale"></i>
              <span>ميزان المراجعة</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fas fa-chart-line"></i>
              <span>قائمة المركز المالي</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fas fa-chart-pie"></i>
              <span>قائمة الدخل</span>
            </a>
          </li>
          <li>
            <a href="#">
              <i class="fas fa-file-alt"></i>
              <span>تقارير مالية</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>

    <div class="sidebar-footer">
      <div class="decorative-line"></div>
    </div>
  </nav>

  <main class="main-content">



    <div class="advances-section" id="advances-view" >

      @yield('content')

    </div>


  </main>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  @yield('js')


  <script src="{{asset('newDesign/script.js')}}"></script>
</body>
</html>
