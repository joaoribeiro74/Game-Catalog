<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameApiController extends Controller
{
    public function liveSearch(Request $request)
    {
        $query = $request->query('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $response = Http::get("https://steamcommunity.com/actions/SearchApps/{$query}");

        if ($response->failed()) {
            return response()->json([]);
        }

        return $response->json();
    }
}
