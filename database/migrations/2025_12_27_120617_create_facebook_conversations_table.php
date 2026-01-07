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
        Schema::create('facebook_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('psid')->unique();
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('unread')->default(false);
            $table->string('last_snippet')->nullable();
            $table->timestamp('last_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_conversations');
    }
};