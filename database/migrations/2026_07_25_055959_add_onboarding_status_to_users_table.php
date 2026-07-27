<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // null = belum selesai, terisi = timestamp kapan selesai (sekaligus jadi audit trail)
            $table->timestamp('profile_setup_completed_at')->nullable()->after('career_goal_id');
            $table->timestamp('assessment_completed_at')->nullable()->after('profile_setup_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_setup_completed_at', 'assessment_completed_at']);
        });
    }
};
