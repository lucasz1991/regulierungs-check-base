@props([
    'insurance',
    'isSubTypeFilter' => false,
    'subTypeFilterSubType' => null,
    'selectedInsuranceTypeIds' => [],
    'selectedInsuranceSubTypeIds' => [],
    'selectedInsuranceTypeSubtypeIds' => [],
    'rank' => null,
])

@php
    $selectedTypeIds = collect($selectedInsuranceTypeIds ?? [])
        ->filter(fn ($id) => is_numeric($id))
        ->map(fn ($id) => (int) $id)
        ->values()
        ->all();

    $selectedSubtypeIds = collect($selectedInsuranceSubTypeIds ?? [])
        ->filter(fn ($id) => is_numeric($id))
        ->map(fn ($id) => (int) $id)
        ->values()
        ->all();

    $selectedTypeSubtypeIds = collect($selectedInsuranceTypeSubtypeIds ?? [])
        ->filter(fn ($id) => is_numeric($id))
        ->map(fn ($id) => (int) $id)
        ->values()
        ->all();

    $selectedSubtypeId = is_object($subTypeFilterSubType)
        ? $subTypeFilterSubType?->id
        : $subTypeFilterSubType;

    if (empty($selectedSubtypeIds) && $selectedSubtypeId) {
        $selectedSubtypeIds = [(int) $selectedSubtypeId];
    }

    $hasTypeFilter = !empty($selectedTypeIds);
    $hasSubtypeFilter = !empty($selectedSubtypeIds);

    $avgDuration = $insurance->avgRatingDurationByTypeAndSubtypeIds($selectedTypeIds, $selectedTypeSubtypeIds, $selectedSubtypeIds);
    $avgScore = $insurance->published_ratings_avg_scoreByTypeAndSubtypeIds($selectedTypeIds, $selectedTypeSubtypeIds, $selectedSubtypeIds);
    $ratingsCount = $insurance->published_claimRatingsCountByTypeAndSubtypeIds($selectedTypeIds, $selectedTypeSubtypeIds, $selectedSubtypeIds);

    $regulationTypeDistribution = $insurance->publishedClaimRatingRegulationTypeDistributionByTypeAndSubtypeIds($selectedTypeIds, $selectedTypeSubtypeIds, $selectedSubtypeIds);
    $summaryItems = [
        ['label' => 'Vollzahlung', 'count' => (int) ($regulationTypeDistribution['vollzahlung'] ?? 0), 'color' => '#99c98b'],
        ['label' => 'Teilzahlung', 'count' => (int) ($regulationTypeDistribution['teilzahlung'] ?? 0), 'color' => '#f6c238'],
        ['label' => 'Ablehnung', 'count' => (int) ($regulationTypeDistribution['ablehnung'] ?? 0), 'color' => '#cfd5df'],
    ];
    $barTotal = max(0, (int) ($regulationTypeDistribution['total'] ?? 0));
    $remainderCount = max(0, (int) ($regulationTypeDistribution['austehend'] ?? 0) + (int) ($regulationTypeDistribution['other'] ?? 0));
    $progressItems = array_values(array_filter([
        ...$summaryItems,
        $remainderCount > 0 ? ['label' => 'Rest', 'count' => $remainderCount, 'color' => '#e4e7ee'] : null,
    ]));
    $avgDurationDisplay = is_null($avgDuration) ? '-' : round($avgDuration);
    $reviewCountDisplay = number_format((int) $ratingsCount, 0, ',', '.');
    $hasRank = !is_null($rank);
    $filterQuery = [];

    if ($hasTypeFilter) {
        $filterQuery['types'] = implode(',', $selectedTypeIds);
    }

    if ($hasSubtypeFilter) {
        $filterQuery['subtypes'] = implode(',', $selectedSubtypeIds);
    }

    $insuranceUrl = route('insurance.show-insurance', array_merge(
        ['insurance' => $insurance->slug],
        $filterQuery
    ));
@endphp

<article class="app-insurance-card flex h-full flex-col rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-5 text-[var(--ink)] transition-shadow duration-300 hover:shadow-[0_12px_36px_rgba(20,59,71,0.06)] sm:p-6">
    <header class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <x-insurance.insurance-name
                :insurance="$insurance"
                size="lg"
                wrapperClass="mb-4 flex min-w-0 max-w-full items-center"
                disclaimerButtonClass="text-[var(--muted)] transition-colors hover:text-[var(--accent)]"
            />
            <a href="{{ $insuranceUrl }}" class="inline-block text-base font-semibold leading-snug text-[var(--ink)] hover:text-[var(--accent)]">
                {{ $insurance->name }}
            </a>
        </div>
        @if($hasRank)
            <span class="inline-flex h-8 min-w-8 shrink-0 items-center justify-center rounded-full border border-[var(--line)] bg-[var(--surface-soft)] px-2 text-xs font-semibold text-[var(--accent)]">{{ $rank }}</span>
        @endif
    </header>

    <div class="my-4 flex flex-wrap items-end justify-between gap-x-5 gap-y-3 border-y border-[var(--line)] py-4">
        <div>
            <div class="mb-1 text-[11px] text-[var(--muted)]">Ø Regulierung</div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-semibold leading-none tracking-tight">{{ $avgDurationDisplay }}</span>
                @if($avgDurationDisplay !== '-')<span class="text-xs text-[var(--muted)]">Tage</span>@endif
            </div>
        </div>
        <div>
            <x-insurance.insurance-rating-stars :score="$avgScore" :size="'sm'" />
            <div class="mt-2 text-xs text-[var(--muted)]">{{ $reviewCountDisplay }} Bewertungen</div>
        </div>
    </div>

    <div class="h-1.5 w-full overflow-hidden rounded-full bg-[var(--surface-soft)]" aria-hidden="true">
        <div class="flex h-full w-full">
            @forelse($progressItems as $item)
                @php($width = $barTotal > 0 ? round(($item['count'] / $barTotal) * 100, 2) : 0)
                @if($width > 0)<span class="block h-full" style="width: {{ $width }}%; background-color: {{ $item['color'] }};"></span>@endif
            @empty
                <span class="block h-full w-full bg-[var(--surface-soft)]"></span>
            @endforelse
        </div>
    </div>
    <dl class="mt-3 grid grid-cols-3 gap-3">
        @foreach($summaryItems as $item)
            @php($percentage = $barTotal > 0 ? round(($item['count'] / $barTotal) * 100) : 0)
            <div class="min-w-0">
                <dt class="text-[10px] leading-relaxed text-[var(--muted)]">{{ $item['label'] }}</dt>
                <dd class="mt-1 text-sm font-medium">{{ $percentage }} %</dd>
            </div>
        @endforeach
    </dl>
    <a href="{{ $insuranceUrl }}" class="mt-5 inline-flex min-h-10 items-center justify-between gap-4 border-t border-[var(--line)] pt-4 text-xs font-semibold text-[var(--accent)]">
        <span>Versicherer ansehen</span><span aria-hidden="true">↗</span>
    </a>
</article>
