<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mini_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            // ringkasan project brief, ditampilkan di atas Objectives
            $table->text('brief')->nullable();
            $table->json('objectives')->nullable();
            $table->json('acceptance_criteria')->nullable();
            $table->json('deliverables')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mini_projects');
    }
};
