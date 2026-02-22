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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('birth_date');
            $table->string('gender')->nullable();

            // Novos campos do Prontuário base
            $table->string('cns')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('contact')->nullable();
            $table->string('address')->nullable();
            $table->string('ethnicity')->nullable();

            $table->date('last_visit_date')->nullable();
            $table->string('nutritional_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
