<style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 20px;
      background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);
      min-height: 100vh;
      direction: rtl;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      padding-top: 80px;
    }

    h1 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 40px;
      font-size: 2.5em;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .profiles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
      padding: 20px;
    }

    .profile-card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      overflow: hidden;
      cursor: pointer;
    }

    .profile-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .profile-avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: linear-gradient(45deg, #3498db, #2ecc71);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2em;
      font-weight: bold;
      margin-left: 15px;
    }

    .profile-name {
      flex-grow: 1;
    }

    .profile-name h2 {
      margin: 0;
      color: #2c3e50;
      font-size: 1.4em;
    }

    .profile-name p {
      margin: 5px 0 0;
      color: #7f8c8d;
    }

    .profile-stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin: 20px 0;
      padding: 15px 0;
      border-top: 1px solid #eee;
      border-bottom: 1px solid #eee;
    }

    .stat {
      text-align: center;
    }

    .stat-value {
      font-size: 1.2em;
      font-weight: bold;
      color: #2c3e50;
    }

    .stat-label {
      font-size: 0.9em;
      color: #7f8c8d;
    }

    .profile-info {
      margin-top: 20px;
    }

    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
    }

    .info-label {
      color: #7f8c8d;
      margin-left: 10px;
      font-weight: bold;
    }

    .info-value {
      color: #2c3e50;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 1000;
      overflow-y: auto;
    }

    .modal-content {
      background: white;
      margin: 50px auto;
      padding: 20px;
      border-radius: 15px;
      max-width: 800px;
      position: relative;
    }

    .close-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 24px;
      cursor: pointer;
      color: #7f8c8d;
    }

    .tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
    }

    .tab {
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .tab.active {
      background: #3498db;
      color: white;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .detail-section {
      margin-bottom: 20px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 10px;
    }

    .detail-title {
      font-weight: bold;
      margin-bottom: 10px;
      color: #2c3e50;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }

    .detail-item {
      display: flex;
      justify-content: space-between;
      padding: 8px;
      background: white;
      border-radius: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px;
      text-align: right;
      border-bottom: 1px solid #eee;
    }

    th {
      background: #f8f9fa;
      font-weight: bold;
      color: #2c3e50;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
      animation: fadeIn 0.5s ease-out forwards;
    }

    .search-bar {
      max-width: 500px;
      margin: 0 auto 30px;
      position: relative;
    }

    .search-bar input {
      width: 100%;
      padding: 15px 20px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      font-size: 16px;
    }

    .search-bar input:focus {
      outline: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .search-filters {
      max-width: 500px;
      margin: 0 auto 20px;
      display: flex;
      gap: 10px;
    }

    .search-filters select {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      font-size: 16px;
      background: white;
    }

    .search-filters select:focus {
      outline: none;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .side-menu {
      display: none;
    }

    .header {
      background: linear-gradient(135deg, #3498db, #2ecc71);
      padding: 20px;
      color: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 200;
    }

    .header-content {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-title {
      font-size: 1.8em;
      font-weight: bold;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .header-buttons {
      display: flex;
      gap: 15px;
    }

    .header-btn {
      padding: 10px 20px;
      border: 2px solid white;
      border-radius: 8px;
      background: transparent;
      color: white;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .header-btn:hover {
      background: white;
      color: #3498db;
    }
</style>
    </head>
    <body>

    <div class="header">
      <div class="header-content">
        <div class="header-title">&#x628;&#x631;&#x646;&#x627;&#x645;&#x62c; &#x634;&#x624;&#x648;&#x646; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</div>
        <div class="header-buttons">
          {{-- <button class="header-btn" onclick="navigateToSection(&apos;employee-registration&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x645;&#x648;&#x638;&#x641; &#x62c;&#x62f;&#x64a;&#x62f;</button>
          <button class="header-btn" onclick="navigateToSection(&apos;overtime-registration&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x633;&#x627;&#x639;&#x627;&#x62a; &#x625;&#x636;&#x627;&#x641;&#x64a;&#x629;</button> --}}
          <a class="header-btn" href="{{route('company.dashboard')}}">الرجوع</a>
          <a class="header-btn" href="{{route('company.employees.create')}}">إضافة موظف</a>
        </div>
      </div>
    </div>

    <div class="container">

      <div class="search-bar fade-in">
        <input type="text" id="searchInput" placeholder="&#x628;&#x62d;&#x62b; &#x639;&#x646; &#x645;&#x648;&#x638;&#x641;...">
      </div>

      <div class="search-filters fade-in">
        <select id="branchFilter">
          <option value>&#x627;&#x644;&#x641;&#x631;&#x639;</option>
          @foreach ($branches as $item)
          <option value="{{ $item->id }}">{{ $item->name }}</option>

          @endforeach
        </select>
        <select id="categoryFilter" style="display: none">
          <option value>&#x627;&#x644;&#x62a;&#x635;&#x646;&#x64a;&#x641;</option>
          <option value="&#x645;&#x648;&#x638;&#x641;">&#x645;&#x648;&#x638;&#x641;</option>
          <option value="&#x645;&#x62f;&#x64a;&#x631;">&#x645;&#x62f;&#x64a;&#x631;</option>
          <option value="&#x645;&#x62f;&#x64a;&#x631; &#x639;&#x627;&#x645;">&#x645;&#x62f;&#x64a;&#x631; &#x639;&#x627;&#x645;</option>
        </select>
        <select id="departmentFilter" style="display: none">
          <option value>&#x627;&#x644;&#x627;&#x62f;&#x627;&#x631;&#x629;</option>
          <option value="&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x648;&#x627;&#x631;&#x62f; &#x627;&#x644;&#x628;&#x634;&#x631;&#x64a;&#x629;">&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x648;&#x627;&#x631;&#x62f; &#x627;&#x644;&#x628;&#x634;&#x631;&#x64a;&#x629;</option>
          <option value="&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x627;&#x644;&#x64a;&#x629;">&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x627;&#x644;&#x64a;&#x629;</option>
          <option value="&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x62a;&#x633;&#x648;&#x64a;&#x642;">&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x62a;&#x633;&#x648;&#x64a;&#x642;</option>
        </select>
      </div>

      <div class="profiles-grid" id="profilesGrid">
        <!-- Profiles will be inserted here by JavaScript -->
      </div>
    </div>

    <div id="profileModal" class="modal">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&#xd7;</span>
        <div class="tabs">
          <div class="tab active" onclick="switchTab(event, &apos;basicInfo&apos;)">&#x645;&#x639;&#x644;&#x648;&#x645;&#x627;&#x62a; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;&#x629;</div>
          <div class="tab" onclick="switchTab(event, &apos;leaves&apos;)">&#x627;&#x644;&#x627;&#x62c;&#x627;&#x632;&#x627;&#x62a;</div>
          <div class="tab" onclick="switchTab(event, &apos;overtime&apos;)">&#x627;&#x644;&#x633;&#x627;&#x639;&#x627;&#x62a; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;&#x629;</div>
          <div class="tab" onclick="switchTab(event, &apos;deductions&apos;)">&#x627;&#x644;&#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a;</div>
          <div class="tab" onclick="switchTab(event, &apos;loans&apos;)">&#x627;&#x644;&#x642;&#x631;&#x648;&#x636;</div>
          <div class="tab" onclick="switchTab(event, &apos;salary&apos;)">&#x627;&#x644;&#x631;&#x648;&#x627;&#x62a;&#x628;</div>
        </div>
        <div id="modalContent">
          <!-- Modal content will be inserted here by JavaScript -->
        </div>
      </div>
    </div>

    <div class="side-menu">
      <button class="side-menu-btn" onclick="navigateToSection(&apos;employee-registration&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x645;&#x648;&#x638;&#x641; &#x62c;&#x62f;&#x64a;&#x62f;</button>
      <button class="side-menu-btn" onclick="navigateToSection(&apos;overtime-registration&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x633;&#x627;&#x639;&#x627;&#x62a; &#x625;&#x636;&#x627;&#x641;&#x64a;&#x629;</button>
      <button class="side-menu-btn" onclick="navigateToSection(&apos;deduction-registration&apos;)">&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a;</button>
    </div>

    <script>class EmployeeProfiles {
      constructor() {
        this.employees = <?php echo json_encode($employees); ?>;
        console.log(this.employees);

        this.searchInput = document.getElementById('searchInput');
        this.branchFilter = document.getElementById('branchFilter');
        this.categoryFilter = document.getElementById('categoryFilter');
        this.departmentFilter = document.getElementById('departmentFilter');
        this.profilesGrid = document.getElementById('profilesGrid');
        this.searchInput.addEventListener('input', () => this.handleSearch());
        this.branchFilter.addEventListener('change', () => this.handleSearch());
        this.categoryFilter.addEventListener('change', () => this.handleSearch());
        this.departmentFilter.addEventListener('change', () => this.handleSearch());
        this.renderProfiles(this.employees);
      }
      getInitials(name) {
        return name.split(' ').map(n => n[0]).join('');
      }
      createProfileCard(employee) {
        return `
          <div class="profile-card fade-in" onclick="showEmployeeDetails(${JSON.stringify(employee).replace(/"/g, '&quot;')})">
            <div class="profile-header">
              <div class="profile-avatar">
                ${this.getInitials(employee.name)}
              </div>
              <div class="profile-name">
                <h2>${employee.name}</h2>
              </div>
            </div>

            <div class="profile-stats">
              <div class="stat">
                <div class="stat-value">0</div>
                <div class="stat-label">الخصومات</div>
              </div>
              <div class="stat">
                <div class="stat-value">0</div>
                <div class="stat-label">السلف</div>
              </div>
              <div class="stat">
                <div class="stat-value">${employee.leaves.length}</div>
                <div class="stat-label">إجازات</div>
              </div>
            </div>

            <div class="profile-info">
              <div class="info-item">
                <span class="info-label">الفرع:</span>
                <span class="info-value">${employee.branch.name}</span>
              </div>

              <div class="info-item">
                <span class="info-label">تاريخ التعيين:</span>
                <span class="info-value">${employee.hire_date}</span>
              </div>
            </div>
          </div>
        `;
      }
      renderProfiles(employees) {
        this.profilesGrid.innerHTML = employees.map(employee => this.createProfileCard(employee)).join('');
      }
      handleSearch() {

        const searchTerm = this.searchInput.value.toLowerCase();
        const branchFilter = this.branchFilter.value;
        console.log( branchFilter);
        const filteredEmployees = this.employees.filter(employee => {
          const matchesSearch = employee.name.toLowerCase().includes(searchTerm) ;
          const matchesBranch = !branchFilter || employee.branch_id === branchFilter;
          return matchesSearch && matchesBranch ;
        });
        this.renderProfiles(filteredEmployees);
      }
    }
    function showEmployeeDetails(employee) {
      const modal = document.getElementById('profileModal');
      const modalContent = document.getElementById('modalContent');
      modal.style.display = 'block';
      modal.dataset.employeeData = JSON.stringify(employee);
      switchTab(null, 'basicInfo');
    }
    function closeModal() {
      const modal = document.getElementById('profileModal');
      modal.style.display = 'none';
      modal.dataset.employeeData = '';
    }
    function switchTab(event, tabName) {
      if (event) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
      }
      const modalContent = document.getElementById('modalContent');
      const modal = document.getElementById('profileModal');
      const employee = JSON.parse(modal.dataset.employeeData);
      let content = '';
      switch (tabName) {
        case 'basicInfo':
          content = `
            <div class="detail-section">
              <div class="detail-title">المعلومات الشخصية</div>
              <div class="detail-grid">
                <div class="detail-item">
                  <span>الاسم:</span>
                  <span>${employee.name}</span>
                </div>
                <div class="detail-item">
                  <span>المنصب:</span>
                  <span>${employee.job}</span>
                </div>
                <div class="detail-item">
                  <span>الفرع:</span>
                  <span>${employee.branch.name}</span>
                </div>
                <div class="detail-item">
                  <span>الراتب:</span>
                  <span>${employee.basic_salary} ريال</span>
                </div>

                <div class="detail-item">
                  <span>رقم الهوية:</span>
                  <span>${employee.iqamaNumber}</span>
                </div>
              </div>
            </div>
          `;
          break;
        case 'leaves':
          content = `
            <div class="detail-section">
              <div class="detail-title">سجل الإجازات</div>
              <table>
                <thead>
                  <tr>
                    <th>النوع</th>
                    <th>تاريخ البداية</th>
                    <th>تاريخ النهاية</th>
                    <th>السبب</th>
                  </tr>
                </thead>
                <tbody>
                  ${employee.leaves.map(leave => `
                    <tr>
                      <td>${leave.type}</td>
                      <td>${leave.start_date}</td>
                      <td>${leave.end_date}</td>
                      <td>${leave.reason}</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          `;
          break;
        case 'overtime':
          content = `
            <div class="detail-section">
              <div class="detail-title">سجل الإضافي</div>
              <table>
                <thead>
                  <tr>
                    <th>التاريخ</th>
                    <th>عدد الساعات</th>
                    <th>المبلغ</th>
                  </tr>
                </thead>
                <tbody>
                  ${employee.overtimes.map(ot => `
                    <tr>
                      <td>${ot.date}</td>
                      <td>${ot.hours ?? ''}</td>
                      <td>${ot.total_amount} ريال</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          `;
          break;
        case 'deductions':
          content = `
            <div class="detail-section">
              <div class="detail-title">سجل الخصومات</div>
              <table>
                <thead>
                  <tr>
                    <th>التاريخ</th>
                    <th>السبب</th>
                    <th>المبلغ</th>
                  </tr>
                </thead>
                <tbody>
                  ${employee.deducations.map(deduction => `
                    <tr>
                      <td>${deduction.deduction_date}</td>
                      <td>${deduction.reason}</td>
                      <td>${deduction.deduction_value} ريال</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          `;
          break;
        case 'loans':
          content = `
            <div class="detail-section">
              <div class="detail-title">ملخص السلف</div>
              <div class="detail-grid">
                <div class="detail-item">
                  <span>إجمالي السلف:</span>
                  <span>${employee.loans.total} ريال</span>
                </div>
                <div class="detail-item">
                  <span>السلف المسددة:</span>
                  <span>${employee.loans.paid} ريال</span>
                </div>
                <div class="detail-item">
                  <span>السلف المتبقية:</span>
                  <span>${employee.loans.remaining} ريال</span>
                </div>
              </div>

              <div class="detail-title" style="margin-top: 20px">سجل السلف</div>
              <table>
                <thead>
                  <tr>
                    <th>التاريخ</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                  </tr>
                </thead>
                <tbody>
                  ${employee.loans.history.map(loan => `
                    <tr>
                      <td>${loan.date}</td>
                      <td>${loan.amount} ريال</td>
                      <td>${loan.status}</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          `;
          break;
        case 'salary':
        let total =
        Number(employee.basic_salary) +
        Number(employee.housing_allowance) +
        Number(employee.food_allowance) +
        Number(employee.transportation_allowance);
          content = `
            <div class="detail-section">
              <div class="detail-title">تفاصيل الراتب</div>
              <div class="detail-grid">
                <div class="detail-item">
                  <span>الراتب الأساسي:</span>
                  <span>${employee.basic_salary} ريال</span>
                </div>
                <div class="detail-item">
                  <span>بدل السكن:</span>
                  <span>${employee.housing_allowance} ريال</span>
                </div>
                <div class="detail-item">
                  <span>بدل الإعاشة:</span>
                  <span>${employee.food_allowance} ريال</span>
                </div>
                <div class="detail-item">
                  <span>بدل التنقل:</span>
                  <span>${employee.transportation_allowance} ريال</span>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1; background: #f8f9fa; font-weight: bold;">
                  <span>إجمالي الراتب:</span>
                  <span>
                    ${total}
                     ريال</span>
                </div>
              </div>
            </div>
          `;
          break;
      }
      modalContent.innerHTML = content;
    }
    function navigateToSection(section) {
      alert(`سيتم الانتقال إلى ${section === 'employee-registration' ? 'تسجيل موظف جديد' : section === 'overtime-registration' ? 'تسجيل ساعات إضافية' : 'تسجيل خصومات'}`);
    }
    new EmployeeProfiles();
    window.onclick = function (event) {
      const modal = document.getElementById('profileModal');
      if (event.target == modal) {
        modal.dataset.employeeData = '';
        closeModal();
      }
    };</script></body></html>
