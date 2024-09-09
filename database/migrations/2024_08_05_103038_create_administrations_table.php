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
        Schema::create('administrations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->string('designation');
            $table->string('employment_status');
            $table->string('unit');
            $table->string('highest_degree_attained');
          //  $table->string('rating')->nullable();
         //   $table->string('citations')->nullable();
          //  $table->string('with_phd');
          //  $table->string('academic_background');
          //  $table->string('pursuing_phd');
          //  $table->string('pursuing_ms');
          //  $table->string('leave_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrations');
    }
};
