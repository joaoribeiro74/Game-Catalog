@extends('layouts.user')

@section('content')
    <div class="font-grotesk flex items-center justify-between">
        <div class="flex flex-row items-center justify-center gap-8">
            <x-fas-circle-user class="h-24 w-24" fill=#fff />
            <p class="font-grotesk text-2xl">{{ $user->username }}</p>
            <div class="rounded bg-[#181c34] px-3 py-1 text-xs font-bold uppercase">
                <button>
                    EDITAR PERFIL
                </button>
            </div>
        </div>

        <div class="flex flex-row justify-end space-y-4 font-bold uppercase">
            <div class="flex flex-col items-center text-center">
                <span class="text-lg font-bold">{{ $gamesTotal }}</span>
                <span class="text-xs"class="text-xs">Jogos</span>
            </div>
            <span class="border-r-1 mx-4 border-gray-400 opacity-30"></span>
            <div class="flex flex-col items-center text-center">
                <span class="text-lg font-bold">{{ $gamesThisYear }}</span>
                <span class="text-xs">Este ano</span>
            </div>
        </div>
    </div>
    <div class="font-grotesk mt-10 flex">
        <nav class="min-h-[40px] w-full rounded bg-[#181c34] text-center">
            <ul
                class="flex h-full flex-row flex-nowrap items-center justify-center space-x-12 whitespace-nowrap text-gray-400">
                <li class="h-full">
                    <a href="{{ route('profile.index') }}"
                        class="{{ request()->routeIs('profile.index') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} cursor-pointer flex h-full items-center border-b-[2px] px-2">
                        Perfil
                    </a>
                </li>
                <li class="h-full">
                    <a class="flex h-full items-center border-b-[2px] border-transparent px-2 hover:text-white cursor-pointer">
                        Jogos
                    </a>
                </li>
                <li class="h-full">
                    <a class="flex h-full items-center border-b-[2px] border-transparent px-2 hover:text-white cursor-pointer">
                        Reviews
                    </a>
                </li>
                <li class="h-full">
                    <a class="flex h-full items-center border-b-[2px] border-transparent px-2 hover:text-white cursor-pointer">
                        Quero jogar
                    </a>
                </li>
                <li class="h-full">
                    <a class="flex h-full items-center border-b-[2px] border-transparent px-2 hover:text-white cursor-pointer">
                        Listas
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
