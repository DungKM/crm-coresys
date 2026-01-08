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
        Schema::create('email_threads', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 500);
            $table->string('message_id', 255)->unique(); 
            $table->unsignedInteger('lead_id')->nullable();
            $table->unsignedInteger('person_id')->nullable();
            $table->unsignedInteger('user_id'); 
            $table->timestamp('last_email_at')->nullable();
            $table->unsignedInteger('email_count')->default(0);
            $table->unsignedInteger('unread_count')->default(0);
            $table->boolean('is_read')->default(false);
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_important')->default(false);
            $table->enum('folder', ['inbox', 'sent', 'draft', 'archive', 'trash', 'spam'])
                ->default('inbox');
            $table->json('tags')->nullable();
            $table->json('participants')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('message_id');
            $table->index('lead_id');
            $table->index('person_id');
            $table->index('user_id');
            $table->index('folder');
            $table->index('is_read');
            $table->index('is_starred');
            $table->index('last_email_at');
            $table->index(['user_id', 'folder']);
            $table->index(['user_id', 'is_read']);
        });
        
        Schema::table('emails', function (Blueprint $table) {
            $table->foreign('thread_id')->references('id')->on('email_threads')->onDelete('cascade');
            $table->foreign('reply_to_email_id')->references('id')->on('emails')->onDelete('set null');
            $table->foreign('forward_from_email_id')->references('id')->on('emails')->onDelete('set null');
            $table->foreign('template_id')->references('id')->on('email_templates')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys from emails first
        Schema::table('emails', function (Blueprint $table) {
            $table->dropForeign(['thread_id']);
            $table->dropForeign(['reply_to_email_id']);
            $table->dropForeign(['forward_from_email_id']);
            $table->dropForeign(['template_id']);
        });
        
        Schema::dropIfExists('email_threads');
    }
};
