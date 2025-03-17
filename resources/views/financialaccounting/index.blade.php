@extends('financialaccounting.layouts.master')

@section('content')

    <div id="mainDashboard">
        <h1>لوحة التحكم</h1>
        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-money-bill-wave"></i>
                <h3>إجمالي الأصول</h3>
                <p>1,234,567 ريال</p>
            </div>
            <div class="card">
                <i class="fas fa-chart-line"></i>
                <h3>الإيرادات</h3>
                <p>234,567 ريال</p>
            </div>
            <div class="card">
                <i class="fas fa-chart-pie"></i>
                <h3>المصروفات</h3>
                <p>123,456 ريال</p>
            </div>
        </div>
        <div class="quick-actions">
            <div class="quick-action-card">
                <span class="action-badge">سريع</span>
                <i class="fas fa-journal-whills"></i>
                <h3>قيد يومية جديد</h3>
                <p>إنشاء قيد يومية جديد بشكل سريع</p>
            </div>
            <div class="quick-action-card">
                <span class="action-badge">سريع</span>
                <i class="fas fa-file-invoice"></i>
                <h3>فاتورة مبيعات</h3>
                <p>إنشاء فاتورة مبيعات جديدة</p>
            </div>
            <div class="quick-action-card">
                <span class="action-badge">سريع</span>
                <i class="fas fa-money-bill-wave"></i>
                <h3>سند صرف</h3>
                <p>إنشاء سند صرف جديد</p>
            </div>
            <div class="quick-action-card">
                <span class="action-badge">سريع</span>
                <i class="fas fa-file-invoice-dollar"></i>
                <h3>كشف حساب</h3>
                <p>عرض وطباعة كشف حساب</p>
            </div>
        </div>
    </div>

@endsection
