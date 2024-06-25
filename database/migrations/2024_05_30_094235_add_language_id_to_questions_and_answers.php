<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            Schema::table('questions', function (Blueprint $table) {
                $table->uuid('language_id')->nullable();

                $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            });

            Schema::table('answers', function (Blueprint $table) {
                $table->uuid('language_id')->nullable();

                $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropForeign(['language_id']);
                $table->dropColumn('language_id');
            });

            Schema::table('answers', function (Blueprint $table) {
                $table->dropForeign(['language_id']);
                $table->dropColumn('language_id');
            });
        });
    }
};
