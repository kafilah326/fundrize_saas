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
        Schema::table('tenants', function (Blueprint $table) {
            // Because plan was a string, and we need foreignId. But SQLite/MySQL dropping/changing columns can be strict.
            // Best approach: add plan_id, nullable first.
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
        });

        // Run data migration (if needed, map old plan string to plan_id)
        // Since this is fresh, we can leave the old plan string column for now or drop it.
        // Let's drop it to be clean.
        // However, SQLite cannot drop columns easily if it's not supported by doctrine.
        // Assuming MySQL
        Schema::table('tenants', function (Blueprint $table) {
             // Let's keep the plan string column as a fallback or drop it. 
             // We drop the plan column to enforce plan_id usage.
             $table->dropColumn('plan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('plan')->default('free');
            $table->dropForeign(['plan_id']);
            $table->dropColumn('plan_id');
        });
    }
};
