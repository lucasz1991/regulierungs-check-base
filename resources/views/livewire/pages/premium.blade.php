<div>
    @php
        $modelle = [
            ['name' => 'Basic', 'preis' => '29 €', 'features' => [
                'Eigenes Profil auf der Plattform',
                'Badge „Zertifizierter Partner“',
                'Öffentliches Partnerverzeichnis',
            ]],
            ['name' => 'Premium', 'preis' => '59 €', 'features' => [
                'Alle Leistungen aus Basic',
                'Priorisierte Darstellung',
                'Lead-Weiterleitung',
            ]],
            ['name' => 'Exklusiv', 'preis' => '129 €', 'features' => [
                'Alle Leistungen aus Premium',
                'Exklusive Region',
                'Prominente Startseitendarstellung',
                'Externe Medienwerbung',
            ]],
        ];
        @endphp
    <section class="text-gray-600 body-font overflow-hidden">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-center w-full mb-20">
                <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">Unsere Abonnement-Modelle</h1>
                <p class="lg:w-2/3 mx-auto leading-relaxed text-base text-gray-500">
                    Wähle das passende Modell für deine Bedürfnisse – von grundlegenden Funktionen bis hin zu exklusiven Vorteilen.
                </p>
                <div class="flex mx-auto border-2 border-indigo-500 rounded overflow-hidden mt-6">
                    <button class="py-1 px-4 bg-indigo-500 text-white focus:outline-none">Monatlich</button>
                    <button class="py-1 px-4 focus:outline-none">Jährlich</button>
                </div>
            </div>

            <div class="flex flex-wrap justify-evenly -m-4">
                @foreach($modelle as $index => $modell)
                    <div class="p-4 xl:w-1/4 md:w-1/2 w-full">
                        <div class="h-full p-6 rounded-lg border-2 {{ $index === 1 ? 'border-indigo-500' : 'border-gray-300' }} flex flex-col relative overflow-hidden">
                            @if($index === 1)
                                <span class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">BELIEBT</span>
                            @endif
                            <h2 class="text-sm tracking-widest title-font mb-1 font-medium">{{ strtoupper($modell['name']) }}</h2>
                            <h1 class="text-5xl text-gray-900 pb-4 mb-4 border-b border-gray-200 leading-none">{{ $modell['preis'] }}/Monat</h1>
                            @foreach($modell['features'] as $feature)
                                <p class="flex items-center text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $feature }}
                                </p>
                            @endforeach
                            <button type="button" class="flex items-center mt-auto text-white {{ $index === 1 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400 hover:bg-gray-500' }} border-0 py-2 px-4 w-full focus:outline-none rounded">
                                Modell anzeigen
                            </button>
                            <p class="text-xs text-gray-500 mt-3">Modell „{{ $modell['name'] }}“ basiert auf echten Nutzererfahrungen.</p>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
    </section>
</div>
