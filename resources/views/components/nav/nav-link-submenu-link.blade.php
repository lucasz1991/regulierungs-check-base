@props(['active'])

@php
$classes = ($active ?? false)
            ? 'max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100  md:bg-gray-100 text-primary-900 link-active'
            : 'max-md:text-lg max-md:px-3 max-md:rounded-lg flex items-center md:px-4 py-2 hover:bg-gray-100';
@endphp

<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>