@props(['score'])

@php
    $fullStars = floor($score);
    $halfStar = fmod($score, 1) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
@endphp

<div class="flex items-center gap-1 text-gray-400">
    @for ($i = 0; $i < $fullStars; $i++)
        <x-fas-star class="h-4 w-4" />
    @endfor

    @if ($halfStar)
        <x-fas-star-half class="h-4 w-4" />
    @endif
</div>

