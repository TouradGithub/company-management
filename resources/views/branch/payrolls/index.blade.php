@extends('layouts.branch')

@section('content')
@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<section>
    <div class="container">
        <h1>سجل كشف الرواتب</h1>
        <a class="btn btn-primary" href="{{ route('company.overtimes.create') }}">إضافة كشف راتب</a>

        <!-- Filter Form -->
        <form id="filterForm">
            <div class="form-group">
                <label for="month">الشهر:</label>
                <input type="month" id="month" name="month" required>
            </div>

            <div class="form-group">
                <label>الفروع:</label>
                <div class="checkbox-group branches-group">
                    @foreach ($branches as $item)
                        <label>
                            <input type="checkbox" name="branches[]" value="{{ $item->id }}">
                            {{ $item->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="button" id="filterButton" class="btn btn-primary">تصفية</button>
        </form>


        <div>
            <form style=" display: flex; justify-content: left; align-items: left;"
                id="exportPdfForm" method="POST" action="{{ route('company.payrolls.export.pdf') }}" target="_blank">
                @csrf
                <input type="hidden" name="month" id="hiddenMonth">
                <input type="hidden" name="branches" id="hiddenBranches">
                <button type="submit" style="text-align: left" id="filterExportPdf" class="btn btn-primary">طباعة</button>
            </form>
            <div id="entriesContainer">

                <!-- Placeholder for the table -->
                <table class="records-table" id="payrollTable">
                    <thead>
                        <tr>
                            <th>اسم الموظف</th>
                            <th>اسم الفرع</th>
                            <th>المجموع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Include jQuery (or use vanilla JavaScript) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Function to fetch and display payroll data
        function fetchPayrollData() {
            // Get the selected month and branches
            const month = $("#month").val();
            const branches = $("input[name='branches[]']:checked").map(function () {
                return this.value;
            }).get();
            if (!month || branches.length === 0) {
                return;
            }

            $.ajax({
                url: "{{ route('company.payrolls.data') }}",
                method: "GET",
                data: {
                    month: month,
                    branches: branches
                },
                dataType: "json",
                success: function (response) {
                    // Clear existing rows
                    $("#payrollTable tbody").empty();

                    // Loop through the data and append rows to the table
                    response.forEach(function (payroll) {
                        let deleteUrl = `/company/payrolls/delete/${payroll.id}/${payroll.date}`;
                        let row = `
                            <tr>
                                <td>${payroll.employee.name}</td>
                                <td>${payroll.branch.name}</td>
                                <td>${payroll.net_salary}</td>
                                <td>
                                    <form action="${deleteUrl}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-primary btn-delete" type="submit">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        $("#payrollTable tbody").append(row);
                    });
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching payroll data:", error);
                }
            });
        }

        // Fetch data when the page loads
        fetchPayrollData();

        // Fetch data when the filter button is clicked
        $("#filterButton").click(function () {
            fetchPayrollData();
        });
    // Handle print button click
    $("#filterExportPdf").click(function () {
    // Get the selected month and branches
        const month = $("#month").val();
        const branches = $("input[name='branches[]']:checked").map(function () {
            return this.value;
        }).get();
        if (!month) {
            alert("قم باختيار الشهر.");
            return; // Stop execution
        }

        if (branches.length === 0) {
            alert("قم باختيار الفروع.");
            return; // Stop execution
        }

        // Set the values in the hidden form fields
        $("#hiddenMonth").val(month);
        $("#hiddenBranches").val(branches);

        // Submit the form
        $("#exportPdfForm").submit();
    });



    });
</script>
@endsection
