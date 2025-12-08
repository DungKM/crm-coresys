<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facebook_messages', function (Blueprint $table) {
            $table->id();
            $table->string('thread_id');
            $table->string('sender_id')->nullable();
            $table->string('recipient_id')->nullable();
            $table->string('sender_name')->nullable();
            $table->text('message');
            $table->enum('direction', ['inbound', 'outbound'])->default('inbound');
            $table->enum('status', ['received', 'sent', 'read', 'failed'])->default('received');
            $table->timestamp('sent_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('thread_id');
            $table->index(['direction', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_messages');
    }
};
