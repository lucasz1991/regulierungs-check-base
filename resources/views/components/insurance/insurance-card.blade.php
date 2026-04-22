@props([
    'insurance',
    'isSubTypeFilter' => false,
    'subTypeFilterSubType' => null,
    'selectedInsuranceTypeIds' => [],
    'selectedInsuranceSubTypeIds' => [],
    'selectedInsuranceTypeSubtypeIds' => [],
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
    $barItems = [
        ['label' => 'Vollzahlung', 'count' => (int) ($regulationTypeDistribution['vollzahlung'] ?? 0), 'color' => '#4f9d7a'],
        ['label' => 'Teilzahlung', 'count' => (int) ($regulationTypeDistribution['teilzahlung'] ?? 0), 'color' => '#d6a346'],
        ['label' => 'Ablehnung', 'count' => (int) ($regulationTypeDistribution['ablehnung'] ?? 0), 'color' => '#c76f6f'],
        ['label' => 'Ausstehend', 'count' => (int) ($regulationTypeDistribution['austehend'] ?? 0), 'color' => '#5f86ad'],
    ];
    $barTotal = max(0, array_sum(array_column($barItems, 'count')));
    $avgDurationDisplay = is_null($avgDuration) ? '-' : round($avgDuration);

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

<div class="block" x-data="{ hover: false }" x-cloak>
    <div class="group bg-white p-4 relative transition-all duration-300 flex flex-col justify-between h-full hover:-translate-y-0.5 hover:shadow-md cursor-pointer border border-gray-200 hover:border-slate-300 rounded-lg shadow-sm"
        x-on:mouseenter="hover = true"
        x-on:mouseleave="hover = false"
        onclick="window.location.href='{{ $insuranceUrl }}'"
    >
        <div class="absolute right-0 top-0 rounded-bl-lg rounded-tr-lg border-b border-l border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold leading-none text-slate-700">
            Ø {{ $avgDurationDisplay }} Tage
        </div>

        <div class="transition-all duration-200">
            <div class="pr-28">
                <x-insurance.insurance-name :insurance="$insurance" />
            </div>

            <div class="mt-4 flex items-center justify-start">
                <x-insurance.insurance-rating-stars :score="$avgScore" :size="'lg'" />
            </div>

            <div class="mt-4 border-t border-gray-100 pt-3">
                <div class="mb-2 grid grid-cols-2 gap-x-4 gap-y-1 text-[10px] leading-tight text-slate-500">
                    @foreach ($barItems as $item)
                        <div class="inline-flex items-center gap-1 min-w-0">
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full" style="background-color: {{ $item['color'] }};"></span>
                            <span class="truncate">{{ $item['label'] }}</span>
                            <span class="font-semibold text-slate-600">{{ $item['count'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="h-3 w-full overflow-hidden rounded-full bg-slate-100 flex shadow-inner">
                    @foreach ($barItems as $item)
                        @php
                            $width = $barTotal > 0 ? round(($item['count'] / $barTotal) * 100, 2) : 0;
                        @endphp

                        @if ($width > 0)
                            <span class="block h-full" style="width: {{ $width }}%; background-color: {{ $item['color'] }};"></span>
                        @endif
                    @endforeach
                </div>

                <div class="mt-3 flex items-center justify-center gap-1.5 text-xs font-medium text-slate-500">
                    <i class="fal fa-comments text-slate-400"></i>
                    <span>{{ $ratingsCount }} Bewertungen</span>
                </div>
            </div>
        </div>
    </div>
</div>
