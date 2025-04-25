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
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes(); // 👈 This removes the deleted_at column
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // 👈 This restores the deleted_at column if needed
        });
    }
};
