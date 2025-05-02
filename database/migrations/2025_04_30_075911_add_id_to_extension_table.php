<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('extension', function (Blueprint $table) {
        $table->id()->first(); // adds auto-incrementing primary key
    });
}

public function down()
{
    Schema::table('extension', function (Blueprint $table) {
        $table->dropColumn('id');
    });
}

};
