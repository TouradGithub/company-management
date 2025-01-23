@extends('layouts.appcompany')

@section('content')

<div class="content-sections">
    <div class="form-container active" id="form-container">
        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

        <form action="{{ route('company.leaves.update', $leave->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="branch_id">الفرع</label>
                <select name="branch_id" id="branch_id" required>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == $leave->branch_id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="employee_id">الموظف</label>
                <select name="employee_id" id="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employee->id == $leave->employee_id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date">تاريخ بدء الإجازة</label>
                <input type="date" name="start_date" id="start_date" value="{{ $leave->start_date }}" required>
            </div>

            <div>
                <label for="end_date">تاريخ انتهاء الإجازة</label>
                <input type="date" name="end_date" id="end_date" value="{{ $leave->end_date }}" required>
            </div>

            <div>
                <label for="reason">سبب الإجازة</label>
                <textarea name="reason" id="reason" style="width: 100%" >{{ $leave->reason }}</textarea>
            </div>

            <button type="submit">تحديث الإجازة</button>
        </form>

    </div>
</div>

@endsection
