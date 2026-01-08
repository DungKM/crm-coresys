<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_email_logs', function (Blueprint $table) {
            $table->id();

            // Reference to appointment
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('cascade');

            // Email details
            $table->enum('email_type', [
                'created',
                'updated',
                'cancelled',
                'confirmed',
                'rescheduled',
                'reminder'
            ]);
            $table->string('recipient_email');
            $table->string('recipient_type')->default('customer'); // customer, assigned_user

            // Token for email actions
            $table->string('token', 64)->unique();
            $table->timestamp('token_expires_at')->nullable();

            // Tracking
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('action_taken_at')->nullable(); // confirmed/cancelled via email
            $table->string('action_taken')->nullable(); // 'confirmed', 'cancelled', 'rescheduled'

            // Reminder specific
            $table->integer('hours_before')->nullable(); // For reminder emails

            // Metadata
            $table->json('metadata')->nullable(); // Store additional info

            $table->timestamps();

            // Indexes
            $table->index('appointment_id');
            $table->index('token');
            $table->index('email_type');
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_email_logs');
    }
};
