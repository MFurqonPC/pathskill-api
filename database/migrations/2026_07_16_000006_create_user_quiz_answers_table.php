<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('verification_quiz_question_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('selected_option_index');
            $table->boolean('is_correct');
            $table->timestamps();

            $table->unique(['user_id', 'verification_quiz_question_id'], 'user_quiz_answer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_quiz_answers');
    }
};
