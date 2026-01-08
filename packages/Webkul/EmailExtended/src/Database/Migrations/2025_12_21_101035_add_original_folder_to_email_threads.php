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
        Schema::table('email_threads', function (Blueprint $table) {
            $table->string('original_folder', 20)->nullable()->after('folder') 
                ->comment('Lưu trữ thư mục gốc của email khi di chuyển đến thư mục khác');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_threads', function (Blueprint $table) {
            $table->dropColumn('original_folder');
        });
    }
};
