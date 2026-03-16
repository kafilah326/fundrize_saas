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
        if (!Schema::hasColumn('qurban_orders', 'total')) {
            Schema::table('qurban_orders', function (Blueprint $table) {
                $table->decimal('total', 15, 2)->nullable()->after('amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('qurban_orders', 'total')) {
            Schema::table('qurban_orders', function (Blueprint $table) {
                $table->dropColumn('total');
            });
        }
    }
};
