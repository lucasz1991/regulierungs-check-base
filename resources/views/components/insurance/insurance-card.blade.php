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
    $rankBadgeClasses = match (true) {
        $rank === 1, $rank === 2, $rank === 3 => 'bg-gradient-to-b from-[#269ab2] to-[#17798f] text-white shadow-[0_10px_24px_rgba(23,121,143,0.28)]',
        default => 'bg-[#ffc53a] text-[#0f3654] shadow-[0_10px_24px_rgba(255,197,58,0.28)]',
    };

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

<a href="{{ $insuranceUrl }}" class="block">
    <div class="relative">
        @if ($hasRank)
            <div class="absolute left-0 top-3 z-10 -translate-x-[15%] rounded-r-2xl rounded-l-xl px-4 py-2.5 text-2xl font-semibold leading-none {{ $rankBadgeClasses }}">
                {{ $rank }}
            </div>
        @endif

        <article class="group relative overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_12px_30px_rgba(15,23,42,0.08)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(15,23,42,0.12)]">
            <div class="absolute right-0 top-3 z-[5] w-32 rounded-l-[24px] border border-r-0 border-[#d8e7e5] bg-gradient-to-br from-[#f1fbf8] to-[#e3f1ef] px-3 py-1 text-right shadow-[0_8px_20px_rgba(15,23,42,0.08)]">
                <div class="text-xs font-medium tracking-tight text-slate-600">Ø Regulierung</div>
                @if ($avgDurationDisplay === '-')
                    <div class="mt-0.5 text-base font-semibold leading-none text-[#0f4e69]">-</div>
                @else
                    <div class="mt-0.5 text-[1.55rem] font-medium leading-none text-[#0f4e69]">
                        {{ $avgDurationDisplay }}
                        <span class="text-sm font-medium">Tage</span>
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-2 px-5 pb-2.5 pt-2.5 sm:px-7 sm:pb-2.5 sm:pt-2.5">
                <div class="relative flex items-start">
                    <div class="min-w-0 flex-1 pr-32 {{ $hasRank ? 'pl-9 sm:pl-11' : '' }}">
                        <x-insurance.insurance-name
                            :insurance="$insurance"
                            size="xl"
                            wrapperClass="flex min-w-0 max-w-full items-center gap-2"
                            disclaimerButtonClass="text-slate-400 transition-colors hover:text-slate-600 focus:outline-none"
                        />

                        <div class="mt-1 flex min-w-0 items-center overflow-hidden">
                            <div class="origin-left scale-[0.84] sm:scale-100">
                                <x-insurance.insurance-rating-stars :score="$avgScore" :size="'lg'" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="{{ $hasRank ? 'pl-9 sm:pl-11' : '' }}">
                    <div class="grid grid-cols-3 gap-x-3 text-[12px] leading-tight text-slate-700 sm:gap-x-5 sm:text-[13px]">
                        @foreach ($summaryItems as $item)
                            @php
                                $percentage = $barTotal > 0 ? round(($item['count'] / $barTotal) * 100) : 0;
                            @endphp

                            <div class="flex min-w-0 items-center gap-1 whitespace-nowrap">
                                <span class="min-w-0 truncate font-semibold">{{ $item['label'] }}:</span>
                                <span class="shrink-0">{{ $percentage }} %</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-[#e7ebf1]">
                        <div class="flex h-full w-full">
                            @forelse ($progressItems as $item)
                                @php
                                    $width = $barTotal > 0 ? round(($item['count'] / $barTotal) * 100, 2) : 0;
                                @endphp

                                @if ($width > 0)
                                    <span class="block h-full" style="width: {{ $width }}%; background-color: {{ $item['color'] }};"></span>
                                @endif
                            @empty
                                <span class="block h-full w-full bg-[#e7ebf1]"></span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-1 text-[15px] font-medium text-slate-500">
                        {{ $reviewCountDisplay }} Bewertungen
                    </div>
                </div>
            </div>
        </article>
    </div>
</a>
