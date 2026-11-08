<?php
/**
 * سكريبت لتحديث ملف .env لاستخدام MySQL وقاعدة البيانات mk_group
 * قم بتشغيله: php update_env_to_mysql.php
 */

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "ملف .env غير موجود! سيتم إنشاؤه.\n";
    // إنشاء ملف .env من .env.example إذا كان موجوداً
    if (file_exists(__DIR__ . '/.env.example')) {
        copy(__DIR__ . '/.env.example', $envFile);
    } else {
        echo "خطأ: ملف .env.example غير موجود أيضاً!\n";
        exit(1);
    }
}

// قراءة محتوى ملف .env
$envContent = file_get_contents($envFile);

// تحديث إعدادات قاعدة البيانات
$envContent = preg_replace('/DB_CONNECTION=.*/', 'DB_CONNECTION=mysql', $envContent);
$envContent = preg_replace('/DB_HOST=.*/', 'DB_HOST=127.0.0.1', $envContent);
$envContent = preg_replace('/DB_PORT=.*/', 'DB_PORT=3306', $envContent);
$envContent = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=mk_group', $envContent);
$envContent = preg_replace('/DB_USERNAME=.*/', 'DB_USERNAME=root', $envContent);
$envContent = preg_replace('/DB_PASSWORD=.*/', 'DB_PASSWORD=', $envContent);

// إذا لم تكن الإعدادات موجودة، أضفها
if (strpos($envContent, 'DB_CONNECTION') === false) {
    $envContent .= "\nDB_CONNECTION=mysql\n";
}
if (strpos($envContent, 'DB_HOST') === false) {
    $envContent .= "DB_HOST=127.0.0.1\n";
}
if (strpos($envContent, 'DB_PORT') === false) {
    $envContent .= "DB_PORT=3306\n";
}
if (strpos($envContent, 'DB_DATABASE') === false) {
    $envContent .= "DB_DATABASE=mk_group\n";
}
if (strpos($envContent, 'DB_USERNAME') === false) {
    $envContent .= "DB_USERNAME=root\n";
}
if (strpos($envContent, 'DB_PASSWORD') === false) {
    $envContent .= "DB_PASSWORD=\n";
}

// حفظ الملف
file_put_contents($envFile, $envContent);

echo "تم تحديث ملف .env بنجاح!\n";
echo "إعدادات قاعدة البيانات:\n";
echo "  DB_CONNECTION=mysql\n";
echo "  DB_DATABASE=mk_group\n";
echo "  DB_USERNAME=root\n";
echo "  DB_PASSWORD=\n";
echo "\nالآن قم بتشغيل:\n";
echo "  1. php artisan config:clear\n";
echo "  2. php artisan migrate\n";

