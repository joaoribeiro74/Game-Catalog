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
        return [
            'user_id' => User::factory(),
            'game_id' => $this->faker->numberBetween(100000, 999999),
            'rating' => $this->faker->randomFloat(1, 0.5, 5.0),
            'liked' => $this->faker->boolean(),
            'review' => $this->faker->optional()->paragraph(),
            'reviewed_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
