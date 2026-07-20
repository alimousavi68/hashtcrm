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
        Schema::create('brief_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('business_name')->nullable();
            $table->text('business_description')->nullable();
            $table->text('target_audience')->nullable();
            $table->text('competitors')->nullable();
            $table->string('design_style')->nullable();
            $table->string('color_preferences')->nullable();
            $table->json('features_required')->nullable();
            $table->text('extra_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brief_answers');
    }
};
