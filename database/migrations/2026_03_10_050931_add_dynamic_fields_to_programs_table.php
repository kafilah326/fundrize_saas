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
        Schema::table('programs', function (Blueprint $table) {
            $table->boolean('is_dynamic')->default(false)->after('description');
            $table->decimal('package_price', 15, 2)->nullable()->after('is_dynamic');
            $table->string('package_label')->nullable()->after('package_price');
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->integer('package_quantity')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('package_quantity');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['is_dynamic', 'package_price', 'package_label']);
        });
    }
};
