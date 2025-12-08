<?php

namespace Database\Factories;

use App\Models\FacebookMessage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\FacebookMessage>
 */
class FacebookMessageFactory extends Factory
{
    protected $model = FacebookMessage::class;

    public function definition(): array
    {
        return [
            'thread_id' => $this->faker->uuid(),
            'sender_id' => $this->faker->uuid(),
            'recipient_id' => $this->faker->uuid(),
            'sender_name' => $this->faker->name(),
            'message' => $this->faker->sentence(),
            'direction' => $this->faker->randomElement(['inbound', 'outbound']),
            'status' => $this->faker->randomElement(['received', 'sent', 'read']),
            'sent_at' => now(),
            'metadata' => ['source' => 'test'],
        ];
    }
}
