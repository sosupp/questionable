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
        if(!Schema::hasTable('exams')){
            Schema::create('exams', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique()->nullable();
                $table->text('description')->nullable();
                $table->integer('total_time');
                $table->integer('passing_score')->nullable();
                $table->integer('max_attempts')->default(1);
                $table->boolean('shuffle_sections')->default(false);
                $table->boolean('shuffle_questions')->default(false);
                $table->boolean('shuffle_options')->default(false);
                $table->boolean('require_proctoring')->default(false);
                $table->boolean('show_score_after')->default(false);
                $table->boolean('show_answers_after')->default(false);
                $table->timestamp('available_from')->nullable();
                $table->timestamp('available_to')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
