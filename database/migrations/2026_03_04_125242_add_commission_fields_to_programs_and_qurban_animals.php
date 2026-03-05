<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->enum('commission_type', ['none', 'fixed', 'percentage'])->default('none')->after('is_urgent');
            $table->decimal('commission_amount', 15, 2)->default(0)->after('commission_type');
        });

        Schema::table('qurban_animals', function (Blueprint $table) {
            $table->enum('commission_type', ['none', 'fixed', 'percentage'])->default('none')->after('is_active');
            $table->decimal('commission_amount', 15, 2)->default(0)->after('commission_type');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['commission_type', 'commission_amount']);
        });

        Schema::table('qurban_animals', function (Blueprint $table) {
            $table->dropColumn(['commission_type', 'commission_amount']);
        });
    }
};
