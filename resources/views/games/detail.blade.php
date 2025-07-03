@extends('layouts.user')

@section('title')
    Detalhes do Jogo
@endsection

@section('content')
    <div class="min-h-screen">
        <h1 class="mb-4 text-3xl font-bold text-white">{{ $game['name'] }}</h1>
        <div class="mx-auto mb-16 bg-[#181c34] text-white shadow-md shadow-black">
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="font-grotesk w-full md:w-1/3">
                    <img src="{{ $game['image'] }}" alt="{{ $game['name'] }}" class="mb-4 w-full max-w-md">
                    <p class="m-2 mr-4 w-full max-w-md text-sm text-white">{{ $game['description'] }}</p>
                    <p class="m-2 mr-4 w-full max-w-md text-sm">
                        <span class="text-gray-400">Lançamento:</span>
                        <span class="text-[#04BCFC]">{{ $game['release_date'] }}</span>
                    </p>
                    <p class="m-2 mr-4 w-full max-w-md text-sm">
                        <span class="text-gray-400">Desenvolvedores:</span>
                        <span class="text-[#04BCFC]">{{ implode(', ', $game['developers']) }}</span>
                    </p>
                    <p class="m-2 mr-4 w-full max-w-md text-sm">
                        <span class="text-gray-400">Publicadoras:</span>
                        <span class="text-[#04BCFC]">{{ implode(', ', $game['publishers']) }}</span>
                    </p>

                    @if (!empty($game['genres']))
                        <div class="m-2 mr-4 flex flex-wrap gap-2">
                            @foreach ($game['genres'] as $genre)
                                <span
                                    class="rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-3 py-1 text-sm font-bold uppercase italic text-[#08041c]">
                                    {{ $genre['description'] }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <p
                        class="m-2 mr-4 inline-block whitespace-nowrap bg-[#08041c] px-2 py-2 text-sm font-bold uppercase text-gray-400">
                        {{ $game['price'] }}
                    </p>
                </div>
                @if (!empty($game['screenshots']))
                    <div class="w-full md:w-2/3" x-data="{ mainImage: '{{ $game['screenshots'][0]['path_full'] }}' }">
                        {{-- Imagem principal --}}
                        <div class="mb-4">
                            <img :src="mainImage" alt="Screenshot principal" class="w-full shadow-lg">
                        </div>

                        {{-- Miniaturas --}}
                        <div class="grid grid-cols-5 gap-2">
                            @foreach ($game['screenshots'] as $shot)
                                <img src="{{ $shot['path_thumbnail'] }}" alt="Miniatura"
                                    class="cursor-pointer transition hover:scale-105"
                                    @click="mainImage = '{{ $shot['path_full'] }}'"
                                    :class="mainImage === '{{ $shot['path_full'] }}' ? 'ring-2 ring-[#04BCFC]' : ''">
                            @endforeach
                        </div>

                        <div class="relative flex">
                            <div class="absolute -bottom-12 right-4 translate-y-1/2 rounded-sm bg-[#181c34] p-1">
                                <button @click="addToList()"
                                    class="flex items-center gap-2 bg-gradient-to-r rounded-sm from-[#04BCFC] to-[#2C7CFC] px-4 py-2 text-white transition hover:bg-white/40">
                                    <x-fas-plus class="h-4 w-4" />
                                    <span class="text-sm font-grotesk font-bold uppercase">Adicionar à Lista de Próximo Jogos</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div x-data="ratingComponent({ rating: {{ $userRating ?? 0 }}, liked: {{ $userLiked ?? false ? 'true' : 'false' }} })"
            class="mx-auto flex items-center justify-between space-x-4 rounded-xl bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] p-5 text-white shadow-md shadow-black">

            <!-- Avaliação por estrelas -->
            <div class="flex items-center space-x-1">
                <template x-for="i in 5" :key="i">
                    <div class="relative h-8 w-8">
                        <!-- Fundo escuro sempre visível -->
                        <x-fas-star class="h-8 w-8 text-[#181c34]" />

                        <!-- Botão de clique (estrela inteira) -->
                        <button class="absolute inset-0 z-10 h-full w-full" @click="toggleRating(i)"></button>

                        <!-- Estrela dinâmica por cima -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <template x-if="currentRating >= i">
                                <x-fas-star class="h-8 w-8 text-white" />
                            </template>
                            <template x-if="currentRating >= i - 0.5 && currentRating < i">
                                <x-fas-star-half class="h-8 w-8 text-white" />
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div class="relative flex items-center space-x-16">

                <div class="absolute -bottom-5 right-4 translate-y-1/2 rounded-sm bg-[#181c34] p-1">
                    <button @click="submitRating()"
                        class="font-grotesk cursor pointer rounded bg-[#1DAA2D] px-8 py-2 font-bold uppercase text-white transition hover:bg-[#00c814]">
                        Avaliar
                    </button>
                </div>

                <div class="relative h-8 w-8">
                    <!-- Fundo escuro sempre visível -->
                    <x-fas-heart class="h-8 w-8" fill="#181c34" />

                    <!-- Botão de clique -->
                    <button class="absolute inset-0 z-10 h-full w-full" @click="liked = !liked"></button>

                    <!-- Coração colorido por cima, aparece se liked -->
                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                        <template x-if="liked">
                            <x-fas-heart class="h-8 w-8" fill="#E10000" />
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

<script>
    function ratingComponent({
        rating = 0,
        liked = false
    }) {
        return {
            currentRating: rating,
            liked: liked,
            toggleRating(index) {
                if (this.currentRating >= index) {
                    this.currentRating = index - 0.5;
                } else if (this.currentRating === index - 0.5) {
                    this.currentRating = index - 1;
                } else {
                    this.currentRating = index;
                }
            },
            submitRating() {
                // Aqui você pode fazer um fetch/ajax para salvar a avaliação e o like
                alert(`Avaliação enviada!\nEstrelas: ${this.currentRating}\nGostou: ${this.liked ? 'Sim' : 'Não'}`);
                // Exemplo:
                // fetch('/api/rate-game', { method: 'POST', body: JSON.stringify({ rating: this.currentRating, liked: this.liked }) })
                //   .then(res => res.json())
                //   .then(data => console.log(data));
            }
        };
    }
</script>
