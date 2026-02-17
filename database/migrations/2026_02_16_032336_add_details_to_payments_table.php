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
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('transaction_type')->nullable()->after('external_id'); // program, qurban_langsung, qurban_tabungan
            $table->integer('unique_code')->nullable()->after('total');
            $table->string('customer_name')->nullable()->after('unique_code');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            
            // Optional references for easier querying
            $table->foreignId('program_id')->nullable()->after('customer_phone')->constrained()->nullOnDelete();
            // Qurban references can be nullable foreignIds if tables exist, or simple IDs
            $table->unsignedBigInteger('qurban_order_id')->nullable()->after('program_id');
            $table->unsignedBigInteger('qurban_saving_id')->nullable()->after('qurban_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'transaction_type', 'unique_code', 'customer_name', 'customer_email', 'customer_phone', 'program_id', 'qurban_order_id', 'qurban_saving_id']);
        });
    }
};
