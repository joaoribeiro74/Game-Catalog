@extends('layouts.user')

@section('content')
    <div class="font-grotesk uppercase">
        <h4 class="text-lg font-bold">CONFIGURAÇÕES DA CONTA</h4>

        <div class="mt-10 items-center">
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('profile.settings.edit') }}"
                    class="{{ request()->routeIs('profile.settings.edit') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} border-b-1 flex h-full cursor-pointer items-center px-2">
                    PERFIL
                </a>
                <a href="{{ route('profile.settings.password') }}"
                    class="{{ request()->routeIs('profile.settings.password') ? 'border-[#04fc2d] text-white' : 'border-transparent hover:text-white text-gray-400' }} border-b-1 flex h-full cursor-pointer items-center px-2">
                    SENHA
                </a>
            </div>
        </div>
        <div class="border-b-1 mb-2 -translate-y-[1px] border-gray-400 opacity-30"></div>

        <form method="POST" action="{{ route('profile.settings.password') }}" class="flex gap-8">
            @csrf

            <div class="w-1/2">
                <span class="my-6 block font-bold uppercase text-gray-400">MUDAR SENHA</span>

                <div class="mb-6">
                    <label for="current_password" class="block text-xs font-bold uppercase text-gray-400">SENHA ATUAL</label>
                    <input type="password" name="current_password" id="current_password"
                        class="w-full rounded bg-[#24283F] px-3 py-2 pr-10 text-gray-400 shadow-sm shadow-gray-900" />
                    @error('current_password')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-xs font-bold uppercase text-gray-400">NOVA SENHA</label>
                    <input type="password" name="password" id="password"
                        class="w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">
                    <span class="text-[10px] font-semibold text-gray-400">- Senha com mínimo 8 caracteres, incluindo 1 maiúscula, 1 número e 1 caractere especial.</span>
                    @error('password')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-xs font-bold uppercase text-gray-400">CONFIRME NOVA SENHA</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">
                    @error('password_confirmation')
                        <p class="mt-1 text-[10px] text-[#E10000]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="mt-10 w-1/2 cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-3 py-2 font-bold text-white shadow-sm shadow-gray-900">
                        SALVAR MUDANÇAS
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
