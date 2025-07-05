<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameListItem;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    public function index()
    {
        $games = Cache::remember('steam_games_data', 3600, function () {
            $appIds = [2246340, 221100, 1174180, 1850570, 377160, 1245620, 1091500, 292030, 3240220, 2669320, 108600, 2651280, 1172620, 648800, 1238840, 2933620, 3159330, 359550, 1086940, 381210, 990080, 1593500, 1888930, 1627720, 1903340, 2050650, 2344520, 489830];
            $games = [];

            foreach ($appIds as $appid) {
                $response = Http::get("https://store.steampowered.com/api/appdetails", [
                    'appids' => $appid,
                    'cc' => 'br',
                    'l' => 'brazilian'
                ]);

                $data = $response->json();
                if ($data && isset($data[$appid]['success']) && $data[$appid]['success']) {
                    $info = $data[$appid]['data'];

                    $games[] = [
                        'appid' => $info['steam_appid'],
                        'name' => $info['name'],
                        'price' => $info['is_free'] ? 'Gratuito' : (
                            $info['price_overview']['final_formatted'] ?? 'Indisponível'
                        ),
                        'image' => $info['header_image'] ?? null
                    ];
                }
            }

            return $games;
        });

        $user = Auth::user();
        $list = $user->gameLists()->where('name', 'Próximos Jogos')->first();
        $listGameIds = $list ? $list->items()->pluck('game_id')->toArray() : [];

        foreach ($games as &$game) {
            $game['inList'] = in_array($game['appid'], $listGameIds);
        }

        return view('games.index', compact('games'));
    }

    public function create()
    {
        return view('games.create');
    }

    public function detail(string $id)
    {
        $response = Http::get("https://store.steampowered.com/api/appdetails", [
            'appids' => $id,
            'cc' => 'br',
            'l' => 'brazilian'
        ]);

        $data = $response->json();

        if ($data && isset($data[$id]['success']) && $data[$id]['success']) {
            $info = $data[$id]['data'];

            $game = [
                'appid' => $info['steam_appid'],
                'name' => $info['name'],
                'description' => $info['short_description'] ?? '',
                'price' => $info['is_free'] ? 'Gratuito' : (
                    $info['price_overview']['final_formatted'] ?? 'Indisponível'
                ),
                'image' => $info['header_image'] ?? null,
                'genres' => $info['genres'] ?? [],
                'developers' => $info['developers'] ?? [],
                'publishers' => $info['publishers'] ?? [],
                'release_date' => $info['release_date']['date'] ?? 'Desconhecida',
                'screenshots' => array_slice($info['screenshots'] ?? [], 0, 5),
            ];

            $user = Auth::user();

            $userRating = 0;
            $userLiked = false;

            if ($user) {
                $rating = Rating::where('user_id', $user->id)
                    ->where('game_id', $game['appid'])
                    ->first();

                if ($rating) {
                    $userRating = $rating->rating ?? 0;
                    $userLiked = $rating->liked ?? false;
                }

                $list = $user->gameLists()->where('name', 'Próximos Jogos')->first();
                $listGameIds = $list ? $list->items()->pluck('game_id')->toArray() : [];

                $game['inList'] = in_array($game['appid'], $listGameIds);

                $gameLists = $user->gameLists()->get();
            } else {
                $game['inList'] = false;
                $gameLists = collect();
            }

            $hasRated = $userRating > 0;

            return view('games.detail', compact('game', 'gameLists', 'userRating', 'userLiked', 'hasRated'));
        }

        return redirect()->route('games.index')->with('error', 'Jogo não encontrado.');
    }

    public function edit(string $id)
    {
        return view(
            'games.edit',
            [
                'game' => [
                    'id' => $id,
                    'title' => 'The Witcher 3',
                    'status' => 'Jogado',
                    'rating' => 9.5
                ]
            ]
        );
    }

    public function update(Request $request)
    {
        return redirect()->route('games.index');
    }

    public function myList()
    {
        $user = Auth::user();
        $gameLists = $user->gameLists()->with('items')->get();

        foreach ($gameLists as $list) {
            foreach ($list->items as $item) {
                $response = Http::get("https://store.steampowered.com/api/appdetails", [
                    'appids' => $item->game_id,
                    'cc' => 'br',
                    'l' => 'brazilian',
                ]);

                $data = $response->json();

                if ($data && isset($data[$item->game_id]['success']) && $data[$item->game_id]['success']) {
                    $info = $data[$item->game_id]['data'];

                    // Adiciona os dados da API no item como uma propriedade dinâmica
                    $item->game_data = [
                        'name' => $info['name'],
                        'image' => $info['header_image'] ?? null,
                    ];
                } else {
                    $item->game_data = null;
                }
            }
        }

        return view('games.myList', compact('gameLists'));
    }

    public function toggleGame(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $gameId = $request->game_id;

        $list = $user->gameLists()->firstOrCreate(['name' => 'Próximos Jogos']);

        $exists = $list->items()->where('game_id', $gameId)->exists();

        if ($exists) {
            $list->items()->where('game_id', $gameId)->delete();
            $added = false;
        } else {
            $list->items()->create(['game_id' => $gameId]);
            $added = true;
        }

        return response()->json(['success' => true, 'added' => $added]);
    }

    public function removeItem(GameListItem $item)
    {
        $user = Auth::user();

        // Garante que o item pertence ao usuário
        if ($item->gameList->user_id !== $user->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->back()->with('success', 'Jogo removido da lista com sucesso!');
    }
}
