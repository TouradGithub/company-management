@extends('layouts.overtime')

@section('content')
<div class="container">
    <h1>Edit Deduction</h1>
    <form action="{{ route('deductions.update', $deduction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="employee_id">Employee</label>
            <select name="employee_id" id="employee_id" class="form-control">
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}"
                        {{ $deduction->employee_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="branch_id">Branch</label>
            <select name="branch_id" id="branch_id" class="form-control">
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ $deduction->branch_id == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="deduction_date">Deduction Date</label>
            <input type="date" name="deduction_date" id="deduction_date" class="form-control"
                value="{{ $deduction->deduction_date }}">
        </div>

        <div class="form-group">
            <label for="basic_salary">Basic Salary</label>
            <input type="number" name="basic_salary" id="basic_salary" class="form-control"
                value="{{ $deduction->basic_salary }}">
        </div>

        <div class="form-group">
            <label for="deduction_days">Deduction Days</label>
            <input type="number" name="deduction_days" id="deduction_days" class="form-control"
                value="{{ $deduction->deduction_days }}">
        </div>

        <div class="form-group">
            <label for="deduction_value">Deduction Value</label>
            <input type="number" name="deduction_value" id="deduction_value" class="form-control"
                value="{{ $deduction->deduction_value }}">
        </div>

        <div class="form-group">
            <label for="is_fixed_amount">Is Fixed Amount</label>
            <input type="checkbox" name="is_fixed_amount" id="is_fixed_amount"
                {{ $deduction->is_fixed_amount ? 'checked' : '' }}>
        </div>

        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
