
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
