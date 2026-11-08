-- إنشاء حساب مدير في قاعدة البيانات
-- اسم المستخدم: admin
-- البريد الإلكتروني: admin@admin.com
-- كلمة المرور: admin123

-- تأكد من أن جدول users موجود أولاً
-- إذا كان الجدول موجود، قم بتشغيل هذا الاستعلام:

INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`) 
VALUES ('admin', 'admin@admin.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    `password` = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    `updated_at` = NOW();

-- ملاحظة: كلمة المرور المشفرة أعلاه هي لـ "admin123"
-- إذا لم يعمل، يمكنك استخدام Laravel لتشفير كلمة المرور:
-- php artisan tinker
-- ثم: Hash::make('admin123')



