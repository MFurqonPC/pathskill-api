<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coding_exercises', function (Blueprint $table) {
            // daftar poin buat self-check user sebelum submit — BUKAN hasil
            // eksekusi otomatis, karena belum ada compile/run engine.
            // Ditampilkan sebagai checklist manual di frontend ("Self Validation").
            $table->json('test_cases')->nullable()->after('requirements');
        });
    }

    public function down(): void
    {
        Schema::table('coding_exercises', function (Blueprint $table) {
            $table->dropColumn('test_cases');
        });
    }
};
