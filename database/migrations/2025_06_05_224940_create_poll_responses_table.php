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
        if(!Schema::hasTable('poll_responses')){
            Schema::create('poll_responses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('poll_id')->constrained()->onDelete('cascade');
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('option_id')->nullable()->constrained('options')->onDelete('cascade');
                $table->text('answer_text')->nullable();
                $table->timestamps();
                
                // Composite index for faster queries
                $table->index(['poll_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll_responses');
    }
};
