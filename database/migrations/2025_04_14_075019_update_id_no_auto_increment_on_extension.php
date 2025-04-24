<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('extension', function (Blueprint $table) {
            // Drop the current id_no if it exists (only if it's not auto-incrementing)
            $table->dropColumn('id_no'); 
            // Add id_no as an auto-incrementing column
            $table->increments('id_no'); 
        }); 
    }

    public function down(): void
    {
        Schema::table('extension', function (Blueprint $table) {
            // Drop the auto-increment id_no column and revert back if needed
            $table->dropColumn('id_no');
        });
    }
};
