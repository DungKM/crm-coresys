<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Tạo role Sales nếu chưa có
        $role = \Webkul\User\Models\Role::firstOrCreate([
            'name' => 'Sales'
        ], [
            'description' => 'Sales User',
            'permission_type' => 'custom',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Tạo 3 user role Sales
        \Webkul\User\Models\User::factory()->count(3)->create(['role_id' => $role->id]);

        // Tạo 10 organization
        \Webkul\Contact\Models\Organization::factory()->count(10)->create();

        // Tạo 20 person với dữ liệu đúng định dạng (insert trực tiếp để bypass CustomAttribute trait)
        for ($i = 0; $i < 20; $i++) {
            DB::table('persons')->insert([
                'name' => fake()->name(),
                'emails' => json_encode([
                    ['value' => fake()->unique()->safeEmail(), 'label' => 'work'],
                ]),
                'contact_numbers' => json_encode([
                    ['value' => fake()->numerify('0#########'), 'label' => 'mobile'],
                ]),
                'job_title' => fake()->jobTitle(),
                'user_id' => \Webkul\User\Models\User::inRandomOrder()->value('id'),
                'organization_id' => \Webkul\Contact\Models\Organization::inRandomOrder()->value('id'),
                'unique_id' => strtoupper(\Illuminate\Support\Str::random(10)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tạo 20 product
        \Webkul\Product\Models\Product::factory()->count(20)->create();

        // Tạo 10 quote
        \Webkul\Quote\Models\Quote::factory()->count(10)->create();

        // Tạo 20 lead
        \Webkul\Lead\Models\Lead::factory()->count(20)->create();
    }
}
