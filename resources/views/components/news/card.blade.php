@props(['post'])

@php
    $image = $post->firstNewsImage();
@endphp

<a href="{{ route('news.show', $post) }}"
   wire:navigate
   class="group block overflow-hidden rounded-2xl bg-white/95 shadow-md transition hover:shadow-xl"
>
    @if($image)
        <div class="relative overflow-hidden">
            <img
                src="{{ $image['url'] }}"
                alt="{{ $image['alt'] ?? $post->title }}"
                loading="lazy"
                class="h-48 w-full object-cover transition-transform duration-500 ease-out group-hover:scale-[1.06]"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-black/0 to-black/0 pointer-events-none"></div>
        </div>
    @endif

    <div class="p-4 md:p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-rcgold">
            {{ $post->published_at->format('d.m.Y') }}
        </p>

        <h2 class="mt-2 text-lg font-semibold leading-snug text-gray-900 md:text-xl">
            {!! $post->title !!}
        </h2>

        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-700 md:text-base">
            {{ $post->excerpt_preview }}
        </p>

        <div class="mt-5 flex justify-end">
            <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-medium text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                News lesen
                <i class="fal fa-arrow-right text-xs transition-transform group-hover:translate-x-0.5"></i>
            </span>
        </div>
    </div>
</a>
