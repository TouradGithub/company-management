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
                <select id="employees" name="employe_id"  class="form-control" required>
                    @foreach ($employees as $employee)
                    <option data-salary="{{ $employee->basic_salary }}" value="{{ $employee->id }}" {{ $employee->id == $deduction->employee_id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="employee-details ">
                <div class="info-card checkbox-group " style="margin-bottom: 10px">

                    <div class="info-row">
                        <span class="info-label">الراتب الأساسي:</span>
                        <span   id="basicSalary" class=" info-value" > {{ $deduction->employee->basic_salary }} ريال</span>
                    </div>

                </div>
              </div>

            <div class="form-group">
                <label for="deduction_date">التاريخ</label>
                <input type="date" name="deduction_date" id="deduction_date" class="form-control" value="{{ $deduction->deduction_date }}">
            </div>

            <div class="form-group" id="deduction_days_hidden">
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

            <div class="form-group" id="month_days_field" style="{{ $deduction->deduction_type == 'fixed_amount' ? 'display: none;' : '' }}">
                <label for="month_days">عدد أيام الشهر</label>
                <input type="number" name="month_days" id="month_days" value="{{$deduction->month_days}}" class="form-control" >
            </div>
            <div class="form-group" id="fixed_amount_field" style="{{ $deduction->deduction_type == 'fixed_amount' ? '' : 'display: none;' }}">
                <label for="deduction_value">قيمة المبلغ الثابت</label>
                <input type="text"  id="deduction_value" class="form-control" value="{{ $deduction->deduction_value }}">

            </div>


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
    const employeeDetails = document.querySelector('.employee-details');

    const monthDaysField = document.getElementById('month_days_field');
    const monthDaysInput = document.getElementById('month_days');

    function getDaysInCurrentMonth() {
        const now = new Date();
        return new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
    }

    function getSalary() {
        const employeesSelect = document.getElementById('employees');
        const selectedEmploy = employeesSelect.options[employeesSelect.selectedIndex];
        const salary = parseFloat(selectedEmploy.getAttribute('data-salary'));

        document.getElementById('basicSalary').textContent = salary + ' ريال';

        employeeDetails.classList.remove('hidden');

    }
    function toggleFields() {
        if (deductionType.value === 'fixed_amount') {
            deductionDaysField.style.display = 'none';
            fixedAmountField.style.display = 'block';
            monthDaysField.style.display = 'none';
            if(deductionValueInput){
                deductionValueInput.setAttribute('required', 'required');
            }

        } else {
            deductionDaysField.style.display = 'block';
            fixedAmountField.style.display = 'none';
            monthDaysField.style.display = 'block';
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
        const totalMonthDays = parseInt(monthDaysInput.value) || getDaysInCurrentMonth();
        let deductionAmount = 0;

        if (deductionType.value === 'salary_percentage') {
            deductionAmount =  (salary / totalMonthDays) * days;

            deductionTHidden.value =deductionAmount.toFixed(2);


            if(deductionValueInput){
                deductionValueInput.value = deductionAmount.toFixed(2);
            }
        } else {
            deductionAmount = parseFloat(deductionValueInput.value) || 0;
        }

        deductionValueTotal.innerHTML = `${deductionAmount.toFixed(2)} ريال`;
        deductionTHidden.value = deductionAmount.toFixed(2);
    }

    deductionType.addEventListener('change', toggleFields);
    employeesSelect.addEventListener('change', function() {
        getSalary();  // Updates the details (iqama number, salary)
        calculateDeduction();  // Calculates the deduction based on salary
    });
    deductionDays.addEventListener('input', calculateDeduction);
    deductionValueInput.addEventListener('input', calculateDeduction);
    monthDaysInput.addEventListener('input', calculateDeduction);

    toggleFields();
    getSalary();
});
</script>
{{-- <script src="{{asset('overtime.js')}}"></script></script> --}}
@endsection
