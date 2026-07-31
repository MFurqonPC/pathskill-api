<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Simpan HASH-nya saja, bukan plain token — kalau DB bocor,
            // token asli tidak bisa direkonstruksi dari sini.
            $table->string('token_hash', 64)->unique();
            // true kalau user centang "Ingat saya" — dipakai lagi saat
            // token dirotasi, supaya cookie baru punya durasi yang sama.
            $table->boolean('remember')->default(false);
            $table->timestamp('expires_at');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'revoked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};