<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_coding_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coding_exercise_id')->constrained()->cascadeOnDelete();
            // cuma nyimpen source code buat direview manual — belum ada compile/run/auto grading
            $table->longText('source_code');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // 1 user cuma punya 1 submission aktif per exercise; submit ulang = update (bukan insert baru)
            $table->unique(['user_id', 'coding_exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_coding_submissions');
    }
};
