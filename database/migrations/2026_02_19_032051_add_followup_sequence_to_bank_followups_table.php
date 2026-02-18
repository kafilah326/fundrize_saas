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
        Schema::table('bank_followups', function (Blueprint $table) {
            $table->string('followup_sequence')->nullable()->after('type'); // FollowUp1, FollowUp2, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_followups', function (Blueprint $table) {
            $table->dropColumn('followup_sequence');
        });
    }
};
