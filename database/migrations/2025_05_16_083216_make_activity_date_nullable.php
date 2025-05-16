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
    Schema::table('extensionnew', function (Blueprint $table) {
        $table->date('activity_date')->nullable()->change();
    });
}

public function down()
{
    Schema::table('extensionnew', function (Blueprint $table) {
        $table->date('activity_date')->nullable(false)->change();
    });
}

};
