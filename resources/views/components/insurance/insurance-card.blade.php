<div class="block" x-data="{ showInfos: false }" @click.away="showInfos = false" >
    <div class="bg-white rounded transition-shadow duration-300 p-4 flex flex-col justify-between h-full  hover:shadow-lg  cursor-pointer"
        :class="showInfos ? 'border-blue-200 border-2 shadow-lg' : 'border-gray-200 border-2  shadow '"
         @click="showInfos = !showInfos">
        <div class="opacity-70 hover:opacity-100  transition-all duration-200">
            <div  class="flex gap-2 overflow-hidden">
                <div class="flex-none shrink-0">
                    <div class=" w-min rounded flex items-center justify-center text-base border px-2 font-medium" style="background-color: {{ $insurance->style['bg_color'] ?? '#eee' }}; color: {{ $insurance->style['font_color'] ?? '#333' }}; border-color: {{ $insurance->style['border_color'] ?? '#ccc' }};">
                        {{ strtoupper(substr( $insurance->initials, 0 ,8)) }}
                    </div>
                </div>
                <div class="grow">
                    <div >
                        <h2 class="text-base break-words  truncate text-ellipsis">
                            {{  $insurance->name }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative">
        <div x-show="showInfos" x-collapse.duration.300ms
            class="absolute right-0 -top-1 w-full bg-primary-50 border-b-2 border-l-2 border-t border-r-2  border-blue-200 rounded shadow-lg z-10"
            @click.away="showInfos = false">
            <x-insurance.insurance-card-dropdown-infos :insurance="$insurance" lazy />
        </div>
    </div>
</div>
