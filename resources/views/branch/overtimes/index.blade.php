@extends('layouts.branch')

@section('content')


@if(session('success'))
    <p>{{ session('success') }}</p>
@endif
<section  >
    <div class="container">
        <h1>سجل العمل الإضافي</h1>
        <a  class="btn btn-primary" href="{{ route('branch.overtimes.create') }}">إضافة الإضافي</a>

      <div  >
        <div id="entriesContainer">

            <table class="records-table">
                <thead>
                    <tr>
                        <th>اسم الموظف</th>
                        <th>نوع الإضافي</th>
                        <th> المجموع</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overtimes as $overtime)
                        <tr>
                            <td>{{ $overtime->employee->name??'' }}</td>
                            <td>
                                @switch($overtime->overtime_type)
                                    @case('fixed')
                                     مبلغ ثابت  ({{$overtime->fixed_amount }})
                                        @break
                                    @case('hours')
                                     عدد ساعات  ({{$overtime->hour_multiplier }})
                                        @break
                                    @case('daily')
                                       قيمة اليوم ({{$overtime->hours }})
                                        @break
                                    @default
                                        غير محدد
                                @endswitch
                            </td>

                            <td>{{ $overtime->total_amount }}</td>
                            <td>
                                <a class="btn-primary btn-edit" href="{{ route('branch.overtimes.edit', $overtime->id) }}">تعديل</a>
                                <form action="{{ route('branch.overtimes.destroy', $overtime->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-primary btn-delete" type="submit">حذف</button>
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
