@extends('layouts.mastercomany')

@section('content')
<div class="section-header">
    <h2>تعديل سلف </h2>
</div>

<div class="add-advance-content">
    <form action="{{route('company.overtimes.update' , $overtime->id)}}" method="POST" id="add-advance-form" class="standard-form">

        @if (session('success'))
        <div style="color: green;text-align: center;">
            <h2 style="color: green;">{{ session('success') }}</h2>
        </div>
        @endif
        @csrf

      <div class="form-group">
        <label for="date">التاريخ:</label>
        <input  class="form-control"type="date" name="date" id="date" value="{{ $overtime->date }}" required>
      </div>
      <div class="search-bar fade-in">
      <div class="form-group">

        <label for="branches">الفروع:</label>
        <select id="branches" multiple required>
            @foreach ($branches as $item)
            <option value="{{$item->id}}" {{ $item->id== $overtime->branch_id ? 'selected' : '' }}>
                {{$item->name}}
            </option>
            @endforeach
        </select>
      </div>
      </div>

      <div class="form-group">
        <label for="employees">الموظفين:</label>
        <select id="employees" name="employe_id" required>
            @foreach ($employees as $employee)
            <option value="{{$employee->id}}" {{ $overtime->employe_id == $employee->id ? 'selected' : '' }}>
                {{$employee->name}}
            </option>
            @endforeach
        </select>
      </div>

      <div class="employee-details {{ $overtime->overtime_type ? '' : 'hidden' }}">
        <div class="info-card checkbox-group " style="margin-bottom: 10px">
          <div class="info-row ">
            <span class="info-label">رقم الإقامة:</span>
            <span id="iqamaNumber" class="info-value">{{$overtime->employee->iqamaNumber}}</span>
          </div>
          <div class="info-row">
            <span class="info-label">الراتب الأساسي:</span>
            <span id="basicSalary" class="info-value">{{$overtime->employee->basic_salary}}</span>
          </div>
          <div class="info-row">
            <span class="info-label">قيمة الساعة:</span>
            <span id="hourlyRate" class="info-value">{{$overtime->hourlyRate}}</span>
          </div>
        </div>

        <div class="overtime-type">
          <h3>نوع تسجيل الإضافي</h3>
          <div class="checkbox-group">
            <label>
              <input type="radio" name="overtimeType" value="fixed" {{ $overtime->overtime_type == 'fixed' ? 'checked' : '' }}>
              مبلغ ثابت
            </label>
            <label>
              <input type="radio" name="overtimeType" value="hours" {{ $overtime->overtime_type == 'hours' ? 'checked' : '' }}>
              عدد ساعات
            </label>
            <label>
              <input type="radio" name="overtimeType" value="daily" {{ $overtime->overtime_type == 'daily' ? 'checked' : '' }}>
              قيمة اليوم
            </label>
          </div>
        </div>

        <div id="fixedAmountSection" class="form-group {{ $overtime->overtime_type == 'fixed' ? '' : 'hidden' }}">
          <label for="fixedAmount">المبلغ الثابت:</label>
          <input type="number" class="form-control" name="fixedAmount" id="fixedAmount" value="{{ $overtime->fixed_amount }}" min="0" step="0.01">
        </div>

        <div id="hoursSection" class="form-group {{ $overtime->overtime_type == 'hours' ? '' : 'hidden' }}">
          <label for="hours">عدد الساعات:</label>
          <input type="number" id="hours" class="form-control" name="hours" value="{{ $overtime->hours }}" min="0" step="0.5">
          <div class="multiplier-group">
            <label>
              <input type="radio" name="hourMultiplier" value="1" {{ $overtime->hour_multiplier == 1.00 ? 'checked' : '' }}>
              × 1 (عادي)
            </label>
            <label>
              <input type="radio" name="hourMultiplier" value="1.5" {{ $overtime->hour_multiplier == 1.5 ? 'checked' : '' }}>
              × 1.5 (إضافي)
            </label>
          </div>
        </div>

        <div id="dailyRateSection" class="form-group {{ $overtime->overtime_type == 'daily' ? '' : 'hidden' }}">
          <div class="days-rate-container">
            <div class="form-group half-width">
              <label for="days">عدد الأيام:</label>
              <input type="number" class="form-control" name="days" id="days" value="{{ $overtime->days }}" min="0" step="0.5">
            </div>
            <div class="form-group half-width">
              <label for="dailyRate">قيمة اليوم:</label>
              <input type="number" class="form-control" name="dailyRate" id="dailyRate" value="{{ $overtime->daily_rate }}" min="0" step="0.01">
            </div>
          </div>
        </div>

        <div class="total-section">
          <input type="hidden" name="totalAmount" id="totalAmountHidden" value="{{ $overtime->total_amount }}">
          <h3 class="save-btn" style="text-align: center">الإجمالي: <span  id="totalAmount">{{$overtime->total_amount}}</span> ريال</h3>
        </div>

        <div class="form-actions">
          <button type="submit"  style="margin-top: 30px" class="save-btn">تحديث</button>
        </div>
      </div>
    </form>

</section>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
      $('#branches').select2();
      $('#employees').select2();
    });
</script>
{{-- <script src="{{asset('employee/script.js')}}"></script> --}}
@endsection
