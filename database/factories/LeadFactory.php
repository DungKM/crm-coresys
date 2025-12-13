<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
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
        // Lấy các ID hợp lệ từ DB
        $userId = class_exists('Webkul\\User\\Models\\User') ? \Webkul\User\Models\User::inRandomOrder()->value('id') : null;
        $personId = class_exists('Webkul\\Contact\\Models\\Person') ? \Webkul\Contact\Models\Person::inRandomOrder()->value('id') : null;

        // Lấy pipeline và stage từ DB nếu có, nếu không thì null
        $pipelineId = DB::table('lead_pipelines')->inRandomOrder()->value('id');
        $stageId = $pipelineId ? DB::table('lead_pipeline_stages')->where('lead_pipeline_id', $pipelineId)->inRandomOrder()->value('id') : null;
        $sourceId = DB::table('lead_sources')->inRandomOrder()->value('id');
        $typeId = DB::table('lead_types')->inRandomOrder()->value('id');

        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'lead_value' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => 1,
            'lost_reason' => null,
            'closed_at' => null,
            'user_id' => null, // Để null để phân bổ thủ công qua Lead Assignment
            'person_id' => $personId,
            'lead_source_id' => $sourceId,
            'lead_type_id' => $typeId,
            'lead_pipeline_id' => $pipelineId,
            'lead_pipeline_stage_id' => $stageId,
            'created_at' => now(),
            'updated_at' => now(),
            'expected_close_date' => $this->faker->date(),
        ];

    }
}
