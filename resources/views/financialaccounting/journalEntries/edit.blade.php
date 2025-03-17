@extends('financialaccounting.layouts.master')

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
        <div id="responseMessage" style="text-align: center; color: red"></div>

        <div class="accounts-summary">
            <h2>تعديل قيد يومية</h2>
            <div class="table-actions">
                <div class="form-group">
                    <label>الفرع:</label>
                    <select id="branchSelect">
                        <option value="">اختر الفرع</option>
                        @foreach($branches as $item)
                            <option value="{{ $item->id }}" {{ $entry->branch_id == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>التاريخ:</label>
                    <input type="date" value="{{ $entry->entry_date }}" id="entryDate">
                </div>

                <div class="form-group">
                    <label>رقم القيد:</label>
                    <input type="text" value="{{ $entry->entry_number }}" id="entryNumber" style=" background-color: #f1f1f1;" readonly>
                </div>
            </div>

            <div class="accounts-table-container">
                <table class="accounts-table" id="entriesTable">
                    <thead>
                    <tr>
                        <th>رقم الحساب</th>
                        <th>مدين</th>
                        <th>دائن</th>
                        <th>مركز التكلفة</th>
                        <th>ملاحظة</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($entry->details as $detail)
                        <tr class="entry-row" data-id="{{ $detail->id}}">
                            <td>
                                <select class="account-select select2">
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ $detail->account_id == $account->id ? 'selected' : '' }}>
                                            {{ $account->account_number }} - {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="debit"
                                       value="{{ $detail->debit != 0 ? $detail->debit : '-' }}"
                                    {{ $detail->credit != 0 ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <input type="number" class="credit"
                                       value="{{ $detail->credit != 0 ? $detail->credit : '-' }}"
                                    {{ $detail->debit != 0 ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <select class="cost-center-select select-cost-center">
                                    @foreach($costcenters as $costcenter)
                                        <option value="{{ $costcenter->id }}" {{ $detail->cost_center_id == $costcenter->id ? 'selected' : '' }}>
                                            {{ $costcenter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="notes" value="{{ $detail->notes }}"></td>
                            <td><button class="action-btn delete-row"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <button id="saveEntry" type="submit" style="padding: 0.8rem 1.5rem; background: rgb(30,144,255); width: 20%; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s ease;">حفظ التعديلات</button>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2, .select-cost-center').select2({
                width: '100%',
                placeholder: "اختر",
                allowClear: true
            });

            // التنقل بين الحقول عند الضغط على Enter
            $(document).on('keydown', 'input, select', function (e) {
                if (e.key === "Enter") {
                    console.log("Enter ");
                    e.preventDefault(); // منع الإرسال الافتراضي للنموذج
                    let row = $(this).closest('tr');
                    let inputs = row.find('input, select').not(':disabled, [readonly]'); // تحديد الحقول القابلة للتحرير فقط
                    let index = inputs.index(this);
                    console.log(index);
                    console.log(inputs.length );
                    if (index !== -1) {
                        if (index === inputs.length - 1) {
                            addNewRow(); // إضافة صف جديد فقط إذا كان المستخدم في آخر حقل نشط
                        } else {
                            inputs.eq(index + 1).focus(); // الانتقال إلى الحقل التالي
                        }
                    }
                }
            });

            // **معالجة التنقل في select2 عند الضغط على Enter**
            $(document).on('select2:close', '.select2, .select-cost-center', function () {
                let inputs = $('input, select').not('[disabled], [readonly]');
                let index = inputs.index(this);
                if (index !== -1 && index < inputs.length - 1) {
                    inputs.eq(index + 1).focus(); // الانتقال إلى الحقل التالي بعد إغلاق select2
                }
            });

            // دالة لإنشاء سطر جديد
            function addNewRow() {
                let newRow = `<tr class="entry-row">
                <td>
                    <select class="account-select select2">
                        @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->account_number }} - {{ $account->name }}</option>
                        @endforeach
                </select>
            </td>
            <td><input type="number" class="debit"></td>
            <td><input type="number" class="credit"></td>
            <td>
                <select class="cost-center-select select-cost-center">
@foreach($costcenters as $costcenter)
                <option value="{{ $costcenter->id }}">{{ $costcenter->name }}</option>
                        @endforeach
                </select>
            </td>
            <td><input type="text" class="notes"></td>
            <td><button class="action-btn delete-row"><i class="fas fa-trash"></i></button></td>
        </tr>`;

                $('#entriesTable tbody').append(newRow);
                $('.select2, .select-cost-center').select2({ width: '100%', placeholder: "اختر", allowClear: true });

                // ضبط التركيز على أول عنصر في الصف الجديد
                setTimeout(() => $('#entriesTable tbody tr:last-child td:first-child select').focus(), 100);
            }

            // تعطيل أحد الحقول عند إدخال قيمة في الآخر
            $(document).on('input', '.debit', function () {
                let row = $(this).closest('tr');
                let creditField = row.find('.credit');
                if ($(this).val().trim() !== '' && $(this).val() != 0) {
                    creditField.prop('disabled', true).val('-');
                } else {
                    creditField.prop('disabled', false).val('');
                }
            });

            $(document).on('input', '.credit', function () {
                let row = $(this).closest('tr');
                let debitField = row.find('.debit');
                if ($(this).val().trim() !== '' && $(this).val() != 0) {
                    debitField.prop('disabled', true).val('-');
                } else {
                    debitField.prop('disabled', false).val('');
                }
            });

            // حذف الصف عند النقر على زر الحذف
            $(document).on('click', '.delete-row', function () {
                if($(this).closest('tr').data('id')){
                    let entryId = $(this).closest('tr').data('id');
                    let row = $(this).closest('tr');
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
                                url: `/journal-entry-details/${entryId}`, // تأكد أن المسار صحيح
                                type: "DELETE",
                                data: {
                                    _token: $('meta[name="csrf-token"]').attr("content")
                                },
                                success: function(response) {
                                    row.remove();

                                },
                                error: function(xhr) {
                                    Swal.fire("خطأ!", "حدث خطأ أثناء الحذف.", "error");
                                }
                            });
                        }
                    });
                }else {
                    $(this).closest('tr').remove();
                }


            });

            $('#saveEntry').on('click', function() {
                let entries = [];
                let totalDebit = 0;
                let totalCredit = 0;

                $('#entriesTable tbody tr').each(function() {
                    let row = $(this);
                    let debit = parseFloat(row.find('.debit').val()) || 0;
                    let credit = parseFloat(row.find('.credit').val()) || 0;

                    let entry = {
                        id: row.data('id') ?? null,
                        account_id: row.find('.account-select').val(),
                        debit: debit,
                        credit: credit,
                        cost_center: row.find('.cost-center-select').val(),
                        notes: row.find('.notes').val(),
                    };

                    totalDebit += debit;
                    totalCredit += credit;

                    entries.push(entry);
                });
                console.log(entries);

                // Check if total debit and credit are equal
                console.log(totalCredit);
                console.log(totalDebit);
                if (totalDebit != totalCredit) {
                    Swal.fire({
                        title: 'خطأ!',
                        text: "يجب أن يكون مجموع المدين والدائن متساويًا.",
                        icon: 'error',
                        confirmButtonText: 'موافق'
                    });
                    return; // Stop the function execution
                }

                let data = {
                    entry_id: $('#entryNumber').val(),
                    branch: $('#branchSelect').val(),
                    date: $('#entryDate').val(),
                    entries: entries,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                console.log(data);

                $.ajax({
                    url: "{{ route('journal-entry.update') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        Swal.fire({
                            title: 'نجاح!',
                            text: "تم حفظ قيود اليوميه",
                            icon: 'success',
                            confirmButtonText: 'موافق'
                        });
                        window.location.href = '/journal-entry/index';
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<div class="error-message"><ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '' + value[0] + '<br>';
                        });
                        errorHtml += '</ul></div>';
                        $('#responseMessage').html(errorHtml);
                    }
                });
            });

        });
    </script>



@endsection
