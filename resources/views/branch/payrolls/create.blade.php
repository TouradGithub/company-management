
@extends('layouts.branch')

@section('content')


<div class="container">
    <h1>كشف الرواتب</h1>
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="color: green;text-align: center;">
            <h2 style="color: green;">  {{ session('success') }}</h2>

        </div>
    @endif
    <form id="salaryForm">
      <div class="form-group">
        <label for="month">الشهر:</label>
        <input type="month" id="month" required>
      </div>


      <div class="fields-selection">
        <h3>حدد الحقول المطلوبة في الكشف:</h3>
        <div class="checkbox-group">
          <label>
            <input type="checkbox" name="fields" value="basicSalary" checked>
            الراتب الأساسي
          </label>
          <label>
            <input type="checkbox" name="fields" value="transportation">
            بدل التنقل
          </label>
          <label>
            <input type="checkbox" name="fields" value="food">
            بدل الإعاشة
          </label>
          <label>
            <input type="checkbox" name="fields" value="overtime">
            الإضافي
          </label>
          <label>
            <input type="checkbox" name="fields" value="deductions">
            الخصومات
          </label>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">عرض الكشف</button>
        <button type="button" class="btn-secondary hidden" id="printBtn">طباعة</button>
      </div>
    </form>

    <div id="salaryTable" class="hidden">
      <h2>كشف الرواتب - <span id="reportTitle"></span></h2>
      <form action="{{route('branch.payrolls.store')}}" method="POST">@csrf
        <input type="hidden" id="monthYear" value="" name="date">
      <table>
        <thead id="tableHeader">
          <!-- سيتم إنشاء العناوين ديناميكياً -->
        </thead>
        <tbody id="tableBody">
          <!-- سيتم إنشاء الصفوف ديناميكياً -->
        </tbody>
      </table>
      <button type="submit" class="btn-secondary hidden" id="formvalidat">إضافة</button>
      </form>
    </div>

    <div id="approvedReports" class="approved-reports">
      <!-- سيتم إضافة الكشوفات المعتمدة هنا -->
    </div>
  </div>
