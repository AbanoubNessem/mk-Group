<?php
/**
 * ملف لتغيير إعدادات قاعدة البيانات من MySQL إلى SQLite
 * قم بتشغيله: php fix_database.php
 */

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "ملف .env غير موجود!\n";
    exit(1);
}

// قراءة محتوى ملف .env
$envContent = file_get_contents($envFile);

// تغيير إعدادات قاعدة البيانات
$envContent = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=sqlite', $envContent);
$envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=' . __DIR__ . '/database/database.sqlite', $envContent);

// حفظ الملف
file_put_contents($envFile, $envContent);

echo "تم تغيير إعدادات قاعدة البيانات إلى SQLite بنجاح!\n";
echo "الآن قم بتشغيل:\n";
echo "1. php artisan migrate\n";
echo "2. php artisan db:seed --class=AdminSeeder\n";



