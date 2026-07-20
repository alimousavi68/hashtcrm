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
        Schema::create('project_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('host_provider')->nullable();
            $table->string('host_username')->nullable();
            $table->text('host_password')->nullable();
            $table->string('host_panel_url')->nullable();
            $table->string('domain_provider')->nullable();
            $table->string('domain_username')->nullable();
            $table->text('domain_password')->nullable();
            $table->string('domain_panel_url')->nullable();
            $table->string('admin_panel_url')->nullable();
            $table->string('admin_username')->nullable();
            $table->text('admin_password')->nullable();
            $table->text('other_credentials')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_credentials');
    }
};
