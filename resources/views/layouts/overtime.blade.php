
<html dir="rtl" lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نظام الشركه</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="{{asset('overtime.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{asset('payroll/style.css')}}">
  <style>
    /* .form-container {
        max-width: 800px;
        margin: 0 auto;
    } */

    .row-employe {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .form-group-employe {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .form-group-employe label {
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 14px;
    }

    .form-group-employe input,
    .form-group-employe select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        width: 100%;
    }

    .form-group-employe input:focus,
    .form-group-employe select:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0px 0px 4px rgba(0, 123, 255, 0.5);
    }

    .btn-employe {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-employe:hover {
        background-color: #0056b3;
    }
</style>

  <style>
    .menu-item a {
    display: flex;
    align-items: center;
    text-decoration: none; /* Remove underline */
    color: white; /* Default text color */
    font-weight: 600; /* Make the text bold */
    padding: 10px 15px;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.menu-item a:hover {
     /* Add a hover background color */
    color: #fff; /* Change text color on hover */
}

.statistics-container {
    margin: 20px 0;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.statistics-grid {
    display: flex;
    gap: 20px;
    justify-content: space-around;
    flex-wrap: wrap;
}

.stat-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    width: 200px;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

.stat-card p {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
}

<style>
    .styled-textarea {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
        resize: none;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .styled-textarea:focus {
        border-color: #007bff;
        background-color: #fff;
        outline: none;
        box-shadow: 0px 4px 10px rgba(0, 123, 255, 0.2);
    }
/*
    .form-group label {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
        display: inline-block;
    } */
</style>


  </style>
</head>
<body>
  <nav class="top-nav">
    <div class="nav-content">
      <div class="nav-brand">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24' height='24'%3E%3Cpath fill='none' d='M0 0h24v24H0z'/%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z' fill='%23ffffff'/%3E%3C/svg%3E" alt="Logo" class="nav-logo">
        <span>نظام الشركه</span>
      </div>
      <div class="nav-right">


        <div class="user-menu">
          <button class="user-btn" onclick="toggleUserMenu()">
            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24' height='24'%3E%3Cpath fill='none' d='M0 0h24v24H0z'/%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z' fill='%23ffffff'/%3E%3C/svg%3E" alt="User" class="user-avatar">
            <span> {{auth()->user()->name}}</span>
            <i class="bi bi-chevron-down"></i>
          </button>
          <div class="user-dropdown" id="userDropdown">

            <div class="dropdown-divider"></div>
            <a  onclick="document.getElementById('logout-form').submit();" class="dropdown-item text-danger">
              <i class="bi bi-box-arrow-right"></i>
              تسجيل الخروج
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
  <div class="layout
  ">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <a href="{{route('company.dashboard')}}"> <h2>نظام الشركه</h2></a>

      </div>
      <ul class="menu-items">
        <li class="menu-item {{ Request::routeIs('company.overtimes.create') || Request::routeIs('company.overtimes.index') ? 'active' : '' }}" data-section="overtime-form">
          <span class="menu-icon"><i class="bi bi-file-earmark-plus"></i></span>
          <a href="{{route('company.overtimes.index')}}">الاضافي</a>
        </li>
        <li class="menu-item {{ Request::routeIs('company.loans.create') || Request::routeIs('company.loans.index') ? 'active' : '' }}" data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('loans.index')}}">السلف</a>

        </li>


        <li class="menu-item {{ Request::routeIs('deductions.create') || Request::routeIs('deductions.index') ? 'active' : '' }}" data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('deductions.index')}}">الخصومات</a>

          </li>

          <li class="menu-item {{ Request::routeIs('company.leaves.create') || Request::routeIs('company.leaves.index') ? 'active' : '' }}" data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('company.leaves.index')}}">الاجازات</a>

        </li>

        <li class="menu-item {{ Request::routeIs('branches.create') || Request::routeIs('branches.index') ? 'active' : '' }}" data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('branches.index')}}">الفروع</a>

        </li>

        <li class="menu-item {{ Request::routeIs('categories.create') ||Request::routeIs('categories.index')  ? 'active' : '' }}" data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('categories.index')}}">الفئات</a>

        </li>

        <li class="menu-item " data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('company.employees.index')}}">الموظفين</a>

        </li>

        <li class="menu-item " data-section="records">
            <span class="menu-icon"><i class="bi bi-table"></i></span>
            <a href="{{route('company.payrolls.index')}}">كشوف الرواتب</a>

        </li>

      </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">


          @yield('content')




      <!-- Records Section -->


      <!-- Reports Section -->
      {{-- <section id="reports" class="content-section">
        <div class="container">
          <h1>التقارير</h1>
          <div class="coming-soon">
            <p>قريباً...</p>
          </div>
        </div>
      </section>

      <!-- Settings Section -->
      <section id="settings" class="content-section">
        <div class="container">
          <h1>الإعدادات</h1>
          <div class="coming-soon">
            <p>قريباً...</p>
          </div>
        </div>
      </section> --}}
    </main>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script src="{{asset('overtime.js')}}"></script>

  <script>
     window.toggleUserMenu = function() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');

    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
      if (!e.target.closest('.user-menu')) {
        dropdown.classList.remove('show');
        document.removeEventListener('click', closeDropdown);
      }
    }, { once: true }); // Use once option to automatically remove listener after first use
  };
  </script>

  @yield('js')
</body>
</html>
