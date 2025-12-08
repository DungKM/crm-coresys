<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Lead\Models\Lead;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Webkul\Lead\Models\Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'lead_value' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => 1,
            'lost_reason' => null,
            'closed_at' => null,
            'user_id' => null, // hoặc random từ User nếu cần
            'person_id' => null, // hoặc random từ Person nếu cần
            'lead_source_id' => null,
            'lead_type_id' => null,
            'lead_pipeline_id' => null,
            'lead_pipeline_stage_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'expected_close_date' => $this->faker->date(),
        ];
    }
}
