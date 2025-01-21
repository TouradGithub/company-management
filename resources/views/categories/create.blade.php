@extends('layouts.overtime')

@section('content')
<div style="margin-top: 150px;">
    <h1> إضافة فئة</h1>

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

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <label>الاسم:</label>
        <input type="text" name="name" required>

        <label>الكود:</label>
        <input type="text" name="code" required>

        <button  class="btn btn-primary mb-3" type="submit">حفظ</button>
    </form>
</div>
@endsection
