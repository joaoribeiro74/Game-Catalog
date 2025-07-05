@extends('layouts.public')

@props(['action'])

@section('content')
    <div class="font-grotesk flex flex-col items-center justify-center">
        <div class="w-full max-w-md">
            <h1 class="mb-4 text-left text-2xl font-bold text-white">CADASTRAR-SE</h1>
            <form action="{{ $action }}" method="POST">
                @csrf

                <div class="space-y-4 rounded-xl bg-[#181c34] p-8 py-16 shadow-sm shadow-black">
                    <div class="">
                        <label for="email" class="block font-bold uppercase text-white">E-mail</label>
                        <input type="text" name="email" id="email"
                            class="w-full rounded bg-[#24283F] px-3 py-2 text-white shadow-sm shadow-gray-900"
                            value="{{ old('email') }}">
                        <div class="min-h-[8px]">
                            @error('email')
                                <span class="text-sm text-[#E10000]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="">
                        <label for="username" class="block font-bold uppercase text-white">Nome de usuário</label>
                        <input type="text" name="username" id="username"
                            class="w-full rounded bg-[#24283F] px-3 py-2 text-white shadow-sm shadow-gray-900"
                            value="{{ old('username') }}">
                        <div class="min-h-[8px]">
                            @error('username')
                                <span class="text-sm text-[#E10000]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="relative">
                        <label for="password" class="block font-bold text-white">SENHA</label>

                        <input type="password" name="password" id="password"
                            class="w-full rounded bg-[#24283F] px-3 py-2 pr-10 text-white shadow-sm shadow-gray-900">

                        <button type="button"
                            onclick="togglePassword('password', 'eye-icon-show-password', 'eye-icon-hide-password')"
                            class="absolute right-3 flex -translate-y-[30px] items-center text-white hover:text-[#04BCFC] focus:outline-none">
                            <span id="eye-icon-show-password" class="block">
                                <x-fas-eye class="h-5 w-5" />
                            </span>
                            <span id="eye-icon-hide-password" class="hidden">
                                <x-fas-eye-slash class="h-5 w-5" />
                            </span>
                        </button>
                        <span class="text-[10px] font-semibold text-gray-400">- Senha com mínimo 8 caracteres, incluindo 1
                            maiúscula, 1 número e 1 caractere especial.</span>

                        <div class="min-h-[8px]">
                            @error('password')
                                <span class="text-sm text-[#E10000]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <div class="relative">
                        <label for="password_confirmation" class="block font-bold text-white">CONFIRME A SENHA</label>

                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full rounded bg-[#24283F] px-3 py-2 pr-10 text-white shadow-sm shadow-gray-900">

                        <button type="button"
                            onclick="togglePassword('password_confirmation', 'eye-icon-show-confirm', 'eye-icon-hide-confirm')"
                            class="absolute right-3 flex -translate-y-[30px] items-center text-white hover:text-[#04BCFC] focus:outline-none">
                            <span id="eye-icon-show-confirm" class="block">
                                <x-fas-eye class="h-5 w-5" />
                            </span>
                            <span id="eye-icon-hide-confirm" class="hidden">
                                <x-fas-eye-slash class="h-5 w-5" />
                            </span>
                        </button>

                        <div class="min-h-[1rem]">
                            @error('password_confirmation')
                                <span class="text-sm text-[#E10000]">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-3 text-center">
                        <button type="submit"
                            class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-8 py-3 text-sm font-bold text-white hover:bg-blue-700">
                            CADASTRAR
                        </button>
                        <p class="mt-2 text-[10px] font-bold uppercase text-white"> Ja possui uma conta? <a
                                class="text-[10px] italic text-white underline hover:underline"
                                href="{{ route('login') }}">Login</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePassword(inputId, showIconId, hideIconId) {
            const input = document.getElementById(inputId);
            const eyeShow = document.getElementById(showIconId);
            const eyeHide = document.getElementById(hideIconId);

            if (input.type === 'password') {
                input.type = 'text';
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            }
        }
    </script>
@endpush
