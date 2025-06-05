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
        if(!Schema::hasTable('exam_attempt_sections')){
            Schema::create('exam_attempt_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attempt_id')->constrained('exam_attempts')->onDelete('cascade');
                $table->foreignId('exam_section_id')->constrained()->onDelete('cascade');
                $table->timestamp('started_at')->useCurrent();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempt_sections');
    }
};
