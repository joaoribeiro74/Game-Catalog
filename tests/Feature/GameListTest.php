<?php

namespace Tests\Feature;

use App\Models\GameList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameListTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_a_new_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('games.myList.store'), [
            'name' => 'Minha Lista',
        ]);

        $response->assertRedirect(route('games.myList'));
        $response->assertSessionHas('success', 'Lista criada com sucesso!');

        $this->assertDatabaseHas('game_lists', [
            'user_id' => $user->id,
            'name' => 'Minha Lista',
        ]);
    }

    public function test_add_item_adds_game_to_lists_and_skips_existing()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list1 = GameList::factory()->create(['user_id' => $user->id, 'name' => 'Lista 1']);
        $list2 = GameList::factory()->create(['user_id' => $user->id, 'name' => 'Lista 2']);

        $list1->items()->create(['game_id' => 100]);

        $response = $this->post(route('games.myList.addItem'), [
            'game_id' => 100,
            'list_ids' => [$list1->id, $list2->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $successMessage = session('success');
        $this->assertStringContainsString('Jogo adicionado em 1 lista(s) com sucesso!', $successMessage);
        $this->assertStringContainsString('Já estava nas seguintes: Lista 1', $successMessage);

        $this->assertDatabaseHas('game_list_items', [
            'game_list_id' => $list2->id,
            'game_id' => 100,
        ]);
    }

    public function test_update_renames_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = GameList::factory()->create(['user_id' => $user->id, 'name' => 'Antigo Nome']);

        $response = $this->put(route('games.myList.update', $list->id), [
            'name' => 'Novo Nome',
        ]);

        $response->assertRedirect(route('games.myList'));
        $response->assertSessionHas('success', 'Nome da lista atualizado com sucesso!');

        $this->assertDatabaseHas('game_lists', [
            'id' => $list->id,
            'name' => 'Novo Nome',
        ]);
    }

    public function test_destroy_deletes_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $list = GameList::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(route('games.myList.destroy', $list->id));

        $response->assertRedirect(route('games.myList'));
        $response->assertSessionHas('success', 'Lista excluída com sucesso!');

        $this->assertDatabaseMissing('game_lists', ['id' => $list->id]);
    }
}
