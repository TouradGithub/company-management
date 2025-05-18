


@extends('financialaccounting.layouts.master')
@section('content')

    <div  >
        <h1>الإضافات</h1>
        <div class="accounts-modal-header">
            <h2 class="accounts-modal-title">
                <i class="fas fa-users"></i>
              لعملاء
            </h2>
            <div class="total-balance">
                الرصيد الإجمالي: 342323 ريال
            </div>
        </div>
        <div class="accounts-grid">
            @foreach($customers as $item)
            <div class="account-card">
                <i class="fas fa-users account-icon"></i>
                <div class="account-name">{{$item->name}}</div>
                <div class="account-balance"> {{$item->contact_info}}</div>
{{--                <div class="card-actions">--}}
{{--                    <button class="action-btn view" title="عرض التفاصيل">--}}
{{--                        <i class="fas fa-eye"></i>--}}
{{--                    </button>--}}
{{--                    <button class="action-btn edit" title="تعديل">--}}
{{--                        <i class="fas fa-edit"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
            </div>
            @endforeach
        </div>
        <div class="modal-buttons">
            <button class="back-btn">رجوع</button>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {




        });
    </script>
@endsection
