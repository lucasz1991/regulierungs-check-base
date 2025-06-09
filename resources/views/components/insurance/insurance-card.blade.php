<div class="block" x-data="{ showInfos: false, hover: false }" @click.away="showInfos = false" >

    <div class="bg-white relative transition-shadow duration-300 flex flex-col justify-between h-full  hover:shadow-lg  cursor-pointer"
        :class="showInfos ? 'border-blue-400 border shadow-lg  rounded-t' : 'border-gray-300 border  overflow-hidden rounded shadow '"
        x-on:mouseenter="hover = true"
        x-on:mouseleave="hover = false"
         @click="showInfos = !showInfos">
        <div class=" transition-all duration-200">
            <div  class="flex  items-stretch gap-2">
                <div class="shrink-0 py-2 pl-2 pr-1 transition-all duration-200"
                    :class="hover || showInfos ? 'opacity-100' : 'opacity-60'">
                @if ($insurance->logo)
                    <img src="{{ asset('storage/' . $insurance->logo) }}"
                        alt=""
                        class=" h-8 mx-auto object-contain rounded">
                @else
                    <div class=" w-min rounded flex items-center justify-center text-sm border px-1 font-medium shadow-sm" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                        {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
                    </div>
                @endif
                </div>
                <div class="grow  min-w-0 py-2  pr-1 transition-all duration-200"
                    :class="hover || showInfos ? 'opacity-100' : 'opacity-90'">
                    <div >
                        <h2 class="text-base break-words  truncate text-ellipsis">
                            {{  $insurance->name }}
                        </h2>
                    </div>
                </div>
                <div class="shrink-0 transition-all relative self-auto" 
                    x-show="!showInfos" x-collapse.duration.600ms>
                    <div class="h-full bg-primary-50 border-l border-secondary rounded-e py-1 px-1 flex  items-center relative " style="">
                        <div class="">
                            <x-insurance.insurance-rating-stars :score="$insurance->ratings_avg_score()" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <div x-show="showInfos" x-collapse.duration.600ms
            class="relative  w-full bg-primary-50 border border-t-0  border-blue-400 rounded-b shadow-lg  z-10"
            @click.away="showInfos = false">
            <x-insurance.insurance-card-dropdown-infos :insurance="$insurance" lazy />
        </div>
    </div>
</div>