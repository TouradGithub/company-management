@extends('layouts.appcompany')

@section('content')
<h1>تعديل العمل الإضافي</h1>

<form action="{{ route('company.overtimes.update', $overtime->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="employee_id">الموظف</label>
        <select name="employee_id" id="employee_id" required>
            <option value="" disabled>اختر الموظف</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ $employee->id == $overtime->employee_id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="branch_id">الفرع</label>
        <select name="branch_id" id="branch_id" required>
            <option value="" disabled>اختر الفرع</option>
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ $branch->id == $overtime->branch_id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="fixed_amount">مبلغ الإضافي ثابت</label>
        <input type="number" name="fixed_amount" id="fixed_amount" value="{{ $overtime->fixed_amount }}" required>
    </div>

    <div>
        <label for="percentage_of_salary">مبلغ الإضافي حسب الراتب</label>
        <input type="number" name="percentage_of_salary" id="percentage_of_salary" value="{{ $overtime->percentage_of_salary }}" required>
    </div>

    <div>
        <label for="overtime_hours">عدد ساعات الإضافي</label>
        <input type="number" name="overtime_hours" id="overtime_hours" value="{{ $overtime->overtime_hours }}" required>
    </div>

    <div>
        <label for="overtime_value">قيمة الإضافي</label>
        <input type="number" name="overtime_value" id="overtime_value" value="{{ $overtime->overtime_value }}" readonly>
    </div>

    <div>
        <label for="basic_salary">الراتب الأساسي</label>
        <input type="number" name="basic_salary" id="basic_salary" value="{{ $overtime->basic_salary }}" readonly>
    </div>

    <button type="submit">تعديل الإضافي</button>
</form>
@endsection
