<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // lógica para mostrar o perfil
        $user = Auth::user();

        $gamesTotal = $user->ratings()->count();
        $gamesThisYear = $user->ratings()
            ->whereYear('created_at', now()->year)
            ->count();

        return view('profile.index', compact('user', 'gamesTotal', 'gamesThisYear'));
    }
}
