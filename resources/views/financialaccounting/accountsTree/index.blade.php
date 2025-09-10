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
        </div>
        <div class="account-form-modal">
            <div class="modal-content">
                <h2>إضافة حساب جديد</h2>
                <form action="{{ route('accounting.accountsTree.store') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>رقم الحساب</label>
                            <input type="text" name="account_number" id="accountNumber" required>
                        </div>
                        <div class="form-group-model">
                            <label>اسم الحساب</label>
                            <input type="text" name="name" id="accountName" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>نوع الحساب</label>
                            <select name="account_type_id" id="accountType" required>
                                @foreach($accounttypes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-model">
                            <label>تصنيف الحساب</label>
                            <select name="ref_account_id" id="refAccount" required>
                                <option value="">اختر التصنيف...</option>
                                @foreach($refAccounts as $ref)
                                    <option value="{{ $ref->id }}">{{ $ref->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-model">
                            <label>الحساب الرئيسي</label>
                            <select name="parent_id" id="parentAccount" required>
                                <option value="">اختر الحساب الرئيسي...</option>
                                <option value="0">حساب رئيسي</option>
                                @foreach($addAccounts  as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-model" id="openingBalanceRow" style="display: none;">
                            <label>الرصيد الافتتاحي</label>
                            <input type="number"  name="opening_balance" id="openingBalance" step="0.01" value="0" required>
                        </div>
                        <div class="form-group-model">
                            <label> القائمة الختامية</label>
                            <select name="closing_list_type" id="closingListType">
                                <option value="">اختر نوع</option>
                                <option value="1">قائمة الدخل</option>
                                <option value="2">الميزانيه العموميه</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>
                                هل هو حساب فرعي (نهائي)؟ </label>
                            <input type="checkbox"  id="isLastCheckbox" name="islast" value="1">
                        </div>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="cancel-btn">إلغاء</button>
                        <button type="submit" class="save-btn">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="account-form-modal-show">
            <div class="modal-content">
                <h2>  </h2>
                <form action="{#" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>رقم الحساب</label>
                            <input type="text" name="account_number" id="accountNumberShow" required>
                        </div>
                        <div class="form-group-model">
                            <label>اسم الحساب</label>
                            <input type="text" name="name" id="accountNameShow" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-model">
                            <label>نوع الحساب</label>
                            <select name="account_type_id" id="accountTypeShow" required>
                                @foreach($accounttypes as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-model">
                            <label>تصنيف الحساب</label>
                            <select name="ref_account_id" id="refAccountShow" required>
                                <option value="">اختر التصنيف...</option>
                                @foreach($refAccounts as $ref)
                                    <option value="{{ $ref->id }}">{{ $ref->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-model">
                            <label>الحساب الرئيسي</label>
                            <select name="parent_id" id="parentAccountShow" required>
                                <option value="">اختر الحساب الرئيسي...</option>
                                <option value="0">حساب رئيسي</option>
                                @foreach($accounts as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group-model" id="openingBalanceRowShow" style="display: none;">
                            <label>الرصيد الافتتاحي</label>
                            <input type="number"  name="opening_balance" id="openingBalance" step="0.01" value="0" required>
                        </div>
                        <div class="form-group-model">
                            <label> القائمة الختامية</label>
                            <select name="closing_list_type" id="closingListTypeShow">
                                <option value="">اختر نوع</option>
                                <option value="1">قائمة الدخل</option>
                                <option value="2">الميزانيه العموميه</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-model">
                            <label>
                                هل هو حساب فرعي (نهائي)؟ </label>
                            <input type="checkbox"  id="isLastCheckboxShow" name="islast" value="1">

                        </div>
                    </div>

                    <div class="modal-buttons">
                        <button type="button" class="cancel-btn">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script >
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.add-sub-account').forEach(btn => {
                btn.addEventListener('click', async  function(e) {
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
                    modal.querySelector('h2').textContent = `إضافة حساب فرعي لـ ${parentName}`;

                    const parentAccountSelect = modal.querySelector('#parentAccount');
                    Array.from(parentAccountSelect.options).forEach(option => {
                        if (option.value == parentId && parentId != '') {
                            console.log("GOOD A");

                            option.selected = true;
                        } else {
                            option.selected = false;
                        }
                    });
                    if (parentId) {
                        try {
                            const response = await fetch(`/accounts/next-number/${parentId}`);
                            const data = await response.json();
                            document.getElementById('accountNumber').value = data.account_number;
                        } catch (error) {
                            console.error('فشل في جلب رقم الحساب:', error);
                        }
                    }
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
            const checkbox = document.getElementById('isLastCheckbox');
            const openingBalanceRow = document.getElementById('openingBalanceRow');
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    openingBalanceRow.style.display = 'block';
                } else {
                    // الحصول على input داخل div ومسح قيمته
                    const input = openingBalanceRow.querySelector('input');
                    if (input) input.value = '';
                    openingBalanceRow.style.display = 'none';
                }


            });
        });
        $(document).ready(function (){
            $('.show-account-btn').on('click', function() {
                let accountId = $(this).attr('id');
                showLoadingOverlay();
                $.ajax({
                    url: `/Acounting/edit/${accountId}`,
                    method: 'GET',
                    success: function(response) {
                        $('#accountId').val(response.id);
                        $('.account-form-modal-show h2').text(`عرض حساب ${response.name}`);

                        $('#accountNumberShow').val(response.account_number).prop('readonly', true);
                        $('#accountNameShow').val(response.name).prop('readonly', true);
                        $('#accountTypeShow').val(response.account_type_id).prop('disabled', true);
                        $('#parentAccountShow').val(response.parent_id).prop('disabled', true);
                        $('#openingBalanceShow').val(response.opening_balance).prop('readonly', true);
                        $('#closingListTypeShow').val(response.closing_list_type).prop('disabled', true);
                        $('#isLastCheckboxShow').prop('checked', response.islast == 1).prop('disabled', true);

                        if(response.islast == 1){
                            $('#openingBalanceRowShow').show();
                        } else {
                            $('#openingBalanceRowShow').hide();
                        }
                        $('#isLastCheckboxShow').prop('checked', response.islast == 1);

                        $('.account-form-modal-show').show();
                        hideLoadingOverlay();
                    },
                    error: function(xhr) {
                        hideLoadingOverlay();
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: xhr.responseJSON?.message || 'فشل في جلب بيانات الحساب',
                        });
                    }
                });
            });

            $('.cancel-btn').on('click', function() {
                $('.account-form-modal').hide();
                $('.account-form-modal-show').hide();
            });
        });
    </script>
@endsection
