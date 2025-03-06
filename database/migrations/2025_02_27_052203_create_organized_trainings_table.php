<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('organized_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('contributing_unit');
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('special_notes')->nullable();
            $table->string('resource_persons')->nullable();
            $table->string('activity_category');
            $table->string('venue');
            $table->integer('total_trainees')->nullable();
            $table->integer('weighted_trainees')->nullable();
            $table->integer('training_hours')->nullable();
            $table->string('funding_source');
            $table->integer('sample_size')->nullable();
            $table->integer('responses_poor')->nullable();
            $table->integer('responses_fair')->nullable();
            $table->integer('responses_satisfactory')->nullable();
            $table->integer('responses_very_satisfactory')->nullable();
            $table->integer('responses_outstanding')->nullable();
            $table->string('related_extension_program')->nullable();
            $table->string('pdf_file_1')->nullable();
            $table->string('pdf_file_2')->nullable();
            $table->string('relevant_documents')->nullable();
            $table->string('project_title')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organized_trainings');
    }
};

