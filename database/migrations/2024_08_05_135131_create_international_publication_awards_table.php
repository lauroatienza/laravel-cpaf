<?php

use App\Models\Faculty;
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
        Schema::create('international_publication_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->nullable();
            $table->string("title")->nullable();
            $table->date('date_awarded')->nullable();
            $table->date('date_published')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('international_publication_awards');
    }
};
