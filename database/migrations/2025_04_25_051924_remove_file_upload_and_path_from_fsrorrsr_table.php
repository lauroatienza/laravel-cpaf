<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('f_s_ror_r_s_r_s', function (Blueprint $table) {
            $table->dropColumn(['file_upload', 'file_path']);
        });
    }

    public function down(): void
    {
        Schema::table('f_s_ror_r_s_r_s', function (Blueprint $table) {
            $table->string('file_upload')->nullable();
            $table->string('file_path')->nullable();
        });
    }
};
