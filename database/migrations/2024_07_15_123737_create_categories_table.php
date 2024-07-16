<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string("name");
            $table->timestamps();
        });

        Schema::create('category_quiz', function (Blueprint $table) {
            $table->foreignUUid('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreignUuid('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->primary(['category_id', 'quiz_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_quiz');
    }
};
