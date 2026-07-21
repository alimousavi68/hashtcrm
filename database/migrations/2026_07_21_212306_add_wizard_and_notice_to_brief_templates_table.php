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
            $table->boolean('wizard_mode')->default(false)->after('is_active');
            $table->text('guide_notice')->nullable()->after('wizard_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brief_templates', function (Blueprint $table) {
            $table->dropColumn(['wizard_mode', 'guide_notice']);
        });
    }
};
