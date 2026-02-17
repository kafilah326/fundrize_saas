<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qurban_savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('target_animal_type'); // kambing, sapi-1-7, sapi-utuh
            $table->decimal('target_amount', 15, 2);
            $table->decimal('saved_amount', 15, 2)->default(0);
            $table->string('target_hijri_year');
            $table->string('donor_name');
            $table->string('whatsapp');
            $table->string('qurban_name')->nullable();
            $table->boolean('reminder_enabled')->default(false);
            $table->string('reminder_frequency')->default('bulanan');
            $table->string('status')->default('active'); // active, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qurban_savings');
    }
};
