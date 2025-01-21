<html>
    <head>
        @include('layouts.style.employee')


        <link rel="stylesheet" href="{{asset('employee/style.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 </head>
    <body>

   @include('layouts.navbaremployee')

    <div class="container">

        @yield('content')

      {{-- <div class="search-bar fade-in">
        <input type="text" id="searchInput" placeholder="&#x628;&#x62d;&#x62b; &#x639;&#x646; &#x645;&#x648;&#x638;&#x641;...">
      </div> --}}

      {{-- <div class="search-filters fade-in">
        <select id="branchFilter">
          <option value>&#x627;&#x644;&#x641;&#x631;&#x639;</option>
          <option value="&#x627;&#x644;&#x641;&#x631;&#x639; &#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;&#x64a;">&#x627;&#x644;&#x641;&#x631;&#x639; &#x627;&#x644;&#x631;&#x626;&#x64a;&#x633;&#x64a;</option>
          <option value="&#x627;&#x644;&#x641;&#x631;&#x639; &#x627;&#x644;&#x634;&#x645;&#x627;&#x644;&#x64a;">&#x627;&#x644;&#x641;&#x631;&#x639; &#x627;&#x644;&#x634;&#x645;&#x627;&#x644;&#x64a;</option>
          <option value="&#x627;&#x644;&#x641;&#x631;&#x639; &#x627;&#x644;&#x62c;&#x646;&#x648;&#x628;&#x64a;">&#x627;&#x644;&#x641;&#x631;&#x639; &#x627;&#x644;&#x62c;&#x646;&#x648;&#x628;&#x64a;</option>
        </select>
        <select id="categoryFilter">
          <option value>&#x627;&#x644;&#x62a;&#x635;&#x646;&#x64a;&#x641;</option>
          <option value="&#x645;&#x648;&#x638;&#x641;">&#x645;&#x648;&#x638;&#x641;</option>
          <option value="&#x645;&#x62f;&#x64a;&#x631;">&#x645;&#x62f;&#x64a;&#x631;</option>
          <option value="&#x645;&#x62f;&#x64a;&#x631; &#x639;&#x627;&#x645;">&#x645;&#x62f;&#x64a;&#x631; &#x639;&#x627;&#x645;</option>
        </select>
        <select id="departmentFilter">
          <option value>&#x627;&#x644;&#x627;&#x62f;&#x627;&#x631;&#x629;</option>
          <option value="&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x648;&#x627;&#x631;&#x62f; &#x627;&#x644;&#x628;&#x634;&#x631;&#x64a;&#x629;">&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x648;&#x627;&#x631;&#x62f; &#x627;&#x644;&#x628;&#x634;&#x631;&#x64a;&#x629;</option>
          <option value="&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x627;&#x644;&#x64a;&#x629;">&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x645;&#x627;&#x644;&#x64a;&#x629;</option>
          <option value="&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x62a;&#x633;&#x648;&#x64a;&#x642;">&#x627;&#x62f;&#x627;&#x631;&#x629; &#x627;&#x644;&#x62a;&#x633;&#x648;&#x64a;&#x642;</option>
        </select>
      </div> --}}

      {{-- <div class="profiles-grid" id="profilesGrid">
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

        </div>
      </div>
    </div> --}}


    <script>class EmployeeProfiles {
      constructor() {
        this.employees = [{
          name: 'أحمد محمد',
          position: 'مدير تطوير البرمجيات',
          department: 'تقنية المعلومات',
          email: 'ahmed@company.com',
          phone: '٠٥٠٠٠٠٠٠١',
          joinDate: '٢٠٢٠',
          projects: 15,
          tasks: 45,
          leaves: [{
            type: 'سنوية',
            startDate: '2023-01-15',
            endDate: '2023-01-20',
            status: 'مقبولة'
          }, {
            type: 'مرضية',
            startDate: '2023-03-10',
            endDate: '2023-03-12',
            status: 'مقبولة'
          }],
          overtime: [{
            date: '2023-04-15',
            hours: 3,
            amount: 150
          }, {
            date: '2023-05-20',
            hours: 2,
            amount: 100
          }],
          deductions: [{
            date: '2023-02-01',
            reason: 'تأخير',
            amount: 50
          }, {
            date: '2023-03-15',
            reason: 'غياب',
            amount: 200
          }],
          salary: 15000,
          nationality: 'سعودي',
          id: '1122334455',
          birthDate: '1985-05-15',
          loans: {
            total: 10000,
            paid: 6000,
            remaining: 4000,
            history: [{
              date: '2023-01-15',
              amount: 5000,
              status: 'مسددة'
            }, {
              date: '2023-06-01',
              amount: 5000,
              status: 'جارية'
            }]
          },
          branch: 'الرياض',
          category: 'إداري',
          salaryDetails: {
            basic: 10000,
            housing: 2500,
            food: 1000,
            transport: 1500,
            total: 15000
          }
        }, {
          name: 'فاطمة علي',
          position: 'محللة أعمال',
          department: 'تطوير الأعمال',
          email: 'fatima@company.com',
          phone: '٠٥٠٠٠٠٠٠٢',
          joinDate: '٢٠١٩',
          projects: 12,
          tasks: 38,
          leaves: [{
            type: 'سنوية',
            startDate: '2023-02-15',
            endDate: '2023-02-20',
            status: 'مقبولة'
          }, {
            type: 'مرضية',
            startDate: '2023-04-10',
            endDate: '2023-04-12',
            status: 'مقبولة'
          }],
          overtime: [{
            date: '2023-05-15',
            hours: 3,
            amount: 150
          }, {
            date: '2023-06-20',
            hours: 2,
            amount: 100
          }],
          deductions: [{
            date: '2023-03-01',
            reason: 'تأخير',
            amount: 50
          }, {
            date: '2023-04-15',
            reason: 'غياب',
            amount: 200
          }],
          salary: 12000,
          nationality: 'سعودي',
          id: '1122334456',
          birthDate: '1990-05-15',
          loans: {
            total: 10000,
            paid: 6000,
            remaining: 4000,
            history: [{
              date: '2023-01-15',
              amount: 5000,
              status: 'مسددة'
            }, {
              date: '2023-06-01',
              amount: 5000,
              status: 'جارية'
            }]
          },
          branch: 'الرياض',
          category: 'إداري',
          salaryDetails: {
            basic: 8000,
            housing: 2000,
            food: 1000,
            transport: 1000,
            total: 12000
          }
        }, {
          name: 'محمد خالد',
          position: 'مهندس برمجيات',
          department: 'تقنية المعلومات',
          email: 'mohammed@company.com',
          phone: '٠٥٠٠٠٠٠٠٣',
          joinDate: '٢٠٢١',
          projects: 8,
          tasks: 32,
          leaves: [{
            type: 'سنوية',
            startDate: '2023-03-15',
            endDate: '2023-03-20',
            status: 'مقبولة'
          }, {
            type: 'مرضية',
            startDate: '2023-05-10',
            endDate: '2023-05-12',
            status: 'مقبولة'
          }],
          overtime: [{
            date: '2023-06-15',
            hours: 3,
            amount: 150
          }, {
            date: '2023-07-20',
            hours: 2,
            amount: 100
          }],
          deductions: [{
            date: '2023-04-01',
            reason: 'تأخير',
            amount: 50
          }, {
            date: '2023-05-15',
            reason: 'غياب',
            amount: 200
          }],
          salary: 10000,
          nationality: 'سعودي',
          id: '1122334457',
          birthDate: '1995-05-15',
          loans: {
            total: 10000,
            paid: 6000,
            remaining: 4000,
            history: [{
              date: '2023-01-15',
              amount: 5000,
              status: 'مسددة'
            }, {
              date: '2023-06-01',
              amount: 5000,
              status: 'جارية'
            }]
          },
          branch: 'الرياض',
          category: 'إداري',
          salaryDetails: {
            basic: 6500,
            housing: 1800,
            food: 800,
            transport: 900,
            total: 10000
          }
        }, {
          name: 'نورة سعد',
          position: 'مديرة الموارد البشرية',
          department: 'الموارد البشرية',
          email: 'noura@company.com',
          phone: '٠٥٠٠٠٠٠٠٤',
          joinDate: '٢٠١٨',
          projects: 20,
          tasks: 60,
          leaves: [{
            type: 'سنوية',
            startDate: '2023-04-15',
            endDate: '2023-04-20',
            status: 'مقبولة'
          }, {
            type: 'مرضية',
            startDate: '2023-06-10',
            endDate: '2023-06-12',
            status: 'مقبولة'
          }],
          overtime: [{
            date: '2023-07-15',
            hours: 3,
            amount: 150
          }, {
            date: '2023-08-20',
            hours: 2,
            amount: 100
          }],
          deductions: [{
            date: '2023-05-01',
            reason: 'تأخير',
            amount: 50
          }, {
            date: '2023-06-15',
            reason: 'غياب',
            amount: 200
          }],
          salary: 18000,
          nationality: 'سعودي',
          id: '1122334458',
          birthDate: '1980-05-15',
          loans: {
            total: 10000,
            paid: 6000,
            remaining: 4000,
            history: [{
              date: '2023-01-15',
              amount: 5000,
              status: 'مسددة'
            }, {
              date: '2023-06-01',
              amount: 5000,
              status: 'جارية'
            }]
          },
          branch: 'الرياض',
          category: 'إداري',
          salaryDetails: {
            basic: 12000,
            housing: 3000,
            food: 1500,
            transport: 1500,
            total: 18000
          }
        }];
        // this.searchInput = document.getElementById('searchInput');
        // // this.branchFilter = document.getElementById('branchFilter');
        // this.categoryFilter = document.getElementById('categoryFilter');
        // this.departmentFilter = document.getElementById('departmentFilter');
        // this.profilesGrid = document.getElementById('profilesGrid');
        // this.searchInput.addEventListener('input', () => this.handleSearch());
        // this.branchFilter.addEventListener('change', () => this.handleSearch());
        // this.categoryFilter.addEventListener('change', () => this.handleSearch());
        // this.departmentFilter.addEventListener('change', () => this.handleSearch());
        // this.renderProfiles(this.employees);
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
                <p>${employee.position}</p>
              </div>
            </div>

            <div class="profile-stats">
              <div class="stat">
                <div class="stat-value">${employee.projects}</div>
                <div class="stat-label">مشاريع</div>
              </div>
              <div class="stat">
                <div class="stat-value">${employee.tasks}</div>
                <div class="stat-label">مهام</div>
              </div>
              <div class="stat">
                <div class="stat-value">${employee.leaves.length}</div>
                <div class="stat-label">إجازات</div>
              </div>
            </div>

            <div class="profile-info">
              <div class="info-item">
                <span class="info-label">القسم:</span>
                <span class="info-value">${employee.department}</span>
              </div>
              <div class="info-item">
                <span class="info-label">البريد:</span>
                <span class="info-value">${employee.email}</span>
              </div>
              <div class="info-item">
                <span class="info-label">الهاتف:</span>
                <span class="info-value">${employee.phone}</span>
              </div>
              <div class="info-item">
                <span class="info-label">تاريخ التعيين:</span>
                <span class="info-value">${employee.joinDate}</span>
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
        const categoryFilter = this.categoryFilter.value;
        const departmentFilter = this.departmentFilter.value;
        const filteredEmployees = this.employees.filter(employee => {
          const matchesSearch = employee.name.toLowerCase().includes(searchTerm) || employee.position.toLowerCase().includes(searchTerm) || employee.department.toLowerCase().includes(searchTerm);
          const matchesBranch = !branchFilter || employee.branch === branchFilter;
          const matchesCategory = !categoryFilter || employee.category === categoryFilter;
          const matchesDepartment = !departmentFilter || employee.department === departmentFilter;
          return matchesSearch && matchesBranch && matchesCategory && matchesDepartment;
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
                  <span>${employee.position}</span>
                </div>
                <div class="detail-item">
                  <span>القسم:</span>
                  <span>${employee.department}</span>
                </div>
                <div class="detail-item">
                  <span>الراتب:</span>
                  <span>${employee.salary} ريال</span>
                </div>
                <div class="detail-item">
                  <span>الجنسية:</span>
                  <span>${employee.nationality}</span>
                </div>
                <div class="detail-item">
                  <span>رقم الهوية:</span>
                  <span>${employee.id}</span>
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
                    <th>الحالة</th>
                  </tr>
                </thead>
                <tbody>
                  ${employee.leaves.map(leave => `
                    <tr>
                      <td>${leave.type}</td>
                      <td>${leave.startDate}</td>
                      <td>${leave.endDate}</td>
                      <td>${leave.status}</td>
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
                  ${employee.overtime.map(ot => `
                    <tr>
                      <td>${ot.date}</td>
                      <td>${ot.hours}</td>
                      <td>${ot.amount} ريال</td>
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
                  ${employee.deductions.map(deduction => `
                    <tr>
                      <td>${deduction.date}</td>
                      <td>${deduction.reason}</td>
                      <td>${deduction.amount} ريال</td>
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
          content = `
            <div class="detail-section">
              <div class="detail-title">تفاصيل الراتب</div>
              <div class="detail-grid">
                <div class="detail-item">
                  <span>الراتب الأساسي:</span>
                  <span>${employee.salaryDetails.basic} ريال</span>
                </div>
                <div class="detail-item">
                  <span>بدل السكن:</span>
                  <span>${employee.salaryDetails.housing} ريال</span>
                </div>
                <div class="detail-item">
                  <span>بدل الإعاشة:</span>
                  <span>${employee.salaryDetails.food} ريال</span>
                </div>
                <div class="detail-item">
                  <span>بدل التنقل:</span>
                  <span>${employee.salaryDetails.transport} ريال</span>
                </div>
                <div class="detail-item" style="grid-column: 1 / -1; background: #f8f9fa; font-weight: bold;">
                  <span>إجمالي الراتب:</span>
                  <span>${employee.salaryDetails.total} ريال</span>
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
    };</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{asset('employee/script.js')}}"></script>
{{-- <script src="{{asset('overtime.js')}}"></script> --}}
</body></html>
