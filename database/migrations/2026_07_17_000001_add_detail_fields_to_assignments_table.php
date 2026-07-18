<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->json('learning_outcomes')->nullable()->after('description');
            $table->json('skills_learned')->nullable()->after('learning_outcomes');
            $table->json('prerequisites')->nullable()->after('skills_learned');
            $table->json('tools')->nullable()->after('prerequisites');
            // format: [{"criteria": "Struktur HTML", "weight": 20}, ...]
            $table->json('evaluation_rubrics')->nullable()->after('tools');
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['learning_outcomes', 'skills_learned', 'prerequisites', 'tools', 'evaluation_rubrics']);
        });
    }
};