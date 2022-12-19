<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'body' => $this->faker->sentence(),
            'from' => implode($this->faker->randomElements([
                'user', 'expert'
            ])) ,
            'user_id' => $this->faker->numberBetween(1 , 4) ,
            'expert_id' => $this->faker->numberBetween(1 , 4) ,
        ];
    }
}
