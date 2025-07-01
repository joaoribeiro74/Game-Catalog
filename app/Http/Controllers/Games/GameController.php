<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return view('games.index', [
            'games' => [
                [
                    'id' => 1,
                    'title' => 'The Witcher 3', 
                    'status' => 'Jogado', 
                    'rating' => 9.5],
                [
                    'id' => 2,
                    'title' => 'Elden Ring', 
                    'status' => 'Jogando', 
                    'rating' => 9.0
                ],
                [
                    'id' => 3,
                    'title' => 'Hollow Knight', 
                    'status' => 'Quero Jogar', 
                    'rating' => null
                ],
            ]
        ]);
    }

    public function create() 
    {
        return view('games.create');
    }

    public function detail(string $id)
    {
        return view('games.detail', 
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

    public function edit(string $id)
    {
        return view('games.edit', 
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
}
