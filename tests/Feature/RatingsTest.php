<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_store_rating()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('games.rating.storeOrUpdate'), [
            'game_id' => 12345,
            'rating' => 4.5,
            'liked' => true,
        ]);

        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas('success', 'Sua avaliação foi salva com sucesso!');

        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'game_id' => 12345,
            'rating' => 4.5,
            'liked' => true,
        ]);
    }

    public function test_rating_validation_fails_and_redirects_back_with_errors()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from('/previous-page')->post(route('games.rating.storeOrUpdate'), [
            'game_id' => 12345,
            'rating' => 0,
        ]);

        $response->assertRedirect('/previous-page');
        $response->assertSessionHasErrors('rating');
    }

    public function test_liked_defaults_to_false_when_not_passed()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('games.rating.storeOrUpdate'), [
            'game_id' => 12345,
            'rating' => 3.0,
        ]);

        $response->assertRedirect(route('profile.index'));
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'game_id' => 12345,
            'rating' => 3.0,
            'liked' => false, 
        ]);
    }
}
