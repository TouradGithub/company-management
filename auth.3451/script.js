let currentUser = null;

function handleLogin(e) {
  e.preventDefault();
  
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const userType = document.getElementById('userType').value;

  // Simple authentication demo - in real app, this would be an API call
  if (username && password) {
    currentUser = {
      username,
      type: userType,
      name: userType === 'admin' ? 'مدير النظام' : 'مدير الشركة'
    };

    // Show/hide relevant menu items based on user type
    updateMenuAccess();
    
    // Hide login page and show main app
    document.getElementById('loginPage').classList.add('hidden');
    document.getElementById('mainApp').classList.remove('hidden');
    
    // Update user display name
    const userDisplayName = document.querySelector('.user-btn span');
    if (userDisplayName) {
      userDisplayName.textContent = currentUser.name;
    }
  }
}

function updateMenuAccess() {
  const menuItems = document.querySelectorAll('.menu-items .menu-item');
  
  menuItems.forEach(item => {
    const section = item.getAttribute('data-section');
    
    // Company admin can't access settings
    if (currentUser.type === 'company' && section === 'settings') {
      item.style.display = 'none';
    }
  });
}

function logout() {
  currentUser = null;
  document.getElementById('mainApp').classList.add('hidden');
  document.getElementById('loginPage').classList.remove('hidden');
  document.getElementById('loginForm').reset();
}

