<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Khách hàng
            $table->unsignedInteger('lead_id')->nullable();
            $table->foreign('lead_id')
                ->references('id')
                ->on('leads')
                ->nullOnDelete();

            // Thông tin cơ bản (lấy từ lead)
            $table->string('customer_name');
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_email')->nullable();
            $table->unsignedInteger('source')->nullable(); // lead_source_id

            // Thời điểm khách yêu cầu lịch hẹn
            $table->dateTime('requested_at')->nullable();

            // Thông tin lịch hẹn
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('timezone', 50)->default('Asia/Ho_Chi_Minh');
            $table->integer('duration_minutes')->default(30);

            // Loại và dịch vụ
            $table->enum('meeting_type', ['call', 'onsite', 'online'])->default('call');
            $table->string('service_name')->nullable();
            $table->unsignedInteger('service_id')->nullable();

            // Địa chỉ gặp trực tiếp (onsite)
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('ward')->nullable();
            $table->string('street_address')->nullable();

            // Số điện thoại cho call
            $table->string('call_phone', 20)->nullable();

            // Link meeting (online)
            $table->text('meeting_link')->nullable();

            // Địa điểm cũ (để tương thích)
            $table->unsignedInteger('location_id')->nullable();

            // Trạng thái
            $table->enum('status', [
                'scheduled',
                'confirmed',
                'rescheduled',
                'cancelled',
                'showed',
                'no_show'
            ])->default('scheduled');

            // Người phụ trách
            $table->unsignedInteger('assigned_user_id')->nullable();
            $table->foreign('assigned_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Assignment type
            $table->enum('assignment_type', ['direct', 'routing', 'resource'])->default('direct');

            // Routing / Resource
            $table->string('routing_key')->nullable();
            $table->string('resource_id')->nullable();

            // Organization
            $table->unsignedInteger('organization_id')->nullable();
            $table->foreign('organization_id')
                ->references('id')
                ->on('organizations')
                ->nullOnDelete();

            // Channel
            $table->enum('channel', ['manual', 'web', 'app', 'api'])->default('manual');

            // Lịch sử dời lịch
            $table->dateTime('original_start_at')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->unsignedInteger('rescheduled_by')->nullable();
            $table->timestamp('rescheduled_at')->nullable();

            // Hủy lịch
            $table->text('cancellation_reason')->nullable();
            $table->unsignedInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            // Metadata
            $table->text('note')->nullable();
            $table->string('external_source')->nullable();
            $table->string('external_id')->nullable();
            $table->json('utm_params')->nullable();

            // Audit
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['requested_at']);
            $table->index(['start_at', 'end_at']);
            $table->index('status');
            $table->index('meeting_type');
            $table->index('lead_id');
            $table->index('assigned_user_id');
            $table->index('organization_id');
            $table->index('routing_key');
            $table->index(['external_source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
