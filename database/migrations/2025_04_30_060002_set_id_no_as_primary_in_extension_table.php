<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `extension` DROP PRIMARY KEY, ADD PRIMARY KEY (`id_no`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally reverse it by setting the old 'id' column back as primary
        DB::statement('ALTER TABLE `extension` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`)');
    }
};
