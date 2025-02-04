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
        <h2>تعديل سلف </h2>
    </div>

    <div class="add-advance-content">
    <form action="{{ route('loans.update', $loan->id) }}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf
        @method('PUT') <!-- Use PUT method for update -->

            <div class="form-group">
                <label for="branches">الفروع:</label>
                <select id="branches"  class="form-control" multiple required>
                    @foreach ($branches as $item)
                        <option value="{{ $item->id }}" {{ $loan->branch_id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>


        <div class="form-group">
            <label for="employees">الموظفين:</label>
            <select id="employees" name="employee_id" class="form-control" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $loan->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text"  class="form-control" name="amount" id="amount" value="{{ $loan->amount }}">
        </div>

        <div class="form-group">
            <label for="loan_date">Loan Date</label>
            <input type="date" name="loan_date"  id="loan_date" class="form-control" value="{{ $loan->loan_date }}">
        </div>

        <button   style="margin-top: 30px" class="save-btn">تحديث</button>
    </form>
</div>
@endsection

@section('js')
<script src="{{ asset('overtime.js') }}"></script>
@endsection
