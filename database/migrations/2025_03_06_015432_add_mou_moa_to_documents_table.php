<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('contributing_unit');
            $table->string('partnership_type');
            $table->string('extension_title');
            $table->string('partner_stakeholder');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('training_courses', ['Yes', 'No'])->nullable(); 
            $table->enum('technical_advisory_service', ['Yes', 'No'])->nullable(); 
            $table->enum('information_dissemination', ['Yes', 'No'])->nullable(); 
            $table->enum('consultancy', ['Yes', 'No'])->nullable(); 
            $table->enum('community_outreach', ['Yes', 'No'])->nullable(); 
            $table->enum('technology_transfer', ['Yes', 'No'])->nullable(); 
            $table->enum('organizing_events', ['Yes', 'No'])->nullable(); 
            $table->text('scope_of_work')->nullable();
            $table->string('pdf_file_url')->nullable(); // This is deleted in database using the drop down migration file
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};