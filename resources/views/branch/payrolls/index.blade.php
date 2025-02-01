@extends('layouts.branch')

@section('content')
@if(session('success'))
    <p class="alert alert-success">{{ session('success') }}</p>
@endif

<section>
    <style>
        .selectedPayrolls {
            background-color: #d3d3d3;
            color: #000;
        }

        #payrollTableFetched {
            display: none;
        }
    </style>

    <div class="container">
        <h1>سجل كشف الرواتب</h1>
        <a class="btn btn-primary mb-3" href="{{ route('branch.payrolls.create') }}">إضافة كشف راتب</a>

        <div>
            <form id="exportPdfForm" method="POST" action="{{ route('branch.payrolls.export.pdf') }}">
                @csrf
                <input type="hidden" name="month" id="hiddenMonth">
            </form>

            <div id="entriesContainer">
                <table class="records-table table table-bordered" id="payrollTable">
                    <thead>
                        <tr>
                            <th>التواريخ المتاحة</th>
                            <th>عدد الموظفين</th>
                            <th>إجمالي الرواتب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrollData as $payroll)
                            <tr onclick="selectRow(this)">
                                <td>{{ $payroll->date }}</td>
                                <td>{{ $payroll->employees_count }}</td>
                                <td>{{ number_format($payroll->total_salary, 2) }} ريال</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h3 id="payrollTableTitle" style="display: none;text-align: center;margin-top:30px" >كشوف الرواتب لشهر: <span id="selectedMonth"></span></h3>
            <button type="submit" id="filterExportPdf" style="display: none" class="btn btn-primary ">طباعة</button>

            <div id="entriesContainer">
                <table class="records-table table table-bordered mt-3" id="payrollTableFetched">
                    <thead>
                        <tr>
                            <th>الموظف</th>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function selectRow(row) {
    // إزالة التحديد من جميع الصفوف
    $('#payrollTable tbody tr').css('background-color', '');

    // إضافة التحديد للصف الحالي
    $(row).css('background-color', '#d3d3d3');

    // جلب التاريخ
    var date = $(row).find('td:first').text().trim();

    $("#payrollTableTitle").css("display", "block");
    $("#filterExportPdf").css("display", "block");

    $("#selectedMonth").text(date);
    $("#hiddenMonth").val(date);

    // استدعاء دالة لجلب البيانات
    fetchPayrollData(date);
}

function fetchPayrollData(month) {
    if (!month) return;

    $.ajax({
        url: "{{ route('branch.payrolls.data') }}",
        method: "GET",
        data: { month: month },
        dataType: "json",
        success: function (response) {
            let tableBody = $("#payrollTableFetched tbody");
            tableBody.empty();

            if (response.length === 0) {
                tableBody.append('<tr><td colspan="4" class="text-center text-muted">لا توجد كشوف في هذا الشهر</td></tr>');
                $("#payrollTableFetched").show();
                return;
            }

            response.forEach(payroll => {
                let deleteUrl = `/branch/payrolls/delete/${payroll.id}/${payroll.date}`;
                let row = `
                    <tr>
                        <td>${payroll.employee?.name || '—'}</td>
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
                tableBody.append(row);
            });

            $("#payrollTableFetched").show();
        },
        error: function (xhr, status, error) {
            console.error("خطأ في جلب البيانات:", error);
        }
    });
}

$(document).ready(function () {
    $("#filterExportPdf").click(function () {
        if (!$("#hiddenMonth").val()) {
            alert("قم باختيار الشهر.");
            return;
        }
        $("#exportPdfForm").submit();
    });
});
</script>

@endsection
