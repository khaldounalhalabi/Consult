<?php

namespace Database\Factories;

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
            'expert_id' => $this->faker->numberBetween(1, 4),
            'user_id' => $this->faker->numberBetween(1, 4),
            'appointment_date' => $this->faker->dateTimeThisMonth()->format('Y-m-d'),
            'appointment_time' => $this->faker->time('h:i:s'),
            'status' => implode($this->faker->randomElements([
                'waiting', 'done'
            ]))
        ];
    }
}
