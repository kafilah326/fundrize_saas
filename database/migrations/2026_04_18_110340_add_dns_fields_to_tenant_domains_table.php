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
        Schema::table('tenant_domains', function (Blueprint $table) {
            $table->string('dns_target')->nullable(); // Target CNAME, e.g., fundrize.com
            $table->boolean('dns_verified')->default(false); // Apakah sudah diverifikasi
            $table->timestamp('last_checked_at')->nullable(); // Kapan terakhir dicek DNS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_domains', function (Blueprint $table) {
            $table->dropColumn(['dns_target', 'dns_verified', 'last_checked_at']);
        });
    }
};
