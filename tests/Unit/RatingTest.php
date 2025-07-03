<?php

namespace Tests\Unit;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_rating_belongs_to_user()
    {
        $rating = Rating::factory()->make();

        $this->assertInstanceOf(User::class, $rating->user()->getModel());
    }
}
