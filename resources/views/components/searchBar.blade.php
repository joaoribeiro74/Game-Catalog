<div class="font-grotesk relative mb-6 flex w-full items-center justify-between rounded bg-[#181c34] p-1 text-white">

    <div class="relative flex gap-6 py-1">
        <a href="#" class="pl-4 hover:text-[#04BCFC] hover:underline font-semibold">Novidades</a>

        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
            <a href="#" class="hover:text-[#04BCFC] hover:underline py-2 font-semibold">Categorias</a>
            <div x-show="open" x-transition
                class="absolute left-0 z-10 mt-2 w-40 shadow-md shadow-black bg-gradient-to-b from-[#585c6c] to-[#2c2c3c] p-2">
                <a href="#" class="inline-block mx-2 border-b-[2px] border-transparent hover:text-[#04BCFC] hover:border-[#04BCFC]">Ação</a>
                <a href="#" class="inline-block mx-2 border-b-[2px] border-transparent hover:text-[#04BCFC] hover:border-[#04BCFC]">RPG</a>
                <a href="#" class="inline-block mx-2 border-b-[2px] border-transparent hover:text-[#04BCFC] hover:border-[#04BCFC]">Indie</a>
            </div>
        </div>

        <a href="{{ route('games.myList') }}" class="hover:text-[#04BCFC] hover:underline font-semibold">Quero jogar</a>
    </div>

    <form action="" method="GET" class="relative flex w-full max-w-md items-center">
        <input type="search" name="q" placeholder="Buscar jogos..."
            class="w-full rounded bg-[#0f1329] p-1 pl-4 pr-10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2C7CFC]"
            value="{{ request('q') }}" />
        <button type="submit"
            class="absolute right-[2px] rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] p-1 text-white hover:text-gray-400">
            <x-fas-search class="h-5 w-5" />
        </button>
    </form>
</div>
