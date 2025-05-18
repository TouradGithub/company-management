@extends('financialaccountingbranch.layouts.master')

@section('content')
    <div class="trial-balance-container">
        <div class="report-header">
            <div class="branch-info">


            </div>
            <h1>ميزان المراجعة</h1>
            <div class="report-period">

                <span>  من:</span>
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
                let branch_id = $('#branch_id').val();

                if (!startDate || !endDate ) {
                    alert("❗ يرجى تحديد فترة البحث.");
                    return;
                }

                $.ajax({
                    url: "{{ route('branch.trial.balance.data') }}",
                    type: "GET",
                    data: { from_date: startDate, to_date: endDate},
                    success: function (response) {
                        let tableBody = $('.trial-balance-table tbody');
                        tableBody.empty();

                        // تعريف الإجماليات
                        let totalOpeningDebit = 0, totalOpeningCredit = 0;
                        let totalCurrentDebit = 0, totalCurrentCredit = 0;
                        let totalClosingDebit = 0, totalClosingCredit = 0;

                        // معالجة كل صف من البيانات
                        response.forEach(row => {
                            // تحويل القيم إلى أرقام مع التأكد من القيم الافتراضية
                            const openingDebit = parseFloat(row.opening_debit) || 0;
                            const openingCredit = parseFloat(row.opening_credit) || 0;
                            const currentDebit = parseFloat(row.current_debit) || 0;
                            const currentCredit = parseFloat(row.current_credit) || 0;
                            const closingDebit = parseFloat(row.closing_debit) || 0;
                            const closingCredit = parseFloat(row.closing_credit) || 0;

                            // إضافة القيم إلى الإجماليات
                            totalOpeningDebit += openingDebit;
                            totalOpeningCredit += openingCredit;
                            totalCurrentDebit += currentDebit;
                            totalCurrentCredit += currentCredit;
                            totalClosingDebit += closingDebit;
                            totalClosingCredit += closingCredit;

                            // إضافة الصف إلى الجدول
                            tableBody.append(`
                <tr>
                    <td>${row.account_number}</td>
                    <td>${row.account_name}</td>
                    <td>${openingDebit === 0 ? '-' : openingDebit.toFixed(2)}</td>
                    <td>${openingCredit === 0 ? '-' : openingCredit.toFixed(2)}</td>
                    <td>${currentDebit === 0 ? '-' : currentDebit.toFixed(2)}</td>
                    <td>${currentCredit === 0 ? '-' : currentCredit.toFixed(2)}</td>
                    <td>${closingDebit === 0 ? '-' : closingDebit.toFixed(2)}</td>
                    <td>${closingCredit === 0 ? '-' : closingCredit.toFixed(2)}</td>
                </tr>
            `);
                        });

                        // عرض الإجماليات في الواجهة
                        $('#totalOpeningDebit').text(totalOpeningDebit.toFixed(2));
                        $('#totalOpeningCredit').text(totalOpeningCredit.toFixed(2));
                        $('#totalCurrentDebit').text(totalCurrentDebit.toFixed(2));
                        $('#totalCurrentCredit').text(totalCurrentCredit.toFixed(2));
                        $('#totalClosingDebit').text(totalClosingDebit.toFixed(2));
                        $('#totalClosingCredit').text(totalClosingCredit.toFixed(2));

                        // إظهار الجدول
                        $('#hidetable').show();
                    },
                    error: function (xhr) {
                        alert("❌ حدث خطأ أثناء جلب البيانات: " + (xhr.responseJSON?.error || "غير معروف"));
                    }
                });
            });
        });

    </script>
@endsection
