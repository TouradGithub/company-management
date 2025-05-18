
<html dir="rtl" lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connect Pro - تسجيل الدخول</title>
  <link rel="stylesheet" href="{{asset('auth/globalAuth/style.css')}}">
</head>
<body>
  <div class="vertical-signature">
    <span>by Omar Khedr</span>
  </div>
  <div class="image-overlay"></div>
  <div class="bottom-left-image"></div>

  <div class="historical-box">
    <div class="ornament"></div>
    <h3>برنامج كونكت برو</h3>
    <p>
      برنامج كونكت برو هو البرنامج الأقوى في عالم المحاسبة والذي يحتوي على مزايا أكثر من رائعة وسهل الاستخدام
    </p>
    <div class="ornament"></div>
  </div>

  <div class="container">
    <div class="login-box">
      <h1 class="site-name">Connect Pro</h1>
      <div class="logo">
        <svg width="80" height="80" viewBox="0 0 100 100">
          <circle cx="50" cy="50" r="40" fill="none" stroke="#4A90E2" stroke-width="8" />
          <circle cx="50" cy="35" r="15" fill="#4A90E2" />
          <path d="M25 70 Q50 90 75 70" fill="none" stroke="#4A90E2" stroke-width="8" />
        </svg>
      </div>
      <h2>مرحباً بك</h2>

      <div class="date-box" id="dateDisplay">
        <!-- Date will be inserted by JavaScript -->
      </div>

      <form action="{{route('company.login')}}" method="POST"  class="login-form">
        @csrf
        <div class="input-group">
            <input type="email" id="username" name="email" required autocomplete="off">
          {{-- <input type="text" id="username" required> --}}
          <label for="username">اسم المستخدم</label>
          <div class="input-icon">
            <svg width="20" height="20" viewBox="0 0 24 24">
              <path fill="#666" d="M12 4a4 4 0 100 8 4 4 0 000-8zM6 8a6 6 0 1112 0A6 6 0 016 8zm2 10a3 3 0 00-3 3 1 1 0 11-2 0 5 5 0 015-5h8a5 5 0 015 5 1 1 0 11-2 0 3 3 0 00-3-3H8z"/>
            </svg>
          </div>
        </div>

        <div class="input-group">
        <input type="password" id="password" name="password" required>
          <label for="password">كلمة المرور</label>
          <div class="input-icon">
            <svg width="20" height="20" viewBox="0 0 24 24">
              <path fill="#666" d="M12 1a4 4 0 014 4v4h2a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V11a2 2 0 012-2h2V5a4 4 0 014-4zm0 2a2 2 0 00-2 2v4h4V5a2 2 0 00-2-2z"/>
            </svg>
          </div>
        </div>



        <button type="submit" class="login-btn">تسجيل الدخول</button>
      </form>

      <div class="links">
        <a href="#">نسيت كلمة المرور؟</a>
        <a href="#">إنشاء حساب جديد</a>
      </div>
    </div>
  </div>

  <a href="https://wa.me/966590025167" target="_blank" class="whatsapp-btn">
    <svg viewBox="0 0 24 24" width="24" height="24">
      <path fill="currentColor" d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"/>
    </svg>
  </a>
<script>


</script>
  <script src="{{asset('auth/globalAuth/script.js')}}"></script>
</body>
</html>
