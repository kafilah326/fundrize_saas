<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_message_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20);
            $table->text('message');
            $table->string('event_type', 30); // payment_created, payment_success, payment_expired, test
            $table->string('status', 10)->default('sent'); // sent, failed
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->json('response_data')->nullable();
            $table->timestamps();

            $table->index('event_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message_logs');
    }
};
