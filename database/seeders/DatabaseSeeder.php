<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as KrayinDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Chạy Krayin seeder trước để tạo dữ liệu cơ bản (pipelines, stages, sources, types, etc.)
        $this->call(KrayinDatabaseSeeder::class);

        // Xóa persons được tạo bởi KrayinDatabaseSeeder (định dạng cũ)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('persons')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Sau đó chạy Demo seeder để tạo dữ liệu mẫu với factory đã chuẩn hóa
        $this->call(DemoDataSeeder::class);
    }
}
