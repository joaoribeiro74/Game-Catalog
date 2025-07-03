<?php

namespace Tests\Unit;

use App\Models\GameList;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class UserRelationshipsTest extends TestCase
{
     use RefreshDatabase;

    public function test_user_has_many_game_lists()
    {
        $user = User::factory()->create();
        $list = GameList::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->lists->contains($list));
        $this->assertInstanceOf(GameList::class, $user->lists->first());
    }

    public function test_user_has_many_ratings()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->ratings->contains($rating));
        $this->assertInstanceOf(Rating::class, $user->ratings->first());
    }
}
