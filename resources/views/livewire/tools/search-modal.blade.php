<div x-data="{ open: @entangle('show') }">
    <!-- Such-Icon -->
    <div class="flex items-center h-full py-2 pr-4 mr-8" >
        <svg 
            wire:click="$set('show', true); window.scrollTo({ top: 0, behavior: 'smooth' });" 
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6  hover:text-gray-700 cursor-pointer transition-all duration-200 ease-in-out" 
            :class="open ? 'text-secondary bg-gray-300' : 'text-gray-500'"
            fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm8-2l4 4"/>
        </svg>
    </div>
    <template x-teleport="#megamenu">
        <!-- Such-Modal -->
        <div x-show="open" x-transition class="relative z-50">
            <div @click.outside="open = false"
                class="fixed bg-white w-full  p-6 border-gray-300 shadow-lg ">
                <div class="container mx-auto">
                    <div class="flex "> 
                        <input type="text"
                            wire:model.live="query"
                            class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Suche..."/>
                        <!-- Auswahl Suchtyp -->
                        <select wire:model.live="searchType"
                                class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="insurance">Versicherung</option>
                            <option value="insurance_type">Versicherungs-Typ</option>
                            <option value="content">Artikel</option>
                        </select>
                    </div>
    
                    <ul class="mt-4 max-h-60 overflow-y-auto divide-y divide-gray-200">
                        @forelse($resultsInsurances as $resultsInsurance)
                            <li class="py-2 cursor-pointer hover:bg-gray-100 px-2 rounded"
                                wire:click="selectResult({{ $resultsInsurance['id'] }})">
                                {{ $resultsInsurance['name'] }}
                            </li>
                        @empty
                            <li class="py-2 text-gray-500 px-2">Keine Ergebnisse</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </template>
</div>
