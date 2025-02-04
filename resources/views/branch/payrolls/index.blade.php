@extends('layouts.masterbranch')

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

<div class="section-header">
    <h2>سجل كشوفات الرواتب</h2>
    <button class="add-deduction-btn">
        <a href="{{route('branch.payrolls.create')}}">
      <i class="fas fa-plus"></i>
      إضافة كشف راتب
        </a>
    </button>
</div>
  <div class="deductions-table">

            <form id="exportPdfForm" method="POST" action="{{ route('branch.payrolls.export.pdf') }}">
                @csrf
                <input type="hidden" name="month" id="hiddenMonth">
                <input type="hidden" name="categorie" id="hiddenCategorie">
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
            <button type="submit" id="filterExportPdf" style="display: none;margin-top: 3px"  class="save-btn">طباعة</button>
            <div style="margin-top: 20px;display: none" id="catego">
                <label for="categoryFilter">تصفية حسب الفئة:</label>
                <select id="categoryFilter" class="form-control">
                    <option value="all">جميع الفئات</option>
                    @foreach ($categories as $item)
                    <option value="{{$item->id}}"> {{$item->name}}</option>
                    @endforeach

                </select>
            </div>
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
    $("#catego").css("display", "block");
    $("#hiddenCategorie").val("all");

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
                    <tr  class="payroll-row" data-category="${payroll.employee?.category_id}">
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
                                <button  class="action-btn delete-btn" type="submit">حذف</button>
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

$("#categoryFilter").change(function() {
        var selectedCategory = $(this).val();

        $("#hiddenCategorie").val(selectedCategory);

        if (selectedCategory === "all") {
            $(".payroll-row").show(); // Show all rows
        } else {
            $(".payroll-row").hide().filter(`[data-category='${selectedCategory}']`).show(); // Show only matching rows
        }

    });


</script>

@endsection
