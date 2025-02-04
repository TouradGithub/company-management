@extends('layouts.masterbranch')

@section('content')
<div class="section-header">
    <h2>الخصومات</h2>
    <button class="add-deduction-btn">
        <a href="{{route('branch.deductions.create')}}">
      <i class="fas fa-plus"></i>
      إضافة خصم
        </a>
    </button>
</div>
</div>
<div class="deductions-table">
            <table class="records-table">
        <thead>
            <tr>
                <th>الموظف</th>
                <th>التاريخ </th>
                <th>  عدد ايام الخصم</th>
                <th> نوع الخصم</th>
                <th>المبلغ </th>
                <th>السبب </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($deductions as $deduction)
                <tr>
                    <td>{{ $deduction->employee->name??''}}</td>
                    <td>{{ $deduction->deduction_date }}</td>
                    <td>{{ $deduction->deduction_days }}</td>
                    <td>
                        @if ($deduction->deduction_type === 'salary_percentage')
                            نسبة من الراتب
                        @elseif ($deduction->deduction_type === 'fixed_amount')
                            مبلغ ثابت
                        @else
                            غير معروف
                        @endif
                    </td>
                    <td>{{ $deduction->deduction_value }}</td>
                    <td>{{ $deduction->reason }}</td>
                    <td>
                        <a  href="{{ route('branch.deductions.edit', $deduction) }}" class="action-btn edit-btn">
                            <i class="fas fa-edit"></i>تعديل</a>
                        <form action="{{ route('branch.deductions.destroy', $deduction) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button  class="action-btn delete-btn">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</section>
@endsection
