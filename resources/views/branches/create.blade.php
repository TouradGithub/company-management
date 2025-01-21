@extends('layouts.overtime')

@section('content')
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

        <h1>إضافه فرع</h1>
      <form action="{{route('branches.store')}}" method="POST">
        @csrf
        <div class="form-group">
            <label>الاسم:</label>
            <input type="text" name="name" required>
          </div>

          <div class="form-group">
            <label>اسم مدير الفرع:</label>
            <input type="text" name="name_admin_company" required>
          </div>

        <div class="form-group">
          <label>الايميل:</label>
          <input type="email" name="email" required>
        </div>

        <div class="form-group">
          <label>كلمة المرور:</label>
          <input type="password" name="password" required>
        </div>

        <button  class="btn btn-primary mb-3" type="submit">حفظ</button>
      </form>
    </div>


    @endsection
