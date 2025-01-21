@extends('layouts.admin')

@section('content')

<div class="content-sections">
    <div class="form-container active" id="form-container">
        <h1>إضافة شركه </h1>
      <form action="{{route('company.store')}}" method="POST">
        @csrf



        <div class="form-group">
            <label>الاسم:</label>
            <input type="text" name="name" required>
          </div>

          <div class="form-group">
            <label>الايميل:</label>
            <input type="email" name="email" required>
          </div>

          <div class="form-group">
            <label>كلمة المرور:</label>
            <input type="password" name="password" required>
          </div>

          <div class="form-group">
            <label>تاريخ البدايه:</label>
            <input type="date" name="start_date" required>
          </div>

          <div class="form-group">
            <label>تاريخ النهاية:</label>
            <input type="date" name="end_date" required>
          </div>

          <button  class="btn btn-primary mb-3" type="submit">حفظ</button>

      </form>
    </div>


    
    @endsection
