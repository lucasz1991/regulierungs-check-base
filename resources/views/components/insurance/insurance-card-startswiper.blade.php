@props(['insurance', 'isSubTypeFilter', 'subTypeFilterSubType'])
<a href="{{ route('insurance.show-insurance', $insurance->slug) }}" class="block  hover:shadow-lg  cursor-pointer overflow-hidden   rounded shadow" x-data="{ hover: false }" @click.away="showInfos = false" x-cloak>
    <div class="bg-white px-2 py-2 relative transition-shadow duration-300 flex flex-col justify-between h-full"
        x-on:mouseenter="hover = true"
        x-on:mouseleave="hover = false"
        >
        <div class=" transition-all duration-200">
            <x-insurance.insurance-name-startswiper :insurance="$insurance" />
            <div class="shrink-0 transition-all relative self-auto" >
                <div class="mt-5">
                    <div class="text-sm text-gray-500 font-medium text-center mb-3">
                        <div class="w-16 mx-auto text-xs text-white p-2 aspect-square bg-[#223d65]  rounded-full flex justify-center items-center">
                            <span>Ø: {{ round($insurance->avgRatingDurationBySubtype($subTypeFilterSubType?->id)) }}<br> Tage</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="" style="">
            <div class=" w-full ">
                <x-insurance.top-insurance-banner.insurance-rating-stars :score="$insurance->ratings_avg_score()" :size="'xs'" />
            </div>
            <div class=" w-full">
                <div class="text-gray-500 flex items-center justify-center text-[10px] font-medium">
                        Bewertungen {{ $insurance->published_claimRatingsCountBySubtype($subTypeFilterSubType?->id) }}
                </div>
            </div>
        </div>
    </div>
</a>