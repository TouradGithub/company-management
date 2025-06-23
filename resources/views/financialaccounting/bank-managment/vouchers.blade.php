
@section('css')
    <link rel="stylesheet" href="{{asset('css/bank-managment.css')}}">
@endsection
@extends('financialaccounting.layouts.master')
@section('content')
    <div id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;"></div>


    <!-- قسم السندات -->
    <div style="display: block" class="container" id="vouchersContainer" >
        <h1>نظام إدارة السندات</h1>

        <div class="voucher-buttons">
            <button class="voucher-btn" id="generalReceiptBtn">
                <i class="fas fa-plus-circle"></i>
                <span>سند قبض عام</span>
            </button>
            <button class="voucher-btn" id="generalPaymentBtn">
                <i class="fas fa-minus-circle"></i>
                <span>سند صرف عام</span>
            </button>
        </div>

        <div class="search-filter-container">
            <div class="search-box">
                <input type="text" id="voucherSearchInput" placeholder="بحث في السندات...">
                <button id="voucherSearchBtn"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-options">
                <select id="voucherTypeFilter">
                    <option value="all">جميع السندات</option>
                    <option value="قبض">سندات القبض</option>
                    <option value="صرف">سندات الصرف</option>
                </select>
                <input type="date" id="voucherDateFilter">
                <button id="resetFiltersBtn"><i class="fas fa-undo"></i> إعادة ضبط</button>
            </div>
        </div>

        <div class="vouchers-list" id="vouchersList">
            <!-- سيتم إضافة قائمة السندات هنا عن طريق JavaScript -->
        </div>
    </div>

    <div class="modal" id="generalReceiptModal" w-tid="475" style="display: none;">
        <div class="modal-content deposit-modal" w-tid="477">
            <span class="close" w-tid="479">×</span>
            <h2 w-tid="481">سند قبض عام</h2>
            <div class="deposit-receipt" w-tid="483">
                <div class="receipt-header" w-tid="485">
                    <div class="receipt-logo" w-tid="487">
                        <i class="fas fa-plus-circle" w-tid="489"></i>
                    </div>
                    <div class="receipt-title" w-tid="491">سند قبض عام</div>
                </div>
                <form id="generalReceiptForm" w-tid="493">
                    <div class="form-row" w-tid="495">
                        <div class="form-group" w-tid="497">
                            <label for="generalReceiptNumber" w-tid="499">رقم السند:</label>
                            <input type="text" id="generalReceiptNumber" required="" w-tid="501">
                        </div>
                        <div class="form-group" w-tid="503">
                            <label for="generalReceiptDate" w-tid="505">التاريخ:</label>
                            <input type="date" id="generalReceiptDate" required="" w-tid="507">
                        </div>
                    </div>
                    <div class="form-group" w-tid="509">
                        <label for="receiptFromName" w-tid="511">استلمنا من:</label>
                        <input type="text" id="receiptFromName" required="" w-tid="513">
                    </div>
                    <div class="form-group" w-tid="515">
                        <label for="generalReceiptAmount" w-tid="517">المبلغ:</label>
                        <input type="number" id="generalReceiptAmount" required="" w-tid="519">
                    </div>
                    <div class="form-group" w-tid="521">
                        <label for="paymentMethod" w-tid="523">طريقة الدفع:</label>
                        <select id="paymentMethod" required="" w-tid="525">
                            <option value="نقدي" w-tid="527">نقدي</option>
                            <option value="شيك" w-tid="529">شيك</option>
                            <option value="تحويل بنكي" w-tid="531">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="form-group" w-tid="533">
                        <label for="generalReceiptDescription" w-tid="535">البيان:</label>
                        <textarea id="generalReceiptDescription" required="" w-tid="537"></textarea>
                    </div>
                    <button type="submit" class="save-btn" w-tid="539"><i class="fas fa-save" w-tid="541"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="generalReceiptModal2" w-tid="475" style="display: none;">
        <div class="modal-content deposit-modal" w-tid="477">
            <span class="close" w-tid="479">×</span>
            <h2 w-tid="481">سند قبض عام</h2>
            <div class="deposit-receipt" w-tid="483">
                <div class="receipt-header" w-tid="485">
                    <div class="receipt-logo" w-tid="487">
                        <i class="fas fa-plus-circle" w-tid="489"></i>
                    </div>
                    <div class="receipt-title" w-tid="491">سند قبض عام</div>
                </div>
                <form id="generalReceiptForm2" w-tid="493">
                    <div class="form-row" w-tid="495">
                        <div class="form-group" w-tid="497">
                            <label for="generalReceiptNumber2" w-tid="499">رقم السند:</label>
                            <input type="text" id="generalReceiptNumber2" required="" w-tid="501">
                        </div>
                        <div class="form-group" w-tid="503">
                            <label for="generalReceiptDate2" w-tid="505">التاريخ:</label>
                            <input type="date" id="generalReceiptDate2" required="" w-tid="507">
                        </div>
                    </div>
                    <div class="form-group" w-tid="509">
                        <label for="receiptFromName2" w-tid="511">استلمنا من:</label>
                        <input type="text" id="receiptFromName2" required="" w-tid="513">
                    </div>
                    <div class="form-group" w-tid="515">
                        <label for="generalReceiptAmount2" w-tid="517">المبلغ:</label>
                        <input type="number" id="generalReceiptAmount2" required="" w-tid="519">
                    </div>
                    <div class="form-group" w-tid="521">
                        <label for="paymentMethod2" w-tid="523">طريقة الدفع:</label>
                        <select id="paymentMethod2" required="" w-tid="525">
                            <option value="نقدي" w-tid="527">نقدي</option>
                            <option value="شيك" w-tid="529">شيك</option>
                            <option value="تحويل بنكي" w-tid="531">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="form-group" w-tid="533">
                        <label for="generalReceiptDescription2" w-tid="535">البيان:</label>
                        <textarea id="generalReceiptDescription2" required="" w-tid="537"></textarea>
                    </div>
                    <button type="submit" class="save-btn" w-tid="539"><i class="fas fa-save" w-tid="541"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>


    <div class="modal" id="generalPaymentModal" w-tid="543" style="display: none;">
        <div class="modal-content transaction-modal" w-tid="545">
            <span class="close" w-tid="547">×</span>
            <h2 w-tid="549">سند صرف عام</h2>
            <div class="transaction-voucher" w-tid="551">
                <div class="voucher-header" w-tid="553">
                    <div class="voucher-logo" w-tid="555">
                        <i class="fas fa-minus-circle" w-tid="557"></i>
                    </div>
                    <div class="voucher-title" w-tid="559">سند صرف عام</div>
                </div>
                <form id="generalPaymentForm" w-tid="561">
                    <div class="form-row" w-tid="563">
                        <div class="form-group" w-tid="565">
                            <label for="generalPaymentNumber" w-tid="567">رقم السند:</label>
                            <input type="text" id="generalPaymentNumber" required="" w-tid="569">
                        </div>
                        <div class="form-group" w-tid="571">
                            <label for="generalPaymentDate" w-tid="573">التاريخ:</label>
                            <input type="date" id="generalPaymentDate" required="" w-tid="575">
                        </div>
                    </div>
                    <div class="form-group" w-tid="577">
                        <label for="paidToName" w-tid="579">صرفنا إلى:</label>
                        <input type="text" id="paidToName" required="" w-tid="581">
                    </div>
                    <div class="form-group" w-tid="583">
                        <label for="generalPaymentAmount" w-tid="585">المبلغ:</label>
                        <input type="number" id="generalPaymentAmount" required="" w-tid="587">
                    </div>
                    <div class="form-group" w-tid="589">
                        <label for="paymentMethodOut" w-tid="591">طريقة الدفع:</label>
                        <select id="paymentMethodOut" required="" w-tid="593">
                            <option value="نقدي" w-tid="595">نقدي</option>
                            <option value="شيك" w-tid="597">شيك</option>
                            <option value="تحويل بنكي" w-tid="599">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="form-group" w-tid="601">
                        <label for="generalPaymentDescription" w-tid="603">البيان:</label>
                        <textarea id="generalPaymentDescription" required="" w-tid="605"></textarea>
                    </div>
                    <button type="submit" class="save-btn" w-tid="607"><i class="fas fa-save" w-tid="609"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="generalPaymentModal2" w-tid="543" style="display: none;">
        <div class="modal-content transaction-modal" w-tid="545">
            <span class="close" w-tid="547">×</span>
            <h2 w-tid="549">سند صرف عام</h2>
            <div class="transaction-voucher" w-tid="551">
                <div class="voucher-header" w-tid="553">
                    <div class="voucher-logo" w-tid="555">
                        <i class="fas fa-minus-circle" w-tid="557"></i>
                    </div>
                    <div class="voucher-title" w-tid="559">سند صرف عام</div>
                </div>
                <form id="generalPaymentForm2" w-tid="561">
                    <div class="form-row" w-tid="563">
                        <div class="form-group" w-tid="565">
                            <label for="generalPaymentNumber2" w-tid="567">رقم السند:</label>
                            <input type="text" id="generalPaymentNumber2" required="" w-tid="569">
                        </div>
                        <div class="form-group" w-tid="571">
                            <label for="generalPaymentDate2" w-tid="573">التاريخ:</label>
                            <input type="date" id="generalPaymentDate2" required="" w-tid="575">
                        </div>
                    </div>
                    <div class="form-group" w-tid="577">
                        <label for="paidToName" w-tid="579">صرفنا إلى:</label>
                        <input type="text" id="paidToName2" required="" w-tid="581">
                    </div>
                    <div class="form-group" w-tid="583">
                        <label for="generalPaymentAmount2" w-tid="585">المبلغ:</label>
                        <input type="number" id="generalPaymentAmount2" required="" w-tid="587">
                    </div>
                    <div class="form-group" w-tid="589">
                        <label for="paymentMethodOut2" w-tid="591">طريقة الدفع:</label>
                        <select id="paymentMethodOut2" required="" w-tid="593">
                            <option value="نقدي" w-tid="595">نقدي</option>
                            <option value="شيك" w-tid="597">شيك</option>
                            <option value="تحويل بنكي" w-tid="599">تحويل بنكي</option>
                        </select>
                    </div>
                    <div class="form-group" w-tid="601">
                        <label for="generalPaymentDescription2" w-tid="603">البيان:</label>
                        <textarea id="generalPaymentDescription2" required="" w-tid="605"></textarea>
                    </div>
                    <button type="submit" class="save-btn" w-tid="607"><i class="fas fa-save" w-tid="609"></i> حفظ</button>
                </form>
            </div>
        </div>
    </div>


    <!-- نافذة منبثقة لمعاينة المستند -->
    <div class="modal" id="previewDocumentModal">
        <div class="modal-content preview-modal">
            <span class="close">&times;</span>
            <div class="preview-actions">
                <button id="printDocumentBtn"><i class="fas fa-print"></i> طباعة</button>
                {{-- <button id="editDocumentBtn"><i class="fas fa-edit"></i> تعديل</button>
                <button id="deleteDocumentBtn"><i class="fas fa-trash-alt"></i> حذف</button> --}}
            </div>
            <div id="printableDocument">
                <div class="document-preview" id="documentPreview">
                    <!-- محتوى المستند للمعاينة -->
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

    <script src="{{asset('js/bank-managment-vouchers.js')}}"></script>

    <script>
        // زر إضافة حساب جديد
        document.getElementById('generalReceiptBtn').addEventListener('click', function() {
                    // console.log('ok')
                    const today = new Date().toISOString().split('T')[0];
            document.getElementById('generalReceiptDate').value = today;

            const receiptCount = vouchers.filter(v => v.type === 'قبض').length;
            document.getElementById('generalReceiptNumber').value = `REC-${String(receiptCount + 1).padStart(3, '0')}`;

            document.getElementById('generalReceiptModal').style.display = 'block';
        });
        document.getElementById('generalPaymentBtn').addEventListener('click', function() {
            // console.log('ok')
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('generalPaymentDate').value = today;
            const paymentCount = vouchers.filter(v => v.type === 'صرف').length;
            document.getElementById('generalPaymentNumber').value = `PAY-${String(paymentCount + 1).padStart(3, '0')}`;

            document.getElementById('generalPaymentModal').style.display = 'block';
        });
        function showToast(message, color = '#38b000') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.backgroundColor = color;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // renderStatistics();
            // renderAccounts();
            // setupEventListeners();
            setupPreviewActions();

            setupSidebar();
        });

            // إغلاق النوافذ المنبثقة
        document.querySelectorAll('.close').forEach(closeBtn => {
            closeBtn.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            });
        });

        // إغلاق النوافذ المنبثقة عند النقر خارجها
        window.addEventListener('click', function(e) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        document.getElementById('generalReceiptForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('vouchers.storeReceipt') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    number: document.getElementById('generalReceiptNumber').value,
                    date: document.getElementById('generalReceiptDate').value,
                    fromTo: document.getElementById('receiptFromName').value,
                    amount: document.getElementById('generalReceiptAmount').value,
                    paymentMethod: document.getElementById('paymentMethod').value,
                    description: document.getElementById('generalReceiptDescription').value
                })
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.message);
                document.getElementById('generalReceiptForm').reset();
                document.getElementById('generalReceiptModal').style.display = 'none';
                fetchVouchers();
            });
        });

        document.getElementById('generalPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('vouchers.storePayment') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    number: document.getElementById('generalPaymentNumber').value,
                    date: document.getElementById('generalPaymentDate').value,
                    fromTo: document.getElementById('paidToName').value,
                    amount: document.getElementById('generalPaymentAmount').value,
                    paymentMethod: document.getElementById('paymentMethodOut').value,
                    description: document.getElementById('generalPaymentDescription').value
                })
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.message);
                document.getElementById('generalPaymentForm').reset();
                document.getElementById('generalPaymentModal').style.display = 'none';
                fetchVouchers();
            });
        });
    </script>

@endsection
