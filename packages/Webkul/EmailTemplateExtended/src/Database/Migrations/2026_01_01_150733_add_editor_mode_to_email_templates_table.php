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
        Schema::table('email_templates', function (Blueprint $table) {
            // Thêm cột editor_mode
            $table->enum('editor_mode', ['classic', 'pro'])
                  ->default('classic')
                  ->after('content')
                  ->comment('Editor mode: classic (TinyMCE) or pro (EmailBuilder.js)');

            // Thêm cột builder_config để lưu JSON từ EmailBuilder.js
            $table->longText('builder_config')
                  ->nullable()
                  ->after('editor_mode')
                  ->comment('JSON config from EmailBuilder.js for pro mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn(['editor_mode', 'builder_config']);
        });
    }
};
