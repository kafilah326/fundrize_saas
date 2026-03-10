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
        Schema::table('zakat_transactions', function (Blueprint $table) {
            $table->foreignId('fundraiser_id')->nullable()->constrained('fundraisers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zakat_transactions', function (Blueprint $table) {
            $table->dropForeign(['fundraiser_id']);
            $table->dropColumn('fundraiser_id');
        });
    }
};
