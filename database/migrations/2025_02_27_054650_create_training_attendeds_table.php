<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingAttendedsTable extends Migration
{
    public function up()
    {
        Schema::create('training_attendeds', function (Blueprint $table) {
            $table->id();
            $table->string('training_title');
            $table->string('full_name');
            $table->enum('unit_center', ['CSPPS', 'CISC', 'IGRD', 'CPAF']);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('category', ['Workshop', 'Training', 'Conference', 'Seminar', 'Forum', 'Symposium', 'Other']);
            $table->string('specific_title')->nullable();
            $table->text('highlights')->nullable();
            $table->boolean('has_gender_component')->default(false);
            $table->integer('total_hours')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
}
