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
        Schema::create('bank_followups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('content');
            $table->enum('type', ['donasi', 'qurban', 'tabungan_qurban'])->default('donasi');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_followups');
    }
};
