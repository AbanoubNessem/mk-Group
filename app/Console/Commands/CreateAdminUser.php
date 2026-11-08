<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إنشاء حساب مدير جديد';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if admin user already exists
        $admin = User::where('email', 'admin@admin.com')
            ->orWhere('name', 'admin')
            ->first();

        if ($admin) {
            $this->warn('حساب المدير موجود بالفعل!');
            if ($this->confirm('هل تريد تحديث كلمة المرور؟', false)) {
                $admin->password = Hash::make('admin123');
                $admin->save();
                $this->info('تم تحديث كلمة المرور بنجاح!');
            }
            return;
        }

        try {
            User::create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
            ]);
            
            $this->info('تم إنشاء حساب المدير بنجاح!');
            $this->info('اسم المستخدم: admin');
            $this->info('البريد الإلكتروني: admin@admin.com');
            $this->info('كلمة المرور: admin123');
        } catch (\Exception $e) {
            $this->error('حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage());
            $this->info('تأكد من أن قاعدة البيانات متصلة وأن جدول users موجود.');
        }
    }
}



