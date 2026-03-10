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
        if (!Schema::hasTable('zakat_distributions')) {
            Schema::create('zakat_distributions', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->decimal('amount', 15, 2);
                $table->text('description');
                $table->date('distribution_date');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_distributions');
    }
};
