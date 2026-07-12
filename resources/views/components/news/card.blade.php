@props(['post'])

@php
    $image = $post->firstNewsImage();
    $category = $post->newsCategory;
@endphp

<a href="{{ route('news.show', $post) }}"
   wire:navigate
   aria-label="{{ strip_tags($post->title) }} lesen"
   class="group grid min-h-[11.5rem] grid-cols-[35%_minmax(0,1fr)] overflow-hidden rounded-[1.35rem] border border-white/80 bg-white shadow-[0_14px_38px_-24px_rgba(15,23,42,0.7)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_22px_48px_-24px_rgba(15,23,42,0.72)] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-secondary sm:min-h-[13.5rem] sm:grid-cols-[34%_minmax(0,1fr)] sm:rounded-[1.6rem] lg:min-h-[15rem] lg:grid-cols-[30%_minmax(0,1fr)]"
>
    <div class="relative min-h-full overflow-hidden bg-slate-100">
        @if($image)
            <img
                src="{{ $image['url'] }}"
                alt="{{ $image['alt'] ?? $post->title }}"
                loading="lazy"
                class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.045]"
            >
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-r from-black/10 via-transparent to-black/5"></div>
        @else
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-primary via-primary-light to-secondary">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-2xl text-white/85 shadow-inner backdrop-blur-sm sm:h-20 sm:w-20 sm:text-3xl">
                    <i class="fal fa-newspaper" aria-hidden="true"></i>
                </span>
            </div>
        @endif
    </div>

    <div class="flex min-w-0 flex-col p-3 sm:p-5 lg:px-8 lg:py-7">
        @if($category)
            <span
                class="news-category-badge inline-flex w-max max-w-full items-center gap-1.5 truncate rounded-md px-2 py-1 text-[0.58rem] font-bold uppercase leading-none tracking-wide text-white shadow-sm sm:px-2.5 sm:text-[0.68rem]"
                style="background-color: {{ $category->color }};"
            >
                @if($category->icon)
                    <i class="fal {{ $category->icon }} text-[0.55rem] sm:text-[0.65rem]" aria-hidden="true"></i>
                @endif
                {{ $category->name }}
            </span>
        @else
            <span class="news-category-badge inline-flex w-max rounded-md bg-primary px-2 py-1 text-[0.58rem] font-bold uppercase leading-none tracking-wide text-white sm:px-2.5 sm:text-[0.68rem]">
                News
            </span>
        @endif

        <h2 class="mt-2 line-clamp-3 text-[0.95rem] font-bold leading-[1.2] tracking-tight text-slate-950 transition-colors duration-300 group-hover:text-primary sm:mt-3 sm:text-xl lg:text-2xl">
            {{ strip_tags((string) $post->title) }}
        </h2>

        <p class="mt-2 line-clamp-2 text-[0.68rem] leading-relaxed text-slate-600 sm:mt-2.5 sm:text-sm lg:max-w-3xl lg:text-base">
            {{ $post->excerpt_preview }}
        </p>

        <div class="mt-auto flex min-w-0 items-center gap-1.5 pt-3 text-[0.58rem] font-medium text-slate-500 sm:gap-3 sm:pt-4 sm:text-xs lg:text-sm">
            <span class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap">
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-slate-300 text-slate-700 sm:h-7 sm:w-7">
                    <i class="fal fa-clock text-[0.58rem] sm:text-xs" aria-hidden="true"></i>
                </span>
                {{ $post->reading_time_minutes }} Min. Lesezeit
            </span>

            @if($post->published_at)
                <time class="ml-auto shrink-0 whitespace-nowrap text-right" datetime="{{ $post->published_at->toDateString() }}">
                    <span class="sm:hidden">{{ $post->published_at->format('d.m.Y') }}</span>
                    <span class="hidden sm:inline">{{ $post->published_at->translatedFormat('j. F Y') }}</span>
                </time>
            @else
                <span class="ml-auto shrink-0 rounded-full bg-amber-100 px-2 py-1 text-[0.55rem] font-bold uppercase tracking-wide text-amber-900 sm:text-[0.65rem]">
                    Entwurf
                </span>
            @endif

            <span class="ml-0.5 inline-flex h-6 w-5 shrink-0 items-center justify-center text-slate-800 transition-transform duration-300 group-hover:translate-x-1 group-hover:text-secondary sm:h-8 sm:w-8">
                <i class="fal fa-chevron-right text-sm sm:text-base" aria-hidden="true"></i>
            </span>
        </div>
    </div>
</a>
