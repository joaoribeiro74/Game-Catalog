<div class="font-grotesk relative mb-6 flex items-center justify-center md:px-0 px-4 md:justify-end rounded text-white">
    <form class="relative flex w-full max-w-md items-center bg-[#181c34] p-1 rounded-t" x-data="searchGames()" @click.away="closeResults()">
        <input type="search" name="q" placeholder="Buscar jogos..." autocomplete="off" x-model="query"
            @input.debounce.300ms="fetchResults()" @keydown.arrow-down.prevent="selectNext()"
            @keydown.arrow-up.prevent="selectPrev()" @keydown.enter.prevent="goToSelected()"
            class="w-full rounded bg-[#0f1329] p-1 pl-4 pr-10 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2C7CFC]" />

        <button type="button"
            class="absolute right-[6px] rounded bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] p-1 text-white hover:text-gray-400">
            <x-fas-search class="h-5 w-5" />
        </button>

        <ul x-show="results.length > 0" x-transition
            class="absolute top-full z-50 -ml-1 w-full bg-[#181c34] text-white shadow-md shadow-black">
            <template x-for="(result, index) in results.slice(0, 6)" :key="result.appid">
                <li :class="{ 'bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC]': index === selectedIndex }" @mouseenter="selectedIndex = index"
                    @mouseleave="selectedIndex = -1" @click="goTo(result.appid)"
                    class="flex cursor-pointer items-center gap-2 px-2 py-2 hover:bg-[#2C7CFC]">

                    <img :src="`https://cdn.cloudflare.steamstatic.com/steam/apps/${result.appid}/capsule_184x69.jpg`"
                        alt="" class="h-10 w-20 object-cover" />
                    <span x-text="result.name"></span>
                </li>
            </template>
        </ul>
    </form>
</div>

@push('scripts')
    <script>
        function searchGames() {
            return {
                query: '',
                results: [],
                selectedIndex: -1,

                fetchResults() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    fetch(`/games/search/suggest?q=${encodeURIComponent(this.query)}`, {
                            credentials: 'same-origin' 
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log('API response:', data);
                            this.results = data;
                            this.selectedIndex = -1;
                        })
                        .catch(() => {
                            this.results = [];
                        });
                },

                closeResults() {
                    this.results = [];
                    this.selectedIndex = -1;
                },

                selectNext() {
                    if (this.results.length === 0) return;
                    this.selectedIndex = (this.selectedIndex + 1) % this.results.length;
                },

                selectPrev() {
                    if (this.results.length === 0) return;
                    this.selectedIndex = (this.selectedIndex - 1 + this.results.length) % this.results.length;
                },

                goToSelected() {
                    if (this.selectedIndex >= 0 && this.selectedIndex < this.results.length) {
                        this.goTo(this.results[this.selectedIndex].appid);
                    }
                },

                goTo(appid) {
                    window.location.href = `/games/${appid}`;
                }
            }
        }
    </script>
@endpush
