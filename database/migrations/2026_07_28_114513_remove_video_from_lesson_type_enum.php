<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrasi data lama dulu: apapun yang masih 'video' diubah ke 'reading'
        // SEBELUM enum diperketat, supaya tidak ada baris yang jadi truncated
        // saat constraint baru diterapkan.
        DB::table('lessons')->where('type', 'video')->update(['type' => 'reading']);

        Schema::table('lessons', function (Blueprint $table) {
            $table->enum('type', ['reading', 'quiz'])->default('reading')->change();
        });
    }

    public function down(): void
    {
        // Rollback: kembalikan opsi 'video' ke enum (data yang sudah
        // ter-migrasi ke 'reading' tidak otomatis dikembalikan ke 'video',
        // karena kita tidak tahu mana yang aslinya video vs reading asli).
        Schema::table('lessons', function (Blueprint $table) {
            $table->enum('type', ['video', 'reading', 'quiz'])->default('video')->change();
        });
    }
};