<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foundation_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->text('about')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable(); // JSON array
            $table->text('focus_areas')->nullable(); // JSON array
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('social_media')->nullable(); // JSON array
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foundation_settings');
    }
};
