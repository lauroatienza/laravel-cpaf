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
        $table->dropColumn('id_no');
    });
}

public function down()
{
    Schema::table('extension', function (Blueprint $table) {
        $table->integer('id_no')->autoIncrement()->primary(); // or appropriate type
    });
}

};
