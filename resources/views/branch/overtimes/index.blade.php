@extends('layouts.masterbranch')

@section('content')


@if(session('success'))
    <p>{{ session('success') }}</p>
@endif
    <div class="section-header">
        <h2>الاضافي</h2>
        <button class="add-deduction-btn">
            <a href="{{route('branch.overtimes.create')}}">
        <i class="fas fa-plus"></i>
        إضافة إضافي
            </a>
        </button>
    </div>
    </div>
    <div class="deductions-table">

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
                                <a  href="{{ route('branch.overtimes.edit', $overtime->id) }}" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i >تعديل</a>
                                <form action="{{ route('branch.overtimes.destroy', $overtime->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button  class="action-btn delete-btn" type="submit">حذف</button>
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
