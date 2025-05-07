<div class="bg-gray-100">
    <div class="container mx-auto p-4 pt-10 pb-8">
        <div class="mb-6 flex gap-2">
            <input wire:model.live="search"
                   type="text"
                   placeholder="Versicherung suchen..."
                   class="w-full border border-gray-300 rounded px-4 py-2 shadow-sm">
            
            <select wire:model.live="type"
                    class="border border-gray-300 rounded px-4 py-2 shadow-sm">
                <option value="">Alle Typen</option>
                @foreach($insuranceTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        @if($insurances->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($insurances as $insurance)
                    <div class="bg-white rounded-lg border border-gray-200 shadow hover:shadow-lg transition-shadow duration-300 p-4 flex flex-col justify-between h-full">
                        <div class="grid grid-cols-12 gap-4 mb-4">
                            <div class="col-span-2 aspect-square w-12 rounded-full flex items-center justify-center text-white text-base font-bold" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                                {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                            </div>
                            <div class="col-span-10">
                                <h2 class="text-xl break-words font-semibold mb-2">
                                    {{ $insurance->name }}
                                </h2>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <x-insurance.insurance-rating-stars :score="$insurance->claim_ratings_avg_rating_score" />
                            </div>
                            <div>
                                <span class="font-medium">Bewertungen:</span>
                                <span class="text-gray-700">
                                    {{ $insurance->claim_ratings_count ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                Keine Versicherungen gefunden.
            </div>
        @endif
        <div class="mt-6">
            {{ $insurances->links() }}
        </div>
    </div>
</div>
