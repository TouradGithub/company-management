#!/bin/bash

# 🚀 سكريبت فحص سريع للنشر
# استخدم هذا السكريبت على السيرفر بعد رفع التعديلات

echo "🔍 بدء فحص النظام..."

# 1. مسح التخزين المؤقت
echo "📋 مسح التخزين المؤقت..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 2. فحص الملفات المهمة
echo "📁 فحص الملفات المعدلة..."
if [ -f "app/Http/Controllers/Accounting/IncomeStatementController.php" ]; then
    echo "✅ IncomeStatementController موجود"
else
    echo "❌ IncomeStatementController مفقود!"
fi

if [ -f "app/Helpers/AccountTransactionHelper.php" ]; then
    echo "✅ AccountTransactionHelper موجود"
else
    echo "❌ AccountTransactionHelper مفقود!"
fi

# 3. فحص قاعدة البيانات
echo "🗄️ فحص قاعدة البيانات..."
php artisan migrate:status

# 4. اختبار أساسي
echo "🧪 اختبار أساسي..."
php artisan tinker --execute="
try {
    \$companies = App\Models\Company::count();
    echo 'عدد الشركات: ' . \$companies . PHP_EOL;

    \$accounts = App\Models\Account::count();
    echo 'عدد الحسابات: ' . \$accounts . PHP_EOL;

    echo '✅ الاتصال بقاعدة البيانات يعمل' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ خطأ في قاعدة البيانات: ' . \$e->getMessage() . PHP_EOL;
}
"

# 5. فحص الصلاحيات
echo "🔐 فحص الصلاحيات..."
if [ -w "storage/logs" ]; then
    echo "✅ مجلد logs قابل للكتابة"
else
    echo "⚠️ مجلد logs غير قابل للكتابة - قم بتعديل الصلاحيات"
fi

if [ -w "bootstrap/cache" ]; then
    echo "✅ مجلد cache قابل للكتابة"
else
    echo "⚠️ مجلد cache غير قابل للكتابة - قم بتعديل الصلاحيات"
fi

# 6. فحص سجل الأخطاء
echo "📝 فحص آخر الأخطاء..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "آخر 3 أخطاء في السجل:"
    tail -n 3 storage/logs/laravel.log
else
    echo "✅ لا يوجد سجل أخطاء"
fi

echo ""
echo "🎉 انتهى الفحص!"
echo "📋 للمتابعة:"
echo "1. اختبر إنشاء فاتورة من المتصفح"
echo "2. اختبر قائمة الدخل"
echo "3. راقب سجل الأخطاء: tail -f storage/logs/laravel.log"
