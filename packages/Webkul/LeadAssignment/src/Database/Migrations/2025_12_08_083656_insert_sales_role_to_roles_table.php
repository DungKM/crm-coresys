<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm role 'Sales' vào bảng roles
        DB::table('roles')->updateOrInsert(
            ['name' => 'Sales'],
            [
                'description' => 'Sales User',
                'permission_type' => 'custom',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa role 'Sales' khi rollback
        DB::table('roles')->where('name', 'Sales')->delete();
    }
};
