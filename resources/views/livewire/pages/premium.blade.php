<div>
    <section class="text-gray-600 body-font overflow-hidden">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-center w-full mb-20">
                <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">Versicherungsmodelle im Vergleich</h1>
                <p class="lg:w-2/3 mx-auto leading-relaxed text-base text-gray-500">
                    Hier siehst du beispielhafte Modelle, wie Versicherungen ihre Leistungen strukturieren – von Basis bis Premium.
                </p>
                <div class="flex mx-auto border-2 border-indigo-500 rounded overflow-hidden mt-6">
                    <button class="py-1 px-4 bg-indigo-500 text-white focus:outline-none">Monatlich</button>
                    <button class="py-1 px-4 focus:outline-none">Jährlich</button>
                </div>
            </div>
            <div class="flex flex-wrap -m-4">
                @foreach(['Basis', 'Standard', 'Premium'] as $index => $modell)
                    <div class="p-4 xl:w-1/4 md:w-1/2 w-full">
                        <div class="h-full p-6 rounded-lg border-2 {{ $index === 1 ? 'border-indigo-500' : 'border-gray-300' }} flex flex-col relative overflow-hidden">
                            @if($index === 1)
                                <span class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">BELIEBT</span>
                            @endif
                            <h2 class="text-sm tracking-widest title-font mb-1 font-medium">{{ strtoupper($modell) }}</h2>
                            <h1 class="text-5xl text-gray-900 pb-4 mb-4 border-b border-gray-200 leading-none">
                                {{ $index === 0 ? 'Kostenlos' : 'ab ' . (20 + $index * 18) . ' €' }}
                            </h1>
                            <p class="flex items-center text-gray-600 mb-2">
                                <x-heroicon-s-check class="w-4 h-4 mr-2 text-green-500"/> Regulierung innerhalb {{ 30 - $index * 5 }} Tagen
                            </p>
                            <p class="flex items-center text-gray-600 mb-2">
                                <x-heroicon-s-check class="w-4 h-4 mr-2 text-green-500"/> Persönliche Beratung
                            </p>
                            <p class="flex items-center text-gray-600 mb-2">
                                <x-heroicon-s-check class="w-4 h-4 mr-2 text-green-500"/> Unterstützung im Schadensfall
                            </p>
                            <p class="flex items-center text-gray-600 mb-6">
                                <x-heroicon-s-check class="w-4 h-4 mr-2 text-green-500"/> Zugang zu Soforthilfe & Dokumentvorlagen
                            </p>
                            <button type="button" class="flex items-center mt-auto text-white {{ $index === 1 ? 'bg-indigo-500 hover:bg-indigo-600' : 'bg-gray-400 hover:bg-gray-500' }} border-0 py-2 px-4 w-full focus:outline-none rounded">
                                Modell anzeigen
                                <x-heroicon-s-arrow-right class="w-4 h-4 ml-auto"/>
                            </button>
                            <p class="text-xs text-gray-500 mt-3">Modell „{{ $modell }}“ basiert auf echten Nutzererfahrungen.</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
