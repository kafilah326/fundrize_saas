<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('fundraiser_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        Schema::table('qurban_orders', function (Blueprint $table) {
            $table->foreignId('fundraiser_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });

        Schema::table('qurban_savings', function (Blueprint $table) {
            $table->foreignId('fundraiser_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['fundraiser_id']);
            $table->dropColumn('fundraiser_id');
        });

        Schema::table('qurban_orders', function (Blueprint $table) {
            $table->dropForeign(['fundraiser_id']);
            $table->dropColumn('fundraiser_id');
        });

        Schema::table('qurban_savings', function (Blueprint $table) {
            $table->dropForeign(['fundraiser_id']);
            $table->dropColumn('fundraiser_id');
        });
    }
};
