<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $admin = User::where('email', 'admin@admin.com')
            ->orWhere('name', 'admin')
            ->first();

        if (!$admin) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
            ]);
            
            $this->command->info('تم إنشاء حساب المدير بنجاح!');
            $this->command->info('اسم المستخدم: admin');
            $this->command->info('البريد الإلكتروني: admin@admin.com');
            $this->command->info('كلمة المرور: admin123');
        } else {
            $this->command->info('حساب المدير موجود بالفعل!');
        }
    }
}



