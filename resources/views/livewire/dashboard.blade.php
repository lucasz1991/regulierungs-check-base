<div class="w-full relative bg-cover bg-center bg-gray-100 py-20" wire:loading.class="cursor-wait">
    @section('title')
        {{ __('Mein Bereich') }}
    @endsection

    <div class="max-w-7xl mx-auto px-5">

        <div class="mr-auto font-semibold text-2xl place-self-center">
            <h1 class="max-w-2xl mb-4 font-bold tracking-tight leading-none text-2xl xl:text-3xl">
                Willkommen {{ $userData->name }},
            </h1>
            <p class="max-w-2xl mb-6 text-gray-500 md:text-lg lg:text-xl">
                Deine Bewertungen und dein Versicherungsprofil im Überblick
            </p>
        </div>

        <!-- Statistiken -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Abgegebene Bewertungen</h2>
                <p class="text-3xl font-bold text-gray-500">{{ $ratingsCount }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Verifiziert</h2>
                <p class="text-3xl font-bold text-green-600">{{ $verifiedRatingsCount }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">In Prüfung</h2>
                <p class="text-3xl font-bold text-yellow-600">{{ $pendingRatingsCount }}</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-5">
                <h2 class="text-lg font-semibold text-gray-700">Durchschnittliche Bewertung</h2>
                <p class="text-3xl font-bold text-indigo-500">{{ number_format($averageScore, 1) }}/5</p>
            </div>
        </div>

        <!-- Bewertungen -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Deine letzten Bewertungen</h2>
        <div class="grid grid-cols-1 gap-6">
            @forelse ($claimRatings as $rating)
                <div class="bg-white shadow rounded-lg p-5" wire:key="rating-{{ $rating->id }}" x-data="{ open: false }">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                {{ $rating->insurance->name ?? 'Unbekannte Versicherung' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Eingereicht am {{ $rating->created_at->format('d.m.Y') }}
                            </p>
                            <p class="text-sm mt-2">
                                Status:
                                @switch($rating->status)
                                    @case('open')
                                        <span class="text-blue-600 font-medium">Offen</span>
                                        @break
                                    @case('pending')
                                        <span class="text-yellow-600 font-medium">In Prüfung</span>
                                        @break
                                    @case('verified')
                                        <span class="text-green-600 font-medium">Verifiziert</span>
                                        @break
                                    @case('rejected')
                                        <span class="text-red-600 font-medium">Abgelehnt</span>
                                        @break
                                    @default
                                        <span class="text-gray-600 font-medium">Unbekannt</span>
                                @endswitch
                            </p>

                        </div>

                        <button @click="open = !open" class="text-blue-500 hover:underline">Optionen</button>
                    </div>

                    <div x-show="open" x-collapse x-cloak class="mt-4 border-t pt-3 space-y-2 text-sm text-gray-700">
                        @if (!$rating->user_id)
                            <p class="text-yellow-600">Noch nicht verifiziert – <a href="{{ route('login') }}" class="text-blue-600 underline">Jetzt verifizieren</a></p>
                        @endif

                        <a href=""
                           class="inline-block text-sm text-blue-600 hover:underline">Details ansehen</a>

                        <a href="#" wire:click.prevent="delete({{ $rating->id }})"
                           class="inline-block text-sm text-red-500 hover:underline">Löschen</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Du hast noch keine Bewertungen abgegeben.</p>
            @endforelse

            @if ($claimRatings->hasPages())
                <div class="mt-6">
                    {{ $claimRatings->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
