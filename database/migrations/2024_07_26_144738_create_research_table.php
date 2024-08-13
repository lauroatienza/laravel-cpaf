<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('research', function (Blueprint $table) {
            $table->id();

           // $table->foreignId('faculty_id');
          //  $table->foreignId('reps_id')->nullable();

            $table->foreignId('faculty_id');    
            $table->string('title');
            $table->string('contributing_unit');
            $table->date('start_date');
            $table->date('end_date');

            $table->date('extension_date')->nullable();
            $table->string('event_highlight');
            $table->string('has_gender_component');
            $table->string('status');

            $table->string('objectives');
            $table->string('expected_output');
            $table->string('no_months_orig_timeframe');
            $table->string('name_of_researchers');

            $table->string('source_funding');
            $table->string('category_source_funding');
            $table->integer('budget');
            $table->string('type_funding');

            $table->string('pdf_image_1');
            $table->date('completed_date')->nullable();
            $table->string('sdg_theme');
            $table->string('agora_theme');

            $table->string('climate_ccam_initiative')->nullable();
            $table->string('disaster_risk_reduction')->nullable();
            $table->string('flagship_theme')->nullable();
            $table->string('pbms_upload_status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research');
    }
};
