<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->enum('role', ['admin', 'user'])->default('user')->after('password');
            $table->enum('theme', ['light', 'dark'])->default('light')->after('role');
            $table->enum('view_mode', ['list', 'grid'])->default('list')->after('theme');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'theme', 'view_mode']);
        });
    }
};