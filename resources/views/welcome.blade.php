<html><head><base href "."; <meta charset="UTF-8">
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
  <div class="toolbar">
    <ul class="toolbar-menu">
      <li class="toolbar-menu-item">
        <a href="#" class="toolbar-menu-link" onclick="showSection(&apos;form-container&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x645;&#x648;&#x638;&#x641;</a>
      </li>
      <li class="toolbar-menu-item">
        <a href="#" class="toolbar-menu-link" onclick="showSection(&apos;overtime-container&apos;)">&#x627;&#x644;&#x639;&#x645;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</a>
      </li>
      <li class="toolbar-menu-item">
        <a href="#" class="toolbar-menu-link" onclick="showSection(&apos;deduction-container&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x62e;&#x635;&#x645;</a>
      </li>
      <li class="toolbar-menu-item">
        <a href="#" class="toolbar-menu-link" onclick="showSection(&apos;employees-container&apos;)">&#x642;&#x627;&#x626;&#x645;&#x629; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</a>
      </li>
      <li class="toolbar-menu-item">
        <a href="#" class="toolbar-menu-link" onclick="showSection(&apos;salary-report-container&apos;)">&#x643;&#x634;&#x641; &#x627;&#x644;&#x631;&#x648;&#x627;&#x62a;&#x628;</a>
      </li>
      <li class="toolbar-menu-item">
        <div class="dropdown">
          <a href="#" class="toolbar-menu-link">&#x627;&#x644;&#x62a;&#x642;&#x627;&#x631;&#x64a;&#x631;</a>
          <div class="dropdown-content">
            <a href="#" onclick="showSection(&apos;deductions-report&apos;)">&#x62a;&#x642;&#x631;&#x64a;&#x631; &#x62e;&#x635;&#x645; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</a>
            <a href="#" onclick="showSection(&apos;overtime-report&apos;)">&#x62a;&#x642;&#x631;&#x64a;&#x631; &#x627;&#x636;&#x627;&#x641;&#x64a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</a>
          </div>
        </div>
      </li>
    </ul>
  </div>
  <div class="login-container active" id="loginContainer">
    <h1>&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h1>
    <form id="loginForm">
      <div class="form-group">
        <label>&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x633;&#x62a;&#x62e;&#x62f;&#x645;</label>
        <input type="text" id="username" required autocomplete="off">
      </div>
      <div class="form-group">
        <label>&#x643;&#x644;&#x645;&#x629; &#x627;&#x644;&#x645;&#x631;&#x648;&#x631;</label>
        <input type="password" id="password" required>
      </div>
      <div class="login-error" id="loginError">
        &#x627;&#x62a;&#x62c; &#x627;&#x644;&#x645; &#x645;&#x64a;&#x627;&#x62a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641; &#x631;&#x627;&#x62a; &#x643;&#x644;&#x645;&#x629; &#x627;&#x644;&#x645;&#x631;&#x648;&#x631;
      </div>
      <button type="submit">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x62f;&#x62e;&#x648;&#x644;</button>
    </form>
  </div>
  <div class="main-content" id="mainContent">
    <div class="container">
      <h1>;&#x646;&#x638;&#x627;&#x645; &#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h1>

      <div class="nav-container">
        <div class="nav-item" onclick="showSection(&apos;form-container&apos;)">
          <h3 class="nav-title">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x645;&#x648;&#x638;&#x641; &#x62c;&#x62f;&#x64a;&#x62f;</h3>
        </div>

        <div class="nav-item" onclick="showSection(&apos;overtime-container&apos;)">
          <h3 class="nav-title">&#x627;&#x644;&#x639;&#x645;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</h3>
        </div>

        <div class="nav-item" onclick="showSection(&apos;deduction-container&apos;)">
          <h3 class="nav-title">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x62e;&#x635;&#x645;</h3>
        </div>

        <div class="nav-item" onclick="showSection(&apos;employees-container&apos;)">
          <h3 class="nav-title">&#x642;&#x627;&#x626;&#x645;&#x629; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h3>
        </div>
        <div class="nav-item" onclick="showSection(&apos;salary-report-container&apos;)">
          <h3 class="nav-title">&#x643;&#x634;&#x641; &#x627;&#x644;&#x631;&#x648;&#x627;&#x62a;&#x628;</h3>
        </div>
      </div>

      <div class="content-sections">
        <div class="form-container active" id="form-container">
          <form id="employeeForm">
            <div class="form-group">
              <label>;&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;:</label>
              <input type="text" id="name" required>
            </div>

            <div class="form-group">
              <label>;&#x627;&#x644;&#x645;&#x647;&#x646;&#x629;:</label>
              <input type="text" id="position" required>
            </div>

            <div class="form-group">
              <label>;&#x627;&#x644;&#x631;&#x627;&#x62a;&#x628; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;:</label>
              <input type="number" id="baseSalary" required>
            </div>

            <div class="form-group">
              <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x633;&#x643;&#x646;:</label>
              <input type="number" id="housingAllowance" required>
            </div>

            <div class="form-group">
              <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x645;&#x648;&#x627;&#x635;&#x644;&#x627;&#x62a;:</label>
              <input type="number" id="transportAllowance" required>
            </div>

            <div class="form-group">
              <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x625;&#x639;&#x627;&#x634;&#x629;:</label>
              <input type="number" id="foodAllowance" required>
            </div>

            <button type="submit">;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;</button>
          </form>
        </div>

        <div class="overtime-container" id="overtime-container">
          <h2>;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x639;&#x645;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</h2>
          <form id="overtimeForm">
            <div class="form-group">
              <label>;&#x627;&#x62e;&#x62a;&#x631; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;:</label>
              <select id="overtimeEmployeeSelect" required></select>
            </div>

            <div class="form-group">
              <label>;&#x639;&#x62f;&#x62f; &#x633;&#x627;&#x639;&#x627;&#x62a; &#x627;&#x644;&#x639;&#x645;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;:</label>
              <input type="number" id="overtimeHours" required>
            </div>

            <div class="form-group">
              <label>;&#x642;&#x64a;&#x645;&#x629; &#x627;&#x644;&#x633;&#x627;&#x639;&#x629; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;&#x629;:</label>
              <input type="number" id="overtimeRate" required>
            </div>

            <div class="form-group">
              <label>;&#x625;&#x62c;&#x645;&#x627;&#x644;&#x64a; &#x642;&#x64a;&#x645;&#x629; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;:</label>
              <div id="overtimeTotalDisplay" class="calculated-total">0</div>
            </div>

            <button type="submit">;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</button>
          </form>
        </div>

        <div class="deduction-container" id="deduction-container">
          <h2>;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x62e;&#x635;&#x645;</h2>
          <form id="deductionForm">
            <div class="form-group">
              <label>;&#x627;&#x62e;&#x62a;&#x631; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;:</label>
              <select id="employeeSelect" required></select>
            </div>

            <div class="form-group">
              <label>;&#x633;&#x628;&#x628; &#x627;&#x644;&#x62e;&#x635;&#x645;:</label>
              <input type="text" id="deductionReason" required>
            </div>

            <div class="form-group">
              <label>;&#x645;&#x628;&#x644;&#x63a; &#x627;&#x644;&#x62e;&#x635;&#x645;:</label>
              <input type="number" id="deductionAmount" required>
            </div>

            <button type="submit">;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x62e;&#x635;&#x645;</button>
          </form>
        </div>

        <div class="employees-container" id="employees-container">
          <h2>;&#x642;&#x627;&#x626;&#x645;&#x629; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h2>
          <div class="search-export-container">
            <div class="search-container">
              <input type="text" id="employeeSearch" placeholder=";&#x628;&#x62d;&#x62b; &#x639;&#x646; &#x645;&#x648;&#x638;&#x641;...">
            </div>
            <div class="export-buttons">
              <button onclick="printReport()" class="print-btn">&#x637;&#x628;&#x627;&#x639;&#x629; &#x627;&#x644;&#x62a;&#x642;&#x631;&#x64a;&#x631;</button>
              <button onclick="exportToExcel()" class="excel-btn">&#x62a;&#x635;&#x62f;&#x64a;&#x631; &#x625;&#x644;&#x649; Excel</button>
            </div>
          </div>
          <table id="employeesTable">
            <thead>
              <tr>
                <th>;&#x627;&#x644;&#x627;&#x633;&#x645;</th>
                <th>;&#x627;&#x644;&#x645;&#x647;&#x646;&#x629;</th>
                <th>;&#x627;&#x644;&#x631;&#x627;&#x62a;&#x628; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;</th>
                <th>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x633;&#x643;&#x646;</th>
                <th>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x645;&#x648;&#x627;&#x635;&#x644;&#x627;&#x62a;</th>
                <th>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x625;&#x639;&#x627;&#x634;&#x629;</th>
                <th>;&#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</th>
                <th>;&#x627;&#x644;&#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a;</th>
                <th>;&#x625;&#x62c;&#x645;&#x627;&#x644;&#x64a; &#x627;&#x644;&#x631;&#x627;&#x62a;&#x628;</th>
                <th>;&#x627;&#x644;&#x62c;&#x627;&#x631;&#x627;&#x62a;&#x631;</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="salary-report-container" id="salary-report-container">
          <div class="form-group">
            <label>&#x615;&#x628;&#x627;&#x631; &#x627;&#x62e;&#x62a;&#x631;:</label>
            <select id="salaryReportEmployeeSelect">
            </select>
          </div>
          <div class="employee-details" id="employeeDetails">
            <div class="detail-row">
              <div class="detail-item">
                <label>;&#x627;&#x644;&#x645;&#x647;&#x646;&#x629;:</label>
                <span id="positionDisplay"></span>
              </div>
              <div class="detail-item">
                <label>;&#x627;&#x644;&#x631;&#x627;&#x62a;&#x628; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;:</label>
                <span id="baseSalaryDisplay"></span>
              </div>
            </div>

            <div class="detail-row">
              <div class="detail-item">
                <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x633;&#x643;&#x646;:</label>
                <span id="housingDisplay"></span>
              </div>
              <div class="detail-item">
                <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x645;&#x648;&#x627;&#x635;&#x644;&#x627;&#x62a;:</label>
                <span id="transportDisplay"></span>
              </div>
            </div>

            <div class="detail-row">
              <div class="detail-item">
                <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x625;&#x639;&#x627;&#x634;&#x629;:</label>
                <span id="foodDisplay"></span>
              </div>
              <div class="detail-item">
                <label>;&#x625;&#x62c;&#x645;&#x627;&#x644;&#x64a; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;:</label>
                <span id="overtimeDisplay" class="overtime"></span>
              </div>
            </div>

            <div class="detail-row">
              <div class="detail-item">
                <label>;&#x625;&#x62c;&#x645;&#x627;&#x644;&#x64a; &#x627;&#x644;&#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a;:</label>
                <span id="deductionsDisplay" class="deduction"></span>
              </div>
              <div class="detail-item total">
                <label>;&#x635;&#x627;&#x641;&#x64a; &#x627;&#x644;&#x631;&#x627;&#x62a;&#x628;:</label>
                <span id="totalSalaryDisplay"></span>
              </div>
            </div>
          </div>
          <div class="detail-row">
            <button id="addToReportBtn" class="add-to-report-btn">&#x627;&#x64a;&#x643;&#x645;&#x627; &#x627;&#x644;&#x645; &#x645;&#x627;&#x631;&#x628;&#x627; &#x627;&#x644;&#x631;&#x648;&#x627;&#x62a;&#x628;</button>
          </div>
          <div class="salary-report-header">
            <div class="form-group">
              <label>;&#x627;&#x644;&#x634;&#x647;&#x631;:</label>
              <select id="reportMonth">
                <option value="1">&#x64a;&#x646;&#x627;&#x64a;&#x631;</option>
                <option value="2">&#x641;&#x628;&#x631;&#x627;&#x64a;&#x631;</option>
                <option value="3">&#x645;&#x627;&#x631;&#x633;</option>
                <option value="4">&#x623;&#x628;&#x631;&#x64a;&#x644;</option>
                <option value="5">&#x645;&#x627;&#x64a;&#x648;</option>
                <option value="6">&#x64a;&#x648;&#x646;&#x64a;&#x648;</option>
                <option value="7">&#x64a;&#x648;&#x644;&#x64a;&#x648;</option>
                <option value="8">&#x623;&#x63a;&#x633;&#x637;&#x633;</option>
                <option value="9">&#x633;&#x628;&#x62a;&#x645;&#x628;&#x631;</option>
                <option value="10">&#x623;&#x643;&#x62a;&#x648;&#x628;&#x631;</option>
                <option value="11">&#x646;&#x648;&#x641;&#x645;&#x628;&#x631;</option>
                <option value="12">&#x62f;&#x64a;&#x633;&#x645;&#x628;&#x631;</option>
              </select>
            </div>
            <div class="form-group">
              <label>;&#x627;&#x644;&#x633;&#x646;&#x629;:</label>
              <input type="number" id="reportYear" min="2000" max="2100">
            </div>
            <div class="form-group">
              <label>;&#x627;&#x644;&#x641;&#x631;&#x639;:</label>
              <input type="text" id="branchName">
            </div>
            <div class="form-group">
              <label>;&#x627;&#x644;&#x645;&#x62f;&#x64a;&#x631; &#x627;&#x644;&#x645;&#x633;&#x624;&#x648;&#x644;:</label>
              <input type="text" id="managerName">
            </div>
          </div>
          <div class="monthly-report-table">
            <table id="monthlyReportTable">
              <thead>
                <tr>
                  <th>&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;</th>
                  <th>;&#x627;&#x644;&#x645;&#x647;&#x646;&#x629;</th>
                  <th>;&#x627;&#x644;&#x631;&#x627;&#x62a;&#x628; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;</th>
                  <th>;&#x627;&#x644;&#x628;&#x62f;&#x644;&#x627;&#x62a;</th>
                  <th>;&#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</th>
                  <th>;&#x627;&#x644;&#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a;</th>
                  <th>;&#x635;&#x627;&#x641;&#x64a; &#x627;&#x644;&#x631;&#x627;&#x62a;&#x628;</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="deductions-report" id="deductions-report">
          <h2>&#x62a;&#x642;&#x631;&#x64a;&#x631; &#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h2>
          <table id="deductionsReportTable">
            <thead>
              <tr>
                <th>&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;</th>
                <th>&#x633;&#x628;&#x628; &#x627;&#x644;&#x62e;&#x635;&#x645;</th>
                <th>&#x627;&#x644;&#x645;&#x628;&#x644;&#x63a;</th>
                <th>&#x627;&#x644;&#x62a;&#x627;&#x631;&#x64a;&#x62e;</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <div class="overtime-report" id="overtime-report">
          <h2>&#x62a;&#x642;&#x631;&#x64a;&#x631; &#x627;&#x636;&#x627;&#x641;&#x64a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h2>
          <table id="overtimeReportTable">
            <thead>
              <tr>
                <th>&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;</th>
                <th>&#x639;&#x62f;&#x62f; &#x627;&#x644;&#x633;&#x627;&#x639;&#x627;&#x62a;</th>
                <th>&#x642;&#x64a;&#x645;&#x629; &#x627;&#x644;&#x633;&#x627;&#x639;&#x629;</th>
                <th>&#x627;&#x644;&#x645;&#x628;&#x644;&#x63a;</th>
                <th>&#x627;&#x644;&#x62a;&#x627;&#x631;&#x64a;&#x62e;</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>class Employee {
  constructor(name, position, baseSalary, housingAllowance, transportAllowance, foodAllowance) {
    this.name = name;
    this.position = position;
    this.baseSalary = parseFloat(baseSalary);
    this.housingAllowance = parseFloat(housingAllowance);
    this.transportAllowance = parseFloat(transportAllowance);
    this.foodAllowance = parseFloat(foodAllowance);
    this.deductions = [];
    this.overtime = [];
  }
  addDeduction(reason, amount) {
    this.deductions.push({
      reason,
      amount: parseFloat(amount)
    });
  }
  addOvertime(hours, rate) {
    this.overtime.push({
      hours: parseFloat(hours),
      rate: parseFloat(rate),
      amount: parseFloat(hours) * parseFloat(rate)
    });
  }
  getTotalDeductions() {
    return this.deductions.reduce((total, deduction) => total + deduction.amount, 0);
  }
  getTotalOvertime() {
    return this.overtime.reduce((total, ot) => total + ot.amount, 0);
  }
  getTotalSalary() {
    return this.baseSalary + this.housingAllowance + this.transportAllowance + this.foodAllowance + this.getTotalOvertime() - this.getTotalDeductions();
  }
}
class Auth {
  constructor() {
    this.validCredentials = null;
    this.isLoggedIn = false;
  }
  login(username, password) {
    if (username.trim() && password.trim()) {
      this.isLoggedIn = true;
      return true;
    }
    return false;
  }
  logout() {
    this.isLoggedIn = false;
  }
}
class SalaryReport {
  constructor() {
    this.employees = [];
    this.month = new Date().getMonth() + 1;
    this.year = new Date().getFullYear();
    this.branchName = '';
    this.managerName = '';
  }
  addEmployee(employee) {
    if (!this.employees.find(e => e.name === employee.name)) {
      this.employees.push({
        ...employee,
        totalSalary: employee.getTotalSalary(),
        dateAdded: new Date()
      });
      return true;
    }
    return false;
  }
  setReportDetails(month, year, branch, manager) {
    this.month = month;
    this.year = year;
    this.branchName = branch;
    this.managerName = manager;
  }
  updateMonthlyReport() {
    const tbody = document.querySelector('#monthlyReportTable tbody');
    tbody.innerHTML = '';
    this.employees.forEach(emp => {
      const row = document.createElement('tr');
      const totalAllowances = emp.housingAllowance + emp.transportAllowance + emp.foodAllowance;
      row.innerHTML = `
        <td>${emp.name}</td>
        <td>${emp.position}</td>
        <td>${emp.baseSalary}</td>
        <td>${totalAllowances}</td>
        <td class="overtime">${emp.getTotalOvertime()}</td>
        <td class="deduction">${emp.getTotalDeductions()}</td>
        <td>${emp.getTotalSalary()}</td>
      `;
      tbody.appendChild(row);
    });
    document.querySelector('.monthly-report-table').scrollIntoView({
      behavior: 'smooth'
    });
  }
}
function printReport() {
  window.print();
}
function exportToExcel() {
  let data = [];
  data.push(['الاسم', 'المهنة', 'الراتب الأساسي', 'بدل السكن', 'بدل المواصلات', 'بدل الإعاشة', 'الإضافي', 'الخصومات', 'إجمالي الراتب']);
  employeeManager.employees.forEach(emp => {
    data.push([emp.name, emp.position, emp.baseSalary, emp.housingAllowance, emp.transportAllowance, emp.foodAllowance, emp.getTotalOvertime(), emp.getTotalDeductions(), emp.getTotalSalary()]);
  });
  let csvContent = "data:text/csv;charset=utf-8,\uFEFF" + data.map(row => row.join(',')).join('\n');
  const encodedUri = encodeURI(csvContent);
  const link = document.createElement("a");
  link.setAttribute("href", encodedUri);
  link.setAttribute("download", "employees_report.csv");
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}
function showSection(sectionId) {
  document.querySelectorAll('.toolbar-menu-link').forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('onclick')?.includes(sectionId)) {
      link.classList.add('active');
    }
  });
  document.querySelectorAll('.content-sections > div').forEach(div => {
    if (div.id === sectionId) {
      div.style.display = 'block';
    } else {
      div.style.display = 'none';
    }
  });
  document.querySelectorAll('.deductions-report, .overtime-report').forEach(div => {
    if (div.id === sectionId) {
      div.style.display = 'block';
    } else {
      div.style.display = 'none';
    }
  });
}
function editEmployeeHandler(index) {
  const employee = employeeManager.employees[index];
  document.getElementById('name').value = employee.name;
  document.getElementById('position').value = employee.position;
  document.getElementById('baseSalary').value = employee.baseSalary;
  document.getElementById('housingAllowance').value = employee.housingAllowance;
  document.getElementById('transportAllowance').value = employee.transportAllowance;
  document.getElementById('foodAllowance').value = employee.foodAllowance;
  showSection('form-container');
  const form = document.getElementById('employeeForm');
  const originalSubmitHandler = form.onsubmit;
  form.onsubmit = function (e) {
    e.preventDefault();
    const updatedEmployee = new Employee(document.getElementById('name').value, document.getElementById('position').value, document.getElementById('baseSalary').value, document.getElementById('housingAllowance').value, document.getElementById('transportAllowance').value, document.getElementById('foodAllowance').value);
    updatedEmployee.overtime = employee.overtime;
    updatedEmployee.deductions = employee.deductions;
    employeeManager.editEmployee(index, updatedEmployee);
    form.reset();
    form.onsubmit = originalSubmitHandler;
  };
}
function deleteEmployeeHandler(index) {
  if (confirm('هل أنت متأكد من حذف هذا الموظف؟')) {
    employeeManager.deleteEmployee(index);
  }
}
function updateSalaryReport(employeeIndex) {
  const employee = employeeManager.employees[employeeIndex];
  if (!employee) return;
  document.getElementById('positionDisplay').textContent = employee.position;
  document.getElementById('baseSalaryDisplay').textContent = employee.baseSalary;
  document.getElementById('housingDisplay').textContent = employee.housingAllowance;
  document.getElementById('transportDisplay').textContent = employee.transportAllowance;
  document.getElementById('foodDisplay').textContent = employee.foodAllowance;
  document.getElementById('overtimeDisplay').textContent = employee.getTotalOvertime();
  document.getElementById('deductionsDisplay').textContent = employee.getTotalDeductions();
  document.getElementById('totalSalaryDisplay').textContent = employee.getTotalSalary();
}
document.addEventListener('DOMContentLoaded', function () {
  const salaryReport = new SalaryReport();
  document.getElementById('reportMonth').value = new Date().getMonth() + 1;
  document.getElementById('reportYear').value = new Date().getFullYear();
  const reportControls = ['reportMonth', 'reportYear', 'branchName', 'managerName'];
  reportControls.forEach(control => {
    document.getElementById(control).addEventListener('change', () => {
      salaryReport.setReportDetails(document.getElementById('reportMonth').value, document.getElementById('reportYear').value, document.getElementById('branchName').value, document.getElementById('managerName').value);
    });
  });
  class EmployeeManager {
    constructor() {
      this.employees = [];
    }
    addEmployee(employee) {
      this.employees.push(employee);
      this.updateTable();
      this.updateEmployeeSelects();
      const form = document.querySelector('.form-container');
      form.style.transform = 'scale(0.95)';
      setTimeout(() => {
        form.style.transform = 'scale(1)';
      }, 200);
    }
    updateEmployeeSelects() {
      const selectors = ['employeeSelect', 'overtimeEmployeeSelect', 'salaryReportEmployeeSelect'];
      selectors.forEach(selectorId => {
        const select = document.getElementById(selectorId);
        if (select) {
          select.innerHTML = '';
          this.employees.forEach((employee, index) => {
            const option = document.createElement('option');
            option.value = index;
            option.textContent = employee.name;
            select.appendChild(option);
          });
        }
      });
    }
    addDeduction(employeeIndex, reason, amount) {
      if (employeeIndex >= 0 && employeeIndex < this.employees.length) {
        this.employees[employeeIndex].addDeduction(reason, amount);
        this.updateTable();
      }
    }
    addOvertime(employeeIndex, hours, rate) {
      if (employeeIndex >= 0 && employeeIndex < this.employees.length) {
        this.employees[employeeIndex].addOvertime(hours, rate);
        this.updateTable();
      }
    }
    deleteEmployee(index) {
      this.employees.splice(index, 1);
      this.updateTable();
      this.updateEmployeeSelects();
    }
    editEmployee(index, updatedEmployee) {
      this.employees[index] = updatedEmployee;
      this.updateTable();
      this.updateEmployeeSelects();
    }
    updateTable() {
      const tbody = document.querySelector('#employeesTable tbody');
      const searchTerm = document.getElementById('employeeSearch')?.value?.toLowerCase() || '';
      tbody.innerHTML = '';
      this.employees.filter(employee => employee.name.toLowerCase().includes(searchTerm) || employee.position.toLowerCase().includes(searchTerm)).forEach((employee, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${employee.name}</td>
            <td>${employee.position}</td>
            <td>${employee.baseSalary}</td>
            <td>${employee.housingAllowance}</td>
            <td>${employee.transportAllowance}</td>
            <td>${employee.foodAllowance}</td>
            <td class="overtime">${employee.getTotalOvertime()}</td>
            <td class="deduction">${employee.getTotalDeductions()}</td>
            <td>${employee.getTotalSalary()}</td>
            <td>
              <div class="action-buttons">
                <button class="edit-btn" onclick="editEmployeeHandler(${index})">تعديل</button>
                <button class="delete-btn" onclick="deleteEmployeeHandler(${index})">حذف</button>
              </div>
            </td>
          `;
        tbody.appendChild(row);
        row.addEventListener('mouseenter', () => {
          row.style.transform = 'scale(1.01)';
          row.style.transition = 'transform 0.3s ease';
        });
        row.addEventListener('mouseleave', () => {
          row.style.transform = 'scale(1)';
        });
      });
    }
    updateReportTables() {
      const deductionsBody = document.querySelector('#deductionsReportTable tbody');
      deductionsBody.innerHTML = '';
      this.employees.forEach(employee => {
        employee.deductions.forEach(deduction => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${employee.name}</td>
            <td>${deduction.reason}</td>
            <td>${deduction.amount}</td>
            <td>${new Date().toLocaleDateString('ar')}</td>
          `;
          deductionsBody.appendChild(row);
        });
      });
      const overtimeBody = document.querySelector('#overtimeReportTable tbody');
      overtimeBody.innerHTML = '';
      this.employees.forEach(employee => {
        employee.overtime.forEach(ot => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${employee.name}</td>
            <td>${ot.hours}</td>
            <td>${ot.rate}</td>
            <td>${ot.amount}</td>
            <td>${new Date().toLocaleDateString('ar')}</td>
          `;
          overtimeBody.appendChild(row);
        });
      });
    }
  }
  const auth = new Auth();
  document.querySelector('.toolbar').style.display = 'none';
  const loginForm = document.getElementById('loginForm');
  const loginError = document.getElementById('loginError');
  const loginContainer = document.getElementById('loginContainer');
  const mainContent = document.getElementById('mainContent');
  const originalLogin = auth.login;
  auth.login = function (...args) {
    const result = originalLogin.apply(this, args);
    if (result) {
      document.querySelector('.toolbar').style.display = 'flex';
    }
    return result;
  };
  loginForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    if (auth.login(username, password)) {
      loginContainer.classList.remove('active');
      mainContent.classList.add('active');
      loginError.style.display = 'none';
      loginForm.reset();
    } else {
      loginError.style.display = 'block';
    }
  });
  const inputs = loginForm.querySelectorAll('input');
  inputs.forEach(input => {
    input.addEventListener('focus', function () {
      this.previousElementSibling.style.color = '#3498db';
    });
    input.addEventListener('blur', function () {
      this.previousElementSibling.style.color = '#7f8c8d';
    });
  });
  loginContainer.style.opacity = '0';
  loginContainer.style.transform = 'translateY(20px)';
  setTimeout(() => {
    loginContainer.style.opacity = '1';
    loginContainer.style.transform = 'translateY(0)';
  }, 100);
  const employeeManager = new EmployeeManager();
  employeeManager.updateReportTables();
  const salaryReportSelect = document.getElementById('salaryReportEmployeeSelect');
  salaryReportSelect.addEventListener('change', function (e) {
    updateSalaryReport(e.target.value);
  });
  const overtimeHoursInput = document.getElementById('overtimeHours');
  const overtimeRateInput = document.getElementById('overtimeRate');
  const overtimeTotalDisplay = document.getElementById('overtimeTotalDisplay');
  function updateOvertimeTotal() {
    const hours = parseFloat(overtimeHoursInput.value) || 0;
    const rate = parseFloat(overtimeRateInput.value) || 0;
    const total = hours * rate;
    overtimeTotalDisplay.textContent = total.toFixed(2);
  }
  overtimeHoursInput.addEventListener('input', updateOvertimeTotal);
  overtimeRateInput.addEventListener('input', updateOvertimeTotal);
  const employeeForm = document.getElementById('employeeForm');
  employeeForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const employee = new Employee(document.getElementById('name').value, document.getElementById('position').value, document.getElementById('baseSalary').value, document.getElementById('housingAllowance').value, document.getElementById('transportAllowance').value, document.getElementById('foodAllowance').value);
    employeeManager.addEmployee(employee);
    employeeForm.reset();
  });
  const deductionForm = document.getElementById('deductionForm');
  deductionForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const employeeIndex = document.getElementById('employeeSelect').value;
    const reason = document.getElementById('deductionReason').value;
    const amount = document.getElementById('deductionAmount').value;
    employeeManager.addDeduction(employeeIndex, reason, amount);
    deductionForm.reset();
  });
  const overtimeForm = document.getElementById('overtimeForm');
  overtimeForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const employeeIndex = document.getElementById('overtimeEmployeeSelect').value;
    const hours = document.getElementById('overtimeHours').value;
    const rate = document.getElementById('overtimeRate').value;
    employeeManager.addOvertime(employeeIndex, hours, rate);
    overtimeForm.reset();
    overtimeTotalDisplay.textContent = '0';
  });
  const searchInput = document.getElementById('employeeSearch');
  searchInput.addEventListener('input', () => {
    employeeManager.updateTable();
  });
  const formInputs = document.querySelectorAll('.form-container input');
  formInputs.forEach(input => {
    input.addEventListener('focus', function () {
      this.closest('.form-group').querySelector('label').style.color = '#3498db';
    });
    input.addEventListener('blur', function () {
      this.closest('.form-group').querySelector('label').style.color = '#7f8c8d';
    });
  });
  const addToReportBtn = document.getElementById('addToReportBtn');
  addToReportBtn.addEventListener('click', function () {
    const selectedIndex = document.getElementById('salaryReportEmployeeSelect').value;
    const selectedEmployee = employeeManager.employees[selectedIndex];
    if (selectedEmployee) {
      if (salaryReport.addEmployee(selectedEmployee)) {
        alert('تم إضافة الموظف بنجاح لكشف الرواتب');
        salaryReport.updateMonthlyReport();
        document.querySelector('.monthly-report-table').scrollIntoView({
          behavior: 'smooth'
        });
      } else {
        alert('هذا الموظف مضاف بالفعل في كشف الرواتب');
      }
    }
  });
  employeeManager.updateTable();
});</script>
</body></html>

