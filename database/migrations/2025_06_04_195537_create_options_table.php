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
        if(!Schema::hasTable('options')){
            Schema::create('options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained()->onDelete('cascade');
                $table->text('option_text');
                $table->boolean('is_correct')->default(false);
                $table->json('metadata')->nullable(); // For additional data like image URLs
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
