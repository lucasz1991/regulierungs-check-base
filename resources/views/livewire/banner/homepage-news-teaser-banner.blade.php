<div class="relative z-20">
    @if($newsEnabled && $posts->isNotEmpty())
        @php
            // Loop braucht genug echte Slides, sonst springt Swiper. Unterhalb
            // dieser Schwelle laeuft der Slider ohne Endlosschleife und ohne
            // Autoplay - es gibt dann schlicht nichts zu rotieren.
            $newsSliderLoops = $posts->count() > 2;
        @endphp

        <section class="homepage-news-ticker" aria-label="Aktuelle News">
            <div
                x-data="{
                    swiper: null,
                    initNewsSwiper() {
                        this.swiper = new Swiper(this.$refs.newsSwiper, {
                            slidesPerView: 'auto',
                            spaceBetween: 12,
                            speed: 600,
                            grabCursor: true,
                            loop: {{ $newsSliderLoops ? 'true' : 'false' }},
                            watchSlidesProgress: true,
                            @if($newsSliderLoops)
                            autoplay: {
                                delay: 3500,
                                // Ziehen darf das Autoplay nicht dauerhaft killen.
                                disableOnInteraction: false,
                                // Pausiert bei Mouseenter und laeuft bei Mouseleave weiter.
                                pauseOnMouseEnter: true,
                            },
                            @endif
                            a11y: {
                                enabled: true,
                                prevSlideMessage: 'Vorherige News',
                                nextSlideMessage: 'Nächste News',
                            },
                        });
                    },
                }"
                x-init="initNewsSwiper()"
                wire:ignore
            >
                <div class="swiper homepage-news-ticker__viewport" x-ref="newsSwiper">
                    <div class="swiper-wrapper">
                        @foreach($posts as $post)
                            @php
                                $category = $post->newsCategory;
                                $teaser = \Illuminate\Support\Str::limit(
                                    trim(strip_tags((string) $post->excerpt_preview)),
                                    90
                                );
                            @endphp

                            <div class="swiper-slide homepage-news-ticker__card">
                                <a
                                    href="{{ route('news.show', $post) }}"
                                    wire:navigate
                                    class="group relative flex h-full w-full items-center gap-2 rounded-lg border border-gray-100 bg-white/95 px-3 pb-1.5 pt-2.5 text-left shadow-md transition duration-300 hover:bg-white hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
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
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
