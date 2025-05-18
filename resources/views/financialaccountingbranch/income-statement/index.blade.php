
@extends('financialaccountingbranch.layouts.master')
@section('content')
    <div class="income-statement-container">
        <div class="report-header">
            <h1>قائمة الدخل</h1>
            <div class="report-period">

                <span>من:</span>
                <input type="date" id="startDate" value="{{ \Carbon\Carbon::now()->startOfMonth()->toDateString() }}">
                <span>إلى:</span>
                <input type="date" id="endDate" value="{{ \Carbon\Carbon::now()->toDateString() }}">
{{--                <button class="view-report-btn" id="fetchReport">--}}
                <button class="view-report-btn" id="fetchReport">
                    <i class="fas fa-search"></i>
                    عرض التقرير
                </button>
            </div>
        </div>

        <div class="income-statement-content" id="statement-content" style="display: none">
            <div class="statement-section revenues">
                <h2><i class="fas fa-chart-line"></i> الإيرادات</h2>
                <div class="statement-items" id="revenues-container">
                    <!-- Revenues will be populated here -->
                </div>
            </div>

            <div class="statement-section expenses">
                <h2><i class="fas fa-chart-pie"></i> المصروفات</h2>
                <div class="statement-items" id="expenses-container">
                    <!-- Expenses will be populated here -->
                </div>
            </div>

            <div class="net-income" id="net-income-container">
                <!-- Net income will be populated here -->
            </div>
        </div>

        <div class="income-statement-summary" id="summary-container">
            <!-- Summary will be populated here -->
        </div>
    </div>


@endsection

@section('js')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#fetchReport').click(function(e) {
                e.preventDefault();

                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                $.ajax({
                    url: '{{ route("branch.income.statement.data") }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    beforeSend: function() {
                        showLoadingOverlay();
                    },
                    success: function(response) {
                        updateIncomeStatement(response.data);
                        $('#statement-content').show();
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        alert('حدث خطأ أثناء جلب البيانات');
                    },
                    complete: function() {
                        hideLoadingOverlay();
                    }
                });
            });


            function updateIncomeStatement(data) {
                const formatNumber = (number) => {
                    return Number(number).toLocaleString('ar-SA', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                };
                let revenuesHtml = '';
                data.revenues.forEach(item => {
                    revenuesHtml += `
                        <div class="statement-item">
                            <span class="item-name">${item.name}</span>
                            <span class="item-amount">${formatNumber(item.amount)} ريال</span>
                        </div>`;
                });
                revenuesHtml += `
                    <div class="statement-total">
                        <span>إجمالي الإيرادات</span>
                        <span>${formatNumber(data.total_revenues)} ريال</span>
                    </div>`;
                $('#revenues-container').html(revenuesHtml);

                // Update Expenses
                let expensesHtml = '';
                data.expenses.forEach(item => {
                    expensesHtml += `
                        <div class="statement-item">
                            <span class="item-name">${item.name}</span>
                            <span class="item-amount">${formatNumber(item.amount)} ريال</span>
                        </div>`;
                });
                expensesHtml += `
                    <div class="statement-total">
                        <span>إجمالي المصروفات</span>
                        <span>${data.total_expenses} ريال</span>
                    </div>`;
                $('#expenses-container').html(expensesHtml);

                // Update Net Income
                const netIncomeClass = data.net_income >= 0 ? 'profit' : 'loss';
                const netIncomeText = data.net_income >= 0 ? 'صافي الربح' : 'صافي الخسارة';
                $('#net-income-container').html(`
                    <h2><i class="fas fa-balance-scale"></i> ${netIncomeText}</h2>
                    <span class="net-amount">${formatNumber(data.net_income)} ريال</span>
                `).removeClass('profit loss').addClass(netIncomeClass);

                // Update Summary
                $('#summary-container').html(`
                    <div class="summary-card">
                        <i class="fas fa-chart-line"></i>
                        <div class="summary-details">
                            <h3>إجمالي الإيرادات</h3>
                            <p>${formatNumber(data.total_revenues)} ريال</p>
                        </div>
                    </div>
                    <div class="summary-card">
                        <i class="fas fa-chart-pie"></i>
                        <div class="summary-details">
                            <h3>إجمالي المصروفات</h3>
                            <p>${formatNumber(data.total_expenses)} ريال</p>
                        </div>
                    </div>
                    <div class="summary-card ${netIncomeClass}">
                        <i class="fas fa-balance-scale"></i>
                        <div class="summary-details">
                            <h3>${netIncomeText}</h3>
                            <p>${formatNumber(data.net_income)} ريال</p>
                        </div>
                    </div>
                `);
            }
        });
    </script>

@endsection
