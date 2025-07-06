@php
    $imageSize = match ($size ?? 'md') {
        'sm' => 'h-24 text-sm',
        'md' => 'h-36 text-base',
        'lg' => 'h-48 text-lg',
        default => 'h-48 text-base',
    };

    $titleSize = match ($size ?? 'md') {
        'sm' => 'text-xs',
        'md' => 'text-base',
        'lg' => 'text-lg',
        default => 'text-base',
    };

    $priceSize = match ($size ?? 'md') {
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        default => 'px-3 py-2 text-sm',
    };

    $buttonSize = match ($size ?? 'md') {
        'sm' => 'px-2 py-1 text-[10px]',
        'md' => 'px-3 py-2 text-sm',
        'lg' => 'px-4 py-2 text-base',
        default => 'px-3 py-2 text-sm',
    };
@endphp

<div class="relative z-0 transform bg-[#181c34] shadow-md shadow-black transition duration-300 hover:scale-110 hover:z-10 will-change-transform rounded">
    <img src="{{ $image }}" alt="{{ $name }}" class="w-full object-cover rounded-t {{ $imageSize }}">

    <div class="md:p-4 p-2 flex flex-col justify-between gap-2">
        <h2 class="font-grotesk font-bold uppercase text-white whitespace-nowrap overflow-hidden overflow-ellipsis {{ $titleSize }}">{{ $name }}</h2>
        <div class="flex justify-between items-center">
            <p class="font-grotesk font-semibold uppercase bg-[#08041c] text-gray-400 whitespace-nowrap {{ $priceSize }}">{{ $price }}</p>
            <a href="{{ $link }}" class="font-grotesk font-semibold uppercase text-white bg-gradient-to-r from-[#2C7CFC] to-[#04BCFC] rounded hover:underline {{ $buttonSize }}">
                Ver detalhes
            </a>
        </div>
    </div>
</div>
