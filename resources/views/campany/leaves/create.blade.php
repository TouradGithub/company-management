
@extends('layouts.overtime')

@section('content')

{{-- <div class="content-sections"> --}}
<div class="form-container active" id="form-container">
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
    <form action="{{ route('company.leaves.store') }}" method="POST">
        @csrf

        <div>
            <div class="search-bar fade-in">
            <label for="employee_id">الموظف</label>
            <select name="branch_id" id="branch_id" required>
                <option >Select</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
            </div>
        </div>
        <div>
            <label for="employee_id">الموظف</label>
            <select name="employee_id" id="employee_id" required>
                <option value="" disabled selected>اختر الموظف</option>
            </select>
        </div>

        <div>
            <label for="start_date">تاريخ بدء الإجازة</label>
            <input type="date" name="start_date" id="start_date" required>
        </div>

        <div>
            <label for="end_date">تاريخ انتهاء الإجازة</label>
            <input type="date" name="end_date" id="end_date" required>
        </div>

        <div>
            <label for="reason">سبب الإجازة</label>
            <textarea name="reason" id="reason"></textarea>
        </div>

        <button  class="btn btn-primary mb-3" type="submit">تسجيل الإجازة</button>
    </form>

</div>
@endsection
@section('js')


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const branchSelect = document.getElementById('branch_id');
        const employeeSelect = document.getElementById('employee_id');

        branchSelect.addEventListener('change', function () {
            const branchId = this.value;

            // Clear the employee dropdown
            employeeSelect.innerHTML = '<option value="" disabled selected>جاري التحميل...</option>';

            // Fetch employees based on the selected branch
            fetch(`/branches/${branchId}/employees`)
                .then(response => response.json())
                .then(data => {
                    // Clear the employee dropdown
                    employeeSelect.innerHTML = '<option value="" disabled selected>اختر الموظف</option>';

                    // Populate the employee dropdown
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        employeeSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching employees:', error);
                    employeeSelect.innerHTML = '<option value="" disabled selected>لا يمكن تحميل الموظفين</option>';
                });
        });
    });
</script>


@endsection
