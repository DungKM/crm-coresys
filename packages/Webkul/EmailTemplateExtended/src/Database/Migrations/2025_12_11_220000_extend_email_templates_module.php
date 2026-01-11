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
            // Variables & Metadata
            $table->json('variables')->nullable()->after('content')
                ->comment('Định nghĩa các biến: [{name, type, default, description}]');
            
            $table->json('sample_data')->nullable()->after('variables')
                ->comment('Dữ liệu mẫu để preview');
            
            $table->json('metadata')->nullable()->after('sample_data')
                ->comment('Thông tin bổ sung');
            
            // Category & Tags
            $table->string('category')->default('general')->after('metadata')->index()
                ->comment('sales, marketing, support, etc.');
            
            $table->json('tags')->nullable()->after('category')
                ->comment('Tags để phân loại');
            
            // Usage tracking
            $table->unsignedBigInteger('usage_count')->default(0)->after('tags')->index()
                ->comment('Số lần template được sử dụng');
            
            $table->timestamp('last_used_at')->nullable()->after('usage_count')
                ->comment('Lần cuối sử dụng template');
            
            // Status & Preview
            $table->boolean('is_active')->default(true)->after('last_used_at')->index()
                ->comment('Template có đang hoạt động');
            
            $table->text('preview_text')->nullable()->after('is_active')
                ->comment('Plain text preview');
            
            $table->string('thumbnail')->nullable()->after('preview_text')
                ->comment('URL ảnh thumbnail');
            
            // Multi-language support
            $table->string('locale', 10)->default('vi')->after('thumbnail')->index()
                ->comment('Ngôn ngữ: vi, en');
            
            // Clone support
            $table->unsignedInteger('cloned_from_id')->nullable()->after('locale')
                ->comment('Template gốc được clone từ đâu');
            
            // Owner/Creator
            $table->unsignedInteger('user_id')->nullable()->after('cloned_from_id')
                ->comment('User tạo template');
            
            // Soft Deletes
            $table->softDeletes()->after('updated_at');
            
            // Foreign keys
            $table->foreign('cloned_from_id')
                ->references('id')
                ->on('email_templates')
                ->onDelete('set null');
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            
            // Composite indexes để tối ưu query
            $table->index(['is_active', 'category'], 'idx_active_category');
            $table->index(['locale', 'is_active'], 'idx_locale_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_templates', function (Blueprint $table) {
            // Drop foreign keys trước
            $table->dropForeign(['cloned_from_id']);
            $table->dropForeign(['user_id']);
            
            // Drop indexes
            $table->dropIndex('idx_active_category');
            $table->dropIndex('idx_locale_active');
            $table->dropIndex(['category']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['locale']);
            $table->dropIndex(['usage_count']);
            
            // Drop columns
            $table->dropColumn([
                'variables',
                'sample_data',
                'metadata',
                'category',
                'tags',
                'usage_count',
                'last_used_at',
                'is_active',
                'preview_text',
                'thumbnail',
                'locale',
                'cloned_from_id',
                'user_id',
                'deleted_at',
            ]);
        });
    }
};
