@extends('layouts.overtime')

@section('content')


@if(session('success'))
    <p>{{ session('success') }}</p>
@endif
<section  >
    <div class="container">
        <h1>سجل العمل الإضافي</h1>
        <a  class="btn btn-primary" href="{{ route('company.overtimes.create') }}">إضافة الإضافي</a>

      <div  >
        <div id="entriesContainer">

            <table class="records-table">
                <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>اسم الفرع</th>
                        <th> المجموع</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overtimes as $overtime)
                        <tr>
                            <td>{{ $overtime->employee->name }}</td>
                            <td>{{ $overtime->branch->name }}</td>
                            <td>{{ $overtime->total_amount }}</td>
                            <td>
                                <a class="btn-primary btn-edit" href="{{ route('company.overtimes.edit', $overtime->id) }}">تعديل</a>
                                <form action="{{ route('company.overtimes.destroy', $overtime->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <butto class="btn-primary btn-delete"n type="submit">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </section>

@endsection
