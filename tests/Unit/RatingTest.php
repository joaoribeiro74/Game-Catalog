<?php

namespace Tests\Unit;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_casts_are_correct()
    {
        $rating = new Rating();

        $expected = [
            'liked' => 'boolean',
            'reviewed_at' => 'datetime',
        ];

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $rating->getCasts());
            $this->assertEquals($value, $rating->getCasts()[$key]);
        }
    }

    public function test_rating_validation_accepts_valid_values()
    {
        $rating = new Rating([
            'user_id' => User::factory()->create()->id,
            'game_id' => 1,
            'rating' => 3.5,
            'liked' => true,
        ]);

        $this->assertTrue($rating->save());
    }

    public function test_rating_invalid_value_throws_exception()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Rating must be between 0.5 and 5.0 and in increments of 0.5.');

        $user = User::factory()->create();

        Rating::create([
            'user_id' => $user->id,
            'game_id' => 123456,
            'rating' => 0,
            'liked' => true,
            'review' => 'Test review',
            'reviewed_at' => now(),
        ]);
    }
}
