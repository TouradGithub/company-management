@extends('layouts.mastercomany')

@section('content')

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

<div class="section-header">
    <h2>إضافة فئة جديد</h2>
</div>

<div class="add-advance-content">
    <form action="{{ route('categories.store') }}" method="POST" id="add-advance-form" class="standard-form">
        @csrf
        <label>الاسم:</label>
        <input type="text" name="name"   class="form-control" required>

        <label>الكود:</label>
        <input type="text" name="code"   class="form-control"required>

        <button  style="margin-top: 10px" class="save-btn" type="submit">حفظ</button>
    </form>
</div>
@endsection
