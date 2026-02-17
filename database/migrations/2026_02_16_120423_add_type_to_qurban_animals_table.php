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
        Schema::table('qurban_animals', function (Blueprint $table) {
            $table->string('type')->default('langsung')->after('id'); // langsung | tabungan
            $table->text('description')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('qurban_animals', function (Blueprint $table) {
            $table->dropColumn(['type', 'description']);
        });
    }
};
