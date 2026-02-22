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
        Schema::create('whatsapp_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type');       // donasi, qurban, tabungan_qurban
            $table->string('event');      // payment_created, payment_success, payment_expired
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['type', 'event', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
