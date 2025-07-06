@extends('layouts.user')

@section('title')
    Início
@endsection

@section('content')
    <x-searchBar />

    <h1 class="font-grotesk mb-4 text-2xl font-bold md:px-0">JOGOS</h1>

    <div class="mb-4 grid grid-cols-2 gap-4 px-2 md:hidden">
        @foreach ($games as $game)
            @include('components.gameCard', [
                'image' => $game['image'],
                'name' => $game['name'],
                'price' => $game['price'],
                'link' => route('games.detail', ['id' => $game['appid']]),
                'size' => 'sm',
            ])
        @endforeach
    </div>

    <div class="hidden md:block">
        @php
            $layout = [4, 3, 2, 3, 4, 3, 2, 3, 4];
            $sizes = [4 => 'sm', 3 => 'md', 2 => 'lg'];
            $gamesCollection = collect($games);
        @endphp

        @foreach ($layout as $count)
            @php
                $chunk = $gamesCollection->splice(0, $count);
                $size = $sizes[$count] ?? 'md';

                $gridColsClass = match ($count) {
                    2 => 'md:grid-cols-2',
                    3 => 'md:grid-cols-3',
                    4 => 'md:grid-cols-4',
                    default => 'md:grid-cols-3',
                };
            @endphp

            @if ($chunk->isNotEmpty())
                <div class="{{ $gridColsClass }} mb-4 grid gap-4 px-2 md:px-0">
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
    </div>
@endsection
