@extends('layouts.user')

@section('content')
    <div class="font-grotesk flex items-center justify-between">
        <div class="flex flex-row items-center justify-center gap-8">
            <x-fas-circle-user class="h-24 w-24" fill=#fff />
            <p class="font-grotesk text-2xl">{{ $user->username }}</p>
            <div class="rounded bg-[#181c34] px-3 py-1 text-xs font-bold uppercase">
                <a href="{{ route('profile.settings.edit') }}">
                    EDITAR PERFIL
                </a>
            </div>
        </div>

        <div class="flex flex-row justify-end space-y-4 font-bold uppercase">
            <div class="flex flex-col items-center text-center">
                <span class="text-lg font-bold">{{ $gamesTotal }}</span>
                <span class="text-xs"class="text-xs">{{ $gamesTotal === 1 ? 'Jogo' : 'Jogos' }}</span>
            </div>
            <span class="border-r-1 mx-4 border-gray-400 opacity-30"></span>
            <div class="flex flex-col items-center text-center">
                <span class="text-lg font-bold">{{ $gamesThisYear }}</span>
                <span class="text-xs">Este ano</span>
            </div>
        </div>
    </div>
    <x-profileHeader />
    <div class="font-grotesk mt-10 flex gap-8">
        <div class="w-2/3">
            <span>JOGOS FAVORITOS</span>
            <div class="border-b-1 mb-2 border-gray-400 opacity-30"></div>
            <div class="mb-8 grid grid-cols-3 gap-4">
                <div class="flex justify-center border-2 border-transparent bg-[#383c54] px-4 py-16 hover:border-white">
                    <x-fas-plus-circle class="h-8 w-8" fill=#181c34 />
                </div>
                <div class="flex justify-center border-2 border-transparent bg-[#383c54] px-4 py-16 hover:border-white">
                    <x-fas-plus-circle class="h-8 w-8" fill=#181c34 />
                </div>
                <div class="flex justify-center border-2 border-transparent bg-[#383c54] px-4 py-16 hover:border-white">
                    <x-fas-plus-circle class="h-8 w-8" fill=#181c34 />
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span>ATIVIDADE</span>
                <a class="text-xs hover:underline" href="{{ route('profile.games') }}">VER TUDO</a>
            </div>
            <div class="border-b-1 mb-2 border-gray-400 opacity-30"></div>
            <div class="grid grid-cols-4 gap-4">
                @foreach ($ratings as $game)
                    <div class="flex flex-col items-start">
                        <a href="{{ route('games.detail', $game->id) }}">
                            <img src="{{ $game->image }}" alt="{{ $game->name }}"
                                class="h-auto w-full border-2 border-transparent transition hover:scale-105 hover:border-white" />
                        </a>
                        <div class="mt-1 flex flex-row items-center justify-start">
                            <x-ratingStars :score="$game->rating" />

                            @if ($game->liked)
                                <x-fas-heart class="h-5 w-5 text-[#E10000]" />
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <aside class="w-1/3">
            <div class="flex items-center justify-between py-1">
                <span class="text-xs">AVALIAÇÕES</span>
                <a class="text-xs hover:underline" href="#">{{ $gamesTotal }}</a>
            </div>
            <div class="border-b-1 mb-4 border-gray-400 opacity-30"></div>
            <div class="relative mt-8 flex h-[60px] flex-row items-end justify-center gap-1">
                {{-- Ícone inicial --}}
                <span class="flex flex-col items-center justify-end">
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                </span>
                {{-- Barras --}}
                @foreach (['0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0'] as $score)
                    @php
                        $count = $ratingsCount[$score] ?? 0;
                        $heightPercent = $count > 0 ? ($count / $maxCount) * 100 : 0;
                        $numericScore = (float) $score;
                    @endphp

                    <div class="group relative flex flex-col items-center">
                        {{-- Barra --}}
                        <div class="w-[15px] rounded-t-[2px] bg-gray-300 transition-all group-hover:bg-gray-400"
                            style="height: calc(60px * {{ $heightPercent / 100 }})">
                        </div>

                        {{-- Tooltip estilizado --}}
                        @if ($count > 0)
                            <div
                                class="absolute -top-7 z-10 hidden w-max items-center whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white group-hover:flex">
                                <span class="mr-1">{{ $count }} jogo{{ $count === 1 ? '' : 's' }}</span>

                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($numericScore >= $i)
                                        <x-fas-star class="h-2 w-2 text-gray-400" />
                                    @elseif ($numericScore === $i - 0.5)
                                        <x-fas-star-half class="h-2 w-2 text-gray-400" />
                                    @endif
                                @endfor
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- Estrelas finais --}}
                <span class="flex flex-row items-center justify-end">
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                </span>

            </div>
            <div class="border-b-1 mb-4 border-gray-400 opacity-30"></div>
        </aside>
    </div>
@endsection
