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
        Schema::create('email_scheduled', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('email_id');
            $table->timestamp('scheduled_at');
            $table->enum('status', ['pending', 'processing', 'sent', 'cancelled', 'failed'])
                ->default('pending');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedTinyInteger('max_attempts')->default(3);
            $table->timestamp('last_attempt_at')->nullable();
            $table->text('error_message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->foreign('email_id')->references('id')->on('emails')->onDelete('cascade');
            $table->index('email_id');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index(['status', 'scheduled_at']);
            $table->index(['scheduled_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_scheduled');
    }
};
