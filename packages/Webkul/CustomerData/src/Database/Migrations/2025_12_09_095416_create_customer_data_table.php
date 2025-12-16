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
        Schema::create('customer_data', function (Blueprint $table) {
            $table->id();
            
            // Thông tin khách hàng
            $table->string('name');
            $table->string('email')->index();
            $table->string('phone', 50)->nullable();
            $table->string('source', 100)->nullable()->comment('Website, Facebook, Google Ads, etc.');
            $table->text('title')->nullable()->comment('Nội dung quan tâm');
            $table->enum('customer_type', ['retail', 'business'])->default('retail');
            
            // Trạng thái
            $table->enum('status', ['pending', 'verified', 'spam', 'converted'])->default('pending')->index();
            
            // Verify token
            $table->string('verify_token', 100)->unique()->nullable();
            $table->timestamp('verify_token_expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // Lead liên kết
            $table->unsignedInteger('converted_to_lead_id')->nullable()->index();
            $table->text('spam_reason')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable()->comment('Additional data from forms');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('source');
            $table->index(['status', 'created_at']);
        });
        Schema::table('customer_data', function (Blueprint $table) {
            $table->foreign('converted_to_lead_id')
                  ->references('id')
                  ->on('leads')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_data');
    }
};