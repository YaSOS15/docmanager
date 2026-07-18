<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Génère un UUID pour toutes les lignes déjà existantes
        DB::table('categories')->orderBy('id')->each(function ($category) {
            DB::table('categories')->where('id', $category->id)->update(['uuid' => Str::uuid()]);
        });

        DB::table('documents')->orderBy('id')->each(function ($document) {
            DB::table('documents')->where('id', $document->id)->update(['uuid' => Str::uuid()]);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};