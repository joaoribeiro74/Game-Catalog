<header class="body-font bg-white text-[#DCDEDF] shadow">
    <div class="container mx-auto flex max-w-[940px] flex-col items-center justify-center py-5 md:flex-row">

        <a href="{{ auth()->check() ? route('games.index') : route('welcome') }}"
            class="title-font mb-3 flex cursor-pointer items-center font-medium md:mb-0">
            <img class="h-12 w-auto" src="{{ asset('images/logotext.png') }}" alt="logo" />
        </a>

        @unless (request()->routeIs('login') || request()->routeIs('register'))
            <nav class="font-grotesk flex flex-col items-center justify-center text-base font-semibold md:mx-auto md:ml-auto md:flex-row">
                <a href="{{ route('games.index') }}" class="mb-2 text-[#100C1C] hover:text-[#1A9FFF] md:mb-0 md:mr-5">Início</a>
                <a href="{{ route('games.myList') }}" class="mb-2 text-[#100C1C] hover:text-[#1A9FFF] md:mb-0 md:mr-5">Minhas Listas</a>
                <a href="{{ route('profile.index') }}" class="mb-2 text-[#100C1C] hover:text-[#1A9FFF] md:mb-0 md:mr-5">Perfil</a>
            </nav>

            @if (auth()->check())
                <form action="{{ route('logout') }}" method="POST" class="mt-3 md:ml-4 md:mt-0">
                    @csrf
                    <button type="submit"
                        class="font-grotesk inline-flex cursor-pointer items-center rounded border-0 bg-gradient-to-r from-[#E10000] to-red-500 px-3 py-1 text-[10px] font-bold uppercase text-white hover:bg-red-600 focus:outline-none">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="font-grotesk mt-3 inline-flex cursor-pointer items-center rounded border-0 bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-3 py-1 text-[10px] font-bold uppercase focus:outline-none md:ml-4 md:mt-0">
                    Iniciar sessão
                </a>
            @endif
        @endunless
    </div>
</header>
