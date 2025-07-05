<footer class="w-full bg-[#08041c] mt-4">
    <div class="mx-auto w-full max-w-[940px] p-4 py-4">
        <div class="flex justify-center">
            <div class="mb-6 md:mb-0">
                <a href="{{ auth()->check() ? route('games.index') : route('welcome') }}">
                    <div class="flex space-x-1">
                        <img class="h-10 w-auto" src="{{ asset('images/logotext-gray.png') }}" alt="logo" />
                    </div>
                </a>
            </div>
        </div>
        <hr class="my-4 border-[#BABABA] sm:mx-auto lg:my-4" />
        <div class="flex items-center justify-center align-middle">
            <span class="text text-center text-[#BABABA] dark:text-gray-400">© 2025 <a routerLink="/"
                    class="hover:underline">GameHub™</a>.
            </span>
        </div>
    </div>
</footer>
