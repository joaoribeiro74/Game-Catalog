<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    public function test_casts_are_correct()
    {
        $user = new User();

        $expected = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'username_changed_at' => 'datetime',
        ];

        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $user->getCasts());
            $this->assertEquals($value, $user->getCasts()[$key]);
        }
    }

    public function test_game_list_is_created_on_user_creation()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('game_lists', [
            'user_id' => $user->id,
            'name' => 'Próximos Jogos',
        ]);

        $this->assertEquals('Próximos Jogos', $user->gameLists()->first()->name);
    }
}
