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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->enum('type', ['feature', 'limit'])->comment('feature unlocks a module, limit increases quota');
            $table->string('target')->comment('e.g. whatsapp, max_programs, max_users, storage_mb');
            $table->integer('value')->default(0)->comment('amount to increase for limits');
            $table->enum('duration', ['one_time', 'monthly'])->default('one_time');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tenant_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('addon_id')->constrained()->onDelete('cascade');
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at')->nullable()->comment('null for lifetime/one_time');
            $table->enum('status', ['active', 'expired', 'canceled'])->default('active');
            $table->integer('amount_paid')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_addons');
        Schema::dropIfExists('addons');
    }
};
