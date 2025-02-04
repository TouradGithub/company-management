@extends('layouts.masterbranch')

@section('content')

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
        <h2>إضافة موظف جديد</h2>
    </div>

    <div class="add-advance-content">
    <form action="{{ route('branch.loans.branch.store') }}" method="POST" id="add-advance-form" class="standard-form">
        @csrf
        <div class="search-bar fade-in">

            </div>

            <div class="form-group">
                <label for="employees">الموظفين:</label>
                <select id="employees" name="employe_id" required>
                    <option >اختر موظف</option>
                    @foreach ($employees as $employee)
                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                    @endforeach
                </select>
              </div>
        <div class="form-group">
            <label for="amount">المبلغ</label>
            <input type="number" name="amount" id="amount"  class="form-control">
        </div>
        <div class="form-group">
            <label for="loan_date">التاريخ</label>
            <input type="date" name="loan_date" id="loan_date" class="form-control">
        </div>
        <button   style="margin-top: 10px" class="save-btn">حفظ</button>
    </form>
</div>
@endsection
@section('js')


<script src="{{asset('overtime.js')}}"></script>


@endsection
