<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // Ajuste se seu model tiver namespace diferente

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register', ['action' => route('register')]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns|unique:users,email',
            'username' => 'required|string|min:4|max:32|regex:/^[a-zA-Z0-9_-]+$/|unique:users,username',
            'password' => 'required|string|min:8|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).+$/|confirmed',
        ]);

        User::create([
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('games.index')->with('success', 'Cadastro realizado com sucesso! Faça login.');
    }
}
