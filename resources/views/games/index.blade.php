@extends('layouts.user')

@section('title')
    Início
@endsection

@section('content')
    <x-searchBar />

    <h1 class="mb-4 text-2xl font-bold font-grotesk">JOGOS</h1>

    <div class="grid gap-4 grid-cols-4">
        @forelse ($games as $game)
            @include('components.gameCard', [
                'image' => $game['image'],
                'name' => $game['name'],
                'price' => $game['price'],
                'link' => route('games.detail', ['id' => $game['appid']]),
                'size' => 'sm', // estilo quando md:grid-cols-4
            ])
        @empty
            <p>Nenhum jogo encontrado.</p>
        @endforelse
    </div>
    <div class="mt-4 grid gap-4 grid-cols-2">
        @forelse ($games as $game)
            @include('components.gameCard', [
                'image' => $game['image'],
                'name' => $game['name'],
                'price' => $game['price'],
                'link' => route('games.detail', ['id' => $game['appid']]),
                'size' => 'lg', // estilo quando md:grid-cols-2
            ])
        @empty
            <p>Nenhum jogo encontrado.</p>
        @endforelse
    </div>
    <div class="mt-4 grid gap-4 grid-cols-3">
        @forelse ($games as $game)
            @include('components.gameCard', [
                'image' => $game['image'],
                'name' => $game['name'],
                'price' => $game['price'],
                'link' => route('games.detail', ['id' => $game['appid']]),
                'size' => 'md', // estilo para layout 3 colunas padrão
            ])
        @empty
            <p>Nenhum jogo encontrado.</p>
        @endforelse
    </div>
@endsection
