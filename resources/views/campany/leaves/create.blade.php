
@extends('layouts.mastercomany')

@section('content')

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
        <div class="section-header">
            <h2>إضافة إجازة جديد</h2>
        </div>

        <div class="add-advance-content">
    <form action="{{ route('company.leaves.store') }}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf

        <div>
            <div class="search-bar fade-in">
            <label for="employee_id">الموظف</label>
            <select name="branch_id"  class="form-control" id="branch_id" required>
                <option >Select</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
            </div>
        </div>
        <div>
            <label for="employee_id">الموظف</label>
            <select name="employee_id"  class="form-control" id="employee_id" required>
                <option value="" disabled selected>اختر الموظف</option>
            </select>
        </div>

        <div>
            <label for="start_date">تاريخ بدء الإجازة</label>
            <input type="date"  class="form-control" name="start_date" id="start_date" required>
        </div>

        <div>
            <label for="end_date">تاريخ انتهاء الإجازة</label>
            <input type="date"  class="form-control" name="end_date" id="end_date" required>
        </div>

        <div class="form-group">
            <label for="reason">سبب الإجازة</label>
            <textarea name="reason" id="reason" class="form-control" style="width: 100%"  rows="5"></textarea>
        </div>

        <button  style="margin-top: 10px" class="save-btn" type="submit">تسجيل الإجازة</button>
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
