@props(['insurance', 'isSubTypeFilter', 'subTypeFilterSubType'])
<div class="block bg-[#3684b5] p-2 hover:shadow-lg  cursor-pointer    rounded shadow" x-data="{ hover: false }" @click.away="showInfos = false" x-cloak>
    <div class="bg-white px-2 py-2 relative transition-shadow duration-300 flex flex-col justify-between h-full  rounded "
        x-on:mouseenter="hover = true"
        x-on:mouseleave="hover = false"
        >
        <div class=" transition-all duration-200">
            <x-insurance.insurance-name-startswiper :insurance="$insurance" />
            <div class="shrink-0 transition-all relative self-auto" >
                <div  class="" >
                    </div>
                    <div class="mt-5">
                        <div class="text-sm text-gray-500 font-medium text-center mb-3">
                            <div class="w-16 mx-auto text-xs text-white p-2 aspect-square bg-[#223d65]  rounded-full flex justify-center items-center"><span>Ø: {{ round($insurance->avgRatingDurationBySubtype($subTypeFilterSubType?->id)) }}<br> Tage</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-2" style="">
            <div class="bg-[#013370] p-1 w-full rounded mb-2">
                <x-insurance.insurance-rating-stars :score="$insurance->ratings_avg_score()" :size="'xs'"  class="text-white"/>
            </div>
            <div class="bg-[#013370] p-1 w-full rounded">
                <div class="flex items-center justify-center text-[10px] text-white font-medium">
                    Bewertungen {{ $insurance->claimRatingsCountBySubtype($subTypeFilterSubType?->id) }}
                </div>
            </div>
        </div>
</div>