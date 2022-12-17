<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expert>
 */
class ExpertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email() ,
            'password' => bcrypt('123456') ,
            'photo' => $this->faker->image(
                'C:\xampp\htdocs\Fatima\storage\app\public',
                640,
                480,
                'animals',
                true,
                true,
                null,
                false,
                'png'
            ),
            'experience' => $this->faker->sentence(15),
            'phone' => $this->faker->phoneNumber(),
            'mobile' => $this->faker->phoneNumber(),
            'country' => $this->faker->country(),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'wallet' => $this->faker->numberBetween(500, 100000),
            'category_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
