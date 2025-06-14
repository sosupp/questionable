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
        if(!Schema::hasTable('exam_sections')){
            Schema::create('exam_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_id')->constrained()->onDelete('cascade');
                $table->string('title');
                $table->string('slug')->unique()->nullable();
                $table->text('description')->nullable();
                $table->integer('time_limit')->nullable();
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
        Schema::dropIfExists('exam_sections');
    }
};
