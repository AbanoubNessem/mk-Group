# حل مشكلة حجم رفع الملفات الكبير

## المشكلة:
خطأ: `PostTooLargeException - The POST data is too large`

## الحل:

### 1. تحديث إعدادات PHP في XAMPP:

افتح ملف `php.ini` في XAMPP:
- المسار: `C:\xampp\php\php.ini`

ابحث عن هذه الأسطر وعدلها:

```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300
memory_limit = 512M
```

**ملاحظة مهمة:** `post_max_size` يجب أن يكون أكبر من أو يساوي `upload_max_filesize`

### 2. إعادة تشغيل Apache:

بعد تعديل `php.ini`:
1. افتح XAMPP Control Panel
2. أوقف Apache
3. شغّل Apache مرة أخرى

### 3. التحقق من الإعدادات:

شغّل هذا الأمر للتأكد من التغييرات:

```bash
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

يجب أن ترى:
```
upload_max_filesize: 100M
post_max_size: 100M
```

### 4. إذا لم تعمل التغييرات:

إذا كان XAMPP يستخدم PHP-FPM أو CGI، قد تحتاج إلى:

1. إنشاء ملف `php.ini` في مجلد المشروع
2. أو تحديث إعدادات Apache مباشرة

### 5. الحد الأقصى الحالي في الكود:

- الحد الأقصى للصور في الكود: **50MB** (51200 كيلوبايت)
- تم تحديث `.htaccess` لدعم حتى **100MB**

## ملاحظات:

- إذا كنت ترفع صور متعددة، يجب أن يكون `post_max_size` أكبر من مجموع أحجام جميع الصور
- الحد الأقصى الموصى به للصور على الويب هو 5-10MB للصور العادية
- للصور الكبيرة جداً، فكر في ضغط الصور قبل الرفع



