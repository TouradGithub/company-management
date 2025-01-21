@extends('layouts.overtime')

@section('content')



<div class="statistics-container" id="statistics-container">
    <h2>الإحصائيات العامة</h2>
    <div class="statistics-grid" style="margin-bottom: 10px;">
        <div class="stat-card">
            <h3>عدد الموظفين</h3>
            <p id="employeeCount">{{App\Models\Employee::whereIn('branch_id', App\Models\Branch::where('company_id',auth()->user()->model_id)->pluck('id') )->count()}}</p>
        </div>
        <div class="stat-card">
            <h3>عدد المستخدمين</h3>
            <p id="userCount">{{App\Models\User::where('model_type' , 'USER_COMPANY')->where('model_id',auth()->user()->model_id)

            ->count()}}</p>
        </div>
        <div class="stat-card">
            <h3>عدد الإجازات</h3>
            <p id="leaveCount">{{App\Models\Leave::whereIn('branch_id' , App\Models\Branch::where('company_id',auth()->user()->model_id)->pluck('id'))->count()}}</p>
        </div>
        <div class="stat-card">
            <h3>عدد الفروع</h3>
            <p id="departmentCount">{{App\Models\Branch::where('company_id',auth()->user()->model_id)->count()}}</p>
        </div>
    </div>

    <div class="statistics-grid">
        <div class="stat-card">
            <h3>إجماب الخصم </h3>
            <p id="employeeCount">0</p>
        </div>
        <div class="stat-card">
            <h3> إجمالي الاضافي</h3>
            <p id="userCount">0</p>
        </div>
        <div class="stat-card">
            <h3>إجمالي السلف </h3>
            <p id="leaveCount">0</p>
        </div>


        <div class="stat-card">
            <h3>إجمالي السلف </h3>
            <p id="leaveCount">0</p>
        </div>

    </div>
</div>

  @endsection
