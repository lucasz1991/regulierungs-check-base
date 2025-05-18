<div>
    <!-- Such-Icon -->
    <div class="flex items-center h-full py-2 pr-4 mr-8">
        <svg wire:click="$set('show', true)" xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 text-gray-500 hover:text-gray-700 cursor-pointer" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm8-2l4 4"/>
        </svg>
    </div>

    <!-- Such-Modal -->
    <div x-data="{ open: @entangle('show') }"
         x-show="open"
         x-transition.opacity
         x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div @click.outside="open = false"
             class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 m-4">
            <h2 class="text-xl font-semibold mb-4">Suche</h2>

            <input type="text"
                   wire:model.live="query"
                   class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Suchbegriff eingeben..."/>

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
