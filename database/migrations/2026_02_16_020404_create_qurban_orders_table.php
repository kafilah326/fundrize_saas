<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qurban_orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('qurban_animal_id')->constrained()->cascadeOnDelete();
            $table->string('hijri_year');
            $table->string('donor_name');
            $table->string('whatsapp');
            $table->string('email')->nullable();
            $table->string('qurban_name'); // Atas nama qurban
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('slaughter_method'); // wakalah, hadir
            $table->string('delivery_method'); // dikirim, ambil, wakaf
            $table->decimal('amount', 15, 2);
            $table->string('payment_method')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qurban_orders');
    }
};
