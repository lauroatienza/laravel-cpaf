<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('organized_trainings', function (Blueprint $table) {
            $table->longText('resource_persons')->change();
        });
    }

    public function down()
    {
        Schema::table('organized_trainings', function (Blueprint $table) {
            $table->text('resource_persons')->change();
        });
    }
};
