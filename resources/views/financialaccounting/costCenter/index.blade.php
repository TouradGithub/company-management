@extends('financialaccounting.layouts.master')
@section('content')
    <div id="accountsTreeSection" >
        <div class="accounts-tree-container">
            @if($errors->any())
                <div style="color: red;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="accounts-header">
                <h1>مراكز التكلفه </h1>
                <button class="add-account-btn">
                    <i class="fas fa-plus"></i>
                    إضافة مركز تكلفه جديد
                </button>
            </div>

                <div class="table-actions">

                    <button class="export-excel-btn">
                        <i class="fas fa-file-excel"></i>
                        تصدير Excel
                    </button>
                    <button class="export-pdf-btn">
                        <i class="fas fa-file-pdf"></i>
                        تصدير PDF
                    </button>
                </div>

                <div class="accounts-table-container">
                    <table class="accounts-table" id="accountsTable">
                        <thead>
                        <tr>
                            <th>إسم مركز التكلفة </th>
                            <th>رمز مركز التكلفه </th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                           @foreach($costcenters as $item)
                               <tr>
                                   <td> {{$item->name}} </td>
                                   <td> {{$item->code}} </td>
                                   <td>حذف</td>
                               </tr>
                           @endforeach
                        </tbody>

                    </table>
                </div>


                {{--            </div>--}}
        </div>


        <div class="account-form-modal">
            <div class="modal-content">
                <h2>إضافة مركز تكلفة جديد</h2>
                <form  action="{{ route('cost-center.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>إسم المركز </label>
                        <input type="text" name="name" id="accountNumber" required>
                    </div>
                    <div class="form-group">
                        <label> رمز المركز</label>
                        <input type="text"  name="code"  id="accountName" required>
                    </div>


                    <div class="modal-buttons">
                        <button type="button" class="cancel-btn">إلغاء</button>
                        <button type="submit" class="save-btn">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    </div>
@endsection
@section('js')
    <script >
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelector('.add-account-btn').addEventListener('click', function(e) {
                const modal = document.querySelector('.account-form-modal');

                modal.classList.add('active');
                document.querySelector('.modal-overlay').classList.add('active');
            });

        });
    </script>
@endsection
