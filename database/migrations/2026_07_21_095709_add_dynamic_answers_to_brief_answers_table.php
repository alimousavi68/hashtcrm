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
        Schema::table('brief_answers', function (Blueprint $table) {
            $table->json('dynamic_answers')->nullable()->after('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brief_answers', function (Blueprint $table) {
            $table->dropColumn('dynamic_answers');
        });
    }
};
