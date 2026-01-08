<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Thêm 'scheduled' vào ENUM folder
        DB::statement("ALTER TABLE email_threads MODIFY COLUMN folder ENUM('inbox','sent','draft','scheduled','archive','trash','spam') NOT NULL DEFAULT 'inbox'");
    }

    public function down()
    {
        // Rollback: xóa 'scheduled' khỏi ENUM
        DB::statement("ALTER TABLE email_threads MODIFY COLUMN folder ENUM('inbox','sent','draft','archive','trash','spam') NOT NULL DEFAULT 'inbox'");
    }
};
