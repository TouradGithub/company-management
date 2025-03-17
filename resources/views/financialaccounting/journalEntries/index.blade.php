@extends('financialaccounting.layouts.master')

@section('content')
    <div id="accountsTreeSection" >
        @if($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="responseMessage" style="text-align: center;color: red"></div>

        <div class="accounts-summary">
            <div class="accounts-header">
                <h1> قيود يومية </h1>
                <button class="add-account-btn">
                    <a style="color: white;text-decoration: none" href="{{route('journal-entry.create')}}">
                    <i class="fas fa-plus"></i>
                    إضافة قيةد يومية
                    </a>
                </button>
            </div>
            <div class="table-actions">
                <div class="form-group">
                    <label>الفرع:</label>
                    <select id="branchSelect" >
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
            </div>

            <div class="accounts-table-container">

                <div id="entriesContainer"></div>

            </div>

        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function deleteEntry(entryId) {
            Swal.fire({
                title: "هل أنت متأكد؟",
                text: "لن تتمكن من استعادة هذا القيد بعد الحذف!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "نعم، احذف!",
                cancelButtonText: "إلغاء"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/journal-entry/${entryId}`, // تأكد أن المسار صحيح
                        type: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function(response) {
                            fetchEntries();
                            Swal.fire("تم الحذف!", "تم حذف القيد بنجاح.", "success");
                            fetchEntries(); // تحديث القائمة بعد الحذف
                        },
                        error: function(xhr) {
                            Swal.fire("خطأ!", "حدث خطأ أثناء الحذف.", "error");
                        }
                    });
                }
            });
        }
        {{--function fetchEntries() {--}}
        {{--    let branchId = $("#branchSelect").val();--}}
        {{--    let fromDate = $("#entryDateDebut").val();--}}
        {{--    let toDate = $("#entryDateFin").val();--}}

        {{--    $.ajax({--}}
        {{--        url: "{{ route('journal-entry.fetchEntries') }}", // Ensure correct route--}}
        {{--        method: "GET",--}}
        {{--        data: {--}}
        {{--            branch_id: branchId,--}}
        {{--            from_date: fromDate,--}}
        {{--            to_date: toDate--}}
        {{--        },--}}
        {{--        dataType: "json",--}}
        {{--        success: function (response) {--}}
        {{--            if (response.status === "success") {--}}
        {{--                displayEntries(response.data);--}}
        {{--            } else {--}}
        {{--                alert("خطأ في جلب البيانات");--}}
        {{--            }--}}
        {{--        },--}}
        {{--        error: function () {--}}
        {{--            alert("حدث خطأ أثناء جلب القيود");--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}



            {{--const accountsData = @json($accounts);--}}
            {{--const costCentersData = @json($costcenters);--}}
            fetchEntries();

            function fetchEntries() {
                let branchId = $("#branchSelect").val();
                let fromDate = $("#entryDateDebut").val();
                let toDate = $("#entryDateFin").val();
                showLoadingOverlay();
                $.ajax({
                    url: "{{ route('journal-entry.fetchEntries') }}", // Ensure correct route
                    method: "GET",
                    data: {
                        branch_id: branchId,
                        from_date: fromDate,
                        to_date: toDate
                    },
                    dataType: "json",
                    success: function (response) {
                        hideLoadingOverlay();
                        if (response.status === "success") {
                            displayEntries(response.data);
                        } else {
                            alert("خطأ في جلب البيانات");
                        }
                    },
                    error: function () {
                        hideLoadingOverlay();
                        alert("حدث خطأ أثناء جلب القيود");
                    }
                });
            }

// Trigger fetch when filters change
            $("#branchSelect, #entryDateDebut, #entryDateFin").on("change", function() {
                fetchEntries();
            });


            function displayEntries(entries) {
                let entriesContainer = $("#entriesContainer"); // Assuming you have a div with this ID
                entriesContainer.html(""); // Clear previous data
                if (entries.length == 0) {
                    let detailsHtml = '';
                        detailsHtml += `
                            <div style="width: 100%;height50px;color:black;text-align: center">لايوجد قيود في هذا التاريخ</div>
                        `;

                    entriesContainer.append(detailsHtml);  // Changed from entryHtml to detailsHtml
                }


                entries.forEach(entry => {

                    let detailsHtml = "";
                    entry.details.forEach(detail => {
                        let debit = detail.debit == 0 ? ' - ' : detail.debit;
                        let credit = detail.credit == 0 ? ' - ' : detail.credit;

                        detailsHtml += `
                        <tr>
                            <td style="text-align: center">${detail.account ? detail.account.name +'-'+detail.account.account_number: 'N/A'}</td>
                            <td style="text-align: center">${debit}</td>
                            <td style="text-align: center">${credit}</td>
                            <td style="text-align: center">${detail.costcenter.name+' - ' +detail.costcenter.code || 'N/A'}</td>
                            <td style="text-align: center">${detail.comment || ''}</td>
                        </tr>
                    `;
                    });

                    let entryHtml = `
                    <div class="entry-card">
                        <div class="entry-header">
                            <div class="entry-info">
                                <span class="entry-number">قيد رقم ${entry.entry_number}</span>
                                <span class="entry-date">${entry.entry_date}</span>
                                <span class="entry-branch">${entry.branch ? entry.branch.name : 'N/A'}</span>
                            </div>
                            <div class="entry-totals">
                                <span class="total-debit">مدين: ${getTotal(entry.details, 'debit')}</span>
                                <span class="total-credit">دائن: ${getTotal(entry.details, 'credit')}</span>
                            </div>
                        </div>
                        <div class="entry-details">
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>الحساب</th>
                                        <th>مدين</th>
                                        <th>دائن</th>
                                        <th>مركز التكلفة</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${detailsHtml}
                                </tbody>
                            </table>
                        </div>
                        <div class="" style="display: flex">
                            <a href="/journal-entry/edit/${entry.id}" style="text-decoration:none;color:white">
                                <button class="btn edit-btn " style="margin-right: 15px">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                            </a>

                            <button class="btn edit-btn" style="margin-right: 15px;background: red" onclick="deleteEntry(${entry.id})">
                                <i class="fas fa-trash"></i> حذف
                            </button>

                        </div>
                    </div><br>
                `;

                    entriesContainer.append(entryHtml);
                });
            }

            function editEntry(entryId) {
                window.location.href = `/journal-entry/${entryId}/edit`; // توجيه المستخدم إلى صفحة التعديل
            }


            function getTotal(details, type) {
                return details.reduce((sum, item) => sum + (parseFloat(item[type]) || 0), 0);
            }


    </script>
@endsection
