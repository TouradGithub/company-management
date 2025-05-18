@extends('financialaccounting.layouts.master')
@section('content')
    <div>
        <h1>الإضافات</h1>
        <div class="additions-grid">
            <div class="addition-card">
                <i class="fas fa-building"></i>
                <h3>مراكز التكلفة</h3>
                <p class="balance">{{App\Models\CostCenter::where('company_id' , getCompanyId())->count()}}</p>

            </div>
            <div class="addition-card">
                <a href="{{ route('customers.index') }}" style="text-decoration: none">
                    <i class="fas fa-users"></i>
                    <h3>العملاء</h3>
                    @if(customers_register())

                    <p class="balance">{{customers_register()->sessionBalance->balance}} ريال</p>

                        <p class="balance">{{customers_register()->name}} - {{customers_register()->account_number}} </p>
                    @endif
                </a>
                <div class="card-actions">
                    @if(!customers_register())
                        <button class="action-btn link-account" id="linkAccountBtn" title="ربط حساب"><i class="fas fa-link"></i></button>
                    @endif
                </div>
            </div>
            <div class="addition-card">
                <a href="{{ route('suppliers-company.index') }}" style="text-decoration: none">
                <i class="fas fa-truck"></i>
                <h3>الموردين</h3>
                @if(suppliers_register() != null)
                <p class="balance">{{suppliers_register()->sessionBalance->balance}} ريال</p>
                    <p class="balance">{{suppliers_register()->name . ' - ' . suppliers_register()->account_number}}</p>
                @endif
                    @php
//dd(suppliers_register());
 @endphp
                </a>
                <div class="card-actions">
                    @if(!suppliers_register())
                        <button class="action-btn link-account" id="link-to-supplier" title="ربط حساب"><i class="fas fa-link"></i></button>
                    @endif
                </div>

            </div>
            <div class="addition-card">
                <i class="fas fa-cash-register"></i>
                <h3>الصناديق</h3>
                @if(cach_register()!= null)
                <p class="balance">{{ cach_register()->sessionBalance->balance}} ريال </p>

                    <p class="balance">{{cach_register()->name}} - {{cach_register()->account_number}} </p>
                @endif

                <div class="card-actions">
                    @if(!cach_register())
                        <button class="action-btn link-account" id="link-cash-register" title="ربط حساب"><i class="fas fa-link"></i></button>
                    @endif
                </div>
            </div>
            <div class="addition-card">
                <i class="fas fa-university"></i>
                <h3>البنوك</h3>
                <p class="balance">892,450 ريال</p>
                <div class="card-actions">
                </div>
            </div>
            <div class="addition-card">
                <i class="fas fa-code-branch"></i>
                <h3>الفروع</h3>
                <p class="balance">{{getCompany()->branches()->count()}}</p>
                <div class="card-actions">
                </div>
            </div>
            <div class="addition-card">
                <i class="fas fa-warehouse"></i>
                <h3>المخازن</h3>
                <p class="balance">567,800 ريال</p>

                <div class="card-actions">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <style>
        .additions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .addition-card {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            border-radius: 5px;
            background-color: #fff;
            position: relative;
        }

        .addition-card i {
            font-size: 30px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .addition-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .addition-card .balance {
            font-size: 16px;
            color: #666;
        }

        .card-actions {
            margin-top: 10px;
        }

        .action-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin: 0 5px;
            color: #007bff;
        }

        .action-btn:hover {
            color: #0056b3;
        }

        .link-account {
            color: #28a745;
        }

        .link-account:hover {
            color: #218838;
        }
    </style>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // تحويل الحسابات إلى تنسيق يمكن استخدامه في SweetAlert2
            const accounts = @json($accounts);

            // عند الضغط على زر "ربط حساب"
            // if(!account){
            $('#linkAccountBtn').on('click', async function () {
                const { value: accountId } = await Swal.fire({
                    title: 'اختيار حساب لربطه بالعملاء',
                    input: 'select',
                    inputOptions: accounts.reduce((options, account) => {
                        options[account.id] = `${account.name} (${account.account_number})`;
                        return options;
                    }, { '': 'اختر حسابًا' }),
                    inputPlaceholder: 'اختر حسابًا',
                    showCancelButton: true,
                    confirmButtonText: 'تأكيد',
                    cancelButtonText: 'إلغاء',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'يرجى اختيار حساب!';
                        }
                    }
                });

                if (accountId) {
                    $.ajax({
                        url: '/link-account-to-customers',
                        method: 'POST',
                        data: {
                            account_id: accountId,
                            _token: '{{ csrf_token() }}' // للتحقق من CSRF في Laravel
                        },
                        success: function (response) {
                            if(response.success){
                                Swal.fire({
                                    title: 'تم الربط',
                                    text: response.message,
                                    icon: 'success',
                                });
                            }else{
                                Swal.fire({
                                    title: ' خطأ ',
                                    text: response.message,
                                    icon: 'error',
                                });
                            }

                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء ربط الحساب',
                                icon: 'error',
                            });
                        }
                    });
                }
            });

            $('#link-cash-register').on('click', async function () {
                const { value: accountId } = await Swal.fire({
                    title: 'اختيار حساب لربط الصناديق',
                    input: 'select',
                    inputOptions: accounts.reduce((options, account) => {
                        options[account.id] = `${account.name} (${account.account_number})`;
                        return options;
                    }, { '': 'اختر حسابًا' }),
                    inputPlaceholder: 'اختر حسابًا',
                    showCancelButton: true,
                    confirmButtonText: 'تأكيد',
                    cancelButtonText: 'إلغاء',
                    inputValidator: async (value) => {
                        if (!value) {
                            return 'يرجى اختيار حساب!';
                        }

                        try {
                            const response = await fetch('/link-cash-register', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                                },
                                body: JSON.stringify({ account_id: value })
                            });

                            const data = await response.json();

                            if (!data.success) {
                                return data.message || 'فشل في ربط الحساب!';
                            }

                            Swal.fire({
                                title: 'تم الربط',
                                text: data.message,
                                icon: 'success',
                            });
                            location.reload();

                        } catch (error) {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء ربط الحساب',
                                icon: 'error',
                            });
                        }
                    }
                });
            });

            $('#link-to-supplier').on('click', async function () {
                const { value: accountId } = await Swal.fire({
                    title: 'اختيار حساب لربط الصناديق',
                    input: 'select',
                    inputOptions: accounts.reduce((options, account) => {
                        options[account.id] = `${account.name} (${account.account_number})`;
                        return options;
                    }, { '': 'اختر حسابًا' }),
                    inputPlaceholder: 'اختر حسابًا',
                    showCancelButton: true,
                    confirmButtonText: 'تأكيد',
                    cancelButtonText: 'إلغاء',
                    inputValidator: async (value) => {
                        if (!value) {
                            return 'يرجى اختيار حساب!';
                        }

                        try {
                            const response = await fetch('/link-to-supplier', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF token
                                },
                                body: JSON.stringify({ account_id: value })
                            });

                            const data = await response.json();

                            if (!data.success) {
                                return data.message || 'فشل في ربط الحساب!';
                            }

                            Swal.fire({
                                title: 'تم الربط',
                                text: data.message,
                                icon: 'success',
                            });
                            location.reload();

                        } catch (error) {
                            Swal.fire({
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء ربط الحساب',
                                icon: 'error',
                            });
                        }
                    }
                });
            });

            // }

        });
    </script>
@endsection
