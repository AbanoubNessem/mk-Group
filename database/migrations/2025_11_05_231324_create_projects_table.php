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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المشروع بالإنجليزية
            $table->string('name_ar'); // اسم المشروع بالعربية
            $table->text('description')->nullable(); // الوصف بالإنجليزية
            $table->text('description_ar')->nullable(); // الوصف بالعربية
            $table->string('thumbnail')->nullable(); // الصورة الأساسية
            $table->json('images')->nullable(); // مجموعة الصور (JSON array)
            $table->string('area')->nullable(); // المساحة
            $table->integer('rooms_count')->nullable(); // عدد الغرف
            $table->string('project_type')->nullable(); // نوع المشروع (Residential, Commercial, etc.)
            $table->string('project_type_ar')->nullable(); // نوع المشروع بالعربية
            $table->string('engineering_function')->nullable(); // نوع الوظيفة الهندسية (Interior Design, Architecture, etc.)
            $table->string('engineering_function_ar')->nullable(); // نوع الوظيفة الهندسية بالعربية
            $table->string('location')->nullable(); // المكان بالإنجليزية
            $table->string('location_ar')->nullable(); // المكان بالعربية
            $table->string('duration')->nullable(); // المدة (مثل: 6 months)
            $table->string('category')->nullable(); // الفئة (مثل: Residential Interior Design)
            $table->string('category_ar')->nullable(); // الفئة بالعربية
            $table->boolean('is_active')->default(true); // حالة المشروع (نشط/غير نشط)
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
