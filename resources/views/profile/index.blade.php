@extends('layouts.user')

@section('content')
    <div class="font-grotesk flex md:flex-row flex-col md:items-center justify-between">
        <div class="flex flex-row items-center md:justify-center md:px-0 px-2 md:gap-8 md:pb-0 pb-4 gap-4">
            @if ($user->attachment)
                <img src="{{ asset('storage/' . $user->attachment->filepath) }}" alt="Avatar"
                    class="h-24 w-24 rounded-full object-cover" />
            @else
                <x-fas-circle-user class="h-24 w-24" fill="#fff" />
            @endif
            <div class="flex flex-col justify-center gap-1 text-start">
                @if (!empty($user->display_name))
                    <p class="font-grotesk text-md md:text-xl font-semibold">{{ $user->display_name }}</p>
                @endif
                <p class="font-grotesk text-sm md:text-md">{{ $user->username }}</p>
            </div>
            <div class="rounded bg-[#181c34] px-3 py-1 text-xs font-bold uppercase">
                <a href="{{ route('profile.settings.edit') }}">
                    EDITAR PERFIL
                </a>
            </div>
        </div>

        <div class="flex flex-row md:justify-end justify-center space-y-4 font-bold uppercase">
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
    <div class="font-grotesk mt-10 flex flex-col md:flex-row gap-8 md:px-0 px-2">
        <div class="w-full md:w-2/3">
            <div class="flex items-center justify-between">
                <span>ATIVIDADE</span>
                <a class="text-xs hover:underline" href="{{ route('profile.games') }}">VER TUDO</a>
            </div>
            <div class="border-b-1 mb-2 border-gray-400 opacity-30"></div>
            <div class="grid md:grid-cols-3 grid-cols-2 gap-4">
                @foreach ($ratings->take(6) as $game)
                    <div class="flex flex-col items-start">
                        <a href="{{ route('games.detail', $game->id) }}">
                            <img src="{{ $game->image }}" alt="{{ $game->name }}"
                                class="h-auto w-full border-2 border-transparent transition hover:scale-105 hover:border-white" />
                        </a>
                        <div class="mt-1 flex flex-row items-center justify-start gap-2">
                            <x-ratingStars :score="$game->rating" />

                            @if ($game->liked)
                                <x-fas-heart class="h-5 w-5 text-[#E10000]" />
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <aside class="w-full md:w-1/3">
            <div class="flex items-center justify-between py-1">
                <span class="text-xs">AVALIAÇÕES</span>
                <a class="text-xs hover:underline" href="{{ route('profile.games') }}">{{ $gamesTotal }}</a>
            </div>
            <div class="border-b-1 mb-4 border-gray-400 opacity-30"></div>
            <div class="relative mt-8 flex h-[60px] flex-row items-end justify-center gap-1">

                <span class="flex flex-col items-center justify-end">
                    <x-fas-star class="h-3 w-3 text-gray-400" />
                </span>
                @foreach (['0.5', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0'] as $score)
                    @php
                        $count = $ratingsCount[$score] ?? 0;
                        $heightPercent = $count > 0 ? ($count / $maxCount) * 100 : 0;
                        $numericScore = (float) $score;
                    @endphp

                    <div class="group relative flex flex-col items-center">
                        <div class="w-[15px] rounded-t-[2px] bg-gray-300 transition-all group-hover:bg-gray-400"
                            style="height: calc(60px * {{ $heightPercent / 100 }})">
                        </div>

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
