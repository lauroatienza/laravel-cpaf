<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_attendeds', function (Blueprint $table) {
            $table->text('category')->change();
        });
    }

    public function down(): void
    {
        Schema::table('training_attendeds', function (Blueprint $table) {
            $table->string('category', 255)->change();
        });
    }
};
