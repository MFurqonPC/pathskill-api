<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_warmup')->default(false);
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->unsignedTinyInteger('tab_switch_count')->default(0);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedTinyInteger('score_percentage')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'career_id', 'is_warmup'], 'user_career_attempt_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_quiz_attempts');
    }
};