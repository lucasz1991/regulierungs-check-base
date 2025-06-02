<div class="block" x-data="{ showInfos: false }" @click.away="showInfos = false">
    <div class="bg-white rounded border border-gray-200 shadow  transition-shadow duration-300 p-4 flex flex-col justify-between h-full  hover:shadow-lg ">
        <div class="opacity-70 hover:opacity-100  transition-all duration-200 cursor-pointer">
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
        <div class="flex justify-end mt-2">
            <button type="button"
                class="text-sm px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 transition"
                @click.stop="showInfos = !showInfos">
                Infos anzeigen
            </button>
        </div>
        <div x-show="showInfos" class=" items-center justify-between ">
            <x-insurance.insurance-card-dropdown-infos :insurance="$insurance" lazy />
        </div>
    </div>
</div>
