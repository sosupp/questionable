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
        if(!Schema::hasTable('responses')){
            Schema::create('responses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attempt_id')->constrained()->onDelete('cascade');
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->foreignId('option_id')->nullable()->constrained()->onDelete('cascade');
                $table->text('answer_text')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('points_earned')->default(0);
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
