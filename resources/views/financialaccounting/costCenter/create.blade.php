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
                <h1>شجرة الحسابات</h1>
                <button class="add-account-btn">
                    <i class="fas fa-plus"></i>
                    إضافة حساب جديد
                </button>
            </div>




            {{--            </div>--}}
        </div>
        <div class="accounts-summary">
            <h2>جدول الحسابات</h2>

            <div class="table-actions">
                <button class="add-row-btn">
                    <i class="fas fa-plus"></i>
                    إضافة حساب جديد
                </button>
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
                        <th>رقم الحساب</th>
                        <th>اسم الحساب</th>
                        <th>المستوى</th>
                        <th>نوع الحساب</th>
                        <th>الحساب الرئيسي</th>
                        <th>رصيد مدين</th>
                        <th>رصيد دائن</th>
                        <th>الرصيد</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Rows will be added here dynamically -->
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">الإجمالي</td>
                        <td class="total-debit">0</td>
                        <td class="total-credit">0</td>
                        <td class="total-balance">0</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="account-form-modal">
            <div class="modal-content">
                <h2>إضافة حساب جديد</h2>
                <form  action="{{ route('accounting.accountsTree.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>رقم الحساب</label>
                        <input type="text" name="account_number" id="accountNumber" required>
                    </div>
                    <div class="form-group">
                        <label>اسم الحساب</label>
                        <input type="text"  name="name"  id="accountName" required>
                    </div>
                    <div class="form-group">
                        <label>نوع الحساب</label>
                        <select name="account_type_id" id="accountType" required>
                            @foreach($accounttypes as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <label>الحساب الرئيسي</label>
                        <select name="parent_id" id="parentAccount" required>
                            <option value="">اختر الحساب الرئيسي...</option>
                            <option value="0">حساب رئيسي</option>
                            @foreach($accounts as $item)
                                <option value="{{$item->id}}"> {{$item->name}}</option>
                            @endforeach
                        </select>
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

@endsection
