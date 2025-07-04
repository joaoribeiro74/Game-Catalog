@extends('layouts.user')

@section('content')
<div x-data="{ editOpen: false, deleteOpen: false, editName: '{{ $list->name }}' }" class="font-grotesk container mt-4">
    <div class="mb-4 flex items-center justify-between">
        <h4 class="text-2xl text-white">{{ $list->name }}</h4>
        <div class="flex items-center gap-4">
            <button @click="editOpen = true" class="cursor-pointer text-white transition hover:text-blue-400" title="Editar lista">
                <x-fas-edit class="h-5 w-5" />
            </button>

            <button @click="deleteOpen = true" class="cursor-pointer text-[#E10000] transition hover:text-red-500" title="Excluir lista">
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
                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}" />
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

            <!-- Modal Confirmar Exclusão -->
            <div x-show="deleteOpen" x-transition x-cloak @keydown.escape.window="deleteOpen = false"
                class="fixed inset-0 z-50 flex items-center justify-center"
                style="background-color: rgba(91, 112, 131, 0.4);">
                <div class="w-96 rounded-lg bg-[#181c34] p-6 uppercase text-white shadow-lg">
                    <h2 class="mb-4 text-lg font-bold">Confirmar Exclusão</h2>
                    <p class="mb-6 text-sm">Tem certeza que deseja excluir a lista "{{ $list->name }}"?</p>

                    <div class="flex justify-end gap-4">
                        <button @click="deleteOpen = false"
                            class="rounded bg-gray-600 px-4 py-2 hover:bg-gray-700 cursor-pointer uppercase font-bold">Cancelar</button>
                        <form action="{{ route('games.myList.destroy', $list->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="redirect_to" value="{{ route('profile.index') }}" />
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
        <ul class="font-grotesk mt-2 grid grid-cols-2 flex-row gap-4 font-bold lg:grid-cols-4">
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
@endsection
