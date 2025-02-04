@extends('layouts.mastercomany')

@section('content')
<div class="section-header">
    <h2>الاجازات</h2>
    <button class="add-deduction-btn">
        <a href="{{route('company.leaves.create')}}">
      <i class="fas fa-plus"></i>
      إضافة إجازة
        </a>
    </button>
</div>
  <div class="deductions-table">

    <table class="records-table">
    <thead>
        <tr>
            <th>اسم الموظف</th>
            <th> الفرع</th>
            <th>تاريخ البدء</th>
            <th>تاريخ الانتهاء</th>
            <th>السبب</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->employee->name??'' }}</td>
                <td>{{ $leave->branch->name??'' }}</td>
                <td>{{ $leave->start_date }}</td>
                <td>{{ $leave->end_date }}</td>
                <td>{{ $leave->reason }}</td>
                <td>
                    <a   href="{{ route('company.leaves.edit', $leave->id) }}"
                        class="action-btn edit-btn">
                        <i class="fas fa-edit"></i >تعديل</a>
                    <form action="{{ route('company.leaves.destroy', $leave->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"  class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>

</div>
</section>
@endsection
