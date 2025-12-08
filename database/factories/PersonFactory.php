<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Webkul\Contact\Models\Person;
use Webkul\User\Models\User;
use Webkul\Contact\Models\Organization;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition(): array
    {
        $userId = class_exists(User::class) ? User::inRandomOrder()->value('id') : null;
        $orgId = class_exists(Organization::class) ? Organization::inRandomOrder()->value('id') : null;

        return [
            'name' => $this->faker->name(),
            'emails' => [
                ['value' => $this->faker->unique()->safeEmail(), 'label' => 'work'],
                ...($this->faker->boolean(30) ? [['value' => $this->faker->safeEmail(), 'label' => 'personal']] : []),
            ],
            'contact_numbers' => [
                ['value' => $this->faker->numerify('0#########'), 'label' => 'mobile'],
                ...($this->faker->boolean(25) ? [['value' => $this->faker->numerify('0#########'), 'label' => 'home']] : []),
            ],
            'job_title' => $this->faker->jobTitle(),
            'user_id' => $userId,
            'organization_id' => $orgId,
            'unique_id' => strtoupper(Str::random(10)),
        ];
    }

    public function withUser(): static
    {
        return $this->state(function () {
            return [
                'user_id' => User::factory()->create()->id,
            ];
        });
    }

    public function withOrganization(): static
    {
        return $this->state(function () {
            return [
                'organization_id' => Organization::factory()->create()->id,
            ];
        });
    }
}
