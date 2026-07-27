<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->text('foundation_summary');
            $table->text('priority_areas');
            $table->json('priority_skill_names');
            $table->unsignedSmallInteger('estimated_weeks');
            $table->timestamp('generated_at');
            $table->timestamps();

            // satu user hanya punya 1 rekomendasi aktif per career —
            // kalau perlu regenerate, kita update record ini, bukan insert baru
            $table->unique(['user_id', 'career_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_recommendations');
    }
};