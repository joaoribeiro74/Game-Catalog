<?php

namespace Tests\Unit;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_rating_belongs_to_user()
    {
        $user = User::factory()->create();
        $rating = Rating::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $rating->user()->getModel());
    }

    public function test_rating_invalid_value_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Rating must be between 0.5 and 5.0 and in increments of 0.5.');

        $user = User::factory()->create();

        // Tentar criar um rating inválido (rating = 0)
        Rating::create([
            'user_id' => $user->id,
            'game_id' => 123456,
            'rating' => 0, // inválido
            'liked' => true,
            'review' => 'Test review',
            'reviewed_at' => now(),
        ]);
    }
}
