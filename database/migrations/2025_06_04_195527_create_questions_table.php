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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_type_id')->constrained();
            $table->text('question_text');
            $table->json('metadata')->nullable(); // For additional configurations
            $table->integer('points')->default(1);
            $table->boolean('is_active')->default(true);
            $table->foreignId('subject_id')->nullable();
            $table->foreignId('academic_level_id')->nullable();
            $table->foreignId('year_id')->nullable();
            $table->integer('difficulty_level')->nullable()->comment('1-5 scale');
            $table->string('topic')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
