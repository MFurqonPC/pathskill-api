<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_assignment_progress', function (Blueprint $table) {
            $table->unsignedTinyInteger('mentor_score')->nullable()->after('status'); // 0-100
            $table->text('mentor_feedback')->nullable()->after('mentor_score');
            $table->timestamp('reviewed_at')->nullable()->after('mentor_feedback');
            $table->foreignId('reviewed_by')->nullable()->after('reviewed_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_assignment_progress', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['mentor_score', 'mentor_feedback', 'reviewed_at']);
        });
    }
};