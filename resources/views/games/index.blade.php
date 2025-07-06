@extends('layouts.user')

@section('title')
    Início
@endsection

@section('content')
    <x-searchBar />

    <h1 class="font-grotesk mb-4 text-2xl font-bold">JOGOS</h1>

    @php
        $layout = [4, 3, 2, 3, 4, 3, 2, 3, 4]; 
        $sizes = [4 => 'sm', 3 => 'md', 2 => 'lg'];
        $gamesCollection = collect($games); 
    @endphp

    @foreach ($layout as $count)
        @php
            $chunk = $gamesCollection->splice(0, $count); 
            $size = $sizes[$count] ?? 'md';
        @endphp

        @if ($chunk->isNotEmpty())
            <div class="grid-cols-{{ $count }} mb-4 grid gap-4">
                @foreach ($chunk as $game)
                    @include('components.gameCard', [
                        'image' => $game['image'],
                        'name' => $game['name'],
                        'price' => $game['price'],
                        'link' => route('games.detail', ['id' => $game['appid']]),
                        'size' => $size,
                    ])
                @endforeach
            </div>
        @endif
    @endforeach
@endsection
