@props([
    'insurance',
    'isSubTypeFilter' => false,
    'subTypeFilterSubType' => null,
    'subTypeFilterSubTypeIds' => [],
    'selectedInsuranceTypeId' => null,
])

@php
    $selectedSubtypeIds = collect($subTypeFilterSubTypeIds ?? [])
        ->filter(fn ($id) => !is_null($id) && $id !== '')
        ->map(fn ($id) => (int) $id)
        ->values()
        ->all();

    $selectedSubtypeId = is_object($subTypeFilterSubType)
        ? $subTypeFilterSubType?->id
        : $subTypeFilterSubType;

    if (empty($selectedSubtypeIds) && $selectedSubtypeId) {
        $selectedSubtypeIds = [(int) $selectedSubtypeId];
    }

    $selectedTypeIds = $selectedInsuranceTypeId ? [(int) $selectedInsuranceTypeId] : [];

    $filterQuery = [];

    if (!empty($selectedTypeIds)) {
        $filterQuery['types'] = implode(',', $selectedTypeIds);
    }

    if (empty($selectedTypeIds) && !empty($selectedSubtypeIds)) {
        $filterQuery['subtypes'] = implode(',', $selectedSubtypeIds);
    }

    $insuranceUrl = route('insurance.show-insurance', array_merge(
        ['insurance' => $insurance->slug],
        $filterQuery
    ));

    $hasTypeFilter = !empty($selectedTypeIds);
    $hasSubtypeFilter = !$hasTypeFilter && !empty($selectedSubtypeIds);
    $typeSubtypeIds = $hasTypeFilter ? $selectedSubtypeIds : [];
    $ratingSubtypeIds = $hasSubtypeFilter ? $selectedSubtypeIds : [];

    $avgDuration = $insurance->avgRatingDurationByTypeAndSubtypeIds($selectedTypeIds, $typeSubtypeIds, $ratingSubtypeIds);
    $avgScore = $insurance->published_ratings_avg_scoreByTypeAndSubtypeIds($selectedTypeIds, $typeSubtypeIds, $ratingSubtypeIds);
    $publishedCount = $insurance->published_claimRatingsCountByTypeAndSubtypeIds($selectedTypeIds, $typeSubtypeIds, $ratingSubtypeIds);

    $avgDurationDisplay = is_null($avgDuration) ? '-' : round($avgDuration);
@endphp

<a href="{{ $insuranceUrl }}" class="block  hover:shadow-lg  cursor-pointer bg-white border border-slate-200 rounded-xl shadow-xl" x-data="{ hover: false }" @click.away="showInfos = false" x-cloak>
    <div class="px-2 py-2 relative transition-shadow duration-300 flex flex-col justify-between h-full"
        x-on:mouseenter="hover = true"
        x-on:mouseleave="hover = false"
        >
        <div class=" transition-all duration-200">
            <x-insurance.insurance-name-startswiper :insurance="$insurance" />
            <div class="shrink-0 transition-all relative self-auto" >
                <div class="mt-3">
                    <div class="text-sm text-gray-500 font-medium text-center mb-1">
                        <div class="w-12 mx-auto  text-[11px] leading-tight text-white p-2 aspect-square bg-secondary-light ring-2 ring-offset-2 ring-secondary-light  rounded-full flex justify-center items-center">
                            <span>Ø:&nbsp;{{ $avgDurationDisplay }}<br>Tage</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="">
            <div class=" w-full mb-1">
                <x-insurance.top-insurance-banner.insurance-rating-stars :score="$avgScore" :size="'xs'" />
            </div>
            <div class=" w-full">
                <div class="text-gray-500 flex items-center justify-center text-[10px] font-medium">
                        Bewertungen {{ $publishedCount }}
                </div>
            </div>
        </div>
    </div>
</a>
