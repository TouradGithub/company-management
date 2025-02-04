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
          <div class="user-controls">
            <button class="logout-btn" onclick="document.getElementById('logout-form').submit();" >
              <i class="fas fa-sign-out-alt"></i>
              تسجيل الخروج

            </button>
            <div class="user-profile">
              <i class="fas fa-user-circle"></i>
            </div>
          </div>
        </div>
      </header>

  <nav class="sidebar">
    <ul class="menu-items">
      <li class="menu-item  {{ Request::routeIs('company.create') || Request::routeIs('company.index') ? 'active' : '' }}">
        <a href="{{route('company.index')}}">
          <i class="fas fa-users"></i>
          <span>الشركات</span>
          <div class="hover-effect"></div>
        </a>
      </li>




    </ul>

    <div class="sidebar-footer">
      <div class="decorative-line"></div>
    </div>
  </nav>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
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
