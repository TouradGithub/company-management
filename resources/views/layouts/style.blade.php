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
      margin-bottom: 20px;
    }

    .form-container, .deduction-container, .overtime-container, .employees-container, .salary-report-container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 10px;
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
      margin-bottom: 5px;
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
        margin-top:150px;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      border-radius: 10px;
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
      margin-bottom: 5px;
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
      padding: 5px;
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




    .logout-item {
  text-align: right; /* Aligns the logout button to the right */
}

.logout-item form button {
  background: none;
  border: none;
  color: #ecf0f1;
  font-size: 16px;
  text-decoration: none;
  /* padding: 10px 20px; */
  cursor: pointer;
  transition: background-color 0.3s;
}
</style>
