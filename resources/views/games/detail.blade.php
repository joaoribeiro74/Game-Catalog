@extends('layouts.user')

@section('title')
    Detalhes do Jogo
@endsection

@section('content')
    <div class="min-h-screen">
        <h1 class="mb-4 text-3xl font-bold text-white">{{ $game['name'] }}</h1>
        <div class="mx-auto bg-[#181c34] text-white shadow-md shadow-black">
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
                            @foreach (collect($game['genres'])->take(3) as $genre)
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
                        <div class="mb-4">
                            <img :src="mainImage" alt="Screenshot principal" class="w-full shadow-lg">
                        </div>

                        <div class="mb-10 grid grid-cols-5 gap-2">
                            @foreach ($game['screenshots'] as $shot)
                                <img src="{{ $shot['path_thumbnail'] }}" alt="Miniatura"
                                    class="cursor-pointer transition hover:scale-105"
                                    @click="mainImage = '{{ $shot['path_full'] }}'"
                                    :class="mainImage === '{{ $shot['path_full'] }}' ? 'ring-2 ring-[#04BCFC]' : ''">
                            @endforeach
                        </div>

                    </div>
                @endif
            </div>
        </div>
        <div x-data="{ open: false, selectedLists: [] }" class="relative mb-16 flex justify-end">
            <div class="absolute right-4 -translate-y-1/2 rounded-sm bg-[#181c34] p-1">
                <button @click="open = true"
                    class="flex cursor-pointer items-center gap-2 rounded-sm bg-gradient-to-r from-[#04BCFC] to-[#2C7CFC] px-4 py-2 text-white transition">
                    <x-fas-plus class="h-4 w-4" />
                    <span class="font-grotesk text-sm font-bold uppercase">Adicionar a uma lista</span>
                </button>
            </div>

            <div x-show="open" x-cloak class="font-grotesk fixed inset-0 z-50 flex items-center justify-center"
                style="background-color: rgba(91, 112, 131, 0.4);">
                <div class="w-96 rounded-lg bg-[#181c34] p-6 text-white shadow-lg">
                    <h3 class="mb-4 text-lg font-bold uppercase">Escolha a(s) lista(s)</h3>

                    <form method="POST" action="{{ route('games.myList.addItem') }}">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game['appid'] }}">

                        <div class="space-y-2">
                            @foreach ($gameLists as $list)
                                <label class="block">
                                    <input type="checkbox" name="list_ids[]" value="{{ $list->id }}"
                                        x-model="selectedLists" class="mr-2">
                                    {{ $list->name }}
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-end gap-3 font-bold">
                            <button type="button" @click="open = false"
                                class="cursor-pointer rounded bg-gray-600 px-4 py-2 uppercase hover:bg-gray-700">Cancelar</button>

                            <button type="submit" :disabled="selectedLists.length === 0"
                                class="cursor-pointer rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] px-4 py-2 uppercase disabled:opacity-50">
                                Adicionar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <form x-data="ratingComponent({ rating: {{ $userRating ?? 0 }}, liked: {{ json_encode($userLiked ?? false) }} })" action="{{ route('games.rating.storeOrUpdate') }}" method="POST"
            class="mx-auto flex items-center justify-between space-x-4 rounded-xl bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] p-5 text-white shadow-md shadow-black">
            @csrf

            <input type="hidden" name="game_id" value="{{ $game['appid'] }}">
            <input type="hidden" name="rating" :value="currentRating">
            <input type="hidden" name="liked" :value="liked ? 1 : 0">

            <div class="flex items-center space-x-1">
                <template x-for="i in 5" :key="i">
                    <div class="relative h-8 w-8">
                        <x-fas-star class="h-8 w-8 text-[#181c34]" />

                        <button type="button" class="absolute inset-0 z-10 h-full w-full"
                            @click="toggleRating(i)"></button>

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
                    <button type="submit"
                        class="font-grotesk cursor-pointer whitespace-nowrap rounded bg-[#1DAA2D] px-8 py-2 font-bold uppercase text-white transition hover:bg-[#00c814]">
                        {{ $hasRated ? 'Avaliar novamente' : 'Avaliar' }}
                    </button>
                </div>

                <div class="relative h-8 w-8">
                    <x-fas-heart class="h-8 w-8" fill="#181c34" />

                    <button type="button" class="absolute inset-0 z-10 h-full w-full" @click="liked = !liked"></button>

                    <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                        <template x-if="liked">
                            <x-fas-heart class="h-8 w-8" fill="#E10000" />
                        </template>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
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
            };
        }
    </script>


    <script>
        function gameListHandler(gameId, alreadyInList) {
            return {
                inList: alreadyInList,

                async toggleList() {
                    try {
                        const response = await fetch('{{ route('games.toggleList') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                game_id: gameId
                            })
                        });
                        const data = await response.json();

                        if (data.success) {
                            this.inList = data.added;

                            if (this.inList) {
                                window.location.href = '{{ route('games.myList') }}';
                            }
                        } else {
                            alert('Erro ao atualizar a lista');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Erro na requisição');
                    }
                }
            }
        }
    </script>
@endpush
