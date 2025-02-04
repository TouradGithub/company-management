@extends('layouts.mastercomany')

@section('content')

<div class="section-header">
    <h2>إضافة إضافي جديد</h2>
</div>

<div class="add-advance-content">
    <form action="{{ url('overtimes') }}" method="POST" id="add-advance-form" class="standard-form">
        @csrf

        @if (session('success'))
            <div style="color: green; text-align: center;">
                <h2>{{ session('success') }}</h2>
            </div>
        @endif

        <div class="form-group">
            <label for="date">التاريخ:</label>
            <input type="date" name="date" class="form-control" id="date" required>
        </div>

        <div class="form-group">
            <label for="branches">الفروع:</label>
            <select id="branches" class="form-control select2"  name="branches[]" multiple required>
                @foreach ($branches as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="employees">الموظفين:</label>
            <select id="employees" class="form-control" name="employe_id" required>
                <!-- Employee options will be populated dynamically -->
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
                    <span   id="basicSalary" class=" info-value" ></span>
                </div>
                <div class="info-row">
                    <span class="info-label">قيمة الساعة:</span>
                    <span   id="hourlyRate" class=" info-value" ></span>
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
                <input type="number" class="form-control" name="fixedAmount" id="fixedAmount" min="0" step="0.01">
            </div>

            <div id="hoursSection" class="form-group hidden">
                <label for="hours">عدد الساعات:</label>
                <input type="number" class="form-control" name="hours" id="hours" min="0" step="0.5">
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
                        <input type="number" class="form-control" name="days" id="days" min="0" step="0.5">
                    </div>
                    <div class="form-group half-width">
                        <label for="dailyRate">قيمة اليوم:</label>
                        <input type="number" class="form-control" name="dailyRate" id="dailyRate" min="0" step="0.01">
                    </div>
                </div>
            </div>

            <div class="total-section">
                <input type="hidden" name="totalAmount" id="totalAmountHidden">
                <h3 class="save-btn" style="text-align: center">الإجمالي: <span id="totalAmount">0</span> ريال</h3>
            </div>

            <div class="form-actions">
                <button type="submit" style="margin-top: 30px" class="save-btn">حفظ</button>
            </div>
        </div>
    </form>
</div>

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // $(document).ready(function() {
    //     // Initialize Select2
    //     $('#branches').select2();
    // });

    // $(document).ready(function() {
    //     // Initialize Select2
    //     $('#branches').select2({
    //         placeholder: "اختر الفروع",
    //         allowClear: true
    //     });


    // });
</script>
<script src="{{asset('employee/script.js')}}"><script>
@endsection
