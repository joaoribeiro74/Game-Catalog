<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    public function index()
    {
        $appIds = [730, 570, 2246340, 221100]; // Ex: CS2, Dota 2, TF2, PUBG
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

            return view('games.detail', compact('game'));
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
        if (strlen($request->get("title")) == 0) {
            return redirect()->back()->with('error', 'Erro ao realizar a operação!');
        } else {
            return redirect()->route('games.index')->with('success', 'Alteração realizada com sucesso!');
        }
    }

    public function mylist()
    {
        return view('games.mylist');
    }

    // public function rate(Request $request, string $id)
    // {
    //     $request->validate([
    //         'rating' => 'required|numeric|min:0|max:5',
    //         'liked' => 'boolean' // opcional, se enviado
    //     ]);

    //     $user = auth()->user();
    //     if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

    //     // Salvar ou atualizar a nota
    //     GameRating::updateOrCreate(
    //         ['user_id' => $user->id, 'game_id' => $id],
    //         ['rating' => $request->rating]
    //     );

    //     // Salvar ou atualizar o like
    //     GameLike::updateOrCreate(
    //         ['user_id' => $user->id, 'game_id' => $id],
    //         ['liked' => $request->input('liked', false)]
    //     );

    //     return response()->json(['success' => true]);
    // }
}
