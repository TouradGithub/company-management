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
        <h2>إضافة فرع جديد</h2>
    </div>

    <div class="add-advance-content">
      <form action="{{route('branches.store')}}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf
        <div class="form-group">
            <label>الاسم:</label>
            <input type="text"  class="form-control" name="name" required>
          </div>

          <div class="form-group">
            <label>اسم مدير الفرع:</label>
            <input type="text"   class="form-control" name="name_admin_company" required>
          </div>

        <div class="form-group">
          <label>الايميل:</label>
          <input type="email"  class="form-control" name="email" required>
        </div>

        <div class="form-group">
          <label>كلمة المرور:</label>
          <input type="password"  class="form-control" name="password" required>
        </div>

        <button   style="margin-top: 30px" class="save-btn" type="submit">حفظ</button>
      </form>
    </div>


    @endsection
