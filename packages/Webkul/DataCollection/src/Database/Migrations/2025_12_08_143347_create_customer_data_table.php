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
            $table->string('name');
            $table->string('email');
            $table->string('phone', 10);
            $table->string('title')->nullable()->comment('Nội dung quan tâm');
            $table->enum('customer_type', ['Lẻ', 'Doanh Nghiệp'])->default('Lẻ');
            $table->string('source')->default('Website Form');
            $table->enum('status', ['pending', 'verified', 'junk'])->default('pending');
            $table->string('verify_token', 64)->nullable();
            $table->unsignedInteger('last_assigned_to')->nullable()->comment('Nhân viên đã xử lý');
            $table->timestamps();

            $table->index(['email', 'phone']);
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
