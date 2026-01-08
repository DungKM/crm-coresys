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
        Schema::table('email_settings', function (Blueprint $table) {
            // Webhook enabled toggle
            $table->boolean('webhook_enabled')->default(false)->after('signature');
            
            // Signing key từ SendGrid (optional, để verify signature)
            $table->string('webhook_signing_key', 500)->nullable()->after('webhook_enabled');
            
            // Các events muốn track (JSON array)
            // VD: ["delivered", "open", "click", "bounce"]
            $table->json('webhook_events')->nullable()->after('webhook_signing_key');
            
            // Thời điểm verify webhook thành công
            $table->timestamp('webhook_verified_at')->nullable()->after('webhook_events');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_settings', function (Blueprint $table) {
            $table->dropColumn([
                'webhook_enabled',
                'webhook_signing_key',
                'webhook_events',
                'webhook_verified_at',
            ]);
        });
    }
};
