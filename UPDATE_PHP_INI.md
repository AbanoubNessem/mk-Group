# تحديث إعدادات PHP في XAMPP - حل مشكلة رفع الملفات الكبيرة

## المشكلة الحالية:
- `post_max_size` = 40MB
- `upload_max_filesize` = 40MB
- الكود يسمح بصور حتى 30MB (تم التحديث)

## الحل الدائم (موصى به):

### 1. تحديث php.ini في XAMPP:

**الخطوات:**
1. افتح ملف `php.ini`:
   - المسار: `C:\xampp\php\php.ini`
   - أو من XAMPP Control Panel → Config → PHP → php.ini

2. ابحث عن هذه الأسطر (اضغط Ctrl+F):
   ```ini
   upload_max_filesize
   post_max_size
   max_execution_time
   max_input_time
   memory_limit
   ```

3. غيّر القيم إلى:
   ```ini
   upload_max_filesize = 100M
   post_max_size = 100M
   max_execution_time = 300
   max_input_time = 300
   memory_limit = 512M
   ```

4. احفظ الملف

5. أعد تشغيل Apache:
   - افتح XAMPP Control Panel
   - اضغط Stop بجانب Apache
   - اضغط Start بجانب Apache

### 2. التحقق من التغييرات:

شغّل هذا الأمر في Terminal:
```bash
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL; echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

يجب أن ترى:
```
upload_max_filesize: 100M
post_max_size: 100M
```

### 3. بعد التحديث، يمكنك زيادة الحد الأقصى في الكود:

إذا أردت رفع صور أكبر من 30MB، يمكنك تحديث الكود في `DashboardController.php`:
- غيّر `max:30720` إلى `max:51200` (50MB) أو أكثر

## الحلول المؤقتة المطبقة:

✅ تم إنشاء ملف `.user.ini` في مجلد `public/`
✅ تم تقليل الحد الأقصى في الكود إلى 30MB

## ملاحظات مهمة:

1. **post_max_size يجب أن يكون أكبر من upload_max_filesize:**
   - إذا كان `upload_max_filesize = 50M`
   - يجب أن يكون `post_max_size = 60M` على الأقل (لترك مساحة للبيانات الأخرى)

2. **عند رفع صور متعددة:**
   - مجموع جميع الصور يجب أن يكون أقل من `post_max_size`
   - مثال: إذا كان `post_max_size = 100M`، يمكنك رفع 3 صور × 30MB = 90MB

3. **إذا لم تعمل التغييرات:**
   - تأكد من إعادة تشغيل Apache
   - تأكد من أنك عدلت الملف الصحيح (php.ini)
   - تحقق من أن PHP يقرأ الملف الصحيح: `php -i | findstr "Loaded Configuration File"`

## الملفات المحدثة:

- ✅ `public/.htaccess` - إعدادات Apache
- ✅ `public/.user.ini` - إعدادات PHP-FPM/CGI
- ✅ `app/Http/Controllers/DashboardController.php` - تقليل الحد الأقصى إلى 30MB



