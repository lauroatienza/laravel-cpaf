<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('guard_name')->default('web')->after('name')->change();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('guard_name')->default('web')->after('name')->change();
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('guard_name')->nullable()->change(); // Rollback by allowing NULL again
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('guard_name')->nullable()->change();
        });
    }
};
