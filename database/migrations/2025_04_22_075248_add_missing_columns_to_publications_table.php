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
    Schema::table('publications', function (Blueprint $table) {
        //$table->string('contributing_unit')->nullable();
        //$table->string('type_of_publication')->nullable();
        //$table->string('other_type')->nullable();
        //$table->string('title_of_publication')->nullable();
        //$table->text('co_authors')->nullable();

        //$table->text('research_conference_publisher_details')->nullable();
        //$table->text('study_research_project')->nullable();
        //$table->text('journal_book_conference')->nullable();
        //$table->string('publisher_organizer')->nullable();
        //$table->string('type_of_publisher')->nullable();
        //$table->string('location_of_publisher')->nullable();
        $table->text('editors')->nullable();
        $table->string('volume_issue')->nullable();
        $table->date('date_published')->nullable();
        //$table->date('conference_start_date')->nullable();
        //$table->date('conference_end_date')->nullable();
        //$table->text('conference_venue')->nullable();
        //$table->string('doi_or_link')->nullable();
        $table->string('isbn_issn')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('publications');
}

};
