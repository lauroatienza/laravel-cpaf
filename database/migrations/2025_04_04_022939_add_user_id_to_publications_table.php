<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('publications', 'user_id')) {
            Schema::table('publications', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            });
        }

        DB::table('publications')->whereNull('user_id')->update(['user_id' => 1]);

        Schema::table('publications', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            if (Schema::hasColumn('publications', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

