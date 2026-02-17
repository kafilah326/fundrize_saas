<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount_distributed', 15, 2);
            $table->text('description');
            $table->date('documentation_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_distributions');
    }
};
