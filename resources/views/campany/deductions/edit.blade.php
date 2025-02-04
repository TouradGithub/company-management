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
        <h2>تعديل خصم </h2>
    </div>

    <div class="add-advance-content">
        <form action="{{ route('deductions.update', $deduction->id) }}" method="POST"  id="add-advance-form" class="standard-form">
            @csrf
            @method('PUT') <!-- إضافة طريقة التحديث -->

            <div class="search-bar fade-in">
                <div class="form-group">
                    <label for="branches">الفروع:</label>
                    <select id="branches" name="branch_id" multiple required>
                        @foreach ($branches as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $deduction->branch_id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="employees">الموظفين:</label>
                <select id="employees" name="employe_id" required>
                    @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $employee->id == $deduction->employee_id ? 'selected' : '' }}>
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

            <div class="form-group" id="fixed_amount_field" style="{{ $deduction->deduction_type == 'fixed_amount' ? '' : 'display: none;' }}">
                <label for="deduction_value">قيمة المبلغ الثابت</label>
                <input type="number" name="deduction_value" id="deduction_value" class="form-control" value="{{ $deduction->deduction_value }}">
            </div>
            <div class="form-group">
                <label for="reason">سبب </label>
                <textarea name="reason" id="reason" class="form-control" style="width: 100%"  rows="5">{{ $deduction->reason }}</textarea>
            </div>

            <button style="margin-top: 30px" class="save-btn">تحديث</button>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('overtime.js') }}"></script>
<script>
    document.getElementById('deduction_type').addEventListener('change', function () {
        const fixedAmountField = document.getElementById('fixed_amount_field');
        if (this.value === 'fixed_amount') {
            fixedAmountField.style.display = 'block';
        } else {
            fixedAmountField.style.display = 'none';
        }
    });
</script>
@endsection
