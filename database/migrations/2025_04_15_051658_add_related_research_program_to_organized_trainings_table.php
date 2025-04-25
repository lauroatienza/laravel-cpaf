<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('organized_trainings', function (Blueprint $table) {
            $table->string('related_research_program')->nullable()->after('related_extension_program');
        });
    }

    public function down(): void
    {
        Schema::table('organized_trainings', function (Blueprint $table) {
            $table->dropColumn('related_research_program');
        });
    }
};
