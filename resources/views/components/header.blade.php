<header class="body-font bg-white text-[#DCDEDF] shadow">
    <div
        class="{{ request()->routeIs('login') || request()->routeIs('register') ? 'justify-center' : 'flex-wrap md:flex-row justify-between' }} container mx-auto flex max-w-[940px] items-center justify-between py-5">

        <a href="{{ auth()->check() ? route('games.index') : route('welcome') }}"
            class="title-font flex cursor-pointer items-center font-medium">
            <img class="h-12 w-auto" src="{{ asset('images/logotext.png') }}" alt="logo" />
        </a>

        @unless (request()->routeIs('login') || request()->routeIs('register'))
            <nav class="font-grotesk mx-auto flex flex-wrap items-center justify-center text-base font-semibold md:ml-auto">
                <a href="{{ route('games.index') }}" class="mr-5 text-[#100C1C] hover:text-[#1A9FFF]">Início</a>
                <a href="{{ route('games.myList') }}" class="mr-5 text-[#100C1C] hover:text-[#1A9FFF]">Minhas Listas</a>
                <a href="#" class="mr-5 text-[#100C1C] hover:text-[#1A9FFF]">Avaliações</a>
                <a href="{{ route('profile.index') }}" class="mr-5 text-[#100C1C] hover:text-[#1A9FFF]">Perfil</a>
            </nav>

            @if (auth()->check())
                <form action="{{ route('logout') }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit"
                        class="font-grotesk mt-4 inline-flex cursor-pointer items-center rounded border-0 bg-gradient-to-r from-[#E10000] to-red-500 px-3 py-1 text-[10px] font-bold uppercase text-white hover:bg-red-600 focus:outline-none md:mt-0">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="font-grotesk ml-4 mt-4 inline-flex cursor-pointer items-center rounded border-0 bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-3 py-1 text-[10px] font-bold uppercase focus:outline-none md:mt-0">
                    Iniciar sessão
                </a>
            @endif
        @endunless

    </div>
</header>
