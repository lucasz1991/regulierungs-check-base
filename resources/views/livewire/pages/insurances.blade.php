<div class="container mx-auto p-4">
    <div class="mb-4 flex gap-2">
        <input wire:model.live="search"
               type="text"
               placeholder="Versicherung suchen..."
               class="w-full border border-gray-300 rounded px-4 py-2">
        <select wire:model.live="type"
                class="border border-gray-300 rounded px-4 py-2">
            <option value="">Alle Typen</option>
            @foreach($insuranceTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <table class="table-auto w-full border-collapse border border-gray-200 shadow-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-200 px-4 py-2 text-left">Name</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Score</th>
                <th class="border border-gray-200 px-4 py-2 text-left">Anzahl Bewertungen</th>
            </tr>
        </thead>
        <tbody>
            @forelse($insurances as $insurance)
                <tr>
                    <td class="border border-gray-200 px-4 py-2">{{ $insurance->name }}</td>
                    <td class="border border-gray-200 px-4 py-2">
                        {{ number_format($insurance->ratings_avg_score() ?? 0, 1) }} ⭐
                    </td>
                    <td class="border border-gray-200 px-4 py-2">
                        {{ $insurance->ratings_count() ?? 0 }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Keine Versicherungen gefunden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $insurances->links() }}
    </div>
</div>
