<section class="section experience-section" id="erfahrungen" data-carousel aria-labelledby="experience-title">
    <div class="experience-intro rail-content">
        <div class="section-heading">
            <h2 id="experience-title">Aktuelle Bewertungen</h2>
            <p>Hier findest du eine zufällige Auswahl an aktuellen Bewertungen.</p>
        </div>
    </div>
    @if ($claimRatings->isNotEmpty())
        <div class="review-stack carousel-track" id="reviews-track" data-carousel-track role="list" aria-label="Aktuelle Bewertungen" tabindex="0">
            @foreach ($claimRatings as $claimRating)
                @php
                    $ratingUser = $claimRating->user;
                    $showName = $ratingUser && ! $ratingUser->isAdmin() && $ratingUser->isNameVisibleIn('ratings', auth()->user());
                    $displayName = $showName ? $ratingUser->name : 'Anonym';
                    $score = is_numeric($claimRating->rating_score) ? max(0, min(1, (float) $claimRating->rating_score)) * 5 : null;
                    $summary = \App\Support\Text::utf8((string) data_get($claimRating->attachments, 'scorings.ai_overall_comment', ''));
                @endphp
                <article class="review-card" data-carousel-item data-review role="listitem" wire:key="homepage-review-{{ $claimRating->id }}">
                    <div class="review-core">
                        <div class="review-top">
                            @if ($score !== null)
                                <span class="stars stars-meter" aria-label="{{ number_format($score, 1, ',', '.') }} von 5 Sternen" style="--rating-fill: {{ $score * 20 }}%">★★★★★</span>
                            @endif
                            @if ($claimRating->created_at)
                                <time class="review-date" datetime="{{ $claimRating->created_at->toDateString() }}">{{ $claimRating->created_at->format('d.m.Y') }}</time>
                            @endif
                        </div>
                        @if ($summary !== '')
                            <p class="review-summary">{{ $summary }}</p>
                        @endif
                        <div class="review-meta">
                            <span class="review-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($displayName, 0, 1)) }}</span>
                            <div>
                                <strong>{{ $displayName }}</strong>
                                <span>{{ $claimRating->insurance?->name }}@if ($claimRating->insurance && $claimRating->insuranceSubtype) · @endif{{ $claimRating->insuranceSubtype?->name }}</span>
                            </div>
                        </div>
                        <a class="review-link" href="{{ route('review.show', $claimRating->id) }}" wire:navigate>Bewertung ansehen <span aria-hidden="true">↗</span></a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="carousel-footer rail-wide" data-carousel-controls>
            <input class="carousel-scrollbar" type="range" min="0" max="1000" step="1" value="0" disabled data-carousel-progress aria-controls="reviews-track" aria-label="Bewertungen durchblättern">
            <span class="carousel-status" data-carousel-status aria-live="off"></span>
            <div class="carousel-buttons">
                <button class="carousel-arrow" type="button" data-carousel-prev aria-controls="reviews-track" aria-label="Vorherige Bewertungen"><span aria-hidden="true">←</span></button>
                <button class="carousel-arrow" type="button" data-carousel-next aria-controls="reviews-track" aria-label="Nächste Bewertungen"><span aria-hidden="true">→</span></button>
            </div>
        </div>
    @endif
</section>
