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
    Schema::table('administrations', function (Blueprint $table) {
        if (!Schema::hasColumn('administrations', 'highest_degree_attained')) {
            $table->string('highest_degree_attained')->after('designation');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('administrations', function (Blueprint $table) {
            $table->dropColumn('highest_degree_attained');
        });
    }
};
