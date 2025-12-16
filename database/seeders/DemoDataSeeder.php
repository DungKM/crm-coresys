<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Cho phép tuỳ biến số lượng bằng ENV, có giá trị mặc định hợp lý
        $salesCount = (int) env('DEMO_SALES_USERS', 12);
        $orgCount = (int) env('DEMO_ORGS', 40);
        $personCount = (int) env('DEMO_PERSONS', 150);
        $productCount = (int) env('DEMO_PRODUCTS', 40);
        $quoteCount = (int) env('DEMO_QUOTES', 25);
        $leadCount = (int) env('DEMO_LEADS', 200);

        // Tạo role Sales nếu chưa có với quyền đầy đủ
        $role = \Webkul\User\Models\Role::firstOrCreate([
            'name' => 'Sales'
        ], [
            'description' => 'Sales User',
            'permission_type' => 'all', // Đặt thành 'all' để có toàn bộ quyền
            'permissions' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Nếu chưa được tạo trong lần trước, cập nhật permission_type
        if ($role->wasRecentlyCreated === false && $role->permission_type !== 'all') {
            $role->update(['permission_type' => 'all', 'permissions' => null]);
        }

        // Tạo user role Sales (mặc định 12, có thể chỉnh qua ENV)
        \Webkul\User\Models\User::factory()->count($salesCount)->create(['role_id' => $role->id]);

        // Tạo organization (mặc định 40)
        \Webkul\Contact\Models\Organization::factory()->count($orgCount)->create();

        // Tạo person với dữ liệu đúng định dạng (insert trực tiếp để bypass CustomAttribute trait)
        for ($i = 0; $i < $personCount; $i++) {
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

        // Tạo product (mặc định 40)
        \Webkul\Product\Models\Product::factory()->count($productCount)->create();

        // Tạo quote (mặc định 25)
        \Webkul\Quote\Models\Quote::factory()->count($quoteCount)->create();

        // Tạo lead (mặc định 200) với user_id = null để thử Lead Assignment
        \Webkul\Lead\Models\Lead::factory()->count($leadCount)->create();
    }
}
