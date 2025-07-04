<?php

namespace Tests\Unit;

use App\Models\GameList;
use App\Models\GameListItem;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_game_lists()
    {
        $user = User::factory()->create();
        $list = GameList::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->gameLists->contains($list));
        $this->assertInstanceOf(GameList::class, $user->gameLists->first());
    }

    public function test_user_has_many_ratings()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->ratings->contains($rating));
        $this->assertInstanceOf(Rating::class, $user->ratings->first());
    }

     public function test_user_has_default_list_plus_multiple_lists_with_games()
    {
        $user = User::factory()->create();

        $this->assertCount(1, $user->gameLists);

        for ($i = 0; $i < 3; $i++) {
            $list = GameList::factory()->create(['user_id' => $user->id]);
            GameListItem::factory()->count(5)->create(['game_list_id' => $list->id]);
        }

        $user->refresh();

        $this->assertCount(4, $user->gameLists);

        foreach ($user->gameLists->slice(1) as $list) {
            $this->assertCount(5, $list->items); 
        }
    }
}
