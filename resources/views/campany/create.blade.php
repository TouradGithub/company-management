@extends('layouts.masteradmin')

@section('content')
<div class="section-header">
    <h2>إضافة شركة جديد</h2>
</div>

<div class="add-advance-content">
      <form action="{{route('company.store')}}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf



        <div class="form-group">
            <label>الاسم:</label>
            <input type="text" name="name"   class="form-control" required>
          </div>

          <div class="form-group">
            <label>الايميل:</label>
            <input type="email" name="email"   class="form-control" required>
          </div>

          <div class="form-group">
            <label>كلمة المرور:</label>
            <input type="password" name="password"  class="form-control" required>
          </div>

          <div class="form-group">
            <label>تاريخ البدايه:</label>
            <input type="date" name="start_date"  class="form-control" required>
          </div>

          <div class="form-group">
            <label>تاريخ النهاية:</label>
            <input type="date" name="end_date"  class="form-control" required>
          </div>

          <button   style="margin-top: 5px" class="save-btn" type="submit">حفظ</button>

      </form>
    </div>



    @endsection
