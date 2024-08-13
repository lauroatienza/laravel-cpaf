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
        Schema::create('chapter_in_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->nullable();
            $table->string("title")->nullable();
            $table->string("co-authors")->nullable();
            $table->date('date_publication')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapter_in_books');
    }
};
