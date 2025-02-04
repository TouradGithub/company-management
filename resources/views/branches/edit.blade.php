@extends('layouts.mastercomany')

@section('content')


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
    <div class="section-header">
        <h2>تعديل الفرع </h2>
    </div>

    <div class="add-advance-content">
    <form action="{{ route('branches.update', $company->id) }}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf
        <label>الاسم:</label>
        <input type="text" name="name" value="{{ $company->name }}" class="form-control" required>

        <label>اسم مدير الفرع:</label>
        <input type="text" name="name_admin_company"  class="form-control"value="{{ $company->name_admin_company }}" required>

        <label>الايميل:</label>
        <input type="email" name="email"  class="form-control"value="{{ $company->email }}" required>

        <label>كبمة المرور:</label>
        <input type="password" class="form-control" name="password">

        <button   style="margin-top: 30px" class="save-btn" type="submit">تحديث</button>
    </form>
</div>
@endsection
