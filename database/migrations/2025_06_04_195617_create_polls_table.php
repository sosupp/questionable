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
        if(!Schema::hasTable('polls')){
            Schema::create('polls', function (Blueprint $table) {
                $table->id();
                $table->foreignId('year_id')->nullable();
                $table->foreignId('category_id')->nullable();
                $table->string('title');
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_anonymous')->default(false);
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('views')->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
