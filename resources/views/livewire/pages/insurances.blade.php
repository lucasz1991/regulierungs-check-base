<div class="container mx-auto p-4">
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
                <div class="bg-white rounded-lg border border-gray-200 shadow hover:shadow-lg transition-shadow duration-300 p-4">
                    <h2 class="text-xl font-semibold mb-2 ">
                        <div class="aspect-square w-12 rounded-full flex items-center justify-center text-white text-base font-bold float-start mr-2" style="background-color: {{ $insurance->color ?? '#ccc' }};">
                            {{ strtoupper(substr( $insurance->initials, 0 ,4)) }}
                        </div>
                        {{ $insurance->name }}
                    </h2>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="font-medium">Score:</span>
                            <span class="text-yellow-500">
                                {{ number_format($insurance->claim_ratings_avg_rating_score ?? 0, 1) }} ⭐
                            </span>
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
