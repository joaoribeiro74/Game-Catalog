@extends('layouts.public')

@section('content')
    <div class="mt-10 flex flex-col items-center justify-center">
        <div class="w-full max-w-md">
            <h1 class="mb-4 text-left text-2xl font-bold text-white">INICIAR SESSÃO</h1>
            <form action="{{ route('authenticate') }}" method="POST">
                @csrf

                <div class="space-y-4 rounded-xl bg-[#181c34] p-8 py-16 shadow-sm shadow-black">
                    <x-flash />

                    <div class="pb-4">
                        <label for="login" class="block font-bold text-white">E-MAIL OU NOME DE USUÁRIO</label>
                        <input type="text" name="login" id="login"
                            class="w-full rounded bg-[#24283F] px-3 py-2 text-white shadow-sm shadow-gray-900"
                            value="{{ old('login') }}">
                        @error('login')
                            <span class="text-sm text-[#E10000]">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="mb-1 block font-bold text-white">SENHA</label>

                        <input type="password" name="password" id="password"
                            class="w-full rounded bg-[#24283F] px-3 py-2 pr-10 text-white shadow-sm shadow-gray-900">

                        <!-- Botão do olho -->
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 flex -translate-y-[30px] items-center text-white hover:text-[#04BCFC] focus:outline-none">
                            <span id="eye-icon-show" class="block">
                                <x-fas-eye class="h-5 w-5" />
                            </span>
                            <span id="eye-icon-hide" class="hidden">
                                <x-fas-eye-slash class="h-5 w-5" />
                            </span>
                        </button>

                        @error('password')
                            <span class="text-sm text-[#E10000]">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="items-center pt-3 text-center">
                        <button type="submit"
                            class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-8 py-3 text-sm font-bold text-white hover:bg-blue-700">
                            INICIAR SESSÃO
                        </button>
                        <p class="mt-2 flex justify-center gap-1 text-[10px] font-bold uppercase text-white">Não possui
                            conta?<a class="cursor-pointer text-[10px] italic text-white underline hover:underline"
                                href="{{ route('register') }}">Cadastre-se agora</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const eyeShow = document.getElementById('eye-icon-show');
        const eyeHide = document.getElementById('eye-icon-hide');

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
