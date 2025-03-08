<?php

namespace Database\Factories;

use App\Models\Expert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'expert_id' => Expert::factory(),
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'time' => $this->faker->time('h:i:s'),
            'status' => implode($this->faker->randomElements([
                'waiting', 'done'
            ]))
        ];
    }
}
