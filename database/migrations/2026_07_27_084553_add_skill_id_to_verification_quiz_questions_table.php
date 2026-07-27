<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verification_quiz_questions', function (Blueprint $table) {
            $table->foreignId('skill_id')
                ->nullable()
                ->after('career_id')
                ->constrained('career_skills')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('verification_quiz_questions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('skill_id');
        });
    }
};