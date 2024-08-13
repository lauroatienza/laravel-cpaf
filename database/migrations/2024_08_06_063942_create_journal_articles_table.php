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
        Schema::create('journal_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->nullable();
            $table->string("authors")->nullable();
            $table->string("article_title")->nullable();
            $table->string("journal_name")->nullable();
            $table->date('date_published')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_articles');
    }
};
