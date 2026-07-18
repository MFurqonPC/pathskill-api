<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coding_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('requirements')->nullable();
            // bahasa starter code, buat label editor & syntax hint di frontend (mis. "html", "javascript")
            $table->string('language')->default('html');
            $table->longText('starter_code')->nullable();
            $table->text('hint')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coding_exercises');
    }
};
