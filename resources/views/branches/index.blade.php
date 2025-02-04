@extends('layouts.mastercomany')

@section('content')

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <div class="section-header">
        <h2>الفروع</h2>
        <button class="add-deduction-btn">
            <a href="{{route('branches.create')}}">
          <i class="fas fa-plus"></i>
          إضافة فرع
            </a>
        </button>
    </div>
      <div class="deductions-table">
        <table class="records-table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>اسم المدير</th>
                <th>الايميل</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->name_admin_company }}</td>
                <td>{{ $company->email }}</td>
                <td>
                    <a  href="{{ route('branches.edit', $company->id) }}"
                        class="action-btn edit-btn">
                            <i class="fas fa-edit"></i
                                >تحديث</a>
                    <form action="{{ route('branches.destroy', $company->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button class="action-btn delete-btn" type="submit" onclick="return confirm('Are you sure?')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
