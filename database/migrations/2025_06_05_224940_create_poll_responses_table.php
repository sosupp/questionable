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

                $table->morphs('ownable'); // user, guest, visitor, etc.
                
                $table->foreignId('option_id')->nullable()->constrained('options')->onDelete('cascade');
                $table->text('answer_text')->nullable();
                $table->timestamps();
                
                // Composite index for faster queries
                $table->index(['poll_id', 'ownable_id', 'ownable_type']);
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
