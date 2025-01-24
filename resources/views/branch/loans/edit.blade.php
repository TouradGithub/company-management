@extends('layouts.branch')

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

    <h1>تعديل سلف</h1>
    <form action="{{ route('branch.loans.update', $loan->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT method for update -->



        <div class="form-group">
            <label for="employees">الموظفين:</label>
            <select id="employees" name="employee_id" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $loan->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" name="amount" id="amount" value="{{ $loan->amount }}">
        </div>

        <div class="form-group">
            <label for="loan_date">Loan Date</label>
            <input type="date" name="loan_date" id="loan_date" class="form-control" value="{{ $loan->loan_date }}">
        </div>

        <button class="btn btn-primary mb-3">تحديث</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('overtime.js') }}"></script>
@endsection
