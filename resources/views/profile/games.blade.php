@extends('layouts.user')

@section('content')
    <x-profileHeader />
    <div x-data="{ columns: 6 }" class="font-grotesk">
        <div class="mt-10">
            <div class="flex items-center justify-between">
                <span>ATIVIDADE</span>
                <div class="flex flex-row gap-1">
                    <x-fas-th-large class="h-4 w-4 cursor-pointer hover:text-white" @click="columns = 6" />
                    <x-fas-square class="h-4 w-4 cursor-pointer hover:text-white" @click="columns = 4" />
                </div>
            </div>
            <div class="border-b-1 mb-2 border-gray-400 opacity-30"></div>
            <div x-bind:class="`grid gap-4 ${columns === 6 ? 'grid-cols-6' : 'grid-cols-4'}`">
                @foreach ($ratings as $game)
                    <div class="flex flex-col items-start">
                        <a href="{{ route('games.detail', $game->id) }}">
                            <img src="{{ $game->image }}" alt="{{ $game->name }}"
                                class="h-auto w-full border-2 border-transparent transition hover:scale-105 hover:border-white" />
                        </a>
                        <div class="mt-1 flex w-full flex-row items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <x-ratingStars :score="$game->rating" />
                                @if ($game->liked)
                                    <x-fas-heart class="h-4 w-4 text-[#E10000]" />
                                @endif
                            </div>

                            <template x-if="columns === 4">
                                <span class="whitespace-nowrap text-xs font-semibold text-gray-400">
                                    {{ $game->date }} @if ($game->year)
                                        {{ $game->year }}
                                    @endif
                                </span>
                            </template>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
