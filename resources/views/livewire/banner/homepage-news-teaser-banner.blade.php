<div class="relative z-20">
    @if($newsEnabled && $posts->isNotEmpty())
        @php
            // Kartenbreite (20rem) plus Abstand. Der Abstand haengt als
            // margin-right an der Karte und nicht als gap an der Spur - nur so
            // ist die Spur exakt doppelt so breit wie eine Sequenz und der
            // Sprung bei -50 % faellt nahtlos auf den Anfang zurueck.
            $cardStride = 332;

            // Eine Sequenz muss breiter sein als der Bildschirm, sonst klafft
            // bei wenigen News eine Luecke. Mindestens 16 Karten ergeben rund
            // 5.300 px und decken damit auch sehr breite Desktop-Viewports ab.
            $minimumSequenceCards = 16;
            $sequenceRepeat = (int) max(1, ceil($minimumSequenceCards / max(1, $posts->count())));
            $sequenceWidth = $sequenceRepeat * $posts->count() * $cardStride;

            // Konstantes Tempo statt fester Dauer: mehr Karten laufen laenger,
            // nicht schneller.
            $tickerDuration = (int) max(30, round($sequenceWidth / 45));
        @endphp

        {{--
            Bewusst eine CSS-Laufschrift und kein Swiper.

            Swiper bewegt sich in Schritten und ordnet beim Umbruch die Slides
            um (loopFix), was genau das Zucken erzeugt. Eine einzelne
            Transform-Animation ueber eine doppelt vorhandene Spur laeuft
            dagegen linear durch und kann an der Naht nicht stocken. Sie
            braucht ausserdem kein JavaScript - damit entfaellt auch das
            Ruckeln durch nachtraegliche swiper.update()-Aufrufe beim Laden.
        --}}
        <section
            class="homepage-news-ticker"
            aria-label="Aktuelle News"
            style="--homepage-news-ticker-duration: {{ $tickerDuration }}s"
        >
            <div class="homepage-news-ticker__track">
                {{-- Zwei identische Durchlaeufe: der zweite traegt die Naht. --}}
                @for($copy = 0; $copy < 2; $copy++)
                    @for($repeat = 0; $repeat < $sequenceRepeat; $repeat++)
                        @foreach($posts as $post)
                            @php
                                $category = $post->newsCategory;
                                $teaser = \Illuminate\Support\Str::limit(
                                    trim(strip_tags((string) $post->excerpt_preview)),
                                    90
                                );
                                // Nur der erste Durchlauf ist fuer Screenreader
                                // und Tastatur da, sonst kaeme jede News mehrfach.
                                $isEcho = $copy > 0 || $repeat > 0;
                            @endphp

                            <div class="homepage-news-ticker__card" @if($isEcho) aria-hidden="true" @endif>
                                <a
                                    href="{{ route('news.show', $post) }}"
                                    wire:navigate
                                    @if($isEcho) tabindex="-1" @endif
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
                    @endfor
                @endfor
            </div>
        </section>
    @endif
</div>
