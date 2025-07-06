@extends('layouts.user')

@section('content')
    <div x-data="{ createOpen: false }" class="font-grotesk container mt-4 md:px-0 px-2">
        <div class="mb-4 flex justify-end">
            <button @click="createOpen = true"
                class="flex cursor-pointer items-center gap-2 rounded-sm bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 font-bold text-white shadow-sm shadow-black transition">Criar
                Lista
                <x-fas-plus class="h-4 w-4" />
            </button>

            <div x-show="createOpen" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center"
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
                            <button type="submit"
                                class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2">Criar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if ($gameLists->isEmpty())
            <p>Você ainda não tem nenhuma lista.</p>
        @else
            @foreach ($gameLists as $list)
                <div class="mb-16">
                    <div class="flex items-center justify-between">
                        <h4 class="text-2xl text-white">{{ $list->name }}</h4>

                        <div x-data="{ editOpen: false, deleteOpen: false, editName: '{{ $list->name }}' }" class="flex items-center gap-4">
                            <button @click="editOpen = true"
                                class="cursor-pointer text-white transition hover:text-blue-400" title="Editar lista">
                                <x-fas-edit class="h-5 w-5" />
                            </button>

                            <button @click="deleteOpen = true"
                                class="cursor-pointer text-[#E10000] transition hover:text-red-500" title="Excluir lista">
                                <x-fas-trash class="h-5 w-5" />
                            </button>

                            <div x-show="editOpen" x-transition x-cloak @keydown.escape.window="editOpen = false"
                                class="fixed inset-0 z-50 flex items-center justify-center"
                                style="background-color: rgba(91, 112, 131, 0.4);">
                                <div class="w-96 rounded-lg bg-[#181c34] p-6 uppercase text-white shadow-lg">
                                    <h2 class="mb-4 text-lg font-bold">Editar Nome da Lista</h2>
                                    <form method="POST" action="{{ route('games.myList.update', $list->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <input type="text" name="name" x-model="editName"
                                            class="w-full rounded border border-gray-500 bg-[#101529] px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />

                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" @click="editOpen = false"
                                                class="rounded bg-gray-600 px-4 py-2 hover:bg-gray-700 cursor-pointer uppercase font-bold">Cancelar</button>
                                            <button type="submit"
                                                class="rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 cursor-pointer uppercase font-bold">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div x-show="deleteOpen" x-transition x-cloak @keydown.escape.window="deleteOpen = false"
                                class="fixed inset-0 z-50 flex items-center justify-center"
                                style="background-color: rgba(91, 112, 131, 0.4);">
                                <div class="w-96 rounded-lg bg-[#181c34] p-6 uppercase text-white shadow-lg">
                                    <h2 class="mb-4 text-lg font-bold">Confirmar Exclusão</h2>
                                    <p class="mb-6 text-sm">
                                        Tem certeza que deseja excluir a lista "{{ $list->name }}"?
                                    </p>

                                    <div class="flex justify-end gap-4">
                                        <button @click="deleteOpen = false"
                                            class="rounded bg-gray-600 px-4 py-2 hover:bg-gray-700 cursor-pointer uppercase font-bold">Cancelar</button>
                                        <form action="{{ route('games.myList.destroy', $list->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded bg-[#E10000] px-4 py-2 hover:bg-red-700 cursor-pointer uppercase font-bold">Excluir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-b-1 my-4 border-gray-400 opacity-30"></div>
                    @if ($list->items->isEmpty())
                        <p>Essa lista está vazia.</p>
                    @else
                        <ul class="font-grotesk mt-2 grid grid-cols-2 flex-row gap-4 font-bold md:grid-cols-4">
                            @foreach ($list->items as $item)
                                @if ($item->game_data)
                                    <x-gameListCard :game="$item->game_data" :itemId="$item->id" :listName="$list->name" />
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
