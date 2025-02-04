@extends('layouts.mastercomany')

@section('content')
    <div class="section-header">
        <h2>السلف</h2>
        <button class="add-deduction-btn">
            <a href="{{route('loans.create')}}">
          <i class="fas fa-plus"></i>
          إضافة سلف
            </a>
        </button>
    </div>
      <div class="deductions-table">
        <table >
        <thead>
            <tr>
                <th>الموظف</th>
                <th>الفرع</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الحاله </th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
                <tr>
                    <td>{{ $loan->employee->name??'' }}</td>
                    <td>{{ $loan->branch->name??'' }}</td>
                    <td>{{ $loan->amount }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>
                        @if ($loan->remaining_loan == 0)
                            <span class="badge bg-success">تم السداد بالكامل</span>
                        @elseif ($loan->paid_loan > 0)
                            <span class="badge bg-warning">تم السداد جزئياً ({{$loan->paid_loan}})</span>
                        @else
                            <span class="badge bg-danger">لم يتم السداد بعد</span>
                        @endif
                    </td>
                    <td>
                        <a  href="{{ route('loans.edit', $loan) }}" class="action-btn edit-btn">
                            <i class="fas fa-edit"></i
                                >
                                تحديث</a>
                        <form action="{{ route('loans.destroy', $loan) }}" method="POST" style="display:inline;">
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
</div>
</section>
@endsection
