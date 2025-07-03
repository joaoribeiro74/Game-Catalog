<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer',
            'rating' => 'nullable|numeric|between:0.5,5.0',
            'liked' => 'nullable|boolean',
            'review' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Tenta buscar avaliação existente
        $rating = Rating::firstOrNew([
            'user_id' => $user->id,
            'game_id' => $request->game_id,
        ]);

        // Atualiza os campos
        $rating->rating = $request->rating;
        $rating->liked = $request->liked ?? false;
        $rating->review = $request->review;
        $rating->reviewed_at = now();

        $rating->save();

        return response()->json(['message' => 'Avaliação salva com sucesso!', 'rating' => $rating]);
    }

}
