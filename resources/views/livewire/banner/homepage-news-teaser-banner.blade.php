<div class="relative z-20">
    @if($newsEnabled && $posts->isNotEmpty())
        <section class="homepage-news-ticker" aria-label="Aktuelle News">
            <div class="homepage-news-ticker__viewport">
                <div class="homepage-news-ticker__track">
                    @foreach($tickerShouldAnimate ? [false, true] : [false] as $duplicate)
                        <div class="homepage-news-ticker__sequence" @if($duplicate) aria-hidden="true" @endif>
                            @foreach($tickerItems as $tickerItem)
                                @if($tickerItem['is_filler'])
                                    <div
                                        class="homepage-news-ticker__card homepage-news-ticker__placeholder homepage-news-ticker__placeholder--slot-{{ $tickerItem['slot'] }}"
                                        aria-hidden="true"
                                    ></div>
                                @else
                                    @php
                                        $post = $tickerItem['post'];
                                        $category = $post->newsCategory;
                                        $teaser = \Illuminate\Support\Str::limit(
                                            trim(strip_tags((string) $post->excerpt_preview)),
                                            90
                                        );
                                    @endphp

                                    <a
                                        href="{{ route('news.show', $post) }}"
                                        wire:navigate
                                        @if($duplicate) aria-hidden="true" tabindex="-1" @endif
                                        class="homepage-news-ticker__card group relative flex items-center gap-2 rounded-lg border border-gray-100 bg-white/95 px-3 pb-1.5 pt-2.5 text-left shadow-md transition duration-300 hover:bg-white hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                                    >
                                        <span
                                            class="news-category-badge absolute -top-2 left-2 inline-flex max-w-[10rem] items-center gap-1 truncate rounded-md px-2 py-1 text-[9px] font-bold uppercase leading-none tracking-wide text-white shadow-sm"
                                            style="background-color: {{ $category?->color ?: '#0c968e' }};"
                                        >
                                            @if($category?->icon)
                                                <i class="fal {{ $category->icon }} shrink-0 text-[9px]" aria-hidden="true"></i>
                                            @endif
                                            <span class="truncate">{{ $category?->name ?: 'News' }}</span>
                                        </span>

                                        @if($post->published_at)
                                            <time
                                                class="absolute -top-2 right-2 rounded-md border border-gray-100 bg-white px-2 py-1 text-[9px] font-semibold leading-none text-gray-600 shadow-sm"
                                                datetime="{{ $post->published_at->toDateString() }}"
                                            >
                                                {{ $post->published_at->format('d.m.Y') }}
                                            </time>
                                        @else
                                            <span class="absolute -top-2 right-2 rounded-md bg-amber-100 px-2 py-1 text-[9px] font-bold uppercase leading-none tracking-wide text-amber-900 shadow-sm">
                                                Entwurf
                                            </span>
                                        @endif

                                        <div class="min-w-0 flex-1">
                                            <h3 class="line-clamp-1 text-sm font-bold leading-tight text-gray-900 transition-colors group-hover:text-primary-light">
                                                {{ strip_tags((string) $post->title) }}
                                            </h3>
                                            <p class="mt-0.5 line-clamp-1 text-[11px] leading-tight text-gray-600">
                                                {{ $teaser }}
                                            </p>
                                        </div>

                                        <svg class="h-3.5 w-3.5 shrink-0 text-primary transition-transform duration-300 group-hover:translate-x-0.5" aria-hidden="true" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M5 12h14"></path>
                                            <path d="m13 6 6 6-6 6"></path>
                                        </svg>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
