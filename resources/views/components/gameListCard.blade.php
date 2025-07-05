@props(['game', 'itemId', 'listName'])

<div x-data="{ open: false }">
    <li
        class="relative bg-[#181c34] text-sm uppercase text-white shadow-sm shadow-black transition duration-300 will-change-transform hover:z-10 hover:scale-110">
        <img src="{{ $game['image'] }}" class="w-full object-cover" alt="{{ $game['name'] }}" />

        <div class="flex items-center justify-between gap-2 p-2">
            <span class="truncate">{{ $game['name'] }}</span>

            <button @click="open = true" class="cursor-pointer" title="Remover">
                <x-fas-trash class="h-4 w-4" fill="#E10000" />
            </button>
        </div>
    </li>

    <div x-show="open" x-transition x-cloak @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-[#181c34] p-6 uppercase text-white shadow-lg font-grotesk font-bold">
            <h2 class="mb-4 text-lg font-bold">Confirmar exclusão</h2>
            <p class="mb-6 text-sm font-semibold">
                Remover {{ $game['name'] }}
                da lista "{{ $listName }}"?
            </p>

            <div class="flex justify-end gap-4">
                <button @click="open = false" class="cursor-pointer rounded bg-gray-600 px-4 py-2 hover:bg-gray-700">
                    Cancelar
                </button>

                <form action="{{ route('games.myList.remove', $itemId) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cursor-pointer rounded bg-[#E10000] px-4 py-2 hover:bg-red-700">
                        Remover
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
