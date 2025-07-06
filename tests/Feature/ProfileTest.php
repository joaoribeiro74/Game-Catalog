<?php

namespace Tests\Feature;

use App\Models\GameList;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function fakeSteamApiResponse(array $appIds)
    {
        $fakeData = [];
        foreach ($appIds as $appId) {
            $fakeData[$appId] = [
                'success' => true,
                'data' => [
                    'name' => "Game $appId",
                    'header_image' => "http://image.url/game_$appId.jpg",
                ],
            ];
        }

        Http::fake([
            'https://store.steampowered.com/api/appdetails*' => Http::response($fakeData, 200),
        ]);
    }

    public function test_guest_cannot_access_any_profile_routes()
    {
        $user = User::factory()->create();
        $list = GameList::factory()->create(['user_id' => $user->id]);

        $urls = [
            route('profile.index'),
            route('profile.games'),
            route('profile.lists'),
            route('profile.lists.show', ['list' => $list->id]),
        ];

        foreach ($urls as $url) {
            $response = $this->get($url);
            $response->assertRedirect(route('login'));
        }
    }

    public function test_index_shows_profile_data()
    {
        $user = User::factory()->create();
        $ratings = Rating::factory()->count(3)->create(['user_id' => $user->id]);

        $gameIds = $ratings->pluck('game_id')->unique()->toArray();
        $this->fakeSteamApiResponse($gameIds);

        $response = $this->actingAs($user)->get(route('profile.index'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.index');
        $response->assertViewHasAll(['user', 'gamesTotal', 'gamesThisYear', 'ratingsCount', 'maxCount']);
        $this->assertEquals($user->id, $response->viewData('user')->id);
        $this->assertEquals($ratings->count(), $response->viewData('gamesTotal'));
    }

    public function test_games_shows_rated_games()
    {
        $user = User::factory()->create();
        $ratings = Rating::factory()->count(2)->create(['user_id' => $user->id]);

        $gameIds = $ratings->pluck('game_id')->unique()->toArray();
        $this->fakeSteamApiResponse($gameIds);

        $response = $this->actingAs($user)->get(route('profile.games'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.games');
        $response->assertViewHas('ratings');

        $ratingsFromView = $response->viewData('ratings');
        $this->assertCount(2, $ratingsFromView);
    }

    public function test_lists_shows_user_game_lists_with_games()
    {
        $user = User::factory()->create();
        $listWithItems = GameList::factory()->create(['user_id' => $user->id]);
        $listEmpty = GameList::factory()->create(['user_id' => $user->id]);

        // Criar itens para a lista com jogos
        $listWithItems->items()->createMany([
            ['game_id' => 101],
            ['game_id' => 202],
        ]);

        $this->fakeSteamApiResponse([101, 202]);

        $response = $this->actingAs($user)->get(route('profile.lists'));

        $response->assertStatus(200);
        $response->assertViewIs('profile.lists');
        $lists = $response->viewData('lists');

        $this->assertTrue($lists->contains('id', $listWithItems->id));
        $this->assertFalse($lists->contains('id', $listEmpty->id));

        foreach ($lists as $list) {
            foreach ($list->games as $game) {
                $this->assertStringContainsString('Game', $game['name']);
                $this->assertStringContainsString('http://image.url', $game['image']);
            }
        }
    }

    public function test_show_displays_list_and_games()
    {
        $user = User::factory()->create();
        $list = GameList::factory()->create(['user_id' => $user->id]);

        $list->items()->createMany([
            ['game_id' => 999],
            ['game_id' => 888],
        ]);

        $this->fakeSteamApiResponse([999, 888]);

        $response = $this->actingAs($user)->get(route('profile.lists.show', ['list' => $list->id]));

        $response->assertStatus(200);
        $response->assertViewIs('profile.lists.show');

        $response->assertViewHas('list');
        $response->assertViewHas('games');

        $listFromView = $response->viewData('list');

        foreach ($listFromView->items as $item) {
            $this->assertNotNull($item->game_data);
            $this->assertStringContainsString('Game', $item->game_data['name']);
        }
    }
}
