@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 left-4 rounded bg-[#1DAA2D] px-4 py-2 text-sm text-white shadow-lg uppercase font-grotesk font-bold">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 left-4 rounded bg-[#E10000] px-4 py-2 text-sm text-white shadow-lg uppercase font-grotesk font-bold">
        {{ session('error') }}
    </div>
@endif

@if(session('info'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 left-4 rounded bg-gray-400 px-4 py-2 text-sm text-white shadow-lg uppercase font-grotesk font-bold">
        {{ session('info') }}
    </div>
@endif
