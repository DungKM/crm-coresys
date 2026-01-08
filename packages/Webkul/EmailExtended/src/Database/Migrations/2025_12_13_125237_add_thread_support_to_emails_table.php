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
        Schema::table('emails', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id')->nullable()->after('id')->index();
            $table->string('in_reply_to', 255)->nullable()->after('message_id')->index();
            $table->enum('direction', ['inbound', 'outbound'])->default('outbound')->after('in_reply_to')->index();
            $table->enum('status', ['draft', 'queued', 'sent', 'failed', 'bounced', 'delivered'])
                ->default('draft')->after('direction')->index();
            $table->timestamp('scheduled_at')->nullable()->after('status')->index();
            $table->timestamp('sent_at')->nullable()->after('scheduled_at')->index();
            $table->timestamp('opened_at')->nullable()->after('sent_at');
            $table->timestamp('clicked_at')->nullable()->after('opened_at');
            $table->timestamp('bounced_at')->nullable()->after('clicked_at');
            $table->unsignedInteger('reply_to_email_id')->nullable()->after('bounced_at');
            $table->unsignedInteger('forward_from_email_id')->nullable()->after('reply_to_email_id');
            $table->unsignedBigInteger('template_id')->nullable()->after('forward_from_email_id')->index();
            $table->longText('rendered_content')->nullable()->after('reply');
            $table->json('tracking_metadata')->nullable()->after('rendered_content');
            $table->json('send_metadata')->nullable()->after('tracking_metadata');
            $table->index(['lead_id', 'direction'], 'emails_lead_direction_index');
            $table->index(['person_id', 'direction'], 'emails_person_direction_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('emails_thread_id_index');
            $table->dropIndex('emails_in_reply_to_index');
            $table->dropIndex('emails_direction_index');
            $table->dropIndex('emails_status_index');
            $table->dropIndex('emails_scheduled_at_index');
            $table->dropIndex('emails_sent_at_index');
            $table->dropIndex('emails_lead_direction_index');
            $table->dropIndex('emails_person_direction_index');
            
            // Drop columns
            $table->dropColumn([
                'thread_id',
                'in_reply_to',
                'direction',
                'status',
                'scheduled_at',
                'sent_at',
                'opened_at',
                'clicked_at',
                'bounced_at',
                'reply_to_email_id',
                'forward_from_email_id',
                'template_id',
                'rendered_content',
                'tracking_metadata',
                'send_metadata',
            ]);
        });
    }
};