<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalHoursColumnInTrainingAttendedTable extends Migration
{
    public function up()
    {
        Schema::table('training_attendeds', function (Blueprint $table) {
            $table->decimal('total_hours', 8, 1)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('training_attendeds', function (Blueprint $table) {
            $table->integer('total_hours')->nullable()->change();
        });
    }
}
