@extends('layouts.overtime')

@section('content')
<div class="form-container active" id="form-container">
    <h1>تعديل الفرع</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
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

    <form action="{{ route('branches.update', $company->id) }}" method="POST">
        @csrf
        <label>الاسم:</label>
        <input type="text" name="name" value="{{ $company->name }}" required>

        <label>اسم مدير الفرع:</label>
        <input type="text" name="name_admin_company" value="{{ $company->name_admin_company }}" required>

        <label>الايميل:</label>
        <input type="email" name="email" value="{{ $company->email }}" required>

        <label>كبمة المرور:</label>
        <input type="password" name="password">

        <button  class="btn btn-primary mb-3" type="submit">تحديث</button>
    </form>
</div>
@endsection
