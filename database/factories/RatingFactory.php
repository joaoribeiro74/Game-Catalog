<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
   protected $model = Rating::class;

    public function definition(): array
    {
        $validRatings = [0.5, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 4.5, 5.0];

        return [
            'user_id' => User::factory(),
            'game_id' => $this->faker->numberBetween(100, 999999),
            'rating' => $this->faker->randomElement($validRatings),
            'liked' => $this->faker->boolean(),
            'review' => $this->faker->optional()->paragraph(),
            'reviewed_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
