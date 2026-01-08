<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_tracking', function (Blueprint $table) {
            $table->string('sg_event_id')->nullable()->unique()->after('event_type');
            $table->timestamp('event_time')->nullable()->after('metadata');
        });
    }

    public function down(): void
    {
        Schema::table('email_tracking', function (Blueprint $table) {
            $table->dropColumn(['sg_event_id', 'event_time']);
        });
    }
};