<div class="block" x-data="{ showInfos: false }" @click.away="showInfos = false" >

    <div class="bg-white relative rounded transition-shadow duration-300 flex flex-col justify-between h-full  hover:shadow-lg  cursor-pointer"
        :class="showInfos ? 'border-blue-200 border shadow-lg' : 'border-gray-200 border  shadow '"
         @click="showInfos = !showInfos">
        <div class="opacity-70 hover:opacity-100  transition-all duration-200">
            <div  class="flex gap-2 overflow-hidden">
                <div class="shrink-0 py-2 pl-2 pr-1">
                    <div class=" w-min rounded flex items-center justify-center text-base border px-2 font-medium" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                        {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
                    </div>
                </div>
                <div class="grow  min-w-0 py-2  pr-1">
                    <div >
                        <h2 class="text-base break-words  truncate text-ellipsis">
                            {{  $insurance->name }}
                        </h2>
                    </div>
                </div>
                <div class="shrink-0 transition-all overflow-hidden" 
                    x-show="!showInfos" x-collapse.duration.300ms>
                    <div class="h-full bg-gray-100 border-l border-secondary py-1 px-2 flex  items-center gap-2" >
                        @if($insurance->claim_ratings_count > 0)
                            <x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" />
                        @endif
                        <span class=" text-sm  px-2 bg-secondary text-white rounded-full">{{ $insurance->claim_ratings_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <div x-show="showInfos" x-collapse.duration.300ms
            class="relative  w-full bg-primary-50 border  border-t-secondary  border-blue-200 rounded shadow-lg  z-10"
            @click.away="showInfos = false">
            <x-insurance.insurance-card-dropdown-infos :insurance="$insurance" lazy />
        </div>
    </div>
</div>
