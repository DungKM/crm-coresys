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
        Schema::create('instagram_conversation', function (Blueprint $table) {
            $table->id();

            $table->string('ig_business_id');   // page/ig account
            $table->string('ig_user_id');       // sender.id

            $table->string('username')->nullable();
            $table->string('avatar')->nullable();

            $table->boolean('unread')->default(false);
            $table->string('last_snippet')->nullable();
            $table->timestamp('last_time')->nullable();

            $table->unique(['ig_business_id', 'ig_user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_conversation');
    }
};