@extends('financialaccounting.layouts.master')

@section('content')
    <div id="mainDashboard">
        <h1>تعديل المعلومات الأساسية للشركة</h1>
        <div class="dashboard-cards">

            <form method="POST" action="{{route('update.company.info.update' ,Auth::user()->model_id)}}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group-model">
                        <label>اسم الشركة</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $company->name ?? '') }}" required>
                        @error('name')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group-model">
                        <label>البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $company->email ?? '') }}" required>
                        @error('email')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group-model">
                        <label>تاريخ البدء</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $company->start_date ?? '') }}" required>
                        @error('start_date')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group-model">
                        <label>تاريخ الانتهاء</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $company->end_date ?? '') }}" required>
                        @error('end_date')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-model">
                        <label> لعنوان</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $company->address ?? '') }}" required>
                        @error('address')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group-model">
                        <label> الرقم الضريبي</label>
                        <input type="number" id="tax_number" name="tax_number" value="{{ old('tax_number', $company->tax_number ?? '') }}" required>
                        @error('tax_number')
                        <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <button type="submit" class="save-btn">حفظ التعديلات</button>
                </div>
            </form>
        </div>
    </div>


@endsection
