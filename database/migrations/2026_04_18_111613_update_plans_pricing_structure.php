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
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['price_monthly', 'price_yearly']);
            $table->bigInteger('price')->default(0)->after('description')->comment('Harga langganan (sekali bayar)');
            $table->decimal('system_fee_percentage', 5, 2)->default(5.00)->after('price')->comment('Persentase potongan transaksi per plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('price_monthly')->default(0);
            $table->integer('price_yearly')->default(0);
            $table->dropColumn(['price', 'system_fee_percentage']);
        });
    }
};
