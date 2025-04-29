<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('new_appointments', function (Blueprint $table) {
            $table->text('new_appointment_file_path')->nullable(); 
        });
    }

    public function down(): void
    {
        Schema::table('new_appointments', function (Blueprint $table) {
            $table->dropColumn('new_appointment_file_path');
        });
    }
};
