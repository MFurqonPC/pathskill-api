<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->longText('explanation')->nullable()->after('order');
            $table->longText('example')->nullable()->after('explanation');
            $table->longText('function_context')->nullable()->after('example');
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['explanation', 'example', 'function_context']);
        });
    }
};