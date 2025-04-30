<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('new_appointments', function (Blueprint $table) {
            $table->id();   
            $table->string('type_of_appointments');  
            $table->string('position');              
            $table->string('appointment');          
            $table->date('appointment_effectivity_date'); 
            $table->string('photo_url')->nullable();  
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_appointments');
    }
};
