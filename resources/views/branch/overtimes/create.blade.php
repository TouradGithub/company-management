@extends('layouts.branch')

@section('content')

<section id="overtime-form" class="content-section active">
<div class="container">
      <!-- Overtime Form Section -->
          <h1>تسجيل الإضافي</h1>
    <form action="{{route('branch.overtimes.store')}}" method="POST">

        @if (session('success'))
        <div style="color: green;text-align: center;">
            <h2 style="color: green;">  {{ session('success') }}</h2>
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
        @csrf
      <div class="form-group">
        <label for="date">التاريخ:</label>
        <input type="date" name="date" id="date" required>
      </div>

      <div class="form-group">
        <label for="employees">الموظفين:</label>
        <select id="employees" name="employe_id" required>
            <option >اختر موظف</option>
            @foreach ($employees as $employee)
            <option value="{{$employee->id}}">{{$employee->name}}</option>
            @endforeach
        </select>
      </div>

      <div class="employee-details hidden">
        <div class="info-card">
          <div class="info-row">
            <span class="info-label">رقم الإقامة:</span>
            <span id="iqamaNumber" class="info-value"></span>
          </div>
          <div class="info-row">
            <span class="info-label">الراتب الأساسي:</span>
            <span id="basicSalary" class="info-value"></span>
          </div>
          <div class="info-row">
            <span class="info-label">قيمة الساعة:</span>
            <span id="hourlyRate" class="info-value"></span>
          </div>
        </div>

        <div class="overtime-type">
          <h3>نوع تسجيل الإضافي</h3>
          <div class="radio-group">
            <label>
              <input type="radio" name="overtimeType" value="fixed" checked>
              مبلغ ثابت
            </label>
            <label>
              <input type="radio" name="overtimeType" value="hours">
              عدد ساعات
            </label>
            <label>
              <input type="radio" name="overtimeType" value="daily">
              قيمة اليوم
            </label>
          </div>
        </div>

        <div id="fixedAmountSection" class="form-group">
          <label for="fixedAmount">المبلغ الثابت:</label>
          <input type="number" name="fixedAmount" id="fixedAmount" min="0" step="0.01">
        </div>

        <div id="hoursSection" class="form-group hidden">
          <label for="hours">عدد الساعات:</label>
          <input type="number" id="hours" min="0" step="0.5">
          <div class="multiplier-group">
            <label>
              <input type="radio" name="hourMultiplier" value="1" checked>
              × 1 (عادي)
            </label>
            <label>
              <input type="radio" name="hourMultiplier" value="1.5">
              × 1.5 (إضافي)
            </label>
          </div>
        </div>

        <div id="dailyRateSection" class="form-group hidden">
          <div class="days-rate-container">
            <div class="form-group half-width">
              <label for="days">عدد الأيام:</label>
              <input type="number" name="days" id="days" min="0" step="0.5">
            </div>
            <div class="form-group half-width">
              <label for="dailyRate">قيمة اليوم:</label>
              <input type="number" name="dailyRate" id="dailyRate" min="0" step="0.01">
            </div>
          </div>
        </div>

        <div class="total-section">
            <input type="hidden" name="totalAmount" id="totalAmountHidden">
          <h3>الإجمالي: <span id="totalAmount">0</span> ريال</h3>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-primary">حفظ</button>
        </div>
      </div>
    </form>
    <div id="employeesData" data-employees="{{ json_encode($employees) }}"></div>

</section>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

    $(document).ready(function() {
      // Initialize Select2
      $('#employees').select2();
    });

</script>
<script src="{{asset('branch/overtime.js')}}"></script>

@endsection
