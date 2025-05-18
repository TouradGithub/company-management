@extends('financialaccountingbranch.layouts.master')

@section('content')
    <div id="accountsTreeSection">
        @if($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div id="responseMessage" style="text-align: center; color: red;"></div>
        <div class="accounts-summary">
            <div class="table-actions" >
                <button class="export-excel-btn" id="export-excel-btn-account">
                    <i class="fas fa-file-excel"></i> تصدير Excel
                </button>
                <button class="export-pdf-btn" id="export-pdf-btn-account">
                    <i class="fas fa-file-pdf"></i> تصدير PDF
                </button>
                <button class="add-account-btn" id="openImportModal">
                    <i class="fas fa-file-import"></i> استيراد قيود
                </button>
            </div>
            <div class="accounts-header">
                <h1>قيود يومية</h1>
                <button class="add-account-btn">
                    <a style="color: white; text-decoration: none" href="{{ route('branch.journal-entry.create') }}">
                        <i class="fas fa-plus"></i> إضافة قيد يومي
                    </a>
                </button>
            </div>
            <div class="table-actions" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; gap: 1rem;">


                    <div class="form-group" style="width: 100%">
                        <label>البحث برقم القيد:</label>
                        <input type="text" id="entryNumberSearch" style="width: 100%" placeholder="أدخل رقم القيد">
                    </div>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <div class="form-group" style="width: 50%">
                        <label>من التاريخ:</label>
                        <input type="date" id="entryDateDebut" value="{{ now()->format('Y-m-d') }}">
                    </div>
                <div class="form-group" style="width: 50%">
                    <label>إلى التاريخ:</label>
                    <input type="date" id="entryDateFin" value="{{ now()->format('Y-m-d') }}">
                </div>


                </div>

            </div>

            <div class="accounts-table-container">
                <div id="entriesContainer"></div>
            </div>
        </div>
    </div>

    <div id="importModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:999;">
        <div style="background:#fff; padding:20px; margin:10% auto; width:400px; border-radius:10px; position:relative;">
            <h3>استيراد ملف Excel</h3>
            <form id="importForm" enctype="multipart/form-data" method="POST" action="{{ route('journal.import') }}">
                @csrf
                <input type="file" name="import_file" class="" accept=".xlsx,.xls" required style="    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    color: #444;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
    cursor: pointer;
    transition: border 0.3s ease, box-shadow 0.3s ease;"/>                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" id="closeImportModal">إلغاء</button>
                    <button type="submit" class="save-btn" id="save-btn">حفظ</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function printObject(url) {
            showLoadingOverlay();
            let iframe = document.createElement("iframe");
            iframe.src = url;
            iframe.style.display = "none";
            document.body.appendChild(iframe);
            iframe.onload = function () {
                iframe.contentWindow.print();
                hideLoadingOverlay();
            };
        }
        function deleteEntry(entryId) {
            alert(entryId);
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
                        url: `/branch/journal-entry/${entryId}`,
                        type: "DELETE",
                        data: { _token: $('meta[name="csrf-token"]').attr("content") },
                        success: function(response) {
                            fetchEntries();
                            Swal.fire("تم الحذف!", "تم حذف القيد بنجاح.", "success");
                        },
                        error: function(xhr) {
                            Swal.fire("خطأ!", "حدث خطأ أثناء الحذف.", "error");
                        }
                    });
                }
            });
        }
        $(document).ready(function() {
            let currentFilters = {
                from_date: $("#entryDateDebut").val(),
                to_date: $("#entryDateFin").val(),
                entry_number: ''
            };

            fetchEntries();


            // Trigger fetch when filters change
            // Trigger fetch when branch or date filters change
            $("#entryDateDebut, #entryDateFin").on("change", function() {
                currentFilters.from_date = $("#entryDateDebut").val();
                currentFilters.to_date = $("#entryDateFin").val();
                fetchEntries();
            });

            // Trigger fetch when typing in entry number search (min 3 characters)
            $("#entryNumberSearch").on("input", function() {
                let searchValue = $(this).val();
                currentFilters.entry_number = searchValue;
                if (searchValue.length >= 4 || searchValue.length === 0) {
                    fetchEntries();
                }
            });

            $('#openImportModal').on('click', function() {
                $('#importModal').fadeIn();
            });

            $('#closeImportModal').on('click', function() {
                $('#importModal').fadeOut();
            });


            // Fetch entries
            function fetchEntries() {
                showLoadingOverlay();
                $.ajax({
                    url: "{{ route('branch.journal-entry.fetchEntries') }}",
                    method: "GET",
                    data: currentFilters,
                    dataType: "json",
                    success: function(response) {
                        hideLoadingOverlay();
                        if (response.status === "success") {
                            displayEntries(response.data);
                        } else {
                            alert("خطأ في جلب البيانات");
                        }
                    },
                    error: function() {
                        hideLoadingOverlay();
                        alert("حدث خطأ أثناء جلب القيود");
                    }
                });
            }

            // Display entries
            function displayEntries(entries) {
                let entriesContainer = $("#entriesContainer");
                entriesContainer.html("");
                if (entries.length === 0) {
                    entriesContainer.append(`
                        <div style="width: 100%; height: 50px; color: black; text-align: center;">
                            لا يوجد قيود تطابق البحث
                        </div>
                    `);
                } else {
                    entries.forEach(entry => {
                        let detailsHtml = "";
                        entry.details.forEach(detail => {
                            let debit = detail.debit == 0 ? '-' : detail.debit;
                            let credit = detail.credit == 0 ? '-' : detail.credit;
                            detailsHtml += `
                                <tr>
                                    <td style="text-align: center">${detail.account ? detail.account.name + ' - ' + detail.account.account_number : 'N/A'}</td>
                                    <td style="text-align: center">${debit}</td>
                                    <td style="text-align: center">${credit}</td>
                                    <td style="text-align: center">${detail.costcenter ? detail.costcenter.name + ' - ' + detail.costcenter.code : 'N/A'}</td>
                                    <td style="text-align: center">${detail.comment || ''}</td>
                                </tr>
                            `;
                        });
                        const printUrl = `/branch/journal-entry/single/print/pdf/${entry.id}`;
                        let entryHtml = `
                            <div class="entry-card">
                                <div class="entry-header">
                                    <div class="entry-info">
                                        <span class="entry-number">قيد رقم ${entry.entry_number}</span>
                                        <span class="entry-date">${entry.entry_date}</span>
                                    </div>
                                    <div class="entry-totals">
                                        <span class="total-debit">مدين: ${getTotal(entry.details, 'debit')}</span>
                                        <span class="total-credit" style="color: white">دائن: ${getTotal(entry.details, 'credit')}</span>
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
                                <div style="display: flex">
                                    <a href="/branch/journal-entry/edit/${entry.id}" style="text-decoration:none; color:white">
                                        <button class="btn edit-btn" style="margin-right: 15px">
                                            <i class="fas fa-edit"></i> تعديل
                                        </button>
                                    </a>
                                    <button class="btn edit-btn" style="margin-right: 15px; background: red" onclick="deleteEntry(${entry.id})">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                     <a href="/branch/journal-entry/single/export/pdf/${entry.id}" style="text-decoration:none; color:white; margin-right: 3px;">
                                        <button class="btn edit-btn" id="export-pdf-btn-account">
                                            <i class="fas fa-file-pdf"></i> تصدير PDF
                                        </button>
                                    </a>
                                     <a  style="text-decoration:none; color:white; margin-right: 3px;">
                                        <button class="btn edit-btn" onclick="printObject('${printUrl}')">
                                            <i class="fas fa-file-pdf"></i> معاينة
                                        </button>
                                    </a>
                                    <a href="/branch/journal-entry/clone/${entry.id}" style="text-decoration:none; color:white; margin-right: 3px;">
                                        <button class="btn edit-btn" id="export-pdf-btn-account">
                                            <i class="fas fa-file-pdf"></i>نسخ
                                        </button>
                                    </a>
                                </div>
                            </div><br>
                        `;
                        entriesContainer.append(entryHtml);
                    });
                }
            }

            function getTotal(details, type) {
                return details.reduce((sum, item) => sum + (parseFloat(item[type]) || 0), 0);
            }
            $('#export-excel-btn-account').on('click', function() {
                window.location.href = '{{ route("journal-entry.export.excel") }}?' + $.param(currentFilters);
            });
            $('#export-pdf-btn-account').on('click', function() {
                window.location.href = '{{ route("journal-entry.export.pdf") }}?' + $.param(currentFilters);
            });



        });
    </script>
@endsection
