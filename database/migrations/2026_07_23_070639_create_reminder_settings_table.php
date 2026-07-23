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
        Schema::create('reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->string('event_type')->unique(); // e.g. pending_brief, pending_contract, pending_payment, pending_feedback
            $table->integer('delay_hours')->default(48); // Wait 48h before sending reminder
            $table->integer('max_reminders')->default(3); // Send max 3 times
            $table->json('channels')->nullable(); // e.g. ["sms", "database"]
            $table->boolean('is_active')->default(true);
            $table->text('message_template')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_settings');
    }
};
