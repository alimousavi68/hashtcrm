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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->index();
            $table->string('driver')->default('ippanel');
            $table->string('type')->default('otp'); // otp, notification, pattern
            $table->string('pattern_code')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('sent'); // pending, sent, failed
            $table->string('message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
