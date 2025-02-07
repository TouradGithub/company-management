@extends('layouts.mastercomany')

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
    <form action="{{ route('deductions.store') }}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf
        <div class="search-bar fade-in">
            <div class="form-group">

              <label for="branches">الفروع:</label>
              <select id="branches" class="form-control" multiple  required>
                  @foreach ($branches as $item)

                  <option value="{{$item->id}}"> {{$item->name}}</option>
                  @endforeach
              </select>
          </div>
            </div>

            <div class="form-group">
                <label for="employees">الموظفين:</label>
                <select id="employees" name="employe_id" required class="form-control">
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
            <label for="deduction_date">التاريخ </label>
            <input type="date" name="deduction_date" id="deduction_date" class="form-control">
        </div>

        <div class="form-group " id="deduction_days_hidden">
            <label for="deduction_days">عدد الايام </label>
            <input type="number" name="deduction_days" id="deduction_days" class="form-control">
        </div>

        <div class="form-group">
            <label for="deduction_type">نوع الخصم</label>
            <select name="deduction_type" id="deduction_type" class="form-control">
                <option value="salary_percentage">قيمة الخصم حسب الراتب</option>
                <option value="fixed_amount">مبلغ ثابت</option>
            </select>
        </div>
        <div class="form-group" id="month_days_field">
            <label for="month_days">عدد أيام الشهر</label>
            <input type="number" name="month_days" id="month_days" class="form-control" >
        </div>

        <div class="form-group" id="fixed_amount_field" style="display: none;">
            <label for="deduction_value">قيمة المبلغ الثابت</label>
            <input type="text" name="deduction_value" id="deduction_value" class="form-control">
        </div>
        <div class="form-group">
            <label for="reason">سبب </label>
            <textarea name="reason" id="reason" class="form-control" style="width: 100%"  rows="5"></textarea>
        </div>
        <div class="total-section">
            <input type="hidden" name="totalAmount" id="totalAmountHidden">
            <h3 class="save-btn" style="text-align: center">الإجمالي: <span id="deduction_value_total">0</span> ريال</h3>
        </div>
        {{-- <span style="margin-top: 30px;text-align: center"  id="deduction_value_total"  class="save-btn"></span> --}}

        <button style="margin-top: 30px" class="save-btn">حفظ</button>
    </form>
</div>
@endsection
@section('js')


<script>
     $('#branches').select2({
        placeholder: 'اختر الموظفين',
        dir: 'rtl',
        language: 'ar'
      });
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

    const monthDaysField = document.getElementById('month_days_field');
    const monthDaysInput = document.getElementById('month_days');
    monthDaysInput.value = getDaysInCurrentMonth();

    $('#branches').on('change', function() {
      const selectedBranches = $(this).val();
      console.log("OK------------");
      // Filter employees based on selected branches
      $.ajax({
          url: '/get-employees-by-branch',
          method: 'GET',
          data: { branches: [selectedBranches] },
          success: function(response) {
            const employeesSelect = $('#employees').get(0);

            // Clear the current options
            employeesSelect.innerHTML = '';
             employees = response;

            // Add new options for filtered employees
            response.forEach(emp => {
                const option = document.createElement('option');
                option.value = emp.id;
                option.textContent = emp.name;
                console.log(emp.basic_salary);
                option.setAttribute('data-salary', emp.basic_salary);

                employeesSelect.appendChild(option);
                console.log(emp.name, emp.salary);
            });

            // Trigger Select2 update to refresh the dropdown
            $('#employees').trigger('change');
            getSalary();
          },
          error: function(xhr, status, error) {
            console.error("There was an error fetching the employees: ", error);
          }
        });

      // Trigger Select2 update
      $('#employees').trigger('change');

    });

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
            monthDaysField.style.display = 'none';
            deductionDaysField.style.display = 'none';
            fixedAmountField.style.display = 'block';
            deductionValueInput.setAttribute('required', 'required');
        } else {
            monthDaysField.style.display = 'block';
            deductionDaysField.style.display = 'block';
            fixedAmountField.style.display = 'none';
            deductionValueInput.removeAttribute('required');
            deductionValueInput.value = '';
        }
        calculateDeduction();
    }

    function calculateDeduction() {
        const selectedEmployee = employeesSelect.options[employeesSelect.selectedIndex];


        if (!selectedEmployee) return;

        const salary = parseFloat(selectedEmployee.getAttribute('data-salary')) || 0;
        const days = parseInt(deductionDays.value) || 0;
        const totalMonthDays = parseInt(monthDaysInput.value) || getDaysInCurrentMonth();
        console.log(salary);
        let deductionAmount = 0;

        if (deductionType.value === 'salary_percentage') {
            deductionAmount = (salary / totalMonthDays) * days;
            if(deductionValueInput){
                deductionValueInput.value = deductionAmount.toFixed(2);
            }
        } else {
            deductionAmount = parseFloat(deductionValueInput.value) || 0;
        }

        deductionValueTotal.innerHTML = `${deductionAmount.toFixed(2)} ريال`;
        totalAmountHidden.value = deductionAmount.toFixed(2);
        // document.getElementById('deduction_value_total_value').value = deductionAmount.toFixed(2);
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
});



    // Calculate deduction based on salary


</script>

<script src="{{asset('overtime.js')}}"></script>
@endsection
