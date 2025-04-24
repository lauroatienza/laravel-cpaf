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
        Schema::create('new_award_recognition', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('award_type')->nullable();
            $table->string('award_title')->nullable();
            $table->string('name')->nullable();
            $table->string('granting_organization')->nullable();
            $table->string('date_awarded')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_award_recognition');
    }
};
