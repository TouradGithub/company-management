
@extends('layouts.branch')

@section('content')

{{-- <div class="content-sections"> --}}
<div class="form-container active" id="form-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
        <h1>إضافه إجازة</h1>
    <form action="{{ route('branch.leaves.store') }}" method="POST">
        @csrf


        <div>
            <label for="employee_id">الموظف</label>
            <select name="employee_id" id="employee_id" required>
                @foreach($employees as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach

            </select>
        </div>

        <div>
            <label for="start_date">تاريخ بدء الإجازة</label>
            <input type="date" name="start_date" id="start_date" required>
        </div>

        <div>
            <label for="end_date">تاريخ انتهاء الإجازة</label>
            <input type="date" name="end_date" id="end_date" required>
        </div>

        <div class="form-group">
            <label for="reason">سبب الإجازة</label>
            <textarea name="reason" id="reason" class="form-control" style="width: 100%"  rows="5"></textarea>
        </div>

        <button  class="btn btn-primary mb-3" type="submit">تسجيل الإجازة</button>
    </form>

</div>
@endsection
@section('js')




@endsection