document.addEventListener('DOMContentLoaded', () => {
  // Add login form handler
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', handleLogin);
  }
  
  // Update logout functionality
  const logoutLink = document.querySelector('a[href="https://example.com/logout"]');
  if (logoutLink) {
    logoutLink.addEventListener('click', (e) => {
      e.preventDefault();
      logout();
    });
  }
  
  function getStatistics() {
    const stats = {
      totalOvertime: savedEntries.length,
      totalEmployees: new Set(savedEntries.map(entry => entry.employeeId)).size,
      totalBranches: new Set(savedEntries.flatMap(entry => entry.branches)).size,
      recentTrend: {
        overtime: calculateTrend('overtime'),
        employees: calculateTrend('employees'),
        branches: calculateTrend('branches')
      }
    };
    return stats;
  }

  function calculateTrend(type) {
    // Get entries from last 30 days
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    
    const recentEntries = savedEntries.filter(entry => 
      new Date(entry.date) >= thirtyDaysAgo
    );
    
    // Compare first half with second half
    const midPoint = new Date(thirtyDaysAgo.getTime() + (15 * 24 * 60 * 60 * 1000));
    const firstHalf = recentEntries.filter(entry => new Date(entry.date) < midPoint);
    const secondHalf = recentEntries.filter(entry => new Date(entry.date) >= midPoint);
    
    let trend = 0;
    switch(type) {
      case 'overtime':
        trend = secondHalf.length - firstHalf.length;
        break;
      case 'employees':
        trend = new Set(secondHalf.map(e => e.employeeId)).size - 
                new Set(firstHalf.map(e => e.employeeId)).size;
        break;
      case 'branches':
        trend = new Set(secondHalf.flatMap(e => e.branches)).size - 
                new Set(firstHalf.flatMap(e => e.branches)).size;
        break;
    }
    
    return {
      direction: trend >= 0 ? 'up' : 'down',
      percentage: Math.abs(trend) * 10
    };
  }

  function updateStatistics() {
    const statsContainer = document.getElementById('statsContainer');
    if (!statsContainer) return;
    
    const stats = getStatistics();
    
    statsContainer.innerHTML = `
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="bi bi-clock"></i>
          </div>
          <div class="stat-value">${stats.totalOvertime}</div>
          <div class="stat-label">إجمالي الإضافي</div>
          <div class="stat-trend ${stats.recentTrend.overtime.direction === 'up' ? 'trend-up' : 'trend-down'}">
            <i class="bi bi-arrow-${stats.recentTrend.overtime.direction}"></i>
            ${stats.recentTrend.overtime.percentage}% في آخر 30 يوم
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">
            <i class="bi bi-people"></i>
          </div>
          <div class="stat-value">${stats.totalEmployees}</div>
          <div class="stat-label">عدد الموظفين</div>
          <div class="stat-trend ${stats.recentTrend.employees.direction === 'up' ? 'trend-up' : 'trend-down'}">
            <i class="bi bi-arrow-${stats.recentTrend.employees.direction}"></i>
            ${stats.recentTrend.employees.percentage}% في آخر 30 يوم
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">
            <i class="bi bi-building"></i>
          </div>
          <div class="stat-value">${stats.totalBranches}</div>
          <div class="stat-label">عدد الفروع</div>
          <div class="stat-trend ${stats.recentTrend.branches.direction === 'up' ? 'trend-up' : 'trend-down'}">
            <i class="bi bi-arrow-${stats.recentTrend.branches.direction}"></i>
            ${stats.recentTrend.branches.percentage}% في آخر 30 يوم
          </div>
        </div>
      </div>
    `;
  }

  const menuItems = document.querySelectorAll('.menu-item');
  const contentSections = document.querySelectorAll('.content-section');

  function switchContent(sectionId) {
    // Hide all sections
    contentSections.forEach(section => {
      section.classList.remove('active');
    });
    
    // Remove active class from all menu items
    menuItems.forEach(item => {
      item.classList.remove('active');
    });
    
    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
      selectedSection.classList.add('active');
    }
    
    // Add active class to selected menu item
    const selectedMenuItem = document.querySelector(`[data-section="${sectionId}"]`);
    if (selectedMenuItem) {
      selectedMenuItem.classList.add('active');
    }
  }

  // Add click handlers to menu items
  menuItems.forEach(item => {
    item.addEventListener('click', () => {
      const sectionId = item.getAttribute('data-section');
      switchContent(sectionId);
    });
  });

  // Show default section
  switchContent('overtime-form');

  const form = document.getElementById('overtimeForm');
  const employeesSelect = document.getElementById('employees');
  const employeeDetails = document.querySelector('.employee-details');
  const fixedAmountSection = document.getElementById('fixedAmountSection');
  const hoursSection = document.getElementById('hoursSection');
  const dailyRateSection = document.getElementById('dailyRateSection');
  const totalAmount = document.getElementById('totalAmount');
  const entriesContainer = document.getElementById('entriesContainer');
  const resetBtn = document.getElementById('resetBtn');

  // Initialize Select2 for multiple select
  $('#branches').select2({
    placeholder: 'اختر الفروع',
    dir: 'rtl',
    language: 'ar'
  });

  $('#employees').select2({
    placeholder: 'اختر الموظفين',
    dir: 'rtl',
    language: 'ar'
  });

  // Set today's date as default
  document.getElementById('date').valueAsDate = new Date();

  // Sample employees data
  const employees = [
    {
      id: 1,
      name: 'أحمد محمد',
      iqamaNumber: '2345678901',
      basicSalary: 5000,
      branch: 'main'
    },
    {
      id: 2,
      name: 'سارة أحمد',
      iqamaNumber: '3456789012',
      basicSalary: 4500,
      branch: 'north'
    },
    {
      id: 3,
      name: 'محمد علي',
      iqamaNumber: '4567890123',
      basicSalary: 5500,
      branch: 'south'
    },
    {
      id: 4,
      name: 'فاطمة حسن',
      iqamaNumber: '5678901234',
      basicSalary: 4800,
      branch: 'main'
    }
  ];

  // Load saved entries from localStorage
  let savedEntries = JSON.parse(localStorage.getItem('overtimeEntries') || '[]');

  // Handle branch selection change
  $('#branches').on('change', function() {
    const selectedBranches = $(this).val();
    
    // Filter employees based on selected branches
    const filteredEmployees = employees.filter(emp => 
      selectedBranches.includes(emp.branch)
    );

    // Update employees dropdown
    employeesSelect.innerHTML = '';
    filteredEmployees.forEach(emp => {
      const option = document.createElement('option');
      option.value = emp.id;
      option.textContent = emp.name;
      employeesSelect.appendChild(option);
    });

    // Trigger Select2 update
    $('#employees').trigger('change');
  });

  // Handle employee selection change
  $('#employees').on('change', function() {
    const selectedIds = $(this).val();
    
    if (selectedIds && selectedIds.length > 0) {
      const selectedEmployee = employees.find(emp => emp.id === parseInt(selectedIds[0]));
      if (selectedEmployee) {
        document.getElementById('iqamaNumber').textContent = selectedEmployee.iqamaNumber;
        document.getElementById('basicSalary').textContent = selectedEmployee.basicSalary.toLocaleString('ar-SA') + ' ريال';
        document.getElementById('hourlyRate').textContent = 
          ((selectedEmployee.basicSalary / 30) / 8).toFixed(2) + ' ريال';
        employeeDetails.classList.remove('hidden');
      }
    } else {
      employeeDetails.classList.add('hidden');
    }
  });

  // Toggle between overtime types
  document.querySelectorAll('input[name="overtimeType"]').forEach(radio => {
    radio.addEventListener('change', (e) => {
      // Hide all sections first
      fixedAmountSection.classList.add('hidden');
      hoursSection.classList.add('hidden');
      dailyRateSection.classList.add('hidden');

      // Show the selected section
      switch(e.target.value) {
        case 'fixed':
          fixedAmountSection.classList.remove('hidden');
          break;
        case 'hours':
          hoursSection.classList.remove('hidden');
          break;
        case 'daily':
          dailyRateSection.classList.remove('hidden');
          break;
      }
      calculateTotal();
    });
  });

  // Calculate total amount
  function calculateTotal() {
    const selectedEmployeeIds = $('#employees').val();
    let amount = 0;
    const selectedEmployee = employees.find(emp => emp.id === parseInt(selectedEmployeeIds[0]));
    if (!selectedEmployee) return;

    const overtimeType = document.querySelector('input[name="overtimeType"]:checked').value;

    switch(overtimeType) {
      case 'fixed':
        amount = parseFloat(document.getElementById('fixedAmount').value) || 0;
        break;
      case 'hours':
        const hours = parseFloat(document.getElementById('hours').value) || 0;
        const multiplier = parseFloat(document.querySelector('input[name="hourMultiplier"]:checked').value);
        const hourlyRate = (selectedEmployee.basicSalary / 30) / 8;
        amount = hours * hourlyRate * multiplier;
        break;
      case 'daily':
        const days = parseFloat(document.getElementById('days').value) || 0;
        const dailyRate = parseFloat(document.getElementById('dailyRate').value) || 0;
        amount = days * dailyRate;
        break;
    }

    totalAmount.textContent = amount.toLocaleString('ar-SA');
  }

  // Add event listeners for input changes
  document.getElementById('fixedAmount').addEventListener('input', calculateTotal);
  document.getElementById('hours').addEventListener('input', calculateTotal);
  document.getElementById('days').addEventListener('input', calculateTotal);
  document.getElementById('dailyRate').addEventListener('input', calculateTotal);

  // Add event listener for multiplier change
  document.querySelectorAll('input[name="hourMultiplier"]').forEach(radio => {
    radio.addEventListener('change', calculateTotal);
  });

  function renderEntries() {
    // Create table structure
    const tableHTML = `
      <table class="records-table">
        <thead>
          <tr>
            <th>التاريخ</th>
            <th>اسم الموظف</th>
            <th>نوع الإضافي</th>
            <th>التفاصيل</th>
            <th>المبلغ</th>
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          ${savedEntries.map(entry => {
            const formattedDate = entry.date ? new Date(entry.date).toLocaleDateString('ar-SA') : '-';
            const formattedAmount = entry.amount ? entry.amount.toLocaleString('ar-SA') : '0';
            
            let details = '';
            switch(entry.overtimeType) {
              case 'fixed':
                details = `مبلغ ثابت: ${entry.fixedAmount} ريال`;
                break;
              case 'hours':
                details = `${entry.hours} ساعة × ${entry.hourMultiplier}`;
                break;
              case 'daily':
                details = `${entry.days} يوم × ${entry.dailyRate} ريال`;
                break;
            }

            const overtimeTypeText = {
              'fixed': 'مبلغ ثابت',
              'hours': 'ساعات',
              'daily': 'يومي'
            }[entry.overtimeType] || '';

            return `
              <tr>
                <td>${formattedDate}</td>
                <td>${entry.employeeName || '-'}</td>
                <td>${overtimeTypeText}</td>
                <td>${details}</td>
                <td>${formattedAmount} ريال</td>
                <td>
                  <div class="actions">
                    <button class="btn-primary btn-edit" onclick="editEntry('${entry.id}')">تعديل</button>
                    <button class="btn-primary btn-delete" onclick="deleteEntry('${entry.id}')">حذف</button>
                  </div>
                </td>
              </tr>
            `;
          }).join('')}
        </tbody>
      </table>
    `;

    entriesContainer.innerHTML = tableHTML;
    updateStatistics();
  }

  window.editEntry = function(entryId) {
    const entry = savedEntries.find(e => e.id === entryId);
    if (!entry) return;

    try {
      document.getElementById('date').value = entry.date || '';
      $('#branches').val(entry.branches || []).trigger('change');
      $('#employees').val([entry.employeeId]).trigger('change');
      
      const overtimeTypeRadio = document.querySelector(`input[name="overtimeType"][value="${entry.overtimeType}"]`);
      if (overtimeTypeRadio) {
        overtimeTypeRadio.checked = true;
        overtimeTypeRadio.dispatchEvent(new Event('change'));
      }
      
      // Hide all sections first
      fixedAmountSection.classList.add('hidden');
      hoursSection.classList.add('hidden');
      dailyRateSection.classList.add('hidden');

      // Show and populate the relevant section
      switch(entry.overtimeType) {
        case 'fixed':
          fixedAmountSection.classList.remove('hidden');
          document.getElementById('fixedAmount').value = entry.fixedAmount || '';
          break;
        case 'hours':
          hoursSection.classList.remove('hidden');
          document.getElementById('hours').value = entry.hours || '';
          const multiplierRadio = document.querySelector(`input[name="hourMultiplier"][value="${entry.hourMultiplier}"]`);
          if (multiplierRadio) multiplierRadio.checked = true;
          break;
        case 'daily':
          dailyRateSection.classList.remove('hidden');
          document.getElementById('days').value = entry.days || '';
          document.getElementById('dailyRate').value = entry.dailyRate || '';
          break;
      }
      
      calculateTotal();
      deleteEntry(entryId);
    } catch (error) {
      console.error('Error editing entry:', error);
    }
  };

  window.deleteEntry = function(entryId) {
    if (!entryId) return;
    savedEntries = savedEntries.filter(e => e.id !== entryId);
    localStorage.setItem('overtimeEntries', JSON.stringify(savedEntries));
    renderEntries();
  };

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const selectedEmployeeIds = $('#employees').val();
    
    if (!selectedEmployeeIds || selectedEmployeeIds.length === 0) return;

    try {
      selectedEmployeeIds.forEach(empId => {
        const selectedEmployee = employees.find(emp => emp.id === parseInt(empId));
        if (!selectedEmployee) return;
        
        const entry = {
          id: Date.now() + Math.random().toString(36).substr(2, 9),
          date: document.getElementById('date').value,
          branches: $('#branches').val() || [],
          employeeId: selectedEmployee.id,
          employeeName: selectedEmployee.name,
          overtimeType: document.querySelector('input[name="overtimeType"]:checked')?.value || 'fixed',
          amount: parseFloat(totalAmount.textContent.replace(/[^\d.-]/g, '')) || 0,
          fixedAmount: document.getElementById('fixedAmount').value || '',
          hours: document.getElementById('hours').value || '',
          hourMultiplier: document.querySelector('input[name="hourMultiplier"]:checked')?.value || '1',
          days: document.getElementById('days').value || '',
          dailyRate: document.getElementById('dailyRate').value || ''
        };

        savedEntries.push(entry);
      });

      localStorage.setItem('overtimeEntries', JSON.stringify(savedEntries));
      renderEntries();
      form.reset();
      $('#branches').val(null).trigger('change');
      $('#employees').val(null).trigger('change');
      document.getElementById('date').valueAsDate = new Date();
      employeeDetails.classList.add('hidden');
      calculateTotal();
    } catch (error) {
      console.error('Error saving entry:', error);
    }
  });

  resetBtn.addEventListener('click', () => {
    form.reset();
    document.getElementById('date').valueAsDate = new Date();
    employeeDetails.classList.add('hidden');
    calculateTotal();
  });

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

  // Add event listener for Escape key to close dropdown
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.getElementById('userDropdown').classList.remove('show');
    }
  });

  // Initial render
  renderEntries();
  updateStatistics();
});