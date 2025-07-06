<?php

namespace Tests\Unit;

use App\Models\Attachment;
use App\Models\GameList;
use App\Models\GameListItem;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelationsTest extends TestCase
{
    use RefreshDatabase;
    public function test_game_list_relationships_are_correct()
    {
        $gameList = new GameList();

        $this->assertInstanceOf(BelongsTo::class, $gameList->user());
        $this->assertInstanceOf(HasMany::class, $gameList->items());
    }

    public function test_game_list_item_relationship_is_correct()
    {
        $item = new GameListItem();

        $this->assertInstanceOf(BelongsTo::class, $item->gameList());
    }

    public function test_attachment_relationship_is_correct()
    {
        $attachment = new Attachment();

        $relation = $attachment->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
    }

    public function test_user_relationships_are_correct()
    {
        $user = new User();

        $this->assertInstanceOf(HasMany::class, $user->gameLists());
        $this->assertInstanceOf(HasMany::class, $user->ratings());
        $this->assertInstanceOf(HasOne::class, $user->attachment());
    }

    public function test_rating_relationship_is_correct()
    {
        $rating = new Rating();

        $this->assertInstanceOf(BelongsTo::class, $rating->user());
    }

    public function test_relationships_work_in_database()
    {
        $user = User::factory()->create();
        $attachment = Attachment::factory()->create(['user_id' => $user->id]);
        $gameList = GameList::factory()->create(['user_id' => $user->id]);
        $item = GameListItem::factory()->create(['game_list_id' => $gameList->id]);
        $rating = Rating::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->gameLists->contains($gameList));
        $this->assertEquals($user->id, $gameList->user->id);

        $this->assertTrue($gameList->items->contains($item));
        $this->assertEquals($gameList->id, $item->gameList->id);

        $this->assertEquals($user->id, $attachment->user->id);
        $this->assertEquals($user->id, $rating->user->id);
    }
}
