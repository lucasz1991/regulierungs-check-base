@php
    $images = $post->newsImages();
    $heroImage = $post->firstNewsImage();
    $secondaryImages = array_slice($images, 1);
    $category = $post->newsCategory;
    $project = $post->pagebuilderProject;
    $layout = in_array($post->layout, \App\Models\Post::NEWS_LAYOUTS, true) ? $post->layout : 'image_top';
    $hasSideLayout = in_array($layout, ['image_left', 'image_right'], true) && count($secondaryImages) > 0;
    $mediaAfterContent = in_array($layout, ['image_bottom', 'image_right'], true);
@endphp

<div class="min-h-screen w-full bg-white text-slate-900">
    <article>
        <header class="relative isolate flex min-h-[22rem] w-full overflow-hidden bg-gradient-to-br from-primary via-primary-light to-secondary sm:min-h-[27rem] lg:min-h-[31rem]">
            @if($heroImage)
                <img
                    src="{{ $heroImage['url'] }}"
                    alt="{{ $heroImage['alt'] ?? $post->title }}"
                    class="absolute inset-0 h-full w-full object-cover"
                >
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-primary to-secondary" aria-hidden="true"></div>
                <div class="absolute -right-12 top-1/2 flex h-72 w-72 -translate-y-1/2 items-center justify-center rounded-full border border-white/10 bg-white/5 text-[8rem] text-white/10 sm:right-10 sm:h-96 sm:w-96 sm:text-[11rem]" aria-hidden="true">
                    <i class="fal fa-newspaper"></i>
                </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/55 to-slate-950/10" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-slate-950/20" aria-hidden="true"></div>

            <div class="container absolute inset-x-0 top-0 z-20 mx-auto flex w-full px-3 pt-4">
                <a
                    href="{{ route('news.index') }}"
                    wire:navigate
                    class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-slate-950/35 px-3 py-2 text-xs font-semibold text-white shadow-lg backdrop-blur-md transition hover:border-white/60 hover:bg-slate-950/55 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:text-sm"
                >
                    <i class="fal fa-arrow-left" aria-hidden="true"></i>
                    Zurück zu News
                </a>
            </div>

            @if($isAdminPreview)
                <div class="container absolute inset-x-0 top-16 z-20 mx-auto w-full px-3">
                    <x-news.admin-preview-notice compact class="max-w-xl" />
                </div>
            @endif

            <div class="container relative z-10 mx-auto flex min-h-[22rem] w-full flex-col items-start justify-end px-3 pb-8 pt-28 text-white sm:min-h-[27rem] sm:pb-12 lg:min-h-[31rem] lg:pb-14">
                <span
                    class="news-category-badge inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-[0.7rem] font-bold uppercase tracking-[0.08em] text-white shadow-lg sm:text-xs"
                    @if($category) style="background-color: {{ $category->color }};" @else style="background-color: #0c968e;" @endif
                >
                    @if($category?->icon)
                        <i class="fal {{ $category->icon }}" aria-hidden="true"></i>
                    @endif
                    {{ $category?->name ?? 'News' }}
                </span>

                @if($post->published_at)
                    <time class="mt-4 text-sm font-medium text-white/90 sm:text-base" datetime="{{ $post->published_at->toDateString() }}">
                        {{ $post->published_at->translatedFormat('j. F Y') }}
                    </time>
                @else
                    <span class="mt-4 inline-flex rounded-full bg-amber-300 px-2.5 py-1 text-[0.65rem] font-bold uppercase tracking-wide text-amber-950">Entwurf</span>
                @endif

                <h1 class="mt-3 max-w-4xl text-balance text-3xl font-bold leading-[1.08] tracking-tight text-white sm:text-4xl lg:text-6xl">
                    {{ strip_tags((string) $post->title) }}
                </h1>
            </div>
        </header>

        <section class="bg-white">
            <div class="container mx-auto px-3">
                @if($project && $pagebuilderHtml !== '')
                    <div id="news-pagebuilder-{{ $project->id }}" class="news-pagebuilder-content w-full" wire:ignore>
                        {!! $pagebuilderHtml !!}
                        @if(filled($project->css))
                            <style data-pagebuilder-project-css="{{ $project->id }}">{!! $project->css !!}</style>
                        @endif
                        @if(filled($project->js))
                            <script data-pagebuilder-project-js="{{ $project->id }}">{!! $project->js !!}</script>
                        @endif
                    </div>
                @else
                <div class="mx-auto w-full max-w-[920px] py-8 sm:py-10 lg:py-12">
                    <div @class([
                        'grid gap-8',
                        'md:grid-cols-2 md:items-start' => $hasSideLayout,
                    ])>
                        @if(count($secondaryImages) > 0 && ! $mediaAfterContent)
                            <x-news.media :images="$secondaryImages" :title="$post->title" />
                        @endif

                        <div class="min-w-0">
                            @if($post->excerpt)
                                <p class="mb-7 text-lg font-medium leading-relaxed text-slate-700 sm:text-xl">
                                    {{ $post->excerpt }}
                                </p>
                            @endif

                            <div class="default-format-text blog-content w-full">
                                {!! $post->body !!}
                            </div>
                        </div>

                        @if(count($secondaryImages) > 0 && $mediaAfterContent)
                            <x-news.media :images="$secondaryImages" :title="$post->title" />
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </section>

        @if($relatedPosts->isNotEmpty())
            <section class="bg-white pb-10 sm:pb-14" aria-labelledby="related-news-heading">
                <div class="container mx-auto w-full border-t border-slate-200 px-3 pt-7 sm:pt-8">
                    <div class="flex items-center justify-between gap-4">
                        <h2 id="related-news-heading" class="text-xs font-bold uppercase tracking-[0.1em] text-secondary sm:text-sm">
                            Ähnliche Themen
                        </h2>
                        <a href="{{ route('news.index') }}" wire:navigate class="inline-flex items-center gap-2 text-xs font-semibold text-secondary transition hover:text-primary sm:text-sm">
                            Alle anzeigen
                            <i class="fal fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                        @foreach($relatedPosts as $related)
                            @php
                                $relatedImage = $related->firstNewsImage();
                                $relatedCategory = $related->newsCategory;
                            @endphp

                            <a
                                href="{{ route('news.show', $related) }}"
                                wire:navigate
                                class="group grid min-w-0 grid-cols-[6rem_minmax(0,1fr)] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_8px_24px_-18px_rgba(15,23,42,0.55)] transition hover:-translate-y-0.5 hover:border-secondary/40 hover:shadow-[0_14px_30px_-18px_rgba(15,23,42,0.55)] md:grid-cols-1"
                            >
                                <div class="relative min-h-28 overflow-hidden bg-slate-100 md:h-32 md:min-h-0">
                                    @if($relatedImage)
                                        <img
                                            src="{{ $relatedImage['url'] }}"
                                            alt="{{ $relatedImage['alt'] ?? $related->title }}"
                                            class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            loading="lazy"
                                        >
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-primary via-primary-light to-secondary text-2xl text-white/80">
                                            <i class="fal fa-newspaper" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="min-w-0 p-3.5 sm:p-4">
                                    <span
                                        class="news-category-badge inline-flex max-w-full truncate rounded-md px-2 py-1 text-[0.58rem] font-bold uppercase leading-none tracking-wide text-white"
                                        @if($relatedCategory) style="background-color: {{ $relatedCategory->color }};" @else style="background-color: #0c968e;" @endif
                                    >
                                        {{ $relatedCategory?->name ?? 'News' }}
                                    </span>

                                    @if($related->published_at)
                                        <time class="mt-2 block text-[0.68rem] text-slate-500" datetime="{{ $related->published_at->toDateString() }}">
                                            {{ $related->published_at->translatedFormat('j. F Y') }}
                                        </time>
                                    @else
                                        <span class="mt-2 inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-[0.55rem] font-bold uppercase tracking-wide text-amber-900">Entwurf</span>
                                    @endif

                                    <h3 class="mt-1.5 line-clamp-3 text-sm font-bold leading-snug text-slate-900 transition group-hover:text-primary sm:text-base">
                                        {{ strip_tags((string) $related->title) }}
                                    </h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
</div>
