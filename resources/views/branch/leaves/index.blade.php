@extends('layouts.branch')

@section('content')
<section id="overtime-form" class="content-section active">
<div class="container">
    <h1>الاجازات</h1>
    <a href="{{ route('branch.leaves.create') }}" class="btn btn-primary">إضافة إجازة </a>
    <div id="entriesContainer">

    <table class="records-table">
    <thead>
        <tr>
            <th>اسم الموظف</th>
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
                <td>{{ $leave->start_date }}</td>
                <td>{{ $leave->end_date }}</td>
                <td>{{ $leave->reason }}</td>
                <td>
                    <a  class="btn-primary btn-edit" href="{{ route('branch.leaves.edit', $leave->id) }}">تعديل</a>
                    <form action="{{ route('branch.leaves.destroy', $leave->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"  class="btn-primary btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
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
