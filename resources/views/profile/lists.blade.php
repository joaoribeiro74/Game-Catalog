@extends('layouts.user')

@section('content')
    <x-profileHeader />

    <div class="font-grotesk">
        <div class="mt-10 items-center justify-between">
            <div class="flex items-center justify-between">
                <span>MINHAS LISTAS</span>
                <div class="flex flex-row gap-1">
                    <h4 class="text-xs font-normal uppercase">Só aparecem listas que possuem pelo menos um jogo</h4>
                </div>
            </div>
        </div>
        <div class="border-b-1 mb-2 border-gray-400 opacity-30"></div>
        @if ($lists->isEmpty())
            <div class="mt-8 text-center text-gray-400">
                Você ainda não tem nenhuma lista com jogos.
            </div>
        @else
            <div class="mt-6 grid grid-cols-2 gap-8">
                @foreach ($lists as $list)
                    <div>
                        <h3 class="mb-1 text-lg font-bold group-hover:underline">{{ $list->name }}</h3>

                        <a href="{{ route('profile.lists.show', $list->id) }}" class="group block w-max cursor-pointer">
                            <div class="relative flex items-center overflow-visible">
                                @foreach ($list->games->take(5) as $index => $game)
                                    <div
                                        class="z-{{ 10 - $index }} {{ $index > 0 ? 'translate-x-1/2 -ml-16' : '' }} relative">
                                        <img src="{{ $game['image'] ?? '' }}" alt="{{ $game['name'] ?? 'Jogo sem nome' }}"
                                            class="h-48 w-32 object-cover shadow-md shadow-black transition duration-200 group-hover:scale-105" />
                                    </div>
                                @endforeach
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
