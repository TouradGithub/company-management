
@extends('layouts.mastercomany')

@section('content')

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
    <div class="section-header">
        <h2>إضافة كشف جديد</h2>
    </div>

    <div class="add-advance-content">
        <form id="salaryForm" id="add-advance-form" class="standard-form" id="add-advance-form" class="standard-form">
        <div class="form-group">
            <label for="month">الشهر:</label>
            <input type="month"  class="form-control" id="month" required>
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

        <div class="form-control">
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
                    <input type="checkbox" name="fields" value="loans">
                    السلف
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
            <button type="submit"  style="margin-top: 5x" class="save-btn">عرض الكشف</button>
            <button type="button"   style="margin-top: 5px" class="save-btn  hidden" id="printBtn">طباعة</button>
        </div>
        </form>
    </div>

    <div id="salaryTable" class="hidden">
      <h2>كشف الرواتب - <span id="reportTitle"></span></h2>
      <form action="{{route('company.payrolls.store')}}" method="POST">@csrf
        <input type="hidden" id="monthYear"  class="form-control" value="" name="date">
          <div class="deductions-table">

            <table>
                <thead id="tableHeader">
                <!-- سيتم إنشاء العناوين ديناميكياً -->
                </thead>
                <tbody id="tableBody">
                <!-- سيتم إنشاء الصفوف ديناميكياً -->
                </tbody>
            </table>
        <button type="submit"  style="margin-top: 5px" class="save-btn hidden" id="formvalidat">إضافة</button>
      </form>
    </div>

    {{-- <div id="approvedReports" class="approved-reports">
      <!-- سيتم إضافة الكشوفات المعتمدة هنا -->
    </div> --}}
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
            function generateEmployeeReport(data, month, selectedFields) {
    reportTitle.textContent = `${month}`;

    let headerRow = '<tr><th>اسم الموظف</th><th>الفرع</th><th>الراتب</th>';

    if (selectedFields.includes('transportation')) {
        headerRow += `<th>بدل التنقل</th>`;
    }
    if (selectedFields.includes('food')) {
        headerRow += `<th>بدل الاعاشة</th>`;
    }
    if (selectedFields.includes('loans')) {
        headerRow += `<th>السلف</th>`;
    }
    if (selectedFields.includes('overtime')) {
        headerRow += `<th>الإضافي</th>`;
    }
    if (selectedFields.includes('deductions')) {
        headerRow += `<th>الخصومات</th>`;
    }

    headerRow += '<th>الإجمالي</th><th>حذف</th></tr>'; // Add Delete Column
    tableHeader.innerHTML = headerRow;

    let tbody = '';
    data.forEach(employee => {
        let total = 0;
        let row = `<tr data-employee-id="${employee.id}">
            <input type="hidden" name="employee[][id]" value="${employee.id}" />
            <td>${employee.name}</td>
            <td>${employee.branch.name}</td>
            <td class="salary">${parseFloat(employee.basic_salary).toFixed(2)}</td>
            <input type="hidden" name="basic_salary[][amount]" value="${parseFloat(employee.basic_salary).toFixed(2)}" />`;

        total += parseFloat(employee.basic_salary) || 0;

        if (selectedFields.includes('transportation')) {
            let transportationAllowance = parseFloat(employee.transportation_allowance) || 0;
            row += `<td>${transportationAllowance.toFixed(2)}</td>`;
            total += transportationAllowance;
        }

        if (selectedFields.includes('food')) {
            let foodAllowance = parseFloat(employee.food_allowance) || 0;
            row += `<td>${foodAllowance.toFixed(2)}</td>`;
            total += foodAllowance;
        }

        if (selectedFields.includes('loans')) {
            let loanAmount = parseFloat(employee.loans_total) || 0;
            row += `<td><input type="number" class="loan-input form-control" name="loans[][amount]" value="${loanAmount.toFixed(2)}" max="${loanAmount}" data-type="loan" /></td>`;
            total += loanAmount;
        }

        if (selectedFields.includes('overtime')) {
            let overtimeAmount =parseFloat(employee.overtime_total) || 0;

            row += `<td><input type="number" class="overtime-input form-control" name="overtime[][amount]" value="${overtimeAmount.toFixed(2)}" max="${overtimeAmount}" data-type="overtime" /></td>`;
            total += overtimeAmount;
        }

        if (selectedFields.includes('deductions')) {
            let deductionsAmount = parseFloat(employee.deducation_total) || 0;
            row += `<td><input type="number" class="deduction-input form-control" name="deductions[][amount]" value="${deductionsAmount.toFixed(2)}" max="${deductionsAmount}" data-type="deduction" /></td>`;
            total -= deductionsAmount;
        }

        row += `<td class="total">${total.toFixed(2)}</td>
            <input type="hidden" name="total[][amount]" value="${total.toFixed(2)}" />
            <td><button type="button"  class="action-btn delete-btn">حذف</button></td>
        </tr>`;

        tbody += row;
    });

    if (data.length === 0) {
        tbody = `<tr>
            <td colspan="${selectedFields.length + 5}" style="text-align: center; color: gray;">
                لا توجد بيانات لعرضها
            </td>
        </tr>`;
    }

    tableBody.innerHTML = tbody;
    document.getElementById('monthYear').value = month;

    if (data.length !== 0) {
        formvalidat.classList.remove('hidden');
    }

    salaryTable.classList.remove('hidden');

    // Add event listeners to update total dynamically & enforce limits
    document.querySelectorAll('.loan-input, .overtime-input, .deduction-input').forEach(input => {
        input.addEventListener('input', function () {
            enforceLimit(this); // Ensure value doesn't exceed the default
            updateTotal(this);  // Recalculate total
        });
    });

    // Add delete event listener
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('tr').remove(); // Remove the row from table
        });
    });
}


// Function to enforce limits
function enforceLimit(input) {
    let maxValue = parseFloat(input.getAttribute('max')) || 0;
    let currentValue = parseFloat(input.value) || 0;

    if (currentValue > maxValue) {
        input.value = maxValue.toFixed(2);
        alert('لا يمكنك إدخال قيمة أكبر من الحد المسموح به!');
    }
}

// Function to update total dynamically
function updateTotal(input) {
    let row = input.closest('tr');
    let baseSalary = parseFloat(row.querySelector('.salary').textContent) || 0;
    let total = baseSalary;

    row.querySelectorAll('input[type="number"]').forEach(inputField => {
        let value = parseFloat(inputField.value) || 0;
        if (inputField.dataset.type === 'deduction') {
            total -= value;
        } else {
            total += value;
        }
    });

    let totalCell = row.querySelector('.total');
    totalCell.textContent = total.toFixed(2);
    row.querySelector('input[name="total[][amount]"]').value = total.toFixed(2);
}


            function getEmployeesByBranch(branch, month,selectedFields ,  callback) {

                $.ajax({
                    url: '/employees-by-branch',
                    method: 'GET',
                    data: { branches: branch ,month:month },
                    success: function(response) {
                        console.log(   response);
                        generateEmployeeReport(response , month ,  selectedFields);



                    },
                    error: function(xhr, status, error) {
                        console.error("There was an error fetching the employees: ", error);
                    }
                    });
            }


            function approveReport(month, branches, selectedFields) {
            const date = new Date(month);

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
            };

        });

    </script>



  @endsection
