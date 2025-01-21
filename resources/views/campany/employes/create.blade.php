@extends('layouts.overtime')

@section('content')
{{-- <div class="container"> --}}
    {{-- <h1>Add User</h1> --}}
    @if (session('success'))
    <div style="color: green;text-align: center;">
        <h2 style="color: green;">  {{ session('success') }}</h2>

    </div>
@endif
<form action="{{ route('company.employees.store') }}" method="POST">
    @csrf

    <div class="search-filtersForm fade-in">
        <select name="category_id" id="category_id" required>
            <option >Select</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
        <div class="search-filtersForm fade-in">
        <select name="branch_id" id="branch_id" required>
            <option value="" disabled selected>اختر الموظف</option>

            @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </select>

      </div>
    <div>



    </div>
    <div class="search-bar fade-in">
        <label for="iqamaNumber"> رقم البطاقه</label>
        <input type="number" name="iqamaNumber" id="iqamaNumber" >
      </div>

    <div>
        <div class="search-filtersForm fade-in">

            <div class="search-bar fade-in">
                <label for="job">الاسم</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="search-bar fade-in">
                <label for="job">المهنة</label>
                <input type="text" name="job" id="job" required>
            </div>
        </div>



    </div>

        <div>
            <div class="search-filtersForm fade-in">

                <div class="search-bar fade-in">
                    <label for="basic_salary">الراتب الأساسي</label>
                    <input  type="number" name="basic_salary" id="searchInput" >
                </div>

                <div class="search-bar fade-in">
                    <label for="housing_allowance">بدل السكن</label>
                    <input type="number" name="housing_allowance" id="searchInput">
                </div>
            </div>

        </div>
    <div>
    <div class="search-filtersForm fade-in">

        <div class="search-bar fade-in">
            <label for="food_allowance">بدل الإعاشة</label>
            <input type="number" name="food_allowance" id="searchInput" >
          </div>
        <div class="search-bar fade-in">
            <label for="transportation_allowance">بدل المواصلات</label>
            <input type="number" name="transportation_allowance" id="searchInput" >
          </div>
    </div>

    <div>


    </div>
    </div>

    <div>

        <div class="search-bar fade-in">
            <label for="hire_date">تاريخ التعيين</label>
            <input type="date" name="hire_date"  id="searchInput" >
          </div>
        {{-- <input type="date" name="hire_date" id="hire_date" required> --}}
    </div>
    <div class="search-bar fade-in">
        <button   class="btn btn-primary mb-3"  type="submit">إنشاء الموظف</button>
    </div>
</form>
</div>

@endsection


