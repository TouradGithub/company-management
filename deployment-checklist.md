# 🚀 قائمة فحص النشر على السيرفر

## ✅ **قبل الرفع - التحقق المحلي:**

### 1. فحص الأخطاء والكود:
```bash
# مسح جميع أنواع التخزين المؤقت
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# فحص أخطاء PHP
php -l app/Http/Controllers/Accounting/IncomeStatementController.php
php -l app/Helpers/AccountTransactionHelper.php
php -l app/Jobs/CreateJournalEntryFromInvoiceJob.php

# تشغيل الاختبارات
php artisan test
```

### 2. فحص قاعدة البيانات:
```bash
# التحقق من الاتصال
php artisan migrate:status

# فحص البيانات الأساسية
php artisan tinker
```

### 3. فحص الملفات المعدلة:
- ✅ `app/Http/Controllers/Accounting/IncomeStatementController.php`
- ✅ `app/Helpers/AccountTransactionHelper.php` 
- ✅ `app/Jobs/CreateJournalEntryFromInvoiceJob.php`
- ✅ `resources/views/financialaccounting/invoices/` (جميع قوالب الفواتير)
- ✅ `resources/views/financialaccounting/script.blade.php`

---

## 🌐 **بعد الرفع - فحص السيرفر:**

### 1. فحص الملفات:
```bash
# التأكد من رفع الملفات
ls -la app/Http/Controllers/Accounting/IncomeStatementController.php
ls -la app/Helpers/AccountTransactionHelper.php

# فحص الصلاحيات
chmod 755 storage/ -R
chmod 755 bootstrap/cache/ -R
```

### 2. فحص التكوين:
```bash
# مسح التخزين المؤقت على السيرفر
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# إعادة تحميل التكوينات
php artisan config:cache
php artisan route:cache
```

### 3. فحص قاعدة البيانات:
```bash
# التحقق من الاتصال
php artisan migrate:status

# فحص الجداول المطلوبة
mysql -u[username] -p[password] [database_name] -e "SHOW TABLES LIKE '%journal%';"
mysql -u[username] -p[password] [database_name] -e "DESCRIBE payrolls;"
```

### 4. اختبار الوظائف الأساسية:
```bash
# اختبار إنشاء فاتورة
php artisan tinker
# $invoice = new App\Models\Invoice();
# // اختبار إنشاء فاتورة بسيطة
```

---

## 🔍 **اختبارات المتصفح:**

### 1. اختبار الفواتير:
- [ ] إنشاء فاتورة مبيعات جديدة
- [ ] التحقق من ظهور Spinner عند الحفظ
- [ ] التأكد من تحديث أرصدة الحسابات
- [ ] فحص القيود المحاسبية (مدين = دائن)

### 2. اختبار قائمة الدخل:
- [ ] فتح صفحة قائمة الدخل
- [ ] الضغط على "عرض التقرير"
- [ ] التأكد من عدم ظهور أخطاء
- [ ] فحص دقة البيانات المعروضة

### 3. اختبار عام:
- [ ] تسجيل الدخول
- [ ] التنقل بين الصفحات
- [ ] فحص سجل الأخطاء: `tail -f storage/logs/laravel.log`

---

## 🚨 **في حالة وجود مشاكل:**

### مشاكل شائعة وحلولها:

1. **خطأ 500 Internal Server Error:**
```bash
# فحص سجل الأخطاء
tail -f storage/logs/laravel.log
# فحص أخطاء الخادم
tail -f /var/log/apache2/error.log  # أو nginx
```

2. **مشاكل الصلاحيات:**
```bash
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
```

3. **مشاكل قاعدة البيانات:**
```bash
# فحص الاتصال
php artisan migrate:status
# إعادة تشغيل المهاجرات إذا لزم الأمر
php artisan migrate:fresh --seed
```

4. **مشاكل التخزين المؤقت:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## 📝 **ملاحظات مهمة:**

1. **احتفظ بنسخة احتياطية** من قاعدة البيانات قبل الرفع
2. **اختبر في بيئة تطوير** أولاً إذا كان متاحاً
3. **راقب سجل الأخطاء** بعد الرفع مباشرة
4. **اختبر جميع الوظائف الأساسية** بعد النشر

---

## ✅ **قائمة التحقق النهائية:**
- [ ] رفع جميع الملفات المعدلة
- [ ] مسح التخزين المؤقت على السيرفر
- [ ] اختبار إنشاء فاتورة
- [ ] اختبار قائمة الدخل
- [ ] فحص سجل الأخطاء
- [ ] اختبار التصميم الجديد
- [ ] التأكد من عمل Spinner

**🎉 عند اكتمال جميع النقاط أعلاه، يكون النظام جاهزاً للاستخدام!**
