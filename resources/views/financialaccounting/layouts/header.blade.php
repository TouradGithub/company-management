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
            <span>مرحباً، {{ auth()->user()->name }}</span>
        </div>
    </div>
</header>
