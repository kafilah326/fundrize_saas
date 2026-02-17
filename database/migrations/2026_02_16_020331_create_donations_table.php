<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->decimal('admin_fee', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->string('donor_name')->nullable();
            $table->string('donor_phone')->nullable();
            $table->string('donor_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamp('payment_expiry')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
