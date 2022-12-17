<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpenedTime>
 */
class OpenedTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'expert_id' => $this->faker->numberBetween(1 , 4),
            'saturday_from' => $this->faker->time('h:i'),
            'saturday_to' => $this->faker->time('h:i'),
            'sunday_from' => $this->faker->time('h:i'),
            'sunday_to' => $this->faker->time('h:i'),
            'monday_from' => $this->faker->time('h:i'),
            'monday_to' => $this->faker->time('h:i'),
            'tuesday_from' => $this->faker->time('h:i'),
            'tuesday_to' => $this->faker->time('h:i'),
            'wednesday_from' => $this->faker->time('h:i'),
            'wednesday_to' => $this->faker->time('h:i'),
            'thursday_from' => $this->faker->time('h:i'),
            'thursday_to' => $this->faker->time('h:i'),
            'friday_from' => $this->faker->time('h:i'),
            'friday_to' => $this->faker->time('h:i'),
        ];
    }
}
