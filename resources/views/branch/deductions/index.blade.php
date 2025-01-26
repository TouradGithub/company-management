@extends('layouts.branch')

@section('content')
<div class="container">
    <section id="overtime-form" class="content-section active">
        <div class="container">
        <h1>الخصومات</h1>
        <a href="{{ route('branch.deductions.create') }}" class="btn btn-primary mb-3">إضافة خصم </a>
        <div id="entriesContainer">

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
                        <a class="btn-primary btn-edit" href="{{ route('branch.deductions.edit', $deduction) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('branch.deductions.destroy', $deduction) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn-primary btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</section>
@endsection
