@extends('layouts.overtime')

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
    <h1>إضافة مستخدم</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="search-bar fade-in">
            <div class="form-group">
                <label>الاسم:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label>كلمة المرور:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit"   style="margin-top: 5px" class="save-btn">حفظ</button>
    </form>
</div>
@endsection

