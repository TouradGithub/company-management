@extends('layouts.branch')

@section('content')
{{-- <div class="container"> --}}
    {{-- <h1>Add User</h1> --}}
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
<h1>إضافه موظف</h1>
{{-- <form action="{{ route('company.employees.store') }}" method="POST">
    @csrf --}}

    <div class="form-container">

        <form action="{{ route('branch.employees.store') }}" method="POST">
            @csrf

            <div class="row-employe">
                <div class="form-group-employe">
                    <label for="category_id">الفئة</label>
                    <select name="category_id" id="category_id" required>
                        <option>اختر الفئة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row-employe">
                <div class="form-group-employe">
                    <label for="iqamaNumber">رقم البطاقة</label>
                    <input type="number" name="iqamaNumber" id="iqamaNumber">
                </div>
                <div class="form-group-employe">
                    <label for="name">الاسم</label>
                    <input type="text" name="name" id="name" required>
                </div>
            </div>

            <div class="row-employe">
                <div class="form-group-employe">
                    <label for="job">المهنة</label>
                    <input type="text" name="job" id="job" required>
                </div>
                <div class="form-group-employe">
                    <label for="basic_salary">الراتب الأساسي</label>
                    <input type="number" name="basic_salary" id="basic_salary">
                </div>
            </div>

            <div class="row-employe">
                <div class="form-group-employe">
                    <label for="housing_allowance">بدل السكن</label>
                    <input type="number" name="housing_allowance" id="housing_allowance">
                </div>
                <div class="form-group-employe">
                    <label for="food_allowance">بدل الإعاشة</label>
                    <input type="number" name="food_allowance" id="food_allowance">
                </div>
            </div>

            <div class="row-employe">
                <div class="form-group-employe">
                    <label for="transportation_allowance">بدل المواصلات</label>
                    <input type="number" name="transportation_allowance" id="transportation_allowance">
                </div>
                <div class="form-group-employe">
                    <label for="hire_date">تاريخ التعيين</label>
                    <input type="date" name="hire_date" id="hire_date">
                </div>
            </div>

            <div class="form-group-employe">
                <button class="btn btn-primary" type="submit">إنشاء الموظف</button>
            </div>
        </form>
    </div>

{{-- </form> --}}
</div>

@endsection


