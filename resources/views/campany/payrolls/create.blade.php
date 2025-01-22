
@extends('layouts.overtime')

@section('content')


<div class="container">
    <h1>كشف الرواتب</h1>

    <form id="salaryForm">
      <div class="form-group">
        <label for="month">الشهر:</label>
        <input type="month" id="month" required>
      </div>

      <div class="form-group">
        <label>الفروع:</label>
        <div class="checkbox-group branches-group">
            @foreach ($branches as $item)
                <label>
                    <input type="checkbox" name="branches" value="{{ $item->id}}">
                     {{ $item->name}}
                </label>
            @endforeach

        </div>
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
      <table>
        <thead id="tableHeader">
          <!-- سيتم إنشاء العناوين ديناميكياً -->
        </thead>
        <tbody id="tableBody">
          <!-- سيتم إنشاء الصفوف ديناميكياً -->
        </tbody>
      </table>
    </div>

    <div id="approvedReports" class="approved-reports">
      <!-- سيتم إضافة الكشوفات المعتمدة هنا -->
    </div>
  </div>
@endsection
@section('js')

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('salaryForm');
            const printBtn = document.getElementById('printBtn');
            const salaryTable = document.getElementById('salaryTable');
            const reportTitle = document.getElementById('reportTitle');
            const tableHeader = document.getElementById('tableHeader');
            const tableBody = document.getElementById('tableBody');
            const approvedReportsContainer = document.getElementById('approvedReports');

            // Map of branch values to their display names
            // const branchesMap = {
            // main: 'الفرع الرئيسي',
            // north: 'الفرع الشمالي',
            // south: 'الفرع الجنوبي'
            // };

            // Map of field names to their display names
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
            const selectedBranches = Array.from(document.querySelectorAll('input[name="branches"]:checked'))
                .map(checkbox => checkbox.value);
                console.log(selectedBranches);
            const selectedFields = Array.from(document.querySelectorAll('input[name="fields"]:checked'))
                .map(checkbox => checkbox.value);

            if (selectedBranches.length === 0) {
                alert('الرجاء اختيار فرع واحد على الأقل');
                return;
            }

            generateReport(month, selectedBranches, selectedFields);
            });

            printBtn.addEventListener('click', () => {
                window.print();
            });

            function generateReport(month, branches, selectedFields) {

                getEmployeesByBranch(branches, month , selectedFields , (employees) => {});


            }

            function generateEmployeeReport(data , selectedFields ) {

                reportTitle.textContent = `- `;

                // Generate table header
                let headerRow = '<tr><th>الرقم</th><th>اسم الموظف</th><th>الفرع</th>';

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
                        <td>${employee.id}</td> <td>${employee.name}</td> <td>${employee.branch.name}</td>
                        <td >${employee.basic_salary}</td>`;
                        total += parseFloat(employee.basic_salary);
                        if (selectedFields.includes('transportation')) {
                            row += `<td>${employee.transportation_allowance}</td>`;
                            total += parseFloat(employee.transportation_allowance);
                        }

                        if (selectedFields.includes('food')) {
                            row += `<td>${employee.food_allowance}</td>`;
                            total += parseFloat(employee.food_allowance);
                        }
                        if (selectedFields.includes('overtime')) {
                            const overtimeAmount =
                                employee.overtimes && employee.overtimes.length > 0 && employee.overtimes[0].total_amount
                                    ? parseFloat(employee.overtimes[0].total_amount) // Ensure it's treated as a number
                                    : 0;

                            row += `<td style="color:green;">${overtimeAmount.toFixed(2)}</td>`;
                            total += overtimeAmount;
                        }

                        if (selectedFields.includes('deductions')) {
                            const deductionsAmount =
                                employee.deducations && employee.deducations.length > 0 && employee.deducations[0].total_amount
                                    ? parseFloat(employee.deducations[0].total_amount) // Ensure it's treated as a number
                                    : 0;

                            row += `<td style="color:red;">${deductionsAmount.toFixed(2)}</td>`;
                            total += deductionsAmount;
                        }




                    row += `<td>${total}</td></tr>`;
                    tbody += row;
                    // });
                });

                tableBody.innerHTML = tbody;
                salaryTable.classList.remove('hidden');

                // // Add approve button if not already present
                if (!document.querySelector('.btn-approve')) {
                    const approveBtn = document.createElement('button');
                    approveBtn.className = 'btn-primary btn-approve';
                    approveBtn.type = 'submit';
                    approveBtn.textContent = 'اعتماد الكشف';
                    document.querySelector('.form-actions').appendChild(approveBtn);


                }
            }
            function getEmployeesByBranch(branch, month,selectedFields ,  callback) {

                $.ajax({
                    url: '/employees-by-branch',
                    method: 'GET',
                    data: { branches: branch ,month:month },
                    success: function(response) {
                        console.log(   response);
                        generateEmployeeReport(response , selectedFields);



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
