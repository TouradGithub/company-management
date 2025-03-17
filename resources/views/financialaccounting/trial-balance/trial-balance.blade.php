@extends('financialaccounting.layouts.master')

@section('content')
    <div class="trial-balance-container">
        <div class="report-header">
            <div class="branch-info">
                <h2>الفرع: الفرع الرئيسي</h2>
            </div>
            <h1>ميزان المراجعة</h1>
            <div class="report-period">
                <span>عن الفترة من:</span>
                <input type="date" id="startDate"  value="{{ now()->format('Y-m-d') }}">
                <span>إلى:</span>
                <input type="date" id="endDate"  value="{{ now()->format('Y-m-d') }}">
                <button class="view-report-btn">
                    <i class="fas fa-search"></i>
                    عرض التقرير
                </button>
            </div>
        </div>

        <div class="trial-balance-table-container">
            <table class="trial-balance-table" id="hidetable">
                <thead>
                <tr>
                    <th>رقم الحساب</th>
                    <th>اسم الحساب</th>
                    <th colspan="2">الأرصدة الافتتاحية</th>
                    <th colspan="2">الحركات</th>
                    <th colspan="2">الرصيد الختامي</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>مدين</th>
                    <th>دائن</th>
                </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                <tr >
                    <td colspan="2">الإجمالي</td>
                    <td id="totalOpeningDebit">0</td>
                    <td id="totalOpeningCredit">0</td>
                    <td id="totalCurrentDebit">0</td>
                    <td id="totalCurrentCredit">0</td>
                    <td id="totalClosingDebit">0</td>
                    <td id="totalClosingCredit">0</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // إخفاء حقول الإجمالي عند تحميل الصفحة
            $('#hidetable').hide();

            $('.view-report-btn').on('click', function () {
                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();

                if (!startDate || !endDate) {
                    alert("❗ يرجى تحديد فترة البحث.");
                    return;
                }

                $.ajax({
                    url: "{{ route('trial.balance.data') }}",
                    type: "GET",
                    data: { from_date: startDate, to_date: endDate },
                    success: function (response) {
                        let tableBody = $('.trial-balance-table tbody');
                        tableBody.empty();

                        let totalOpeningDebit = 0, totalOpeningCredit = 0;
                        let totalCurrentDebit = 0, totalCurrentCredit = 0;
                        let totalClosingDebit = 0, totalClosingCredit = 0;

                        response.forEach(row => {
                            totalOpeningDebit += parseFloat(row.opening_debit) || 0;
                            totalOpeningCredit += parseFloat(row.opening_credit) || 0;
                            totalCurrentDebit += parseFloat(row.current_debit) || 0;
                            totalCurrentCredit += parseFloat(row.current_credit) || 0;
                            totalClosingDebit += parseFloat(row.closing_debit) || 0;
                            totalClosingCredit += parseFloat(row.closing_credit) || 0;

                            tableBody.append(`
                        <tr>
                            <td>${row.account_number}</td>
                            <td>${row.account_name}</td>
                            <td>${row.opening_debit}</td>
                            <td>${row.opening_credit}</td>
                            <td>${row.current_debit}</td>
                            <td>${row.current_credit}</td>
                            <td>${row.closing_debit}</td>
                            <td>${row.closing_credit}</td>
                        </tr>
                    `);
                        });

                        // تحديث الإجماليات
                        $('#totalOpeningDebit').text(totalOpeningDebit.toFixed(2));
                        $('#totalOpeningCredit').text(totalOpeningCredit.toFixed(2));
                        $('#totalCurrentDebit').text(totalCurrentDebit.toFixed(2));
                        $('#totalCurrentCredit').text(totalCurrentCredit.toFixed(2));
                        $('#totalClosingDebit').text(totalClosingDebit.toFixed(2));
                        $('#totalClosingCredit').text(totalClosingCredit.toFixed(2));

                        // إظهار حقول الإجمالي بعد جلب البيانات
                        $('#hidetable').show();
                    },
                    error: function () {
                        alert("❌ حدث خطأ أثناء جلب البيانات.");
                    }
                });
            });
        });

    </script>
@endsection
