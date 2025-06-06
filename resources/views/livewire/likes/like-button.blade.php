@php
    $sizeClasses = match ($size) {
        'sm' => 'text-xs gap-1 bg-gray-100 px-1 rounded-xl',
        'lg' => 'text-xl gap-3',
        default => 'text-base gap-2',
    };

    $buttonPadding = match ($size) {
        'sm' => 'px-1 py-0.5',
        'lg' => 'px-3 py-2',
        default => 'px-2 py-1',
    };
@endphp

<div 
    x-data="{ liked: @js($liked), count: @js($likesCount) }"
    class="inline-flex items-center {{ $sizeClasses }}"
>
    <button
        wire:click="toggle"
        @click="liked = !liked; liked ? count++ : count--"
        :class="liked ? 'text-red-500' : 'text-gray-600 hover:text-red-500'"
        class="transition  flex content-center justify-between space-x-1 {{ $buttonPadding }}"
        title="Gefällt dir"
        wire:loading.attr="disabled"
    >
        {{-- Zähler --}}
        <span class="mr-1 text-sm" x-text="count" :title="count + ' Person/en gefällt das'"></span>

        {{-- Herz-Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             class="w-5 h-5 transition  fill-none"
             :class="liked ? 'fill-red-500 stroke-red-500' : 'stroke-gray-600 hover:stroke-red-500 fill-none stroke-[1.5]'"
             >
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                     2 6.01 4.01 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44h.74
                     C13.09 5.01 14.76 4 16.5 4
                     18.99 4 21 6.01 21 8.5
                     c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
    </button>
</div>
