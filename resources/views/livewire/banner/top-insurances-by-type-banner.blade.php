<section class="section insurance-section" id="versicherer" data-carousel aria-labelledby="insurer-title" data-empty="{{ $insurances->isEmpty() ? 'true' : 'false' }}">
    @php
        $filterQuery = $selectedInsuranceTypeId ? ['types' => (string) $selectedInsuranceTypeId] : [];
        $insurancesUrl = route('insurances', $filterQuery);
        $selectedType = $selectedInsuranceTypeId ? $types->firstWhere('id', $selectedInsuranceTypeId) : null;
        $initialTypes = $types->take(4);
        $additionalTypes = $types->slice(4, 4);
        $additionalTypeSelected = $selectedType && $additionalTypes->contains('id', $selectedType->id);
    @endphp
    <div class="explorer-intro explorer-intro-compact rail-content">
        <div class="section-heading explorer-heading">
            <h2 id="insurer-title">Welche Versicherung möchtest du vergleichen?</h2>
        </div>
        <div class="category-controls">
            @foreach ([$initialTypes, $additionalTypes] as $typeGroup)
                @if ($loop->last && $typeGroup->isNotEmpty())
                    <details class="more-categories" wire:ignore.self @if ($showAllInsuranceTypes || $additionalTypeSelected) open @endif>
                        <summary aria-label="Weitere Versicherungssparten">Weitere Sparten</summary>
                @endif
                @if ($typeGroup->isNotEmpty())
                    <div class="filter-group" role="group" aria-label="{{ $loop->first ? 'Versicherungssparten' : 'Weitere Versicherungssparten' }}">
                        @foreach ($typeGroup as $type)
                            @php
                                $safeIcon = \App\Support\SafeIconMarkup::forType($type->icon_type, $type->icon_svg);
                                $selected = (int) $selectedInsuranceTypeId === (int) $type->id;
                            @endphp
                            <button class="filter{{ $selected ? ' active' : '' }}" type="button" wire:click="selectInsuranceType({{ (int) $type->id }})" wire:loading.attr="disabled" aria-pressed="{{ $selected ? 'true' : 'false' }}" wire:key="homepage-type-{{ $type->id }}">
                                <span class="category-icon" aria-hidden="true">
                                    @if ($safeIcon && $type->icon_type === 'svg')
                                        {!! $safeIcon !!}
                                    @elseif ($safeIcon && $type->icon_type === 'fontawesome')
                                        <i class="{{ $safeIcon }}"></i>
                                    @else
                                        <i class="fal fa-shield-alt"></i>
                                    @endif
                                </span>
                                <span class="category-label">{{ $type->name }}</span>
                                <span class="category-arrow" aria-hidden="true">{{ $selected ? '✓' : '→' }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                @if ($loop->last && $typeGroup->isNotEmpty())
                    </details>
                @endif
            @endforeach
            @if ($selectedInsuranceTypeId)
                <button class="text-link category-reset" type="button" wire:click="clearInsuranceTypeFilter" wire:loading.attr="disabled">Alle Versicherungssparten <span aria-hidden="true">×</span></button>
            @endif
        </div>
    </div>

    <div class="insurance-toolbar rail-wide">
        <h3>{{ $selectedType?->name ?: 'Versicherer im Vergleich' }}</h3>
        <div class="search-bar provider-search">
            <label class="search-field">
                <span class="search-icon" aria-hidden="true"></span>
                <span class="sr-only">Versicherer suchen</span>
                <input id="insurer-search" type="search" autocomplete="off" placeholder="Versicherer suchen">
            </label>
            <a class="search-go" href="#insurance-track" aria-label="Suchergebnisse ansehen">→</a>
        </div>
        <span class="result-count" id="result-count" role="status" aria-live="polite">{{ $insurances->count() }} Versicherer</span>
    </div>

    <div class="insurer-grid carousel-track" id="insurance-track" data-carousel-track role="list" aria-label="Versicherer im Vergleich" tabindex="0" wire:key="homepage-insurance-track-{{ $selectedInsuranceTypeId ?? 'all' }}">
        @foreach ($insurances as $insurance)
            @php
                $durations = $insurance->claimRatings->map(fn ($rating) => $rating->ratingDuration())->filter(fn ($duration) => $duration !== null);
                $averageDuration = $durations->isEmpty() ? null : (int) round($durations->avg());
                $averageScore = is_numeric($insurance->published_avg) ? max(0, min(1, (float) $insurance->published_avg)) * 5 : null;
                $insuranceUrl = route('insurance.show-insurance', array_merge(['insurance' => $insurance->slug], $filterQuery));
            @endphp
            <article class="insurer-card" data-carousel-item data-category="all" data-insurer="{{ $insurance->name }}" role="listitem" wire:key="homepage-insurance-{{ $selectedInsuranceTypeId ?? 'all' }}-{{ $insurance->id }}">
                <div class="insurer-core">
                    <div class="insurer-heading">
                        @if ($insurance->logo)
                            <img class="insurer-logo" src="{{ asset('storage/' . $insurance->logo) }}" alt="" width="158" height="60" loading="lazy">
                            <x-insurance.top-insurance-banner.insurance-logo-disclaim-button />
                        @else
                            <span class="insurer-symbol" aria-hidden="true">{{ mb_strtoupper(mb_substr($insurance->initials ?: $insurance->name, 0, 4)) }}</span>
                        @endif
                    </div>
                    <div class="insurer-identity"><h3>{{ $insurance->name }}</h3><p class="insurer-subline">Schadenregulierung</p></div>
                    <div class="row-duration{{ $averageDuration === null ? ' is-unavailable' : '' }}" @if ($averageDuration === null) aria-label="Keine Angaben zur Regulierungsdauer" @endif>
                        <span class="duration-label">Ø Regulierungsdauer</span>
                        <strong>{{ $averageDuration === null ? '–' : $averageDuration }} @if ($averageDuration !== null)<span>Tage</span>@endif</strong>
                    </div>
                    <div class="insurer-score">
                        <div>
                            @if ($averageScore !== null)
                                <span class="stars stars-meter" aria-label="{{ number_format($averageScore, 1, ',', '.') }} von 5 Sternen" style="--rating-fill: {{ $averageScore * 20 }}%">★★★★★</span>
                            @endif
                            <small>{{ number_format((int) $insurance->published_count, 0, ',', '.') }} {{ (int) $insurance->published_count === 1 ? 'Bewertung' : 'Bewertungen' }}</small>
                        </div>
                    </div>
                    <div class="insurer-bottom"><a class="provider-link" href="{{ $insuranceUrl }}" wire:navigate aria-label="{{ $insurance->name }} ansehen">Versicherer ansehen <span aria-hidden="true">↗</span></a></div>
                </div>
            </article>
        @endforeach
        <article class="insurer-card insurer-discover" data-carousel-item data-carousel-discover role="listitem">
            <div class="insurer-core">
                <span class="discover-symbol" aria-hidden="true">↗</span>
                <h3>Weitere Anbieter vergleichen</h3>
                <a class="btn btn-primary" href="{{ $insurancesUrl }}" wire:navigate>Versicherer ansehen <span aria-hidden="true">→</span></a>
            </div>
        </article>
    </div>
    <div class="empty-state rail-wide" id="insurer-empty" @if ($insurances->isNotEmpty()) hidden @endif>
        <h3>Keine passenden Versicherer gefunden.</h3>
        <p>Versuche einen anderen Namen.</p>
        <button class="btn btn-secondary" id="reset-filters" type="button">Suche zurücksetzen</button>
    </div>
    <div class="carousel-footer rail-wide" data-carousel-controls>
        <input class="carousel-scrollbar" type="range" min="0" max="1000" step="1" value="0" disabled data-carousel-progress aria-controls="insurance-track" aria-label="Versicherer durchblättern">
        <span class="carousel-status" data-carousel-status aria-live="off"></span>
        <div class="carousel-buttons">
            <button class="carousel-arrow" type="button" data-carousel-prev aria-controls="insurance-track" aria-label="Vorherige Versicherer"><span aria-hidden="true">←</span></button>
            <button class="carousel-arrow" type="button" data-carousel-next aria-controls="insurance-track" aria-label="Nächste Versicherer"><span aria-hidden="true">→</span></button>
        </div>
    </div>
</section>
