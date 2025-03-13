<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Make columns nullable
            $table->enum('training_courses', ['Yes', 'No'])->nullable()->change();
            $table->enum('technical_advisory_service', ['Yes', 'No'])->nullable()->change();
            $table->enum('information_dissemination', ['Yes', 'No'])->nullable()->change();
            $table->enum('consultancy', ['Yes', 'No'])->nullable()->change();
            $table->enum('community_outreach', ['Yes', 'No'])->nullable()->change();
            $table->enum('technology_transfer', ['Yes', 'No'])->nullable()->change();
            $table->enum('organizing_events', ['Yes', 'No'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Revert the changes (optional, for rollback)
            $table->enum('training_courses', ['Yes', 'No'])->nullable(false)->change();
            $table->enum('technical_advisory_service', ['Yes', 'No'])->nullable(false)->change();
            $table->enum('information_dissemination', ['Yes', 'No'])->nullable(false)->change();
            $table->enum('consultancy', ['Yes', 'No'])->nullable(false)->change();
            $table->enum('community_outreach', ['Yes', 'No'])->nullable(false)->change();
            $table->enum('technology_transfer', ['Yes', 'No'])->nullable(false)->change();
            $table->enum('organizing_events', ['Yes', 'No'])->nullable(false)->change();
        });
    }
};