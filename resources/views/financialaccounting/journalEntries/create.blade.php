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
            <h2>قيد يوميه</h2>
            <div class="table-actions" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; gap: 1rem;">
                    <div class="form-group" style="width: 50%">
                        <label>الدفتر:</label>
                        <select id="journal_id">
                            <option value="">اختر الدفتر</option>
                            @foreach($journals as $item)
                                <option value="{{$item->id}}"> {{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group"  style="width: 50%">
                        <label>الفرع:</label>
                        <select id="branchSelect">
                            <option value="">اختر الفرع</option>
                            @foreach($branches as $item)
                                <option value="{{$item->id}}"> {{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <div class="form-group" style="width: 50%">
                        <label>التاريخ:</label>
                        <input type="date" id="entryDate" value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group"  style="width: 50%">
                        <label>رقم القيد:</label>
                        <input type="number" id="entryNumber" value="{{$entry_number}}">
                    </div>
                </div>
            </div>
            <div class="accounts-table-container">
                <table class="accounts-table" id="entriesTable">
                    <thead>
                    <tr>
                        <th>رقم الحساب</th>
                        <th> مدين</th>
                        <th>دائن</th>
                        <th> مكز التكلفة</th>
                        <th> ملاحظه</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="entry-row">
                        <td>
                            <select class="account-select select2"></select>
                        </td>
                        <td><input type="number" class="debit"></td>
                        <td><input type="number" class="credit"></td>
                        <td>
                            <select class="cost-center-select select-cost-center"></select>
                        </td>
                        <td><input type="text" class="notes"></td>
                        <td><button class="action-btn delete-row" ><i class="fas fa-trash"></i></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="totals" style="display: flex; margin: 10px; justify-content: space-between; padding: 10px; background-color: #f7f7f7; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <div style="text-align: center; font-size: 16px; font-weight: 600;">
                    <span style="color: #4CAF50;">إجمالي المدين: </span><span id="totalDebit">0</span>
                </div>
                <div style="text-align: center; font-size: 16px; font-weight: 600;">
                    <span style="color: #2196F3;">إجمالي الدائن: </span><span id="totalCredit">0</span>
                </div>
                <div style="text-align: center; font-size: 16px; font-weight: 600;">
                    <span style="color: #FF5722;">القيد غير متساوي: </span><span id="balanceDiff">0</span>
                </div>
            </div>
            <button  id="saveEntry" type="submit" style="  padding: 0.8rem 1.5rem;background: rgb(30,144,255);
                 width: 20%; color: white; border: none;border-radius: 8px; cursor: pointer;font-weight: 600;
                  transition: all 0.3s ease;">حفظ</button>
            <button  id="addNewLine" type="button" style="  padding: 0.8rem 1.5rem;background: rgb(30,144,255);
                 width: 10%; color: white; border: none;border-radius: 8px; cursor: pointer;font-weight: 600;
                  transition: all 0.3s ease;">أضف سطر</button>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
            $(document).ready(function () {

                $('.select2').select2({
                    width: '100%',
                    height:'100%',
                    placeholder: "اختر الحساب",
                    allowClear: true
                });
                $('.select-cost-center').select2({
                    width: '100%',
                    height:'100%',
                    placeholder: "اختر الحساب",
                    allowClear: true
                });


            });

            function updateTotals() {
                let totalDebit = 0;
                let totalCredit = 0;

                $('#entriesTable tbody tr').each(function() {
                    let row = $(this);
                    let debit = parseFloat(row.find('.debit').val()) || 0;
                    let credit = parseFloat(row.find('.credit').val()) || 0;

                    totalDebit += debit;
                    totalCredit += credit;
                });

                // عرض الإجماليات في الصفحة
                $('#totalDebit').text(totalDebit.toFixed(2));
                $('#totalCredit').text(totalCredit.toFixed(2));
                $('#balanceDiff').text((totalDebit - totalCredit).toFixed(2));
            }


            document.addEventListener('DOMContentLoaded', function() {
                $(document).on('click', '.delete-row', function() {
                    $(this).closest('tr').remove();

                });

            const accountsData = @json($accounts);
            const costCentersData = @json($costcenters);

            function populateSelect(select, data) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = `اختر مركز تكلفه`;
                select.appendChild(option);

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.code} - ${item.name}`;
                    select.appendChild(option);
                });
            }

            function populateAccountSelect(select, data) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = `اختر الحساب`;
                select.appendChild(option);
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.account_number } - ${item.name}`;
                    select.appendChild(option);
                });
            }
                $('.select2').on('select2:select', function () {
                    moveToNextField(this);
                });
                $('.select-cost-center').on('select2:select', function () {
                    moveToNextField(this);
                });

            function addNewRow() {
                const table = document.getElementById('entriesTable').getElementsByTagName('tbody')[0];
                const newRow = document.createElement('tr');
                newRow.className = 'entry-row';
                newRow.innerHTML = `
                    <td style="width: 16%;height: 100%"><select style=" height: 100% !important;width:100%  !important" class="account-select select2"></select></td>
                    <td style="width: 16%;"><input type="number" class="debit"></td>
                    <td style="width: 16%;"><input type="number" class="credit"></td>
                    <td style="width: 16%;"><select style=" height: 100% !important;" class="cost-center-select select-cost-center"></select></td>
                    <td style="width: 16%;"><input type="text" class="notes"></td>
                    <td style="width: 16%;"><button class="action-btn delete-row"><i class="fas fa-trash"></i></button></td>
                `;

                table.appendChild(newRow);
                $(newRow).find('.select2').select2({
                    width: '100%',
                    placeholder: "اختر الحساب",
                    allowClear: true
                }).on('select2:select', function () {
                    moveToNextField(this);
                });
                $(newRow).find('.select-cost-center').select2({
                    width: '100%',
                    placeholder: "اختر لمركز",
                    allowClear: true
                }).on('select2:select', function () {
                    moveToNextField(this);
                });
                populateAccountSelect(newRow.querySelector('.account-select'), accountsData);
                populateSelect(newRow.querySelector('.cost-center-select'), costCentersData);

                addInputListeners(newRow);
            }
            $('#addNewLine').on('click',function (){
                const $tbody = $(`#entriesTable tbody`);
                const existingRows = $tbody.find('tr');

                // التحقق من السطر الأخير إذا كان موجودًا
                if (existingRows.length > 0) {
                    const lastRow = existingRows.last();
                    const itemSelect = lastRow.find('.account-select').val();
                    const itemcredit = lastRow.find('.debit').val();
                    const itemdebit= lastRow.find('.credit').val();
                    const itemcostCenter= lastRow.find('.select-cost-center').val();

                    if (!itemSelect || !itemcostCenter || (!itemcredit && !itemdebit)  ) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'خطأ',
                            text: 'يرجى ملئ القيد الحالي قبل إضافة سطر جديد',
                        });
                        return;
                    }
                }
                addNewRow();
                updateTotals();
            });

            function addInputListeners(row) {
                const inputs = [...row.querySelectorAll('input, select')];
                inputs.forEach((input, index) => {
                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (index < inputs.length - 1) {
                                inputs[index + 1].focus();
                            } else {
                                addNewRow();
                                document.querySelector('.entry-row:last-child .account-select').focus();
                            }
                        }
                    });
                });
            }

            function moveToNextField(input) {
                const row = input.closest('tr');
                const inputs = [...row.querySelectorAll('input, select')]; // يشمل الحقول النصية و select
                let currentIndex = inputs.indexOf(input);

                if (currentIndex < inputs.length - 1) {
                    console.log(currentIndex);
                    let nextIndex = currentIndex + 1;
                    console.log(inputs[nextIndex].disabled);
                    while (inputs[nextIndex].disabled) {
                        console.log(nextIndex);
                        nextIndex++;
                    }

                    // inputs[currentIndex + 1].focus();
                    if (nextIndex < inputs.length) {
                        inputs[nextIndex].focus();
                    }
                } else {


                }
            }

            // تعريف دالة حذف الصف
            function deleteRow(button) {
                const row = button.closest('tr');
                row.remove();
            }

            document.querySelectorAll('.entry-row').forEach(row => {
                populateAccountSelect(row.querySelector('.account-select'), accountsData);
                populateSelect(row.querySelector('.cost-center-select'), costCentersData);
                addInputListeners(row);
                updateTotals();
            });

            document.querySelectorAll('.account-input, .cost-center, .debit, .credit, .notes, select').forEach(input => {
                // عند الضغط على Enter، انتقل للحقل التالي
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();

                        moveToNextField(input);
                    }
                });

                input.addEventListener('change', () => {
                    moveToNextField(input);
                });
            });
            $(document).on('input', '.debit, .credit', function() {
                let row = $(this).closest('tr');
                let debitField = row.find('.debit');
                let creditField = row.find('.credit');

                if (debitField.val() !== '' && debitField.val() != 0) {
                    creditField.prop('disabled', true);
                } else if (creditField.val() !== '' && creditField.val() != 0) {
                    debitField.prop('disabled', true);
                } else {
                    debitField.prop('disabled', false);
                    creditField.prop('disabled', false);
                }

                updateTotals();
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

                // Check if total debit and credit are equal
                console.log(totalCredit);
                console.log(totalDebit);
                if (totalDebit != totalCredit && (totalCredit !=0 || totalDebit !=0)) {
                    Swal.fire({
                        title: 'خطأ!',
                        text: "يجب أن يكون مجموع المدين والدائن متساويًا.",
                        icon: 'error',
                        confirmButtonText: 'موافق'
                    });
                    return; // Stop the function execution
                }

                let data = {
                    branch: $('#branchSelect').val(),
                    journal_id: $('#journal_id').val(),
                    date: $('#entryDate').val(),
                    entries: entries,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };


                $.ajax({
                    url: "{{ route('journal-entry.store') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {
                        Swal.fire({
                            title: 'نجاح!',
                            text: "تم حفظ قيود اليوميه",
                            icon: 'success',
                            confirmButtonText: 'موافق'
                        });
                        $('#branchSelect').val('');
                        $('#entryDate').val(new Date().toISOString().split('T')[0]); // ضبط التاريخ إلى اليوم الحالي
                        $('#entriesTable tbody').html(`

                        `);
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
