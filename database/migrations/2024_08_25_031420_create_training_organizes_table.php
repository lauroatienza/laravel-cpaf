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
        Schema::create('training_organizes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('faculty_id');    
            $table->string('title');
            $table->string('activity_type');
            $table->string('contributing_unit');
            $table->date('start_date');
            $table->date('end_date');

            $table->string('research_id')->nullable();
            $table->string('extension_id')->nullable();
            $table->string('venue');
            $table->string('source_majority_share_of_funding');


            $table->string('pdf_image_1');
            $table->string('pdf_image_2');
            $table->string('link_to_article')->nullable();

            $table->string('sample_size');
            $table->string('overall_rating_poor');
            $table->string('overall_rating_fair');
            $table->string('overall_rating_good');
            $table->string('overall_rating_verygood');
            $table->string('overall_rating_excellent');

            $table->string('total_trainees_number');
            $table->string('no_person_trained_weighted_length_of_training');
            $table->string('no_hrs_required_to_complete');
            $table->string('pbms_upload_status');

            $table->string('remarks');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_organizes');
    }
};
