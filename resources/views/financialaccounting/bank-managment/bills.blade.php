@section('css')
    <link rel="stylesheet" href="{{asset('css/bank-managment.css')}}">
     <style>
        .main-container {
        margin-right: 0;
        }
        @media print {
    body * {
        visibility: hidden;
    }

    #print-area, #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
    }
}

    </style>
@endsection
@extends('financialaccounting.layouts.master')
@section('content')

    <div id="toast" style="position: fixed; top: 20px; right: 20px; background: #38b000; color: white; padding: 10px 20px; border-radius: 8px; display: none; z-index: 9999;"></div>


            <!-- قسم الفواتير -->
            <div style="display: block" class="container" id="billsContainer" >
                <h1>نظام إدارة الفواتير</h1>

                <div class="bills-buttons" id="billsButtons">
                    <button class="bill-btn" id="salesInvoiceBtn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>فاتورة مبيعات</span>
                    </button>
                    <button class="bill-btn" id="purchaseInvoiceBtn">
                        <i class="fas fa-shopping-basket"></i>
                        <span>فاتورة مشتريات</span>
                    </button>
                </div>

                <div class="search-filter-container" id="billsSearchContainer">
                    <div class="search-box">
                        <input type="text" id="billSearchInput" placeholder="بحث في الفواتير...">
                        <button id="billSearchBtn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="filter-options">
                        <select id="billTypeFilter">
                            <option value="all">جميع الفواتير</option>
                            <option value="مبيعات">فواتير المبيعات</option>
                            <option value="مشتريات">فواتير المشتريات</option>
                        </select>
                        <input type="date" id="billDateFilter">
                        <button id="resetBillFiltersBtn"><i class="fas fa-undo"></i> إعادة ضبط</button>
                    </div>
                </div>

                <div class="bills-list" id="billsList">
                    <!-- سيتم إضافة قائمة الفواتير هنا عن طريق JavaScript -->
                </div>
            </div>
        </div>
    {{-- </div> --}}



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


    <div id="print-area" style="display: none;"></div>
@endsection

@section('js')

    <script src="{{asset('js/bank-managment-bills.js')}}"></script>

    <script>


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
            setupEventListeners();
            setupPreviewActions();

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
@endsection

