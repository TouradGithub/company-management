@extends('layouts.masterbranch')

@section('content')
<div class="container">

    @if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="section-header">
        <h2>إضافة خصم جديد</h2>
    </div>

    <div class="add-advance-content">
        <form action="{{ route('branch.deductions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="employees">الموظفين:</label>
                <select id="employees"  class="form-control" name="employe_id" required>
                    @foreach ($employees as $item)
                        <option data-salary="{{ $item->basic_salary }}" value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="employee-details hidden">
                <div class="info-card checkbox-group " style="margin-bottom: 10px">

                    <div class="info-row">
                        <span class="info-label">الراتب الأساسي:</span>
                        <span   id="basicSalary" class=" info-value" ></span>
                    </div>

                </div>
              </div>

            <div class="form-group">
                <label for="deduction_date">التاريخ</label>
                <input type="date" name="deduction_date" id="deduction_date" class="form-control">
            </div>

            <div class="form-group" id="deduction_days_hidden">
                <label for="deduction_days">عدد الايام</label>
                <input type="number" name="deduction_days" id="deduction_days" class="form-control">
            </div>

            <div class="form-group">
                <label for="deduction_type">نوع الخصم</label>
                <select name="deduction_type" id="deduction_type" class="form-control">
                    <option value="salary_percentage">قيمة الخصم حسب الراتب</option>
                    <option value="fixed_amount">مبلغ ثابت</option>
                </select>
            </div>

            <div class="form-group" id="fixed_amount_field">
                <label for="deduction_value">قيمة المبلغ الثابت</label>
                <input type="text" id="deduction_value" class="form-control">
                <input type="hidden" name="deduction_value" id="deduction_value_total_value">
            </div>

            <div class="form-group">
                <label for="reason">سبب</label>
                <textarea name="reason" id="reason" class="form-control" style="width: 100%" rows="5"></textarea>
            </div>

            <div class="total-section">
                <input type="hidden" name="totalAmount" id="totalAmountHidden">
                <h3 class="save-btn" style="text-align: center">الإجمالي: <span id="deduction_value_total">0</span> ريال</h3>
            </div>

            <button style="margin-top: 5px" class="save-btn" type="submit">حفظ</button>
        </form>
    </div>
</div>
@endsection

@section('js')


<script>
document.addEventListener('DOMContentLoaded', function () {
    const employeesSelect = document.getElementById('employees');
    const deductionType = document.getElementById('deduction_type');
    const deductionDays = document.getElementById('deduction_days');
    const deductionValueInput = document.getElementById('deduction_value');
    const deductionValueTotal = document.getElementById('deduction_value_total');
    const totalAmountHidden = document.getElementById('totalAmountHidden');
    const fixedAmountField = document.getElementById('fixed_amount_field');
    const deductionDaysField = document.getElementById('deduction_days_hidden');
    const employeeDetails = document.querySelector('.employee-details');

    function toggleFields() {
        if (deductionType.value === 'fixed_amount') {
            deductionDaysField.style.display = 'none';
            fixedAmountField.style.display = 'block';
            deductionValueInput.setAttribute('required', 'required');
        } else {
            deductionDaysField.style.display = 'block';
            fixedAmountField.style.display = 'none';
            deductionValueInput.removeAttribute('required');
            deductionValueInput.value = '';
        }
        calculateDeduction();
    }
    function getSalary() {
        const employeesSelect = document.getElementById('employees');
        const selectedEmploy = employeesSelect.options[employeesSelect.selectedIndex];
        const salary = parseFloat(selectedEmploy.getAttribute('data-salary'));

        document.getElementById('basicSalary').textContent = salary + ' ريال';

        employeeDetails.classList.remove('hidden');

    }

    function calculateDeduction() {
        const selectedEmployee = employeesSelect.options[employeesSelect.selectedIndex];
        if (!selectedEmployee) return;

        const salary = parseFloat(selectedEmployee.getAttribute('data-salary')) || 0;
        const days = parseInt(deductionDays.value) || 0;
        let deductionAmount = 0;

        if (deductionType.value === 'salary_percentage') {
            deductionAmount = (salary / 30) * days;
            deductionValueInput.value = deductionAmount.toFixed(2);
        } else {
            deductionAmount = parseFloat(deductionValueInput.value) || 0;
        }

        deductionValueTotal.innerHTML = `${deductionAmount.toFixed(2)} ريال`;
        totalAmountHidden.value = deductionAmount.toFixed(2);
        document.getElementById('deduction_value_total_value').value = deductionAmount.toFixed(2);
    }

    deductionType.addEventListener('change', toggleFields);
    employeesSelect.addEventListener('change', function() {
        getSalary();
        calculateDeduction();
    });
    deductionDays.addEventListener('input', calculateDeduction);
    deductionValueInput.addEventListener('input', calculateDeduction);

    toggleFields();
    getSalary();
});
</script>
{{-- <script src="{{asset('overtime.js')}}"></script> --}}
@endsection
