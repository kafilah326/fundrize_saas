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
        Schema::create('saas_transactions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->unsignedBigInteger('tenant_id')->nullable();
            $blueprint->string('external_id')->unique();
            $blueprint->string('reference')->nullable();
            $blueprint->string('type'); // registration, maintenance
            $blueprint->decimal('amount', 15, 2);
            $blueprint->string('status')->default('pending'); // pending, paid, expired, failed
            $blueprint->string('payment_method')->nullable();
            $blueprint->json('metadata')->nullable();
            $blueprint->timestamp('paid_at')->nullable();
            $blueprint->timestamps();

            $blueprint->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas_transactions');
    }
};
