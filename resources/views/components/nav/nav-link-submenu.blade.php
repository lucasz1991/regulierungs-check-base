@props(['active'])

@php

$classes = ($active ?? false)
            ? 'sub-menu relative md:px-1 pt-1 border-b  text-sm font-medium leading-5  focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out  md:border-primary-500 text-gray-900  link-active'
            : 'sub-menu relative md:px-1 pt-1 border-b  text-sm font-medium leading-5  focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out  text-gray-500 hover:text-gray-700 border-transparent';
@endphp

<div x-data="{ open: false }" @click.away="open = false" {{ $attributes->merge(['class' => $classes]) }} >
    <div class="sub-menu-link flex items-center cursor-pointer max-md:text-lg max-md:px-3" @click="open = !open">
        {{ $title ?? '' }}
        <svg class="w-4 h-4 ml-2  transition-all ease-in duration-200" :class="open ? 'transform rotate-180' : ''" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m19 9-7 7-7-7"/>
        </svg>
    </div>
    <div x-show="open" x-transition x-cloak class="md:bg-white md:right-0 md:mt-6 mt-3  z-30" :class="$store.nav.isMobile ? 'relative' : 'absolute rounded-lg shadow w-44 z-40 overflow-hidden'">
        <ul class=" max-md:space-y-4 max-md:pt-4 text-sm text-gray-500 hover:text-gray-700" :class="$store.nav.isMobile ? '' : 'divide-y divide-gray-100'">
        {{ $content ?? '' }}
        </ul>
    </div>
</div>
