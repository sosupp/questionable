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
        if(!Schema::hasTable('question_metadata')){
            Schema::create('question_metadata', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->string('key');
                $table->text('value')->nullable();
                $table->timestamps();
                
                $table->unique(['question_id', 'key']);
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_metadata');
    }
};
