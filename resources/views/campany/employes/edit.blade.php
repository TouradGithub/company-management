@extends('layouts.overtime')

@section('content')
@if (session('success'))
    <div style="color: green; text-align: center;">
        <h2>{{ session('success') }}</h2>
    </div>
@endif

<h1>تعديل موظف</h1>

<div class="form-container">
    <form action="{{ route('company.employees.update', $employee->id) }}" method="POST">
        @csrf
        @method('POST') {{-- Ensure the update method is handled correctly --}}

        <!-- Row 1: Category and Branch -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="category_id">الفئة</label>
                <select name="category_id" id="category_id" required>
                    <option value="" disabled selected>اختر الفئة</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $employee->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group-employe">
                <label for="branch_id">الفرع</label>
                <select name="branch_id" id="branch_id" required>
                    <option value="" disabled selected>اختر الفرع</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ $employee->branch_id == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: ID Number and Name -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="iqamaNumber">رقم البطاقة</label>
                <input type="number" name="iqamaNumber" id="iqamaNumber"
                       value="{{ $employee->iqamaNumber }}">
            </div>
            <div class="form-group-employe">
                <label for="name">الاسم</label>
                <input type="text" name="name" id="name" required value="{{ $employee->name }}">
            </div>
        </div>

        <!-- Row 3: Job and Basic Salary -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="job">المهنة</label>
                <input type="text" name="job" id="job" required value="{{ $employee->job }}">
            </div>
            <div class="form-group-employe">
                <label for="basic_salary">الراتب الأساسي</label>
                <input type="number" name="basic_salary" id="basic_salary"
                       value="{{ $employee->basic_salary }}">
            </div>
        </div>

        <!-- Row 4: Allowances -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="housing_allowance">بدل السكن</label>
                <input type="number" name="housing_allowance" id="housing_allowance"
                       value="{{ $employee->housing_allowance }}">
            </div>
            <div class="form-group-employe">
                <label for="food_allowance">بدل الإعاشة</label>
                <input type="number" name="food_allowance" id="food_allowance"
                       value="{{ $employee->food_allowance }}">
            </div>
        </div>

        <!-- Row 5: Transportation Allowance and Hire Date -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="transportation_allowance">بدل المواصلات</label>
                <input type="number" name="transportation_allowance" id="transportation_allowance"
                       value="{{ $employee->transportation_allowance }}">
            </div>
            <div class="form-group-employe">
                <label for="hire_date">تاريخ التعيين</label>
                <input type="date" name="hire_date" id="hire_date"
                       value="{{ $employee->hire_date }}">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group-employe">
            <button class="btn btn-primary" type="submit">تحديث الموظف</button>
        </div>
    </form>
</div>
@endsection
