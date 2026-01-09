<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('cascade');

            // Event info
            $table->enum('event_type', [
                'created',
                'updated',
                'confirmed',
                'rescheduled',
                'cancelled',
                'showed',
                'no_show'
            ]);

            // Status change
            $table->string('old_status')->nullable();
            $table->string('new_status');

            // Changes detail
            $table->json('changes')->nullable();
            $table->text('reason')->nullable();

            // Actor
            $table->unsignedInteger('actor_id')->nullable();
            $table->string('actor_name')->nullable();
            $table->string('actor_type')->default('user'); // user, customer, system

            // Customer info (for quick access)
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();

            $table->timestamps();

            $table->index(['appointment_id', 'created_at']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_status_histories');
    }
};
