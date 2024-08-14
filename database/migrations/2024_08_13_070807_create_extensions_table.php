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
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('faculty_id');    
            $table->string('title');
            $table->string('contributing_unit');
            $table->date('start_date');
            $table->date('end_date');

            $table->date('extension_date')->nullable();
            $table->string('has_gender_component');
            $table->string('status');

            $table->string('objectives');
            $table->string('expected_output');
            $table->string('no_months_orig_timeframe');
            $table->string('name_of_researchers');

            $table->string('source_funding');
            $table->integer('budget');
            $table->string('type_funding');
            $table->string('source_majority_share_of_funding');


            $table->string('pdf_image_1');
            $table->string('training_courses');
            $table->string('technical_advisory_service');
            $table->string('info_dissemination');
            $table->string('consultancy_external_clients');
            $table->string('community_outreach');
            $table->string('tech_transfer');
            $table->string('organizing_conference_eg');
            $table->string('delivery_units_academic_degree');
            $table->string('target_beneficiary_number');
            $table->string('target_beneficiary_group');
            $table->string('role_of_unit');


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
        Schema::dropIfExists('extensions');
    }
};
