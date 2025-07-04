@extends('layouts.user')

@section('content')
    <div class="font-grotesk uppercase">
        <h4 class="text-lg font-bold">CONFIGURAÇÕES DA CONTA</h4>

        <div class="mt-10 items-center">
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('profile.settings.edit') }}"
                    class="{{ request()->routeIs('profile.settings.edit') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} flex h-full cursor-pointer items-center border-b-1 px-2">
                    PERFIL
                </a>
                <a href="{{ route('profile.settings.password') }}"
                    class="{{ request()->routeIs('profile.settings.password') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} flex h-full cursor-pointer items-center border-b-1 px-2">
                    SENHA
                </a>
            </div>
        </div>
        <div class="border-b-1 mb-2 border-gray-400 opacity-30 -translate-y-[1px]"></div>
    </div>
@endsection
