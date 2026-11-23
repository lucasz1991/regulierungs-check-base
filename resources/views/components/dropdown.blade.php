@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-[var(--surface)]', 'dropdownClasses' => ''])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'right':
        $alignmentClasses = 'origin-top-right right-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'none':
    case 'false':
        $alignmentClasses = '';
        break;
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}

switch ($width) {
    case '48':
        $widthClass = 'w-48';
        break;
    default:
        $widthClass = '';
        break;
}
@endphp


<div class="relative" x-data="{ open: false }" x-cloak @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }} 
    </div>

    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $widthClass }} rounded-md shadow-lg {{ $alignmentClasses }} {{ $dropdownClasses }}"
        style="display: none;"
        @click="open = false">
        <div class="rounded-md border border-[var(--line)] {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
