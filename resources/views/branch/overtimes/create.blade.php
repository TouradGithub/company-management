@extends('layouts.masterbranch')

@section('content')
<div class="section-header">
    <h2>إضافة إضافي جديد</h2>
</div>

<div class="add-advance-content">
    <form action="{{route('branch.overtimes.store')}}" method="POST" method="POST" id="add-advance-form" class="standard-form">

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
        <input type="date"  class="form-control" name="date" id="date" required>
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
        <div class="info-card checkbox-group " style="margin-bottom: 10px">
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
          <div class="checkbox-group">
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
          <input type="number" name="fixedAmount"  class="form-control" id="fixedAmount" min="0" step="0.01">
        </div>

        <div id="hoursSection" class="form-group hidden">
          <label for="hours">عدد الساعات:</label>
          <input type="number" id="hours"   class="form-control"min="0" step="0.5">
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
              <input type="number"   class="form-control" name="days" id="days" min="0" step="0.5">
            </div>
            <div class="form-group half-width">
              <label for="dailyRate">قيمة اليوم:</label>
              <input type="number"  class="form-control"name="dailyRate" id="dailyRate" min="0" step="0.01">
            </div>
          </div>
        </div>

        <div class="total-section">
            <input type="hidden" name="totalAmount" id="totalAmountHidden">
          <h3 style="margin-top: 10px;text-align: center" class="save-btn">الإجمالي: <span id="totalAmount">0</span> ريال</h3>
        </div>

        <div class="form-actions">
          <button type="submit" style="margin-top: 10px" class="save-btn">حفظ</button>
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
