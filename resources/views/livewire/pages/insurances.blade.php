<div class="bg-gray-100">
    <div class="container mx-auto p-4 pt-10 pb-8">
        <div class="mb-6 grid  grid-cols-12 gap-2">
            <input wire:model.live="search"
                   type="text"
                   placeholder="Versicherung suchen..."
                   class=" col-span-9 w-full border border-gray-300 rounded px-4 py-2 shadow-sm">
            <select wire:model.live="type"
                    class="col-span-3 border border-gray-300 rounded px-4 py-2 shadow-sm">
                <option value="">Alle Typen</option>
                @foreach($insuranceTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        @if($insurances->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($insurances as $insurance)
                    <a href="/insurance/{{ $insurance->id }}" wire:navigate class="block">
                        <div class="bg-white rounded-lg border border-gray-200 shadow  transition-shadow duration-300 p-4 flex flex-col justify-between h-full @if($insurance->claim_ratings_count <= 0) disabled opacity-30 @else hover:shadow-lg @endif">
                            <div class="grid grid-cols-12 gap-4 mb-4">
                                <div class="col-span-2 pr-4">
                                    <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-base font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                                        {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                                    </div>
                                </div>
                                <div class="col-span-10">
                                    <h2 class="text-xl break-words font-semibold mb-2">
                                        {{ $insurance->name }}
                                    </h2>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($insurance->claim_ratings_count > 0)
                                     <x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" />
                                    @else
                                        <span class="text-gray-500">Keine Bewertungen</span>
                                    @endif
                                </div>
                                <div>
                                    @if($insurance->claim_ratings_count > 0)
                                        <span class="font-medium">Bewertungen:</span>
                                        <span class="text-gray-700">
                                            {{ $insurance->claim_ratings_count ?? 0 }}
                                        </span> 
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                Keine Versicherungen gefunden.
            </div>
        @endif
        <div class="mt-6 text-center">
            <button wire:click="loadMore" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                Mehr laden
            </button>
        </div>
    </div>
</div>
