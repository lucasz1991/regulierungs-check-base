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
                                @php
                                    $score = round($insurance->claim_ratings_avg_rating_score ?? 0, 1);
                                    $percentage = ($score / 5) * 100;
                                @endphp

                                <div class="relative flex items-center">
                                    <div class="flex w-full text-gray-300">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.54-1.118l1.286-3.957a1 1 0 00-.364-1.118L2.049 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
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
