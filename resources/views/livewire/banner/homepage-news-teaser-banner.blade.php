<div>
    @if ($newsEnabled && $posts->isNotEmpty())
        <section class="knowledge-section news-band" id="wissen" aria-label="News &amp; Ratgeber">
            <div class="news-band-inner" data-news-ticker wire:key="homepage-news-{{ $posts->map(fn ($post) => $post->id . '-' . $post->updated_at?->timestamp)->implode('-') }}">
                <div class="news-track carousel-track" id="news-track" data-carousel-track role="list" aria-label="Aktuelle News" tabindex="0">
                    @foreach ($posts as $post)
                        @php
                            $teaser = \Illuminate\Support\Str::limit(trim(strip_tags((string) $post->excerpt_preview)), 90);
                            $title = strip_tags((string) $post->title);
                        @endphp
                        <article class="news-card" data-carousel-item role="listitem" wire:key="homepage-news-card-{{ $post->id }}">
                            <a href="{{ route('news.show', $post) }}" wire:navigate draggable="false" title="{{ $title }}">
                                <div class="news-card-copy">
                                    <div class="news-meta">
                                        <span>{{ $post->newsCategory?->name ?: 'News' }}</span>
                                        @if ($post->published_at)
                                            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->format('d.m.Y') }}</time>
                                        @else
                                            <span>Entwurf</span>
                                        @endif
                                    </div>
                                    <h3>{{ $title }}</h3>
                                    <p>{{ $teaser }}</p>
                                </div>
                                <span class="news-card-arrow" aria-hidden="true">↗</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
