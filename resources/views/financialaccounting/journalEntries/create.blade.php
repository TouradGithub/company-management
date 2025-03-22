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

            <div class="table-actions">

                <div class="form-group">
                    <label>الدفتر:</label>
                    <select id="journal_id" >
                        <option value="">اختر الدفتر</option>
                        @foreach($journals as $item)
                            <option value="{{$item->id}}"> {{$item->name}}</option>
                        @endforeach

                    </select>
                </div>

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
                    <label>التاريخ:</label>
                    <input type="date" id="entryDate">
                </div>


                <div class="form-group">
                    <label>رقم القيد:</label>
                    <input type="number" id="entryDate" value="{{$entry_number}}">
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
            <button  id="saveEntry" type="submit" style="  padding: 0.8rem 1.5rem;background: rgb(30,144,255);
                 width: 20%; color: white; border: none;border-radius: 8px; cursor: pointer;font-weight: 600;
                  transition: all 0.3s ease;">حفظ</button>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
            $(document).ready(function () {
                // تفعيل Select2 عند تحميل الصفحة
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

            document.addEventListener('DOMContentLoaded', function() {
                $(document).on('click', '.delete-row', function() {
                    $(this).closest('tr').remove();
                    checkRows();
                });

            const accountsData = @json($accounts);
            const costCentersData = @json($costcenters);

            function populateSelect(select, data) {
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.code} - ${item.name}`;
                    select.appendChild(option);
                });
            }

            function populateAccountSelect(select, data) {
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
                    <td><select class="account-select select2"></select></td>
                    <td><input type="number" class="debit"></td>
                    <td><input type="number" class="credit"></td>
                    <td><select class="cost-center-select select-cost-center"></select></td>
                    <td><input type="text" class="notes"></td>
                    <td><button class="action-btn delete-row" ><i class="fas fa-trash"></i></button></td>
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

                function checkRows() {
                    let rowCount = document.querySelectorAll('#entriesTable tbody tr').length;
                    let saveButton = document.getElementById('saveEntry');
                    console.log("O");
                    if (rowCount === 0) {
                        saveButton.style.display = 'none'; // إخفاء الزر
                    } else {
                        saveButton.style.display = 'block'; // إظهاره عند وجود صفوف
                    }
                }

            function moveToNextField(input) {
                const row = input.closest('tr');
                const inputs = [...row.querySelectorAll('input, select')]; // يشمل الحقول النصية و select
                let currentIndex = inputs.indexOf(input);

                if (currentIndex < inputs.length - 1) {
                    // if(!inputs[currentIndex+1].disabled){
                    //     inputs[currentIndex + 2].focus();
                    // }
                    inputs[currentIndex + 1].focus(); // الانتقال للحقل التالي
                } else {
                    // إذا كان آخر حقل، أضف صفًا جديدًا وانتقل لأول حقل به
                    // const newRow = addNewRow();
                    // newRow.querySelector('input, select').focus();
                }
                // while (currentIndex < inputs.length - 1) {
                //     currentIndex++;
                //     if (!inputs[currentIndex].disabled) {
                //         inputs[currentIndex].focus();
                //         return;
                //     }
                // }
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
                    branch: $('#branchSelect').val(),
                    journal_id: $('#journal_id').val(),
                    date: $('#entryDate').val(),
                    entries: entries,
                    _token: $('meta[name="csrf-token"]').attr('content')
                };

                console.log(data);

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
