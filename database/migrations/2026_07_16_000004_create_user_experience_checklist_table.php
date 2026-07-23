<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_experience_checklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('experience_checklist_item_id')->constrained()->cascadeOnDelete();
            $table->boolean('checked')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'experience_checklist_item_id'], 'user_checklist_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_experience_checklist');
    }
};
