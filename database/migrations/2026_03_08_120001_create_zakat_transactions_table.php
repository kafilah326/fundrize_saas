<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakat_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('zakat_type', ['fitrah', 'maal']);
            $table->unsignedInteger('jumlah_jiwa')->nullable();  // fitrah only
            $table->decimal('total_harta', 20, 2)->nullable();   // maal only
            $table->decimal('nisab_at_time', 20, 2)->nullable(); // nisab at time of transaction
            $table->decimal('calculated_zakat', 20, 2)->nullable();
            $table->decimal('amount', 20, 2);
            $table->decimal('admin_fee', 20, 2)->default(0);
            $table->decimal('total', 20, 2);
            $table->string('donor_name');
            $table->string('donor_phone')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->timestamp('payment_expiry')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_transactions');
    }
};
