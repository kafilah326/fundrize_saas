<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qurban_savings_deposits', function (Blueprint $table) {
            $table->decimal('total', 15, 2)->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('qurban_savings_deposits', function (Blueprint $table) {
            $table->dropColumn('total');
        });
    }
};
