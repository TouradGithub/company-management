@extends('layouts.masterbranch')

@section('content')

<div class="content-sections">
    <div class="form-container active" id="form-container">
        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
<div style="color: red;">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<h1>تعديل إجازة</h1>
        <form action="{{ route('branch.leaves.update', $leave->id) }}" method="POST" method="POST" id="add-advance-form" class="standard-form">
            @csrf
            @method('PUT')



            <div>
                <label for="employee_id">الموظف</label>
                <select name="employee_id"  class="form-control"  id="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employee->id == $leave->employee_id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date">تاريخ بدء الإجازة</label>
                <input type="date"  class="form-control"  name="start_date" id="start_date" value="{{ $leave->start_date }}" required>
            </div>

            <div>
                <label for="end_date">تاريخ انتهاء الإجازة</label>
                <input type="date"  class="form-control"  name="end_date" id="end_date" value="{{ $leave->end_date }}" required>
            </div>

            <div>
                <label for="reason">سبب الإجازة</label>
                <textarea name="reason"  class="form-control"  id="reason" style="width: 100%"  rows="5">{{ $leave->reason }}</textarea>
            </div>

            <button  style="margin-top: 5px"   class="save-btn" type="submit">تحديث الإجازة</button>
        </form>

    </div>
</div>

@endsection
