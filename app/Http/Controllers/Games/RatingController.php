<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        try {
            $request->validate(
                [
                    'game_id' => 'required|integer',
                    'rating' => 'required|numeric|between:0.5,5.0',
                    'liked' => 'nullable|boolean',
                    'review' => 'nullable|string|max:1000',
                ],
                [
                    'rating.between' => 'A avaliação deve estar entre meia estrela e 5 estrelas.',
                ]
            );

            $user = Auth::user();

            $rating = Rating::firstOrNew([
                'user_id' => $user->id,
                'game_id' => $request->game_id,
            ]);

            $rating->rating = $request->rating;
            $rating->liked = $request->liked ?? false;
            $rating->review = $request->review;
            $rating->reviewed_at = now();

            $rating->save();

            return redirect()->route('profile.index')->with('success', 'Sua avaliação foi salva com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao salvar sua avaliação.');
        }
    }
}
