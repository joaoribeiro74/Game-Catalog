<?php

namespace Database\Factories;

use App\Models\GameList;
use App\Models\GameListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameListItem>
 */
class GameListItemFactory extends Factory
{
   protected $model = GameListItem::class;

    public function definition(): array
    {
        return [
            'game_list_id' => GameList::factory(),
            'game_id' => $this->faker->numberBetween(100000, 999999), // ID da Steam
            'added_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
