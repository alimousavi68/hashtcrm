<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('brief_templates', function (Blueprint $table) {
            $table->unsignedInteger('views_count')->default(0)->after('is_active');
            $table->unsignedInteger('responses_count')->default(0)->after('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brief_templates', function (Blueprint $table) {
            $table->dropColumn(['views_count', 'responses_count']);
        });
    }
};
