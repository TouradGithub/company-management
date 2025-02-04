@extends('layouts.masterbranch')

@section('content')
<div class="section-header">
    <h2>الموظفين</h2>
    <button class="add-deduction-btn">
        <a href="{{route('branch.loans.create')}}">
      <i class="fas fa-plus"></i>
      إضافة موظف
        </a>
    </button>
</div>
</div>
<div class="deductions-table">
        <table class="records-table">
        <thead>
            <tr>
                <th>الموظف</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
                <tr>
                    <td>{{ $loan->employee->name??'' }}</td>
                    <td>{{ $loan->amount }}</td>
                    <td>{{ $loan->loan_date }}</td>
                    <td>
                        <a  href="{{ route('branch.loans.edit', $loan) }}"  class="action-btn edit-btn">
                            <i class="fas fa-edit"></i >تحديث</a>
                        <form action="{{ route('branch.loans.destroy', $loan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button   class="action-btn delete-btn">حذف</button>
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
