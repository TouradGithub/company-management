<header>
    <div class="header-content">
        <div class="digital-clock"></div>
        <div class="search-bar">
             <span class="badge badge-success">
            السنة الماليه : {{ getCurentYearName()??'' }}
            </span>
        </div>
        <div class="user-info">
            <i class="fas fa-bell"></i>
            <i class="fas fa-user-circle"></i>
            <span class="badge badge-secondary" style="cursor: pointer" onclick="document.getElementById('logout-form').submit();" >تسجيل خروج</span>
            <span>مرحباً، {{ auth()->user()->name }}</span>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>
