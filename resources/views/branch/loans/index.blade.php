@extends('layouts.branch')

@section('content')
<section id="overtime-form" class="content-section active">
    <div class="container">
    <h1>السلف</h1>
    <a href="{{ route('branch.loans.create') }}" class="btn btn-primary mb-3">إضافة سلف </a>
    <div id="entriesContainer">

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
                        <a class="btn-primary btn-edit" href="{{ route('branch.loans.edit', $loan) }}" class="btn btn-sm btn-warning">تحديث</a>
                        <form action="{{ route('branch.loans.destroy', $loan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button  class="btn-primary btn-delete">حذف</button>
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
