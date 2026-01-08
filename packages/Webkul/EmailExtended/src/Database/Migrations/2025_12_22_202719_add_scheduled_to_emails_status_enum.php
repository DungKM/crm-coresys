<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Thêm 'scheduled' vào ENUM status
        DB::statement("ALTER TABLE emails MODIFY COLUMN status ENUM('draft','queued','scheduled','sent','failed','bounced','delivered') NOT NULL DEFAULT 'draft'");
    }

    public function down()
    {
        // Rollback: xóa 'scheduled' khỏi ENUM
        DB::statement("ALTER TABLE emails MODIFY COLUMN status ENUM('draft','queued','sent','failed','bounced','delivered') NOT NULL DEFAULT 'draft'");
    }
};
