@extends('layouts.public')

@section('content')
    <div class="flex min-h-[calc(100vh-260px)]">
        <div class="flex w-full flex-row items-center gap-10">
            <div class="w-1/2">
                <h2 class="font-grotesk mb-2 text-lg font-bold text-white">Explore Agora</h2>
                <h1 class="font-grotesk mb-6 text-6xl font-bold text-white">Descubra, organize e exiba sua coleção de jogos
                </h1>
                <h2 class="font-grotesk mb-2 text-lg font-bold text-white">Assim que você se cadastrar, poderá montar sua
                    biblioteca pessoal, visualizar capas, fazer avaliações e manter tudo organizado em um só lugar.</h2>
            </div>

            <x-flash />
            <div class="w-1/2">
                <image class="w-full" src="{{ asset('images/fr.png') }}" alt="login" />
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center w-full mb-5">
            <a href="{{ route('register') }}" class="font-grotesk cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-8 py-3 text-sm font-bold text-white shadow-sm shadow-black">CADASTRE-SE AGORA</a>
        </div>
@endsection
