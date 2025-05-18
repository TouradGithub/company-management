@extends('financialaccounting.layouts.master')
@section('content')
    <div id="accountsTreeSection" >
        <div class="accounts-tree-container">
            @if($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="accounts-header">
                <h1> كشف الحساب</h1>
                <div class="table-actions">
                    <button class="export-excel-btn" id="export-excel-btn-account">
                        <i class="fas fa-file-excel"></i> تصدير Excel
                    </button>
                    <button class="export-pdf-btn" id="export-pdf-btn-account">
                        <i class="fas fa-file-pdf"></i> تصدير PDF
                    </button>
                    <button class="export-pdf-btn" onclick="printObject('{{ route('account.statement.print.pdf') }}')">
                        <i class="fas fa-file-pdf"></i>  معاينة
                    </button>
                </div>
            </div>
                <div class="table-actions">
                    <div class="form-group" style="width: 20%;height: 100%">
                        <label>الحساب:</label>
                        <select id="accountSelect" class=""  >
                            <option value="">اختر الحساب</option>
                            @foreach($accounts as $item)
                                <option value="{{$item->id}}"> {{$item->name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group" style="width: 20%;height: 100%">
                        <label>الفرع:</label>
                        <select id="branchSelect" class=" "  >
                            <option value="">اختر الفرع</option>
                            @foreach($branches as $item)
                                <option value="{{$item->id}}"> {{$item->name}}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label>من التاريخ:</label>
                        <input type="date" id="entryDateDebut" value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label>الى التاريخ:</label>
                        <input type="date" id="entryDateFin" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">

                        <input type="button" id="searchBtn" value="بحث" style="margin-top: 39px">
                    </div>
                </div>
                <div class="accounts-table-container" style="display: none;">
                    <table class="accounts-table">
                        <thead>
                        <tr>
                            <th>الرقم</th>
                            <th>رقم القيد</th>
                            <th>التاريخ</th>
                            <th>موظف</th>
                            <th>الوصف</th>
                            <th>مدين</th>
                            <th>دائن</th>
                            <th>الرصيد</th>
                        </tr>
                        </thead>
                        <tbody id="resultsTable">

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="5"><strong>الإجمالي</strong></td>
                            <td id="totalDebit">0</td>
                            <td id="totalCredit">0</td>
                            <td id="totalBalance">0</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

        </div>

    </div>


    </div>
@endsection
@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>


        function printObject(url) {

            if(!$('#accountSelect').val() || !$('#entryDateDebut').val() || !$('#entryDateFin').val() || !$('#branchSelect').val()){
                alert("يرجى اختيار الحقول لبدأ المعاينة!");
                return;

            }
            showLoadingOverlay();

            let accountId = $('#accountSelect').val();
            let startDate = $('#entryDateDebut').val();
            let endDate = $('#entryDateFin').val();
            let branchId = $('#branchSelect').val();
            const params={
                account_id: accountId,
                from_date: startDate,
                to_date: endDate,
                branch_id: branchId
            };
            const queryString = new URLSearchParams(params).toString();
            const fullUrl = `${url}?${queryString}`;

            let iframe = document.createElement("iframe");
            iframe.src = fullUrl;
            iframe.style.display = "none";
            document.body.appendChild(iframe);
            iframe.onload = function () {
                hideLoadingOverlay();
                iframe.contentWindow.print();

            };
        }
        $(document).ready(function () {

            $('.select2').select2({
                width: '100%',
                placeholder: "اختر الحساب",
                allowClear: true
            });

            $('.branchSelect').select2({
                width: '100%',
                placeholder: "اختر لفرع",
                allowClear: true
            });
            $('#searchBtn').on('click', function() {
                let accountId = $('#accountSelect').val();
                let startDate = $('#entryDateDebut').val();
                let endDate = $('#entryDateFin').val();
                let branchId = $('#branchSelect').val();

                if (!accountId) {
                    alert("يرجى اختيار الحساب!");
                    return;
                }

                $.ajax({
                    url: "{{ route('account.statement.getStatement-by-ajax') }}",
                    type: "GET",
                    data: {
                        account_id: accountId,
                        from_date: startDate,
                        to_date: endDate,
                        branch_id: branchId
                    },
                    success: function(response) {
                        let tableBody = $('#resultsTable');
                        tableBody.empty();

                        if (response.length === 0) {
                            tableBody.append('<tr><td colspan="8" style="text-align: center;">لا توجد بيانات</td></tr>');
                            $('.accounts-table-container').show();
                            return;
                        }

                        let totalDebit = 0;
                        let totalCredit = 0;
                        let balance = 0;

                        // 🔹 إدراج الرصيد السابق في السطر الأول
                        let previousRow = response[0];
                        let previousDebit = parseFloat(previousRow.debit) || 0;
                        let previousCredit = parseFloat(previousRow.credit) || 0;
                        let previousBalance = parseFloat(previousRow.balance) || 0;

                        tableBody.append(`
                <tr>
                    <td colspan="5">${previousRow.comment}</td>
                    <td>${previousDebit.toFixed(2)}</td>
                    <td>${previousCredit.toFixed(2)}</td>
                    <td>${previousBalance.toFixed(2)}</td>
                </tr>
            `);

                        // 🔹 تحديث الأرصدة الإجمالية بإضافة الأرصدة السابقة
                        totalDebit = previousDebit;
                        totalCredit = previousCredit;
                        balance = previousBalance;

                        // 🔹 معالجة باقي السجلات
                        response.slice(1).forEach((row, index) => {
                            let entryDebit = parseFloat(row.debit) || 0;
                            let entryCredit = parseFloat(row.credit) || 0;
                            let entryBalance = entryDebit - entryCredit;

                            balance += entryBalance;
                            totalDebit += entryDebit;
                            totalCredit += entryCredit;

                            tableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${row.entry_number}</td>
                        <td>${row.date}</td>
                        <td>${row.createdby}</td>
                        <td>${row.comment}</td>
                        <td>${entryDebit.toFixed(2)}</td>
                        <td>${entryCredit.toFixed(2)}</td>
                        <td>${balance.toFixed(2)}</td>
                    </tr>
                `);
                        });

                        $('#totalDebit').text(totalDebit.toFixed(2));
                        $('#totalCredit').text(totalCredit.toFixed(2));
                        $('#totalBalance').text(balance.toFixed(2));
                        $('.accounts-table-container').show();
                    },
                    error: function(xhr) {
                        alert("حدث خطأ أثناء جلب البيانات.");
                    }
                });
            });

            $('#export-excel-btn-account').on('click', function () {
                let accountId = $('#accountSelect').val();
                let startDate = $('#entryDateDebut').val();
                let endDate = $('#entryDateFin').val();
                if(!accountId || !startDate || !endDate){
                    alert("يرجى اختيار الحساب!");
                    return;
                }
                let params = buildQueryParams();
                window.location.href = "{{ route('account.statement.export.excel') }}?" + params;
            });
            $('#export-pdf-btn-account').on('click', function () {
                let accountId = $('#accountSelect').val();
                let startDate = $('#entryDateDebut').val();
                let endDate = $('#entryDateFin').val();
                if(!accountId || !startDate || !endDate){
                    alert("يرجى اختيار الحساب!");
                    return;
                }
                let params = buildQueryParams();
                window.open("{{ route('account.statement.export.pdf') }}?" + params, '_blank');
            });

            function buildQueryParams() {
                let accountId = $('#accountSelect').val();
                let startDate = $('#entryDateDebut').val();
                let endDate = $('#entryDateFin').val();
                let branchId = $('#branchSelect').val();

                return $.param({
                    account_id: accountId,
                    from_date: startDate,
                    to_date: endDate,
                    branch_id: branchId
                });
            }



        });
    </script>
@endsection
