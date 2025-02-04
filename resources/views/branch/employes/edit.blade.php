@extends('layouts.masterbranch')

@section('content')
@if (session('success'))
<div style="color: green;text-align: center;">
    <h2 style="color: green;">  {{ session('success') }}</h2>

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
<div class="section-header">
    <h2>تعديل موظف </h2>
</div>

<div class="add-advance-content">
    <form action="{{ route('branch.employees.update', $employee->id) }}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf
        @method('POST') {{-- Ensure the update method is handled correctly --}}

        <!-- Row 1: Category and Branch -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="category_id">الفئة</label>
                <select name="category_id" id="category_id"  class="form-control"  required>
                    <option value="" disabled selected>اختر الفئة</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $employee->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <!-- Row 2: ID Number and Name -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="iqamaNumber">رقم البطاقة</label>
                <input type="number"   class="form-control"  name="iqamaNumber" id="iqamaNumber"
                       value="{{ $employee->iqamaNumber }}">
            </div>
            <div class="form-group-employe">
                <label for="name">الاسم</label>
                <input type="text"  class="form-control"  name="name" id="name" required value="{{ $employee->name }}">
            </div>
        </div>

        <!-- Row 3: Job and Basic Salary -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="job">المهنة</label>
                <input type="text" name="job"  class="form-control"  id="job" required value="{{ $employee->job }}">
            </div>
            <div class="form-group employe">
                <label for="basic_salary">الراتب الأساسي</label>
                <input type="number"  class="form-control"  name="basic_salary" id="basic_salary"
                       value="{{ $employee->basic_salary }}">
            </div>
        </div>

        <!-- Row 4: Allowances -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="housing_allowance">بدل السكن</label>
                <input type="number"  class="form-control"  name="housing_allowance" id="housing_allowance"
                       value="{{ $employee->housing_allowance }}">
            </div>
            <div class="form-group-employe">
                <label for="food_allowance">بدل الإعاشة</label>
                <input type="number"  class="form-control"  name="food_allowance" id="food_allowance"
                       value="{{ $employee->food_allowance }}">
            </div>
        </div>

        <!-- Row 5: Transportation Allowance and Hire Date -->
        <div class="row-employe">
            <div class="form-group-employe">
                <label for="transportation_allowance">بدل المواصلات</label>
                <input type="number"  class="form-control"  name="transportation_allowance" id="transportation_allowance"
                       value="{{ $employee->transportation_allowance }}">
            </div>
            <div class="form-group-employe">
                <label for="hire_date">تاريخ التعيين</label>
                <input type="date"  class="form-control"  name="hire_date" id="hire_date"
                       value="{{ $employee->hire_date }}">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group-employe">
            <button style="margin-top: 5px" class="save-btn"type="submit">تحديث الموظف</button>
        </div>
    </form>
</div>
@endsection
