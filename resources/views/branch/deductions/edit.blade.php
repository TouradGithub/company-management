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
        <h2>تعديل خصم </h2>
    </div>

    <div class="add-advance-content">
        <form action="{{ route('branch.deductions.update', $deduction->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- إضافة طريقة التحديث -->



            <div class="form-group">
                <label for="employees">الموظفين:</label>
                <select id="employees" name="employe_id" required>
                    @foreach ($employees as $employee)
                    <option data-salary="{{ $employee->basic_salary }}" value="{{ $employee->id }}" {{ $employee->id == $deduction->employee_id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="deduction_date">التاريخ</label>
                <input type="date" name="deduction_date" id="deduction_date" class="form-control" value="{{ $deduction->deduction_date }}">
            </div>

            <div class="form-group">
                <label for="deduction_days">عدد الأيام</label>
                <input type="number" name="deduction_days" id="deduction_days" class="form-control" value="{{ $deduction->deduction_days }}">
            </div>

            <div class="form-group">
                <label for="deduction_type">نوع الخصم</label>
                <select name="deduction_type" id="deduction_type" class="form-control">
                    <option value="salary_percentage" {{ $deduction->deduction_type == 'salary_percentage' ? 'selected' : '' }}>قيمة الخصم حسب الراتب</option>
                    <option value="fixed_amount" {{ $deduction->deduction_type == 'fixed_amount' ? 'selected' : '' }}>مبلغ ثابت</option>
                </select>
            </div>
            @if ($deduction->deduction_type == 'fixed_amount')
            <div class="form-group" id="fixed_amount_field" style="{{ $deduction->deduction_type == 'fixed_amount' ? '' : 'display: none;' }}">
                <label for="deduction_value">قيمة المبلغ الثابت</label>
                <input type="number" name="deduction_value" id="deduction_value" class="form-control" value="{{ $deduction->deduction_value }}">
            </div>
            @endif

            <input type="hidden" name="deduction_value" id="deduction_value_t_hidden" class="form-control" value="{{ $deduction->deduction_value }}">


            <div class="form-group">
                <label for="reason">سبب </label>
                <textarea name="reason" id="reason" class="form-control" style="width: 100%"  rows="5">{{ $deduction->reason }}</textarea>
            </div>
            <div class="total-section">
                <input type="hidden" name="totalAmount" id="totalAmountHidden">
                <h3 class="save-btn" style="text-align: center">الإجمالي: <span id="deduction_value_total">{{ $deduction->deduction_value }}</span> ريال</h3>
            </div>

            <button  style="margin-top: 5px" class="save-btn">تحديث</button>
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
    const deductionTHidden = document.getElementById('deduction_value_t_hidden');

    function toggleFields() {
        if (deductionType.value === 'fixed_amount') {
            deductionDaysField.style.display = 'none';
            fixedAmountField.style.display = 'block';
            if(deductionValueInput){
                deductionValueInput.setAttribute('required', 'required');
            }

        } else {
            deductionDaysField.style.display = 'block';
            fixedAmountField.style.display = 'none';
            deductionValueInput.removeAttribute('required');
            if(deductionValueInput){
                deductionValueInput.value = '';
            }

        }
        calculateDeduction();
    }

    function calculateDeduction() {
        const selectedEmployee = employeesSelect.options[employeesSelect.selectedIndex];
        if (!selectedEmployee) return;

        const salary = parseFloat(selectedEmployee.getAttribute('data-salary')) || 0;
        const days = parseInt(deductionDays.value) || 0;
        let deductionAmount = 0;

        if (deductionType.value === 'salary_percentage') {
            deductionAmount = (salary / 30) * days;

            deductionTHidden.value =deductionAmount.toFixed(2);


            if(deductionValueInput){
                deductionValueInput.value = deductionAmount.toFixed(2);
            }
        } else {
            deductionAmount = parseFloat(deductionValueInput.value) || 0;
        }

        deductionValueTotal.innerHTML = `${deductionAmount.toFixed(2)} ريال`;
        totalAmountHidden.value = deductionAmount.toFixed(2);
        document.getElementById('deduction_value_total_value').value = deductionAmount.toFixed(2);
    }

    deductionType.addEventListener('change', toggleFields);
    employeesSelect.addEventListener('change', calculateDeduction);
    deductionDays.addEventListener('input[type="number"]', calculateDeduction);
    deductionValueInput.addEventListener('input[type="text"]', calculateDeduction);

    toggleFields();
});
</script>
<script src="{{asset('overtime.js')}}"></script></script>
@endsection
