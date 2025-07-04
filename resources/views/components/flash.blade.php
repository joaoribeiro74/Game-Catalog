@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="text-center justify-center rounded bg-[#1DAA2D] px-4 py-2 text-white shadow-lg uppercase font-grotesk font-bold">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 10000)"
         class="text-center rounded bg-[#E10000] px-4 py-2 text-white shadow-lg uppercase font-grotesk font-bold">
        {{ session('error') }}
    </div>
@endif
