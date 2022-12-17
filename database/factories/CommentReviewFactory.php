<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentReview>
 */
class CommentReviewFactory extends Factory
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
            'user_id' => $this->faker->numberBetween(1 ,4),
            'comment' => $this->faker->sentence(),
            'star_rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