@endsection
@section('js')

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const formvalidat = document.getElementById('formvalidat');
            const form = document.getElementById('salaryForm');
            const printBtn = document.getElementById('printBtn');
            const salaryTable = document.getElementById('salaryTable');
            const reportTitle = document.getElementById('reportTitle');
            const tableHeader = document.getElementById('tableHeader');
            const tableBody = document.getElementById('tableBody');
            const approvedReportsContainer = document.getElementById('approvedReports');

            // Map of branch values to their display names
            const fieldsMap = {
            basicSalary: 'الراتب الأساسي',
            workHours: 'عدد ساعات العمل',
            transportation: 'بدل التنقل',
            food: 'بدل الإعاشة',
            otherAllowances: 'بدلات أخرى',
            overtime: 'الإضافي',
            deductions: 'الخصومات'
            };

            // بيانات تجريبية للموظفين حسب الفروع
            const employeesByBranch = { };

            // مصفوفة لتخزين الكشوفات المعتمدة
            let approvedReports = JSON.parse(localStorage.getItem('approvedReports') || '[]');

            form.addEventListener('submit', (e) => {
            e.preventDefault();
            const month = document.getElementById('month').value;

            const selectedFields = Array.from(document.querySelectorAll('input[name="fields"]:checked'))
                .map(checkbox => checkbox.value);



            generateReport(month, selectedFields);
            });

            printBtn.addEventListener('click', () => {
                window.print();
            });

            function generateReport(month, selectedFields) {

                getEmployeesByBranch( month , selectedFields , (employees) => {});


            }

            function generateEmployeeReport(data ,month , selectedFields ) {

                reportTitle.textContent = `- ${month}`;

                // Generate table header
                let headerRow = '<tr><th>الرقم</th><th>اسم الموظف</th>';

                        headerRow += `<th>الراتب</th>`;

                    if (selectedFields.includes('transportation')) {
                        headerRow += `<th>بدل التنقل</th>`;
                    }

                    if (selectedFields.includes('food')) {
                        headerRow += `<th>بدل الاعاشه</th>`;
                    }

                    if (selectedFields.includes('overtime')) {
                        headerRow += `<th>الاضافي </th>`;
                    }

                    if (selectedFields.includes('deductions')) {
                        headerRow += `<th>الخصومات </th>`;
                    }

                // });
                headerRow += '<th>الإجمالي</th></tr>';
                tableHeader.innerHTML = headerRow;

                // Generate table body with employees from all selected branches
                let tbody = '';
                data.forEach(employee => {
                    let total = 0;
                    let row = `<tr>
                        <td>${employee.id}
                            <input type="hidden" name="employee[][id]" value="${employee.id}" />
                        </td> <td>${employee.name}</td>
                        <td >${employee.basic_salary}</td>
                         <input type="hidden" name="basic_salary[][amount]" value='${ parseFloat(employee.basic_salary)}' />`;


                        total += parseFloat(employee.basic_salary);
                        if (selectedFields.includes('transportation')) {
                            row += `<td>${employee.transportation_allowance}</td>`;
                            total += parseFloat(employee.transportation_allowance);
                          row += `  <input type="hidden" name="transportation[][amount]" value='${employee.transportation_allowance}' />`;
                        }

                        if (selectedFields.includes('food')) {
                            row += `<td>${employee.food_allowance}</td>`;
                            total += parseFloat(employee.food_allowance);
                          row += `  <input type="hidden" name="food[][amount]" value='${employee.food_allowance}' />`;

                        }
                        if (selectedFields.includes('overtime')) {
                            const overtimeAmount =
                                employee.overtimes && employee.overtimes.length > 0 && employee.overtimes[0].total_amount
                                    ? parseFloat(employee.overtimes[0].total_amount) // Ensure it's treated as a number
                                    : 0;

                            row += `<td style="color:green;">${overtimeAmount.toFixed(2)}</td>`;
                           row += ` <input type="hidden" name="overtime[][amount]" value='${overtimeAmount.toFixed(2)}' />`;

                            total += overtimeAmount;
                        }

                        if (selectedFields.includes('deductions')) {
                            const deductionsAmount =
                                employee.deducations && employee.deducations.length > 0 && employee.deducations[0].total_amount
                                    ? parseFloat(employee.deducations[0].total_amount) // Ensure it's treated as a number
                                    : 0;

                            row += `<td style="color:red;">${deductionsAmount.toFixed(2)}</td>`;
                            row +=` <input type="hidden" name="deductions[][amount]" value='${deductionsAmount.toFixed(2)}' />`;

                            total += deductionsAmount;
                        }

                    row += `<td>${total}</td><input type="hidden" name="total[][amount]" value='${total}' /></tr>`;

                    tbody += row;
                });
                if (data.length == 0) {
                    tbody = `<tr>
                                <td colspan="${selectedFields.length + 4}" style="text-align: center; color: gray;">
                                    لا توجد بيانات لعرضها
                                </td>
                            </tr>`;
                }
                tableBody.innerHTML = tbody;
                document.getElementById('monthYear').value =month;
                if(data.length != 0){
                    formvalidat.classList.remove('hidden');
                }

                salaryTable.classList.remove('hidden');
            }
            function getEmployeesByBranch( month,selectedFields ,  callback) {

                $.ajax({
                    url: '/branch/employees-by-branch',
                    method: 'GET',
                    data: { month:month },
                    success: function(response) {
                        generateEmployeeReport(response , month ,  selectedFields);

                    },
                    error: function(xhr, status, error) {
                        console.error("There was an error fetching the employees: ", error);
                    }
                    });
            }


            function approveReport(month, branches, selectedFields) {
            const date = new Date(month);
                // const gregorianDate = new Intl.DateTimeFormat('en-US', {
                //     month: 'long',
                //     year: 'numeric'
                // }).format(date);

            // const branchNames = branches.map(b => branchesMap[b]).join(' - ');

                const report = {
                    id: Date.now(),
                    month,
                    gregorianDate,
                    branches,
                    branchNames,
                    selectedFields,
                    employees: branches.flatMap(branch => employeesByBranch[branch].map(emp => ({...emp, branch}))),
                    approvalDate: new Date().toISOString()
                };

            approvedReports.push(report);
            localStorage.setItem('approvedReports', JSON.stringify(approvedReports));
            renderApprovedReports();
            }

            function generateReportTable(report) {
            let tableHTML = '<table><thead><tr><th>الرقم</th><th>اسم الموظف</th><th>الفرع</th>';
            report.selectedFields.forEach(field => {
                tableHTML += `<th>${fieldsMap[field]}</th>`;
            });
            tableHTML += '<th>الإجمالي</th></tr></thead><tbody>';

            report.employees.forEach(employee => {
                let total = 0;
                let row = `<tr>
                <td>${employee.id}</td>
                <td>${employee.name}</td>`;

                report.selectedFields.forEach(field => {
                const value = employee[field];
                if (field !== 'deductions') {
                    total += value;
                } else {
                    total -= value;
                }
                row += `<td>${value.toLocaleString('ar-SA')}</td>`;
                });

                row += `<td>${total.toLocaleString('ar-SA')}</td></tr>`;
                tableHTML += row;
            });

            tableHTML += '</tbody></table>';
            return tableHTML;
            }

            function renderApprovedReports() {
            approvedReportsContainer.innerHTML = `
                <h2>الكشوفات المعتمدة</h2>
                ${approvedReports.map(report => `
                <div class="approved-report" data-id="${report.id}">
                    <div class="approved-report-header">
                    <div class="approved-report-info">
                        <div>${report.gregorianDate} - ${report.branchNames}</div>
                        <div class="approval-info">تم الاعتماد في: ${new Date(report.approvalDate).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                        })}</div>
                    </div>
                    <div class="report-actions">
                        <button class="btn-primary btn-edit" onclick="editReport(${report.id})">تعديل</button>
                        <button class="btn-primary btn-delete" onclick="deleteReport(${report.id})">حذف</button>
                    </div>
                    </div>
                    ${generateReportTable(report)}
                </div>
                `).join('')}
            `;
            }

            window.editReport = function(reportId) {
            const report = approvedReports.find(r => r.id === reportId);
            if (report) {
                document.getElementById('month').value = report.month;
                // Select all branches and fields from the report
                document.querySelectorAll('input[name="branches"]').forEach(checkbox => {
                checkbox.checked = report.branches.includes(checkbox.value);
                });
                document.querySelectorAll('input[name="fields"]').forEach(checkbox => {
                checkbox.checked = report.selectedFields.includes(checkbox.value);
                });

                // Delete the old report
                deleteReport(reportId);

                // Generate the new report
                generateReport(report.month, report.branches, report.selectedFields);
            }
            };

            window.deleteReport = function(reportId) {
            approvedReports = approvedReports.filter(r => r.id !== reportId);
            localStorage.setItem('approvedReports', JSON.stringify(approvedReports));
            renderApprovedReports();
            };

            // Initialize approved reports display
            renderApprovedReports();
        });

    </script>



  @endsection
