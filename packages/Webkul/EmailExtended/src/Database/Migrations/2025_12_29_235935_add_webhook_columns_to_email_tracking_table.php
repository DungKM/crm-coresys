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
        Schema::table('email_tracking', function (Blueprint $table) {
            // SendGrid Event ID (để chống duplicate events)
            if (!Schema::hasColumn('email_tracking', 'sg_event_id')) {
                $table->string('sg_event_id', 100)->nullable()->after('email_id');
                $table->index('sg_event_id'); // Index để check duplicate nhanh
            }

            // Processed timestamp
            if (!Schema::hasColumn('email_tracking', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('created_at');
            }

            // Delivered timestamp
            if (!Schema::hasColumn('email_tracking', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('processed_at');
            }

            // Dropped timestamp (khi SendGrid từ chối gửi)
            if (!Schema::hasColumn('email_tracking', 'dropped_at')) {
                $table->timestamp('dropped_at')->nullable()->after('delivered_at');
            }

            // Bounce type (hard/soft bounce)
            if (!Schema::hasColumn('email_tracking', 'bounce_type')) {
                $table->enum('bounce_type', ['hard', 'soft', 'block'])->nullable()->after('dropped_at');
            }

            // Bounce reason (chi tiết lỗi)
            if (!Schema::hasColumn('email_tracking', 'bounce_reason')) {
                $table->text('bounce_reason')->nullable()->after('bounce_type');
            }

            // Status tổng thể của email
            if (!Schema::hasColumn('email_tracking', 'status')) {
                $table->enum('status', [
                    'pending',      // Chưa gửi
                    'processed',    // SendGrid đã nhận
                    'delivered',    // Đã giao thành công
                    'opened',       // Đã mở
                    'clicked',      // Đã click
                    'bounced',      // Bị bounce
                    'dropped',      // Bị drop
                    'spam',         // Bị báo spam
                    'complained'    // Unsubscribe/complaint
                ])->default('pending')->after('bounce_reason');
                $table->index('status'); // Index để query nhanh
            }

            // Metadata JSON (lưu thông tin bổ sung từ webhook)
            if (!Schema::hasColumn('email_tracking', 'metadata')) {
                $table->json('metadata')->nullable()->after('status');
            }

            // Spam reported timestamp
            if (!Schema::hasColumn('email_tracking', 'spam_reported_at')) {
                $table->timestamp('spam_reported_at')->nullable()->after('metadata');
            }

            // Unsubscribed timestamp
            if (!Schema::hasColumn('email_tracking', 'unsubscribed_at')) {
                $table->timestamp('unsubscribed_at')->nullable()->after('spam_reported_at');
            }

            // Bounced timestamp
            if (!Schema::hasColumn('email_tracking', 'bounced_at')) {
                $table->timestamp('bounced_at')->nullable()->after('unsubscribed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_tracking', function (Blueprint $table) {
            // Drop các columns đã thêm
            $columns = [
                'sg_event_id',
                'processed_at',
                'delivered_at',
                'dropped_at',
                'bounce_type',
                'bounce_reason',
                'status',
                'metadata',
                'spam_reported_at',
                'unsubscribed_at',
                'bounced_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('email_tracking', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};