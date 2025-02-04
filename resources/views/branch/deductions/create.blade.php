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
              <select id="employees" name="employe_id" required>
                @foreach ($employees as $item)

                <option value="{{$item->id}}"> {{$item->name}}</option>
                @endforeach
              </select>
            </div>

        <div class="form-group">
            <label for="deduction_date">التاريخ </label>
            <input type="date" name="deduction_date" id="deduction_date" class="form-control">
        </div>

        <div class="form-group">
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
        <div class="form-group" id="fixed_amount_field" style="display: none;">
            <label for="deduction_value">قيمة المبلغ الثابت</label>
            <input type="number" name="deduction_value" id="deduction_value" class="form-control">
        </div>
        <div class="form-group">
            <label for="reason">سبب </label>
            <textarea name="reason" id="reason" class="form-control" style="width: 100%"  rows="5"></textarea>
        </div>

        <button   style="margin-top: 5px" class="save-btn">حفظ</button>
    </form>
</div>
@endsection
@section('js')


<script src="{{asset('overtime.js')}}"></script>
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
