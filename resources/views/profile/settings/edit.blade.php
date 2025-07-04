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
        <div class="flex gap-8">
            <div class="w-1/2">
                <span class="my-6 block font-bold uppercase text-gray-400">Perfil</span>
                <label for="username" class="block text-xs font-bold uppercase text-gray-400">NOME DE USUÁRIO</label>
                <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}"
                    class="mb-6 w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">

                <label for="display_name" class="block text-xs font-bold uppercase text-gray-400">NOME DE EXIBIÇÃO</label>
                <input type="text" name="display_name" id="display_name"
                    value="{{ old('display_name', auth()->user()->display_name) }}"
                    class="mb-6 w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">

                <label for="email" class="block text-xs font-bold uppercase text-gray-400">E-MAIL</label>
                <input type="text" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                    class="mb-6 w-full rounded bg-[#24283F] px-3 py-2 text-gray-400 shadow-sm shadow-gray-900">
                <div class="flex justify-center">
                    <button
                        class="mt-10 w-1/2 cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-3 py-2 font-bold text-white shadow-sm shadow-gray-900">
                        SALVAR MUDANÇAS
                    </button>
                </div>
            </div>
            <div class="w-1/2 bg-green-200 p-2">
                <div class="flex flex-col items-center">
                    <span class="my-6 block font-bold uppercase text-gray-400">IMAGEM DE PERFIL</span>
                    <div class="flex flex-col items-center gap-4">

                        @if (auth()->user()->attachment)
                            <img src="{{ asset('storage/attachments/' . auth()->user()->attachment->filepath) }}"
                                alt="Avatar" class="h-24 w-24 rounded-full" />

                            <form action="{{ route('profile.settings.destroy') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="cursor-pointer rounded bg-gradient-to-r from-red-500 to-[#E10000] px-4 py-2 font-bold uppercase text-white shadow-sm shadow-black">Remover
                                    Avatar</button>
                            </form>
                        @else
                            <x-fas-circle-user class="h-24 w-24" fill="#fff" />

                            <form method="POST" action="{{ route('profile.settings.store') }}"
                                enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <label for="file"
                                    class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 font-bold uppercase text-white shadow-sm shadow-black">
                                    Envie seu avatar
                                </label>
                                <input id="file" name="file" type="file" accept=".jpg,.jpeg,.png"
                                    onchange="this.form.submit()" class="hidden">
                            </form>
                        @endif

                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="mt-6 block font-bold uppercase text-gray-400">JOGOS FAVORITOS</span>
                    <div class="mb-8 grid grid-cols-3 gap-4">
                        <div
                            class="flex justify-center border-2 border-transparent bg-[#383c54] px-4 py-16 hover:border-white">
                            <x-fas-plus-circle class="h-8 w-8" fill=#181c34 />
                        </div>
                        <div
                            class="flex justify-center border-2 border-transparent bg-[#383c54] px-4 py-16 hover:border-white">
                            <x-fas-plus-circle class="h-8 w-8" fill=#181c34 />
                        </div>
                        <div
                            class="flex justify-center border-2 border-transparent bg-[#383c54] px-4 py-16 hover:border-white">
                            <x-fas-plus-circle class="h-8 w-8" fill=#181c34 />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
