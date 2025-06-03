<div class="block" x-data="{ showInfos: false }" @click.away="showInfos = false" >

    <div class="bg-white relative transition-shadow duration-300 flex flex-col justify-between h-full  hover:shadow-lg  cursor-pointer"
        :class="showInfos ? 'border-blue-200 border shadow-lg  rounded-t' : 'border-gray-300 border  overflow-hidden rounded shadow '"
         @click="showInfos = !showInfos">
        <div class="opacity-60 hover:opacity-100  transition-all duration-200">
            <div  class="flex gap-2">
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
                <div class="shrink-0 transition-all " 
                    x-show="!showInfos" x-collapse.duration.300ms>
                    <div class="h-full bg-secondary border-l border-secondary rounded-e pb-1 pt-3 px-1 flex  items-end relative " >
                        <div class="mt-2">
                            <x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" />
                        </div>
                        <span class="absolute right-0 top-0 text-sm rounded-bl px-2 bg-white/70 ">{{ $insurance->claim_ratings_count }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <div x-show="showInfos" x-collapse.duration.300ms
            class="relative  w-full bg-primary-50 border   border-blue-200 rounded-b shadow-lg  z-10"
            @click.away="showInfos = false">
            <x-insurance.insurance-card-dropdown-infos :insurance="$insurance" lazy />
        </div>
    </div>
</div>
