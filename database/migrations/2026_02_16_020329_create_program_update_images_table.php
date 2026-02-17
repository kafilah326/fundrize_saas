<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_update_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_update_id')->constrained()->cascadeOnDelete();
            $table->string('image_url');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_update_images');
    }
};
