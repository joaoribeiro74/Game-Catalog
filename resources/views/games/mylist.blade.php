@extends('layouts.user')

@section('content')
    <div x-data="{ createOpen: false }" class="font-grotesk container mt-4">
        <div class="flex justify-end">
            <button @click="createOpen = true"
                class="flex cursor-pointer items-center gap-2 rounded-sm bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 font-bold text-white shadow-sm shadow-black transition">Criar
                Lista
                <x-fas-plus class="h-4 w-4" />
            </button>

            <div x-show="createOpen" x-transition x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center"
                style="background-color: rgba(91, 112, 131, 0.4);">
                <div class="w-96 rounded-lg bg-[#181c34] p-6 uppercase text-white shadow-lg">
                    <h2 class="mb-4 text-lg font-bold">Nova Lista</h2>
                    <form method="POST" action="{{ route('games.myList.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="mb-1 block text-sm font-semibold">Nome da lista</label>
                            <input type="text" name="name" id="name"
                                class="w-full rounded border border-gray-500 bg-[#101529] px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="createOpen = false"
                                class="cursor-pointer rounded bg-gray-600 px-4 py-2 hover:bg-gray-700">Cancelar</button>
                            <button type="submit" class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2">Criar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($gameLists->isEmpty())
            <p>Você ainda não tem nenhuma lista.</p>
        @else
            @foreach ($gameLists as $list)
                <div class="mb-4">
                    <h4 class="text-2xl text-white">{{ $list->name }}</h4>
                    <div class="border-b-1 my-4 border-gray-400 opacity-30"></div>

                    @if ($list->items->isEmpty())
                        <p>Essa lista está vazia.</p>
                    @else
                        <ul class="font-grotesk mt-2 flex grid-cols-4 flex-row gap-4 font-bold">
                            @foreach ($list->items as $item)
                                @if ($item->game_data)
                                    <li x-data="{ open: false }"
                                        class="relative bg-[#181c34] text-sm uppercase text-white shadow-sm shadow-black transition duration-300 will-change-transform hover:z-10 hover:scale-110">
                                        <img src="{{ $item->game_data['image'] }}" class="w-full" width="100">

                                        <div class="flex items-center justify-between gap-2 p-2">
                                            <span class="truncate">{{ $item->game_data['name'] }}</span>

                                            <button @click="open = true" class="cursor-pointer" title="Remover">
                                                <x-fas-trash class="h-4 w-4" fill="#E10000" />
                                            </button>
                                        </div>
                                        <div x-show="open" x-transition x-cloak @keydown.escape.window="open = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-[#181c34] bg-opacity-50">
                                            <div class="w-80 bg-[#181c34] p-6 uppercase text-white">
                                                <h2 class="mb-4 text-lg font-bold">Confirmar exclusão</h2>
                                                <p class="mb-6 text-sm">Remover {{ $item->game_data['name'] }} da
                                                    lista?</p>

                                                <div class="flex justify-end gap-4">
                                                    <button @click="open = false"
                                                        class="cursor-pointer rounded bg-gray-600 px-4 py-2 hover:bg-gray-700">
                                                        Cancelar
                                                    </button>

                                                    <form action="{{ route('games.myList.remove', $item->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="cursor-pointer rounded bg-[#E10000] px-4 py-2 hover:bg-red-700">
                                                            Remover
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    <li>Jogo não encontrado</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
@endsection
