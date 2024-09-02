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
        Schema::create('training_attendeds', function (Blueprint $table) {
            $table->id();
            $table->string('training_title');
            $table->integer('num_hours');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('faculty_id');  
            $table->string('venue');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_attendeds');
    }
};
