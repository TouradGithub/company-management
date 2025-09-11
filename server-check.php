<?php

// 🚀 سكريبت فحص النظام بعد النشر
// تشغيل: php server-check.php

echo "🔍 بدء فحص النظام المحاسبي...\n\n";

// 1. فحص ملفات Laravel الأساسية
echo "📁 فحص الملفات الأساسية:\n";
$coreFiles = [
    'app/Http/Controllers/Accounting/IncomeStatementController.php',
    'app/Helpers/AccountTransactionHelper.php',
    'app/Jobs/CreateJournalEntryFromInvoiceJob.php',
    'resources/views/financialaccounting/script.blade.php'
];

foreach ($coreFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file\n";
    } else {
        echo "❌ $file مفقود!\n";
    }
}

echo "\n";

// 2. فحص اتصال قاعدة البيانات
echo "🗄️ فحص قاعدة البيانات:\n";
try {
    $pdo = new PDO(
        "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    echo "✅ الاتصال بقاعدة البيانات يعمل\n";

    // فحص الجداول المهمة
    $tables = ['companies', 'accounts', 'invoices', 'journal_entries', 'payrolls'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "📊 جدول $table: $count سجل\n";
    }

} catch (Exception $e) {
    echo "❌ خطأ في قاعدة البيانات: " . $e->getMessage() . "\n";
}

echo "\n";

// 3. فحص الصلاحيات
echo "🔐 فحص الصلاحيات:\n";
$directories = ['storage/logs', 'storage/app', 'bootstrap/cache'];
foreach ($directories as $dir) {
    if (is_writable($dir)) {
        echo "✅ $dir قابل للكتابة\n";
    } else {
        echo "⚠️ $dir غير قابل للكتابة\n";
    }
}

echo "\n";

// 4. فحص ملف البيئة
echo "⚙️ فحص إعدادات البيئة:\n";
if (file_exists('.env')) {
    echo "✅ ملف .env موجود\n";

    // فحص الإعدادات المهمة
    $requiredEnvs = ['APP_KEY', 'DB_DATABASE', 'DB_USERNAME'];
    foreach ($requiredEnvs as $envVar) {
        if (env($envVar)) {
            echo "✅ $envVar محدد\n";
        } else {
            echo "⚠️ $envVar غير محدد\n";
        }
    }
} else {
    echo "❌ ملف .env مفقود!\n";
}

echo "\n";

// 5. فحص آخر الأخطاء
echo "📝 فحص سجل الأخطاء:\n";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $logs = file($logFile);
    if (count($logs) > 0) {
        echo "آخر 3 سطور من سجل الأخطاء:\n";
        $lastLines = array_slice($logs, -3);
        foreach ($lastLines as $line) {
            echo "📋 " . trim($line) . "\n";
        }
    } else {
        echo "✅ سجل الأخطاء فارغ\n";
    }
} else {
    echo "✅ لا يوجد سجل أخطاء\n";
}

echo "\n🎉 انتهى الفحص!\n\n";

echo "📋 الخطوات التالية:\n";
echo "1. اختبر إنشاء فاتورة من المتصفح\n";
echo "2. اختبر قائمة الدخل\n";
echo "3. تحقق من عمل Spinner عند الحفظ\n";
echo "4. راقب سجل الأخطاء بعد كل عملية\n";
echo "5. اختبر تحديث أرصدة الحسابات\n\n";

echo "🚨 في حالة وجود مشاكل:\n";
echo "- تحقق من صلاحيات المجلدات\n";
echo "- امسح التخزين المؤقت: php artisan cache:clear\n";
echo "- راجع سجل الأخطاء: tail -f storage/logs/laravel.log\n";

?>
