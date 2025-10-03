@extends('financialaccounting.layouts.master')

@section('css')
<style>
body {
    background-color: #f0f8ff !important;
    margin: 0;
    padding: 20px;
}

.test-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.test-title {
    font-size: 28px;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.test-button {
    background: rgba(255,255,255,0.2);
    border: 2px solid white;
    color: white;
    padding: 15px 30px;
    border-radius: 25px;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.test-button:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
}
</style>
@endsection

@section('content')
<div class="test-container">
    <h1 class="test-title">🎉 فاتورة المبيعات</h1>
    <p>إذا كنت ترى هذا التصميم، فإن CSS يعمل بشكل صحيح!</p>
    <button class="test-button">اختبار الزر</button>

    <div style="margin-top: 30px; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px;">
        <h3>معلومات الاختبار:</h3>
        <p>التاريخ: {{ date('Y-m-d H:i:s') }}</p>
        <p>Laravel Version: {{ app()->version() }}</p>
        <p>المسار: {{ Request::path() }}</p>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('.test-button').click(function() {
        alert('JavaScript يعمل بشكل صحيح! ✅');
    });

    console.log('تم تحميل الصفحة بنجاح');
});
</script>
@endsection
