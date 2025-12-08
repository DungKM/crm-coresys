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
        // Insert các cấu hình mặc định cho Lead Assignment vào core_config
        DB::table('core_config')->updateOrInsert(
            ['code' => 'lead_assignment.enabled'],
            ['value' => '1', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => 'lead_assignment.method'],
            ['value' => 'round_robin', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => 'lead_assignment.active_users'],
            ['value' => json_encode([]), 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => 'lead_assignment.weights'],
            ['value' => json_encode([]), 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => 'lead_assignment.last_assigned_index'],
            ['value' => '0', 'created_at' => now(), 'updated_at' => now()]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các cấu hình Lead Assignment khi rollback
        DB::table('core_config')->whereIn('code', [
            'lead_assignment.enabled',
            'lead_assignment.method',
            'lead_assignment.active_users',
            'lead_assignment.weights',
            'lead_assignment.last_assigned_index',
        ])->delete();
    }
};
