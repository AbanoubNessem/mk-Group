<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$admin = User::where('email', 'admin@admin.com')->orWhere('name', 'admin')->first();

if ($admin) {
    $admin->password = Hash::make('123456');
    $admin->save();
    echo "تم تحديث كلمة المرور للمستخدم الموجود\n";
} else {
    User::create([
        'name' => 'admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('123456'),
    ]);
    echo "تم إنشاء المستخدم admin بنجاح\n";
}

echo "\nبيانات تسجيل الدخول:\n";
echo "اسم المستخدم: admin\n";
echo "البريد الإلكتروني: admin@admin.com\n";
echo "كلمة المرور: 123456\n";

