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
        if(!Schema::hasTable('academic_levels')){
            Schema::create('academic_levels', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique(); // e.g., "Primary 1", "Grade 10", "University Level"
                $table->string('slug')->unique()->nullable();
                $table->string('code')->unique()->nullable();
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
        Schema::dropIfExists('academic_levels');
    }
};
