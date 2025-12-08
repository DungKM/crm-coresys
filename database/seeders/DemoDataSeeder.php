<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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

        // Tạo 20 person
        \Webkul\Contact\Models\Person::factory()->count(20)->create();

        // Tạo 20 product
        \Webkul\Product\Models\Product::factory()->count(20)->create();

        // Tạo 10 quote
        \Webkul\Quote\Models\Quote::factory()->count(10)->create();

        // Tạo 20 lead
        \Webkul\Lead\Models\Lead::factory()->count(20)->create();
    }
}
