<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('zakat_transaction_id')->nullable()->after('qurban_saving_id');
            $table->foreign('zakat_transaction_id')->references('id')->on('zakat_transactions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['zakat_transaction_id']);
            $table->dropColumn('zakat_transaction_id');
        });
    }
};
