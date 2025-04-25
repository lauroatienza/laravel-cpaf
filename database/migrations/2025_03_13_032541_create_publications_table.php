<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->text('collection_database');
            $table->enum('web_science', ['YES', 'NO']);
            $table->enum('scopus', ['YES', 'NO']);
            $table->enum('science_direct', ['YES', 'NO']);
            $table->enum('pubmed', ['YES', 'NO']);
            $table->enum('ched_journals', ['YES', 'NO']);
            $table->text('other_reputable_collection')->nullable();
            $table->integer('citations')->default(0);
            $table->string('pdf_proof_1')->nullable();
            $table->string('pdf_proof_2')->nullable();
            $table->enum('received_award', ['YES', 'NO']);
            $table->text('award_title')->nullable();
            $table->date('date_awarded')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
};
