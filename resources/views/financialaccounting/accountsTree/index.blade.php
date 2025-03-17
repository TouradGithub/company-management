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
                <div class="accounts-tree">
                    @foreach ($accountsTree as $account)
                        @include('financialaccounting.accountsTree.accountsTree', ['account' => $account])
                    @endforeach
                </div>



{{--            </div>--}}
        </div>
        <div class="accounts-summary">
            <h2>جدول الحسابات</h2>

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
                        <th>رقم الحساب</th>
                        <th>اسم الحساب</th>
                        <th>نوع الحساب</th>
                        <th>الحساب الرئيسي</th>
                        <th>رصيد مدين</th>
                        <th>رصيد دائن</th>
                        <th>الرصيد</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                   @foreach($accounts as $item)
                       <tr>


                       <td>{{$item->account_number }}</td>
                       <td>{{$item->name }}</td>
                       <td>{{$item->accountType->name }}</td>
                           <td style="color: {{ $item->parent_id == 0 ? 'blue' : 'green' }};">
                               {{ $item->parent_id == 0 ? 'حساب رئيسي' : $item->parentAccount->name }}
                           </td>

                           <td class="total-debit">0</td>
                           <td class="total-credit">0</td>
                           <td class="total-balance">0</td>
                           <td>
                               <a href="#" style="margin: 10px; font-size: 20px;"><i class="fas fa-edit" style="color: green;"></i></a>

                               <a href="{{ route('accounting.delete', $item->id) }}" style="margin: 10px; font-size: 20px;"
                                  onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا الحساب؟');">
                                   <i class="fas fa-trash" style="color: red;"></i></a>
                           </td>


                       </tr>
                   @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4">الإجمالي</td>
                        <td class="total-debit">0</td>
                        <td class="total-credit">0</td>
                        <td class="total-balance">0</td>
                        <td>

                        </td>
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
    <script >
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-sub-account').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const parentNode = this.closest('.tree-node');
                    const parentName = parentNode.querySelector('.account-name').textContent;
                    const parentId = parentNode.dataset.id || '';
                    console.log(parentId);
                    // عرض المودال
                    const modal = document.querySelector('.account-form-modal');
                    modal.classList.add('active');
                    document.querySelector('.modal-overlay').classList.add('active');
                    console.log("GOOD");
                    console.log(parentId);
                    // تحديث عنوان المودال
                    modal.querySelector('h2').textContent = `إضافة حساب فرعي لـ ${parentName}`;

                    // تحديث القائمة المنسدلة واختيار الحساب الرئيسي تلقائيًا
                    const parentAccountSelect = modal.querySelector('#parentAccount');
                    Array.from(parentAccountSelect.options).forEach(option => {

                        if (option.value == parentId && parentId != '') {
                            console.log("GOOD A");

                            option.selected = true;
                        } else {
                            option.selected = false;
                        }
                    });
                    // parentAccountSelect.disabled = true;
                    // parentAccountSelect.val();
                });
            });
            document.querySelector('.add-account-btn').addEventListener('click', function(e) {
                const modal = document.querySelector('.account-form-modal');
                modal.querySelector('h2').textContent = `إضافة حساب  جديد`;
                const parentAccountSelect = modal.querySelector('#parentAccount');
                parentAccountSelect.disabled = false;
                modal.classList.add('active');
                document.querySelector('.modal-overlay').classList.add('active');
            });

        });
    </script>
@endsection
