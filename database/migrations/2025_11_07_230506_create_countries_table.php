<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الدولة بالإنجليزية
            $table->string('name_ar'); // اسم الدولة بالعربية
            $table->string('flag_code')->nullable(); // رمز العلم (مثل: eg, us, ae, sa)
            $table->string('flag_emoji')->nullable(); // إيموجي العلم (🇪🇬, 🇺🇸, 🇦🇪, 🇸🇦)
            $table->boolean('is_active')->default(true); // حالة الدولة (نشط/غير نشط)
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
