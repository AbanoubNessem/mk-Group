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
        Schema::create('contact_infos', function (Blueprint $table) {
            $table->id();
            $table->string('contact_title')->nullable();
            $table->string('contact_title_ar')->nullable();
            $table->json('phone_numbers')->nullable(); // Array of phone numbers
            $table->string('email')->nullable();
            $table->text('hours_text')->nullable();
            $table->text('hours_text_ar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_infos');
    }
};
