<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Contact\Models\Organization;
use Webkul\User\Models\User;

class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        $userId = class_exists(User::class)
            ? User::inRandomOrder()->value('id')
            : null;

        return [
            'name' => $this->faker->company(),
            'address' => [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'state' => $this->faker->state(),
                'zip' => $this->faker->postcode(),
                'country' => $this->faker->country(),
            ],
            'user_id' => $userId,
        ];
    }

    /**
     * Nếu muốn chắc chắn có user
     */
    public function withUser(): static
    {
        return $this->state(function () {
            return [
                'user_id' => User::factory()->create()->id,
            ];
        });
    }
}
