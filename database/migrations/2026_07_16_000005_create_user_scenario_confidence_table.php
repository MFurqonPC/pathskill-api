<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_scenario_confidence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scenario_confidence_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('confidence_level'); // 1 (Low) - 5 (High), slider di-map ke sini
            $table->timestamps();

            $table->unique(['user_id', 'scenario_confidence_item_id'], 'user_scenario_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_scenario_confidence');
    }
};
