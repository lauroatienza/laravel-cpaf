<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNameColumnsInOrganizedTrainingsTable extends Migration
{
    public function up()
    {
        Schema::table('organized_trainings', function (Blueprint $table) {
            // Drop first_name, middle_name, and last_name columns
            $table->dropColumn(['first_name', 'middle_name', 'last_name']);
            
            // Add full_name column
            $table->string('full_name')->nullable(); // You can adjust the column type if needed
        });
    }

    public function down()
    {
        Schema::table('organized_trainings', function (Blueprint $table) {
            // Add back the individual columns
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();

            // Drop the full_name column in case we rollback
            $table->dropColumn('full_name');
        });
    }
}

