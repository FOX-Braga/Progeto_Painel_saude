<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('child_vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->foreignId('vaccine_id')->constrained()->onDelete('cascade');
            $table->date('due_date'); // Data limite para aplicação
            $table->date('applied_date')->nullable(); // Data em que foi aplicada
            $table->string('status')->default('pending'); // pending, applied, suspended
            $table->string('lot_number')->nullable();
            $table->string('professional')->nullable();
            $table->text('justification')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_vaccines');
    }
};
