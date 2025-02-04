@extends('layouts.mastercomany')

@section('content')

        @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="section-header">
    <h2>تعديل سلف </h2>
</div>

<div class="add-advance-content">
        <form action="{{ route('company.leaves.update', $leave->id) }}" method="POST"  id="add-advance-form" class="standard-form">
            @csrf
            @method('PUT')

            <div>
                <label for="branch_id">الفرع</label>
                <select name="branch_id"   class="form-control" id="branch_id" required>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ $branch->id == $leave->branch_id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="employee_id">الموظف</label>
                <select name="employee_id"  class="form-control" id="employee_id" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ $employee->id == $leave->employee_id ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date">تاريخ بدء الإجازة</label>
                <input type="date"  class="form-control" name="start_date" id="start_date" value="{{ $leave->start_date }}" required>
            </div>

            <div>
                <label for="end_date">تاريخ انتهاء الإجازة</label>
                <input type="date"   class="form-control" name="end_date" id="end_date" value="{{ $leave->end_date }}" required>
            </div>

            <div>
                <label for="reason">سبب الإجازة</label>
                <textarea name="reason"  class="form-control" id="reason" style="width: 100%" >{{ $leave->reason }}</textarea>
            </div>

            <button style="margin-top: 10px" class="save-btn" type="submit">تحديث الإجازة</button>
        </form>

    </div>
</div>

@endsection
