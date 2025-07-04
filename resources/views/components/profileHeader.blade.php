<div class="font-grotesk mt-10 flex">
    <nav class="min-h-[40px] w-full rounded bg-[#181c34] text-center">
        <ul
            class="flex h-full flex-row flex-nowrap items-center justify-center space-x-12 whitespace-nowrap text-gray-400">
            <li class="h-full">
                <a href="{{ route('profile.index') }}"
                    class="{{ request()->routeIs('profile.index') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} flex h-full cursor-pointer items-center border-b-[2px] px-2">
                    Perfil
                </a>
            </li>
            <li class="h-full">
                <a href="{{ route('profile.games') }}"
                    class="{{ request()->routeIs('profile.games') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} flex h-full cursor-pointer items-center border-b-[2px] px-2">
                    Jogos
                </a>
            </li>
            <li class="h-full">
                <a href="{{ route('profile.lists') }}"
                    class="{{ request()->routeIs('profile.lists') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} flex h-full cursor-pointer items-center border-b-[2px] px-2">
                    Listas
                </a>
            </li>
        </ul>
    </nav>
</div>
