<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique();
            
            $table->text('sendgrid_api_key')->nullable();
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            $table->string('gmail_address')->nullable();
            $table->text('gmail_app_password')->nullable();
            $table->text('signature')->nullable();
            $table->json('merge_tags')->nullable();
            
            $table->boolean('sendgrid_verified')->default(false);
            $table->boolean('gmail_verified')->default(false);
            $table->timestamp('sendgrid_verified_at')->nullable();
            $table->timestamp('gmail_verified_at')->nullable();
            $table->boolean('is_active')->default(false);
            
            $table->integer('emails_sent_count')->default(0);
            $table->timestamp('last_email_sent_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
