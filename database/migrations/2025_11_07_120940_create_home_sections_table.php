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
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique(); // مثل: hero, about_preview, etc.
            $table->string('title')->nullable();
            $table->string('title_ar')->nullable();
            $table->text('content')->nullable();
            $table->text('content_ar')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('subtitle_ar')->nullable();
            $table->json('images')->nullable(); // مجموعة الصور
            $table->string('background_image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_text_ar')->nullable();
            $table->string('button_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('extra_data')->nullable(); // بيانات إضافية مرنة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
