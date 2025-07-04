<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GameList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $gamesTotal = $user->ratings()->count();
        $gamesThisYear = $user->ratings()
            ->whereYear('created_at', now()->year)
            ->count();
        $ratings = $this->getRatedGames($user)->take(4);

        $scores = ['0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0'];
        $ratingsCount = array_fill_keys($scores, 0);

        foreach ($user->ratings as $rating) {
            $score = number_format((float) $rating->rating, 1); // força para string tipo "3.5"

            if (array_key_exists($score, $ratingsCount)) {
                $ratingsCount[$score]++;
            }
        }

        $maxCount = max($ratingsCount);

        return view('profile.index', compact('user', 'gamesTotal', 'gamesThisYear', 'ratings', 'ratingsCount', 'maxCount'));
    }

    public function games(Request $request)
    {
        $user = $request->user();
        $ratings = $this->getRatedGames($user);

        return view('profile.games', compact('user', 'ratings'));
    }
    private function fetchGamesFromApi(array $gameIds): array
    {
        $games = [];

        foreach ($gameIds as $appId) {
            // Tenta pegar o dado do cache primeiro
            $gameData = Cache::remember("steam_game_{$appId}", now()->addHours(6), function () use ($appId) {
                $response = Http::get('https://store.steampowered.com/api/appdetails', [
                    'appids' => $appId,
                    'cc' => 'br',
                    'l' => 'brazilian',
                ]);

                $data = $response->json();

                if ($data && isset($data[$appId]['success']) && $data[$appId]['success']) {
                    $info = $data[$appId]['data'];

                    return [
                        'name' => $info['name'] ?? 'Sem nome',
                        'image' => $info['header_image'] ?? null,
                    ];
                }

                return null;
            });

            if ($gameData) {
                $games[$appId] = $gameData;
            }
        }

        return $games;
    }

    private function getRatedGames(User $user)
    {
        \Carbon\Carbon::setLocale('pt_BR');

        $ratings = $user->ratings()->get();

        $gameIds = $ratings->pluck('game_id')->unique()->toArray();

        $gamesFromApi = $this->fetchGamesFromApi($gameIds);

        return $ratings->map(function ($rating) use ($gamesFromApi) {
            $game = $gamesFromApi[$rating->game_id] ?? null;

            $createdAt = \Carbon\Carbon::parse($rating->created_at);

            return (object) [
                'id' => $rating->game_id,
                'name' => $game['name'] ?? 'Desconhecido',
                'image' => $game['image'] ?? null,
                'rating' => $rating->rating,
                'liked' => $rating->liked,
                'date' => $createdAt->format('d M'),
                'year' => $createdAt->year !== now()->year ? $createdAt->year : null,
            ];
        });
    }

    public function lists(Request $request)
    {
        $user = $request->user();

        // Pega as listas do usuário com os jogos (itens) que não estão vazios
        $lists = $user->gameLists()->with('items')->get()
            ->filter(fn($list) => $list->items->isNotEmpty())
            ->values();

        // Para cada lista, busca os dados completos dos jogos via API
        foreach ($lists as $list) {
            // Pega os IDs dos jogos na lista (ajuste 'game_id' para seu campo correto)
            $gameIds = $list->items->pluck('game_id')->toArray();

            // Busca os dados dos jogos pela API e guarda na propriedade games da lista
            $list->games = collect($this->fetchGamesFromApi($gameIds));
        }

        return view('profile.lists', compact('lists'));
    }

    public function show($listId)
    {
        $list = GameList::with('items')->findOrFail($listId);

        $gameApiIds = $list->items->pluck('game_id')->toArray();

        $games = $this->fetchGamesFromApi($gameApiIds);

        // Associa os dados da API a cada item da lista:
        foreach ($list->items as $item) {
            $item->game_data = $games[$item->game_id] ?? null;
        }

        return view('profile.lists.show', compact('list', 'games'));
    }
}
