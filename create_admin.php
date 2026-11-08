<?php
/**
 * ملف لإضافة مستخدم admin مباشرة
 * قم بتشغيل هذا الملف من المتصفح أو من سطر الأوامر
 * مثال: php create_admin.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // التحقق من وجود المستخدم
    $admin = User::where('email', 'admin@admin.com')
        ->orWhere('name', 'admin')
        ->first();

    if ($admin) {
        echo "حساب المدير موجود بالفعل!\n";
        echo "اسم المستخدم: " . $admin->name . "\n";
        echo "البريد الإلكتروني: " . $admin->email . "\n";
        
        // تحديث كلمة المرور
        $admin->password = Hash::make('admin123');
        $admin->save();
        echo "تم تحديث كلمة المرور بنجاح!\n";
    } else {
        // إنشاء مستخدم جديد
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
        ]);
        
        echo "تم إنشاء حساب المدير بنجاح!\n";
    }
    
    echo "\n";
    echo "========================================\n";
    echo "بيانات تسجيل الدخول:\n";
    echo "========================================\n";
    echo "اسم المستخدم: admin\n";
    echo "البريد الإلكتروني: admin@admin.com\n";
    echo "كلمة المرور: admin123\n";
    echo "========================================\n";
    
} catch (Exception $e) {
    echo "حدث خطأ: " . $e->getMessage() . "\n";
    echo "\n";
    echo "تأكد من:\n";
    echo "1. أن قاعدة البيانات متصلة\n";
    echo "2. أن جدول users موجود (قم بتشغيل migrations)\n";
    echo "3. أن إعدادات قاعدة البيانات في ملف .env صحيحة\n";
}



