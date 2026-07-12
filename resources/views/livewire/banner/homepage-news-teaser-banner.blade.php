<div class="relative z-20">
    @if($newsEnabled && $posts->isNotEmpty())
        <section
            x-data="{ paused: false }"
            :class="{ 'is-paused': paused }"
            class="homepage-news-ticker mt-5 overflow-hidden rounded-xl border border-white/20 bg-primary/80 shadow-2xl backdrop-blur-md md:mt-8"
            aria-labelledby="homepage-news-ticker-title"
        >
            <div class="flex items-center justify-between gap-2 border-b border-white/15 px-3 py-2.5 md:gap-4 md:px-4">
                <div class="flex min-w-0 items-center gap-2.5">
                    <span class="inline-flex shrink-0 items-center rounded-full bg-rcgold px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-primary">
                        News
                    </span>
                    <h2 id="homepage-news-ticker-title" class="truncate text-sm font-semibold text-white md:text-base">
                        Aktuelles vom Regulierungs-Check
                    </h2>
                    @if($isAdminPreview)
                        <span class="shrink-0 rounded-full border border-amber-200/70 bg-amber-300 px-2 py-1 text-[9px] font-bold uppercase tracking-wide text-amber-950 sm:text-[10px]">
                            <span class="sm:hidden">Admin</span>
                            <span class="hidden sm:inline">Nur für Admins sichtbar</span>
                        </span>
                    @endif
                </div>

                <div class="flex shrink-0 items-center gap-2">
                    @if($tickerShouldAnimate)
                        <button
                            type="button"
                            @click="paused = ! paused"
                            :aria-label="paused ? 'News-Laufzeile fortsetzen' : 'News-Laufzeile anhalten'"
                            :title="paused ? 'Laufzeile fortsetzen' : 'Laufzeile anhalten'"
                            class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-white/20 text-white/80 transition hover:bg-white/10 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                        >
                            <svg x-show="! paused" class="h-3.5 w-3.5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V5Zm6 0a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5Z"></path>
                            </svg>
                            <svg x-show="paused" x-cloak class="h-3.5 w-3.5" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5.14v13.72a1 1 0 0 0 1.52.85l10.29-6.86a1 1 0 0 0 0-1.7L9.52 4.29A1 1 0 0 0 8 5.14Z"></path>
                            </svg>
                        </button>
                    @endif

                    <a href="{{ route('news.index') }}" wire:navigate class="group inline-flex shrink-0 items-center gap-1.5 text-xs font-semibold text-white/80 transition hover:text-white md:text-sm">
                        <span class="hidden min-[360px]:inline">Alle News</span>
                        <svg class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-0.5" aria-hidden="true" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14"></path>
                            <path d="m13 6 6 6-6 6"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="{{ $tickerShouldAnimate ? 'homepage-news-ticker__viewport' : '' }} py-3 md:py-4">
                <div class="{{ $tickerShouldAnimate ? 'homepage-news-ticker__track' : 'flex justify-center px-3' }}">
                    @foreach($tickerShouldAnimate ? [false, true] : [false] as $duplicate)
                        <div class="{{ $tickerShouldAnimate ? 'homepage-news-ticker__sequence' : 'flex justify-center' }}" @if($duplicate) aria-hidden="true" @endif>
                            @foreach($tickerItems as $tickerItem)
                                @php
                                    $post = $tickerItem['post'];
                                    $isHiddenRepeat = $duplicate || $tickerItem['is_filler'];
                                    $category = $post->newsCategory;
                                    $teaser = \Illuminate\Support\Str::limit(
                                        trim(strip_tags((string) $post->excerpt_preview)),
                                        115
                                    );
                                @endphp

                                <a
                                    href="{{ route('news.show', $post) }}"
                                    wire:navigate
                                    @if($isHiddenRepeat) aria-hidden="true" tabindex="-1" @endif
                                    class="group flex w-[18rem] shrink-0 flex-col rounded-xl border border-gray-100 bg-white/95 p-3.5 text-left shadow-lg transition duration-300 hover:-translate-y-0.5 hover:bg-white hover:shadow-xl md:w-[22rem] md:p-4"
                                >
                                    <div class="flex items-center justify-between gap-3">
                                        <span
                                            class="news-category-badge inline-flex max-w-[11rem] items-center gap-1.5 truncate rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-white md:max-w-[14rem] md:text-[11px]"
                                            style="background-color: {{ $category?->color ?: '#0c968e' }};"
                                        >
                                            @if($category?->icon)
                                                <i class="fal {{ $category->icon }} shrink-0 text-[10px]"></i>
                                            @endif
                                            <span class="truncate">{{ $category?->name ?: 'News' }}</span>
                                        </span>

                                        @if($post->published_at)
                                            <time class="shrink-0 text-[11px] font-medium text-gray-500" datetime="{{ $post->published_at->toDateString() }}">
                                                {{ $post->published_at->format('d.m.Y') }}
                                            </time>
                                        @else
                                            <span class="shrink-0 rounded-full bg-amber-100 px-2 py-1 text-[9px] font-bold uppercase tracking-wide text-amber-900">
                                                Entwurf
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="mt-2 line-clamp-1 text-sm font-bold leading-snug text-gray-900 transition-colors group-hover:text-primary-light md:text-base">
                                        {{ strip_tags((string) $post->title) }}
                                    </h3>

                                    <div class="mt-1.5 flex items-end gap-3">
                                        <p class="line-clamp-2 min-w-0 flex-1 text-xs leading-relaxed text-gray-600 md:text-sm">
                                            {{ $teaser }}
                                        </p>
                                        <svg class="mb-0.5 h-4 w-4 shrink-0 text-primary transition-transform duration-300 group-hover:translate-x-1" aria-hidden="true" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M5 12h14"></path>
                                            <path d="m13 6 6 6-6 6"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
