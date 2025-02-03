@extends('layouts.overtime')

@section('content')
@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<section>
    <style>
        .selectedPayrolls {
            background-color: #d3d3d3; /* لون خلفية الصف المحدد */
            color: #000;
        }

        #payrollTableFetched {
            display: none;
        }
    </style>
    <div class="container">
        <h1>سجل كشف الرواتب</h1>
        <a class="btn btn-primary" href="{{ route('company.payrolls.create') }}">إضافة كشف راتب</a>

        <div>
            <form style="display: flex; justify-content: left; align-items: left;" id="exportPdfForm" method="POST" action="{{ route('company.payrolls.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="month" id="hiddenMonth">
                <input type="hidden" name="branches" id="hiddenBranches">
            </form>
            <div id="entriesContainer">
                <!-- Placeholder for the table -->
                <table class="records-table" id="payrollTable">
                    <thead>
                        <tr>
                            <th>الفرع</th>
                            <th>عدد الموظفين</th>
                            <th>التواريخ المتاحة</th>
                            <th>إجمالي الرواتب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrollData as $payroll)
                            <tr onclick="selectRow(this)">
                                <td data-branch="{{ $payroll->branch->id }}">{{ $payroll->branch->name }}</td>
                                <td>{{ $payroll->employees_count }}</td>
                                <td>{{ $payroll->date }}</td>
                                <td>{{ number_format($payroll->total_salary, 2) }} ريال</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h3 id="payrollTableTitle" style="display: none;text-align: center;margin-top:30px;">كشوف الرواتب شهر: <span id="selectedMonth"></span></h3>
            <button type="submit" style="display: none;text-align: left" id="filterExportPdf" class="btn btn-primary">طباعة</button>

            <div id="entriesContainer">
                <!-- Placeholder for the table -->
                <table class="records-table" id="payrollTableFetched">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>الفرع</th>
                            <th>الراتب الأساسي</th>
                            <th>الإضافي</th>
                            <th>الخصومات</th>
                            <th>السلف</th>
                            <th>صافي الراتب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- البيانات سيتم إضافتها هنا عبر JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function selectRow(row) {
        console.log(row);
        // إزالة التحديد من جميع الصفوف
        document.querySelectorAll('#payrollTable tbody tr').forEach(function(tr) {
            tr.style.backgroundColor = ''; // إعادة تعيين اللون الأساسي
        });

        // إضافة التحديد للصف الذي تم النقر عليه
        row.style.backgroundColor = '#d3d3d3'; // لون التحديد

        // جلب البيانات من الصف الذي تم النقر عليه
        var branchId = row.querySelector('td[data-branch]').getAttribute('data-branch'); // معرف الفرع
        var branchName = row.querySelector('td[data-branch]').innerText; // اسم الفرع
        var date = row.querySelector('td:nth-child(3)').innerText; // التاريخ

        $("#payrollTableTitle").css("display", "block");
        $("#filterExportPdf").css("display", "block");
         // إظهار العنوان
        $("#selectedMonth").text('');
        $("#selectedMonth").text(date);

        $("#hiddenMonth").val(date);
        $("#hiddenBranches").val([branchId]);
        console.log(date);
        console.log([branchId]);
        // استدعاء دالة لجلب البيانات
        fetchPayrollData(date, [branchId]);

    }

    function fetchPayrollData(month, branches) {
        $("#hiddenMonth").val(month);
        $("#hiddenBranches").val(branches);
        $.ajax({
            url: "{{ route('company.payrolls.data') }}",
            method: "GET",
            data: {
                month: month,
                branches: branches
            },
            dataType: "json",
            success: function(response) {
                // Clear existing rows
                $("#payrollTableFetched tbody").empty();

                // Loop through the data and append rows to the table
                response.forEach(function(payroll) {
                    let deleteUrl = `/company/payrolls/delete/${payroll.id}/${payroll.date}`;
                    let row = `
                        <tr>
                            <td>${payroll.employee?.name || ''}</td>
                            <td>${payroll.branch?.name || ''}</td>
                            <td>${payroll.basic_salary} ريال</td>
                            <td>${payroll.overtime} ريال</td>
                            <td>${payroll.deduction} ريال</td>
                            <td>${payroll.loans} ريال</td>
                            <td>${payroll.net_salary} ريال</td>
                            <td>
                                <form action="${deleteUrl}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-primary btn-delete" type="submit">حذف</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    $("#payrollTableFetched tbody").append(row);
                    $("#payrollTableFetched").css("display", "table");
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching payroll data:", error);
            }
        });
    }

    // Handle print button click
    $("#filterExportPdf").click(function() {
        // Get the selected month and branches
        const month = $("#hiddenMonth").val();
        const branches = $("#hiddenBranches").val();
        console.log(month);
        console.log(branches);

        if (!month) {
            alert("قم باختيار الشهر.");
            return; // Stop execution
        }

        if (!branches) {
            alert("قم باختيار الفروع.");
            return; // Stop execution
        }

        // Set the values in the hidden form fields
        $("#hiddenMonth").val(month);
        $("#hiddenBranches").val([branches]);

        // Submit the form
        if(month && branches){
            $("#exportPdfForm").submit();
        }

    });
</script>
@endsection
