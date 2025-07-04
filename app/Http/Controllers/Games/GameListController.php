<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameListController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        GameList::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return redirect()->route('games.myList')->with('success', 'Lista criada com sucesso!');
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer',
            'list_ids' => 'required|array',
            'list_ids.*' => 'integer|exists:game_lists,id',
        ]);

        $user = Auth::user();
        $addedCount = 0;
        $alreadyExists = [];

        foreach ($request->list_ids as $listId) {
            $list = $user->gameLists()->where('id', $listId)->first();

            if (!$list) {
                continue;
            }

            $alreadyInList = $list->items()->where('game_id', $request->game_id)->exists();

            if ($alreadyInList) {
                $alreadyExists[] = $list->name;
                continue;
            }

            $list->items()->create([
                'game_id' => $request->game_id,
                'game_data' => [], // opcional
            ]);

            $addedCount++;
        }

        if ($addedCount > 0) {
            $message = "Jogo adicionado em $addedCount lista(s) com sucesso!";
            if (count($alreadyExists)) {
                $message .= " Já estava nas seguintes: " . implode(', ', $alreadyExists);
            }
            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'O jogo já está na(s) lista(s) selecionada(s).');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $list = GameList::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $list->update([
            'name' => $request->name,
        ]);

        $redirect = $request->input('redirect_to', route('games.myList'));

        return redirect($redirect)->with('success', 'Nome da lista atualizado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        $list = GameList::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $list->delete();

        $redirect = $request->input('redirect_to', route('games.myList'));

        return redirect($redirect)->with('success', 'Lista excluída com sucesso!');
    }
}
