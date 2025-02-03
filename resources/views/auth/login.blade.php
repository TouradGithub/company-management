{{-- @extends('layouts.app')

@section('content')

<div class="login-container active" id="loginContainer">
    <h1>&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h1>
    <form method="POST"  action="{{'login'}}">
        @csrf

      <div class="form-group">
        <label>&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x633;&#x62a;&#x62e;&#x62f;&#x645;</label>
        <input type="text" id="username" name="email" required autocomplete="off">
      </div>
      <div class="form-group">
        <label>&#x643;&#x644;&#x645;&#x629; &#x627;&#x644;&#x645;&#x631;&#x648;&#x631;</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="login-error" id="loginError">
        &#x627;&#x62a;&#x62c; &#x627;&#x644;&#x645; &#x645;&#x64a;&#x627;&#x62a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641; &#x631;&#x627;&#x62a; &#x643;&#x644;&#x645;&#x629; &#x627;&#x644;&#x645;&#x631;&#x648;&#x631;
      </div>
      <button type="submit">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x62f;&#x62e;&#x648;&#x644;</button>
    </form>
  </div>
@endsection --}}
{{-- <html><head><base href "."; <meta charset="UTF-8">
    <title>;&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</title>
    <style>
        body {
          font-family: 'Cairo', sans-serif;
          direction: rtl;
          margin: 0;
          padding: 20px;
          background: linear-gradient(120deg, #f6f7f9, #e3e7ed);
        }

        .container {
          max-width: 1200px;
          margin: 0 auto;
        }

        .nav-container {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
        }

        .nav-item {
          background: white;
          padding: 20px;
          border-radius: 15px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          text-align: center;
          cursor: pointer;
          transition: transform 0.3s ease;
          text-decoration: none;
          color: #2c3e50;
        }

        .nav-item:hover {
          transform: translateY(-5px);
        }

        .nav-icon {
          display: none;
        }

        .nav-title {
          font-weight: bold;
          margin: 0;
          color: #2c3e50;
        }

        .content-sections > div {
          display: none;
        }

        .content-sections > div.active {
          display: block;
        }

        h1, h2 {
          text-align: center;
          color: #2c3e50;
          margin-bottom: 30px;
        }

        .form-container, .deduction-container, .overtime-container, .employees-container, .salary-report-container {
          background: white;
          padding: 30px;
          border-radius: 15px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          margin-bottom: 30px;
        }

        .form-group {
          margin-bottom: 20px;
        }

        label {
          display: block;
          margin-bottom: 5px;
          color: #2c3e50;
        }

        input, select {
          width: 100%;
          padding: 8px;
          border: 2px solid #e3e7ed;
          border-radius: 5px;
          font-family: 'Cairo', sans-serif;
        }

        button {
          background: #3498db;
          color: white;
          border: none;
          padding: 10px 20px;
          border-radius: 5px;
          cursor: pointer;
          font-family: 'Cairo', sans-serif;
        }

        button:hover {
          background: #2980b9;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }

        th, td {
          padding: 12px;
          text-align: right;
          border-bottom: 1px solid #e3e7ed;
        }

        th {
          background: #f6f7f9;
          color: #2c3e50;
        }

        .total-row {
          font-weight: bold;
          background: #f6f7f9;
        }

        .deduction {
          color: #e74c3c;
        }

        .overtime {
          color: #27ae60;
        }

        .calculated-total {
          background: linear-gradient(120deg, #3498db, #2ecc71);
          color: white;
          padding: 15px;
          border-radius: 12px;
          font-size: 18px;
          text-align: center;
          margin-top: 10px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .action-buttons {
          display: flex;
          gap: 5px;
        }

        .edit-btn {
          background: #f39c12;
          padding: 5px 10px;
          border-radius: 3px;
          color: white;
          cursor: pointer;
        }

        .delete-btn {
          background: #e74c3c;
          padding: 5px 10px;
          border-radius: 3px;
          color: white;
          cursor: pointer;
        }

        .search-export-container {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }

        .search-container input {
          padding: 8px;
          width: 300px;
          border: 2px solid #e3e7ed;
          border-radius: 5px;
          font-family: 'Cairo', sans-serif;
        }

        .export-buttons {
          display: flex;
          gap: 10px;
        }

        .print-btn, .excel-btn {
          padding: 8px 15px;
          border-radius: 5px;
          cursor: pointer;
          font-family: 'Cairo', sans-serif;
          color: white;
        }

        .print-btn {
          background: #2ecc71;
        }

        .excel-btn {
          background: #27ae60;
        }

        @media print {
          .nav-container, .search-export-container, .action-buttons {
            display: none;
          }
        }

        .login-container {
          display: none;
          max-width: 450px;
          margin: 50px auto;
          padding: 40px;
          background: rgba(255, 255, 255, 0.95);
          border-radius: 20px;
          box-shadow: 0 15px 35px rgba(0,0,0,0.1);
          backdrop-filter: blur(10px);
          text-align: center;
          transform: translateY(20px);
          opacity: 0;
          transition: all 0.4s ease;
        }

        .login-container.active {
          display: block;
          transform: translateY(0);
          opacity: 1;
        }

        .login-container::before {
          content: '';
          position: absolute;
          top: -10px;
          left: -10px;
          right: -10px;
          bottom: -10px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          border-radius: 25px;
          z-index: -1;
          opacity: 0.1;
        }

        .login-container h1 {
          margin-bottom: 30px;
          color: #2c3e50;
          font-size: 2.2em;
          text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .login-container .form-group {
          margin-bottom: 25px;
          position: relative;
        }

        .login-container input {
          width: 100%;
          padding: 15px;
          border: 2px solid #e3e7ed;
          border-radius: 12px;
          font-size: 16px;
          transition: all 0.3s ease;
          background: rgba(255,255,255,0.9);
        }

        .login-container input:focus {
          border-color: #3498db;
          box-shadow: 0 0 15px rgba(52,152,219,0.1);
          outline: none;
        }

        .login-container label {
          position: absolute;
          top: -10px;
          left: 15px;
          background: white;
          padding: 0 10px;
          font-size: 14px;
          color: #7f8c8d;
          transition: all 0.3s ease;
        }

        .login-container button {
          width: 100%;
          padding: 15px;
          border: none;
          border-radius: 12px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          color: white;
          font-size: 18px;
          font-weight: bold;
          cursor: pointer;
          transition: all 0.3s ease;
          text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .login-container button:hover {
          transform: translateY(-2px);
          box-shadow: 0 5px 15px rgba(52,152,219,0.3);
        }

        .login-error {
          color: #e74c3c;
          margin: 15px 0;
          padding: 10px;
          border-radius: 8px;
          background: rgba(231,76,60,0.1);
          display: none;
          animation: shake 0.5s ease;
        }

        @keyframes shake {
          0%, 100% { transform: translateX(0); }
          25% { transform: translateX(-10px); }
          75% { transform: translateX(10px); }
        }

        .main-content {
          display: none;
          margin-top: 130px !important;
        }

        .main-content.active {
          display: block;
        }
        /* Update form container styles */
        .form-container {
          background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
          border-radius: 20px;
          box-shadow: 0 15px 35px rgba(0,0,0,0.1);
          padding: 40px;
          transition: all 0.3s ease;
        }

        .form-container:hover {
          transform: translateY(-5px);
          box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .form-container h2 {
          background: linear-gradient(120deg, #3498db, #2ecc71);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          margin-bottom: 30px;
          text-align: center;
          font-size: 2em;
        }

        .form-group {
          position: relative;
          margin-bottom: 30px;
        }

        .form-group label {
          position: absolute;
          top: -10px;
          right: 15px;
          background: white;
          padding: 0 10px;
          font-size: 14px;
          color: #7f8c8d;
          transition: all 0.3s ease;
        }

        .form-group input {
          width: 100%;
          padding: 15px;
          border: 2px solid #e3e7ed;
          border-radius: 12px;
          font-size: 16px;
          transition: all 0.3s ease;
          background: rgba(255,255,255,0.9);
        }

        .form-group input:focus {
          border-color: #3498db;
          box-shadow: 0 0 15px rgba(52,152,219,0.1);
          outline: none;
        }

        .form-group input:focus + label {
          color: #3498db;
        }

        .form-container button {
          width: 100%;
          padding: 15px;
          border: none;
          border-radius: 12px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          color: white;
          font-size: 18px;
          font-weight: bold;
          cursor: pointer;
          transition: all 0.3s ease;
          text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .form-container button:hover {
          transform: translateY(-2px);
          box-shadow: 0 5px 15px rgba(52,152,219,0.3);
        }

        /* Add animated background */
        .form-container::before {
          content: '';
          position: absolute;
          top: -10px;
          left: -10px;
          right: -10px;
          bottom: -10px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          border-radius: 25px;
          z-index: -1;
          opacity: 0.1;
          animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
          0% {
            background-position: 0% 50%;
          }
          50% {
            background-position: 100% 50%;
          }
          100% {
            background-position: 0% 50%;
          }
        }

        .overtime-container, .deduction-container {
          background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
          border-radius: 20px;
          box-shadow: 0 15px 35px rgba(0,0,0,0.1);
          padding: 40px;
          transition: all 0.3s ease;
          position: relative;
        }

        .overtime-container:hover, .deduction-container:hover {
          transform: translateY(-5px);
          box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .overtime-container h2, .deduction-container h2 {
          background: linear-gradient(120deg, #3498db, #2ecc71);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          margin-bottom: 30px;
          text-align: center;
          font-size: 2em;
        }

        .overtime-container::before, .deduction-container::before {
          content: '';
          position: absolute;
          top: -10px;
          left: -10px;
          right: -10px;
          bottom: -10px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          border-radius: 25px;
          z-index: -1;
          opacity: 0.1;
          animation: gradientBG 15s ease infinite;
        }

        .employees-container {
          background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
          border-radius: 20px;
          box-shadow: 0 15px 35px rgba(0,0,0,0.1);
          padding: 40px;
          transition: all 0.3s ease;
          position: relative;
        }

        .employees-container::before {
          content: '';
          position: absolute;
          top: -10px;
          left: -10px;
          right: -10px;
          bottom: -10px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          border-radius: 25px;
          z-index: -1;
          opacity: 0.1;
          animation: gradientBG 15s ease infinite;
        }

        .employees-container h2 {
          background: linear-gradient(120deg, #3498db, #2ecc71);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          margin-bottom: 30px;
          text-align: center;
          font-size: 2em;
        }

        #employeesTable {
          background: rgba(255, 255, 255, 0.9);
          border-radius: 15px;
          overflow: hidden;
          border: none;
          box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        #employeesTable th {
          background: linear-gradient(120deg, #3498db, #2ecc71);
          color: white;
          padding: 15px;
          font-weight: 500;
        }

        #employeesTable td {
          padding: 12px 15px;
          border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        #employeesTable tr:hover {
          background: rgba(52,152,219,0.05);
        }

        .overtime-container input,
        .overtime-container select,
        .deduction-container input,
        .deduction-container select {
          width: 100%;
          padding: 15px;
          border: 2px solid #e3e7ed;
          border-radius: 12px;
          font-size: 16px;
          transition: all 0.3s ease;
          background: rgba(255,255,255,0.9);
        }

        .overtime-container input:focus,
        .overtime-container select:focus,
        .deduction-container input:focus,
        .deduction-container select:focus {
          border-color: #3498db;
          box-shadow: 0 0 15px rgba(52,152,219,0.1);
          outline: none;
        }

        .salary-report-container {
          background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
          border-radius: 20px;
          box-shadow: 0 15px 35px rgba(0,0,0,0.1);
          padding: 40px;
          transition: all 0.3s ease;
          position: relative;
          display: flex;
          flex-direction: column;
          gap: 20px;
        }

        .salary-report-header {
          margin-top: 30px;
        }

        .monthly-report-table {
          margin-top: 0;
        }

        .employee-details {
          margin-top: 30px;
          background: rgba(255, 255, 255, 0.9);
          border-radius: 15px;
          padding: 20px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .detail-row {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 20px;
          margin-bottom: 15px;
        }

        .detail-item {
          padding: 10px;
          border-radius: 8px;
          background: rgba(255, 255, 255, 0.8);
        }

        .detail-item label {
          display: block;
          color: #7f8c8d;
          margin-bottom: 5px;
        }

        .detail-item span {
          font-size: 1.2em;
          color: #2c3e50;
          font-weight: bold;
        }

        .total {
          background: linear-gradient(120deg, #3498db, #2ecc71);
        }

        .total label, .total span {
          color: white;
        }

        .add-to-report-btn {
          width: 100%;
          padding: 15px;
          border: none;
          border-radius: 12px;
          background: linear-gradient(120deg, #3498db, #2ecc71);
          color: white;
          font-size: 18px;
          font-weight: bold;
          cursor: pointer;
          transition: all 0.3s ease;
          text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
          margin-top: 20px;
        }

        .add-to-report-btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 5px 15px rgba(52,152,219,0.3);
        }

        .salary-report-header {
          display: grid;
          grid-template-columns: repeat(2, 1fr);
          gap: 20px;
          margin-bottom: 30px;
          background: white;
          padding: 20px;
          border-radius: 15px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .monthly-report-table {
          background: white;
          padding: 20px;
          border-radius: 15px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          margin-top: 20px;
        }

        #monthlyReportTable {
          width: 100%;
          border-collapse: collapse;
        }

        #monthlyReportTable th {
          background: linear-gradient(120deg, #3498db, #2ecc71);
          color: white;
          padding: 15px;
        }

        #monthlyReportTable td {
          padding: 12px;
          text-align: right;
          border-bottom: 1px solid #e3e7ed;
        }
        .toolbar {
          background: linear-gradient(120deg, #2c3e50, #3498db);
          padding: 15px;
          position: fixed;
          top: 60px;
          left: 0;
          right: 0;
          z-index: 1000;
          display: flex;
          justify-content: center;
          align-items: center;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .toolbar-menu {
          display: flex;
          gap: 20px;
          margin: 0;
          padding: 0;
          list-style: none;
        }

        .toolbar-menu-item {
          position: relative;
        }

        .toolbar-menu-link {
          color: white;
          text-decoration: none;
          padding: 8px 15px;
          border-radius: 5px;
          transition: background 0.3s ease;
        }

        .toolbar-menu-link:hover {
          background: rgba(255,255,255,0.1);
        }

        .toolbar-menu-link.active {
          background: rgba(255,255,255,0.2);
        }

        .login-container {
          margin-top: 130px !important;
        }

        .dropdown {
          position: relative;
          display: inline-block;
        }

        .dropdown-content {
          display: none;
          position: absolute;
          background: white;
          min-width: 200px;
          box-shadow: 0 8px 16px rgba(0,0,0,0.1);
          z-index: 1;
          border-radius: 5px;
          top: 100%;
          right: 0;
        }

        .dropdown:hover .dropdown-content {
          display: block;
        }

        .dropdown-content a {
          color: #2c3e50;
          padding: 12px 16px;
          text-decoration: none;
          display: block;
          text-align: right;
        }

        .dropdown-content a:hover {
          background: #f6f7f9;
        }

        .deductions-report, .overtime-report {
          display: none;
          background: white;
          padding: 30px;
          border-radius: 15px;
          box-shadow: 0 5px 15px rgba(0,0,0,0.1);
          margin-bottom: 30px;
        }
        .salary-report-container select {
          width: 100%;
          padding: 8px;
          border: 2px solid #e3e7ed;
          border-radius: 5px;
          font-family: 'Cairo', sans-serif;
          margin-bottom: 20px;
        }
        .system-title {
          background: linear-gradient(120deg, #2c3e50, #3498db);
          color: white;
          text-align: center;
          padding: 15px;
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          z-index: 1001;
          font-size: 1.5em;
          font-weight: bold;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&amp;display=swap" rel="stylesheet">
    </head>
    <body>
      <div class="system-title">&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</div>

      <div class="login-container active" id="loginContainer">
        <h1>&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h1>
        <form method="POST"  action="{{'login'}}">
            @csrf

          <div class="form-group">
            <label>&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x633;&#x62a;&#x62e;&#x62f;&#x645;</label>
            <input type="text" id="username" name="email" required autocomplete="off">
          </div>
          <div class="form-group">
            <label>&#x643;&#x644;&#x645;&#x629; &#x627;&#x644;&#x645;&#x631;&#x648;&#x631;</label>
            <input type="password" id="password" name="password" required>
          </div>
          <div class="login-error" id="loginError">
            &#x627;&#x62a;&#x62c; &#x627;&#x644;&#x645; &#x645;&#x64a;&#x627;&#x62a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641; &#x631;&#x627;&#x62a; &#x643;&#x644;&#x645;&#x629; &#x627;&#x644;&#x645;&#x631;&#x648;&#x631;
          </div>
          <button type="submit">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x62f;&#x62e;&#x648;&#x644;</button>
        </form>
      </div>



    </body></html> --}}
{{--

    <html dir="rtl" lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>نظام الإضافي</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="{{asset('auth/style.css?v=1.1')}}">
</head>
<body class="auth-body">
  <div id="loginPage" class="login-page">
    <div class="login-container">
      <div class="login-header">
        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='48' height='48'%3E%3Cpath fill='none' d='M0 0h24v24H0z'/%3E%3Cpath d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z' fill='%231a237e'/%3E%3C/svg%3E" alt="Logo" class="login-logo">
        <h1>تسجيل الدخول</h1>
      </div>
      <form id="loginForm" action="" method="POST"  class="login-form">
        @csrf
        <div class="form-group">
          <label for="username">اسم المستخدم:</label>
          <div class="input-group">
            <i class="bi bi-person"></i>
            <input type="email" id="email" name="email" required>
          </div>
        </div>
        <div class="form-group">
          <label for="password">كلمة المرور:</label>
          <div class="input-group">
            <i class="bi bi-lock"></i>
            <input type="password" id="password" name="password" required>
          </div>
        </div>
        <div class="form-group">
          <label for="userType">نوع المستخدم:</label>
          <div class="input-group">
            <i class="bi bi-person-badge"></i>
            <select id="userType" required>
              <option value="admin">مدير النظام</option>
              <option value="company">مدير الشركة</option>
              <option value="branch">مستخدم فرع </option>
            </select>
          </div>
        </div>
        <button type="submit" class="btn-primary login-btn">دخول</button>
      </form>
    </div>
  </div>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    const loginForm = document.getElementById('loginForm');
    const userType = document.getElementById('userType');

    // Update the form action based on the selected user type
    userType.addEventListener('change', () => {
        if (userType.value === 'admin') {
            loginForm.action = "{{ route('login') }}";
        } else if (userType.value === 'company') {
            loginForm.action = "{{ route('company.login') }}";
        }else if (userType.value === 'branch') {
            loginForm.action = "{{ route('branch.login') }}";
        }
    });

    // Set default action on page load
    window.addEventListener('load', () => {
        if (userType.value === 'admin') {
            loginForm.action = "{{ route('login') }}";
        } else if (userType.value === 'company') {
            loginForm.action = "{{ route('company.login') }}";
        }else if (userType.value === 'branch') {
            loginForm.action = "{{ route('branch.login') }}";
        }
    });
</script>
  {{-- <script src="{{asset('auth/script.js')}}"></script> --}}
{{-- </body>
</html> --}}


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

      <form id="loginForm" action="" method="POST"  class="login-form">
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

        <div class="input-group select-group">
          <select id="userType" required>
            <option value="" disabled selected>اختر نوع المستخدم</option>
            <option value="admin">مدير النظام</option>
            <option value="company">مدير الشركة</option>
            <option value="branch">مدير الفرع</option>
          </select>
          <div class="input-icon">
            <svg width="20" height="20" viewBox="0 0 24 24">
              <path fill="#666" d="M12 12a5 5 0 100-10 5 5 0 000 10zm0-8a3 3 0 110 6 3 3 0 010-6zm9 17v-2a7 7 0 00-7-7h-4a7 7 0 00-7 7v2h2v-2a5 5 0 015-5h4a5 5 0 015 5v2h2z"/>
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
        const loginForm = document.getElementById('loginForm');
    const userType = document.getElementById('userType');

    // Update the form action based on the selected user type
    userType.addEventListener('change', () => {
        if (userType.value === 'admin') {
            loginForm.action = "{{ route('login') }}";
        } else if (userType.value === 'company') {
            loginForm.action = "{{ route('company.login') }}";
        }else if (userType.value === 'branch') {
            loginForm.action = "{{ route('branch.login') }}";
        }
    });

    // Set default action on page load
    window.addEventListener('load', () => {
        if (userType.value === 'admin') {
            loginForm.action = "{{ route('login') }}";
        } else if (userType.value === 'company') {
            loginForm.action = "{{ route('company.login') }}";
        }else if (userType.value === 'branch') {
            loginForm.action = "{{ route('branch.login') }}";
        }
    });
</script>
  <script src="{{asset('auth/globalAuth/script.js')}}"></script>
</body>
</html>
