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
        if(!Schema::hasTable('years')){
            Schema::create('years', function (Blueprint $table) {
                $table->id();
                $table->year('name');
                $table->string('label')->nullable(); // e.g., "2023", "2023-2024"
                $table->integer('start_year')->nullable();
                $table->integer('end_year')->nullable();
                $table->boolean('is_current')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('years');
    }
};
