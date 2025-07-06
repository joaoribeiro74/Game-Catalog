<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GameRoutes extends TestCase
{
    use RefreshDatabase;
    public function test_live_search_returns_expected_json()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Http::fake([
            'steamcommunity.com/actions/SearchApps/*' => Http::response([
                ['appid' => 123, 'name' => 'Game Teste'],
                ['appid' => 456, 'name' => 'Outro Jogo'],
            ], 200),
        ]);

        $response = $this->getJson(route('games.search.suggest', ['q' => 'test']));

        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment(['appid' => 123, 'name' => 'Game Teste']);
    }

    public function test_live_search_returns_empty_for_short_query()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->getJson(route('games.search.suggest', ['q' => 'a']));

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function test_game_detail_displays_game_info()
    {
        $appId = 123;

        Http::fake([
            'https://store.steampowered.com/api/appdetails*' => Http::response([
                $appId => [
                    'success' => true,
                    'data' => [
                        'steam_appid' => $appId,
                        'name' => 'Jogo Teste',
                        'short_description' => 'Descrição do jogo teste',
                        'is_free' => false,
                        'price_overview' => ['final_formatted' => 'R$ 59,99'],
                        'header_image' => 'url/imagem.jpg',
                        'genres' => [['description' => 'Ação']],
                        'developers' => ['Dev Studio'],
                        'publishers' => ['Pub Studio'],
                        'release_date' => ['date' => '01 Jan, 2020'],
                        'screenshots' => [
                            [
                                'path_full' => 'url1.jpg',
                                'path_thumbnail' => 'thumb1.jpg',
                            ],
                            [
                                'path_full' => 'url2.jpg',
                                'path_thumbnail' => 'thumb2.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('games.detail', ['id' => $appId]));

        $response->assertStatus(200);
        $response->assertViewIs('games.detail');
        $response->assertSee('Jogo Teste');
        $response->assertSee('Descrição do jogo teste');
        $response->assertSee('R$ 59,99');
        $response->assertSee('Ação');
    }

    public function test_game_detail_redirects_on_invalid_response()
    {
        $appId = 999;

        Http::fake([
            'https://store.steampowered.com/api/appdetails*' => Http::response([], 404),
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('games.detail', ['id' => $appId]));

        $response->assertRedirect(route('games.index'));
        $response->assertSessionHas('error', 'Jogo não encontrado.');
    }
}
