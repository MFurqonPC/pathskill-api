<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');           // "Apa fungsi display:flex?"
            $table->text('code_snippet')->nullable(); // opsional, buat soal yang nampilin potongan kode
            $table->json('options');                  // ["display:flex membuat layout flexible", "...", ...]
            $table->unsignedTinyInteger('correct_option_index'); // index 0-based ke array `options`
            $table->text('explanation')->nullable();  // ditampilkan setelah user jawab
            $table->boolean('is_warmup')->default(false); // true = dipakai di "Short Verification Task" (step 2, 1 soal), false = dipakai di quiz 10 soal (step 3)
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_quiz_questions');
    }
};
