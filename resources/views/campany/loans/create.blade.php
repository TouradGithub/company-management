@extends('layouts.mastercomany')

@section('content')

    @if($errors->any())
        <div style="color: red;text-align: center">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="section-header">
        <h2>إضافة سلف جديد</h2>
    </div>

    <div class="add-advance-content">
    <form action="{{ route('loans.store') }}" method="POST"  id="add-advance-form" class="standard-form">
        @csrf

            <div class="form-group">

              <label for="branches">الفروع:</label>
              <select id="branches" class="form-control" multiple  required>
                  @foreach ($branches as $item)

                  <option value="{{$item->id}}"> {{$item->name}}</option>
                  @endforeach
              </select>

            </div>

            <div class="form-group">
              <label for="employees">الموظفين:</label>
              <select id="employees" class="form-control" name="employe_id" required>
              </select>
            </div>
        <div class="form-group">
            <label for="amount">المبلغ</label>
            <input type="number" name="amount" id="amount"  class="form-control">
        </div>
        <div class="form-group">
            <label for="loan_date">التاريخ</label>
            <input type="date" name="loan_date" id="loan_date" class="form-control">
        </div>
        <button   style="margin-top: 30px" class="save-btn">حفظ</button>
    </form>
</div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{asset('overtime.js')}}"></script>


@endsection
