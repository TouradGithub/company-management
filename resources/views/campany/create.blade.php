@extends('layouts.app')

@section('content')

<div class="content-sections">
    <div class="form-container active" id="form-container">
        <h1>Add Company</h1>
      <form action="{{route('company.store')}}" method="POST">
        @csrf
        <div class="form-group">
          <label>;&#x627;&#x633;&#x645; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;:</label>
          <input type="text" name="name" required>
        </div>

        <div class="form-group">
          <label>;&#x627;&#x644;&#x645;&#x647;&#x646;&#x629;:</label>
          <input type="email" name="email" required>
        </div>

        <div class="form-group">
          <label>;&#x627;&#x644;&#x631;&#x627;&#x62a;&#x628; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;:</label>
          <input type="password" name="password" required>
        </div>

        <div class="form-group">
          <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x633;&#x643;&#x646;:</label>
          <input type="date" name="start_date" required>
        </div>

        <div class="form-group">
          <label>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x645;&#x648;&#x627;&#x635;&#x644;&#x627;&#x62a;:</label>
          <input type="date" name="end_date" required>
        </div>



        <button type="submit">;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;</button>
      </form>
    </div>

    <div class="overtime-container" id="overtime-container">
      <h2>;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x639;&#x645;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</h2>
      <form id="overtimeForm">
        <div class="form-group">
          <label>;&#x627;&#x62e;&#x62a;&#x631; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;:</label>
          <select id="overtimeEmployeeSelect" required></select>
        </div>

        <div class="form-group">
          <label>;&#x639;&#x62f;&#x62f; &#x633;&#x627;&#x639;&#x627;&#x62a; &#x627;&#x644;&#x639;&#x645;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;:</label>
          <input type="number" id="overtimeHours" required>
        </div>

        <div class="form-group">
          <label>;&#x642;&#x64a;&#x645;&#x629; &#x627;&#x644;&#x633;&#x627;&#x639;&#x629; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;&#x629;:</label>
          <input type="number" id="overtimeRate" required>
        </div>

        <div class="form-group">
          <label>;&#x625;&#x62c;&#x645;&#x627;&#x644;&#x64a; &#x642;&#x64a;&#x645;&#x629; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;:</label>
          <div id="overtimeTotalDisplay" class="calculated-total">0</div>
        </div>

        <button type="submit">;&#x62a;&#x633;&#x62c;&#x64a;&#x644; &#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</button>
      </form>
    </div>
    @endsection
