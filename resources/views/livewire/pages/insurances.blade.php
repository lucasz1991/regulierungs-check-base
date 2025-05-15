<div  wire:loading.class="cursor-wait">
  <section class="relative" style="background-image: url('/site-images/background.jpg'); background-size: cover; background-position: 50% 80%;">
    <div class="absolute inset-0 bg-blue-50 opacity-80">
    </div>
    <div class="max-w-4xl mx-auto text-center  py-16 px-6 md:px-12 relative z-10">
      <h2 class="text-3xl md:text-4xl  text-gray-800 mb-4 mt-5">
        Vergleiche Versicherungen 
      </h2>
      <p class="text-lg md:text-xl text-gray-700 pb-8">
        fair, anonym und öffentlich. Erfahre, wie schnell und gerecht andere Kunden entschädigt wurden. Gemeinsam schaffen wir Transparenz im Versicherungsdschungel.
      </p>
      <div>
        <div class=" mb-4 w-full">
            <div class="grid grid-cols-12 ">
                <div
                    class="col-span-5  rounded-md rounded-r-none bg-white  border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-200 focus:shadow-none active:bg-blue-200 hover:bg-blue-300 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    
                >
                <select wire:model="type" placeholder="Typ auswählen"
                            class="appearance-none border-0 text-gray-600 w-full py-2 px-4 pr-8 bg-[#fff0]">
                        <option value="">Alle Typen</option>
                        @foreach($insuranceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div
                    class="col-span-5 rounded-none bg-white  border-l border-r border-slate-700 text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-200 focus:shadow-none active:bg-blue-200 hover:bg-blue-300  active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                   
                >
                    <select wire:model="type" placeholder="Typ auswählen"
                            class="appearance-none border-0 text-gray-600 w-full py-2 px-4 pr-8 bg-[#fff0]">
                        <option value="">Alle Typen</option>
                        @foreach($insuranceTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button
                    class="col-span-2 rounded-md rounded-l-none bg-green-600 py-2 px-4 border border-transparent text-center  text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-200 focus:shadow-none active:bg-blue-200 hover:bg-blue-300  active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                    type="button"
                >
                    Suchen
                </button>
            </div>
        </div>
      </div>
    </div>
  </section>

    <section class="bg-gray-100">
        <div class="container mx-auto p-4 pt-10 pb-8">
            <div class="mb-6">
                <input wire:model.live="search"
                    type="text"
                    placeholder="Versicherung suchen..."
                    class=" w-full border border-gray-300 rounded px-4 py-2 shadow-sm">
                
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
                <x-buttons.button-basic wire:click="loadMore" >
                    Mehr laden
                </x-buttons.button-basic>
            </div>
        </div>
    </section>
</div>
