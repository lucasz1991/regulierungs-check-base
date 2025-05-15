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
                <select wire:model="insuranceType" placeholder="Typ auswählen"
                            class="appearance-none border-0 text-gray-600 w-full py-2 px-4 pr-8 bg-[#fff0] max-w-md">
                        <option >Alle Typen</option>
                        @foreach($insuranceTypes as $type)
                            <option class="max-w-md" value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div
                    class="col-span-5 rounded-none bg-white  border-l border-r border-slate-700 text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-200 focus:shadow-none active:bg-blue-200 hover:bg-blue-300  active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                   
                >
                    <select wire:model="insuranceSubType" placeholder="Art auswählen"
                            class="appearance-none border-0 text-gray-600 w-full py-2 px-4 pr-8 bg-[#fff0] max-w-md">
                        <option >Alle Arten</option>
                        @foreach($insuranceSubTypes as $SubType)
                            <option class="max-w-md" value="{{ $SubType->id }}">{{ $SubType->name }}</option>
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

    <section  x-data="{ showFilters: true }" class="bg-gray-100">
        <div class="container mx-auto p-4 pt-10 pb-8">
            <div class="grid grid-cols-12 gap-6" >
                <div x-show="showFilters" x-transition  class="filter-sidebar col-span-2">
                    <div class="relative flex  w-full max-w-[20rem] flex-col rounded-xl bg-white bg-clip-border p-2 text-gray-700 shadow-xl shadow-gray-900/5">
                        
                        <div class="flex items-center justify-left p-4 mb-2">
                            <button @click="showFilters = !showFilters" class="text-sm text-blue-600 hover:underline p-2 rounded-full bg-gray-200 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>                            
                            </button>
                            <h5 class="text-xl font-semibold text-gray-400">Filter</h5>

                        </div>
                        <div class="p-2" >
                            <div class="relative h-10 w-full min-w-[150px]">
                            <div class="absolute grid w-5 h-5 top-2/4 right-3 -translate-y-2/4 place-items-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                aria-hidden="true" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                </svg>
                            </diqev>
                            <input wire:model.live="search"
                                class="peer h-full w-full rounded-[7px] border border-blue-200 border-t-transparent bg-transparent px-3 py-2.5 !pr-9 font-sans text-sm font-normal text-gray-700 outline outline-0 transition-all placeholder-shown:border placeholder-shown:border-blue-200 placeholder-shown:border-t-gray-200 focus:border-2 focus:border-gray-900 focus:border-t-transparent focus:outline-0 disabled:border-0 disabled:bg-gray-50"
                                placeholder="" />
                            <label
                                class="before:content[' '] after:content[' '] pointer-events-none absolute left-0 -top-1.5 flex h-full w-full select-none !overflow-visible truncate text-[11px] font-normal leading-tight text-gray-500 transition-all before:pointer-events-none before:mt-[6.5px] before:mr-1 before:box-border before:block before:h-1.5 before:w-2.5 before:rounded-tl-md before:border-t before:border-l before:border-blue-200 before:transition-all after:pointer-events-none after:mt-[6.5px] after:ml-1 after:box-border after:block after:h-1.5 after:w-2.5 after:flex-grow after:rounded-tr-md after:border-t after:border-r after:border-blue-200 after:transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:leading-[3.75] peer-placeholder-shown:text-gray-500 peer-placeholder-shown:before:border-transparent peer-placeholder-shown:after:border-transparent peer-focus:text-[11px] peer-focus:leading-tight peer-focus:text-gray-900 peer-focus:before:border-t-2 peer-focus:before:border-l-2 peer-focus:before:!border-gray-900 peer-focus:after:border-t-2 peer-focus:after:border-r-2 peer-focus:after:!border-gray-900 peer-disabled:text-transparent peer-disabled:before:border-transparent peer-disabled:after:border-transparent peer-disabled:peer-placeholder-shown:text-gray-500">
                                Suchen
                            </label>
                            </div>
                        </div>
                        <div class="p-2">
                            <label class="block text-sm text-gray-600 mb-1">Mind. Bewertungen</label>
                            <input type="number" wire:model.debounce.500ms="minRatingCount"
                                min="0"
                                class="w-full px-3 py-2 border rounded-md text-sm border-blue-200" />
                        </div>
                        <div class="p-2">
                            <label class="block text-sm text-gray-600 mb-1">Mind. Ø Score</label>
                            <select wire:model="minAvgScore"
                                    class="w-full px-3 py-2 border rounded-md text-sm border-blue-200">
                                <option value="">Keine Auswahl</option>
                                @foreach([0.5, 1, 2, 3, 4, 4.5] as $val)
                                    <option value="{{ $val }}">{{ $val }}+</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-2">
                            <label class="block text-sm text-gray-600 mb-1">Sortierung</label>
                            <select wire:model="sort"
                                    class="w-full px-3 py-2 border rounded-md text-sm border-blue-200">
                                <option value="">Keine Auswahl</option>
                                <option value="name_asc">Name A–Z</option>
                                <option value="name_desc">Name Z–A</option>
                                <option value="score_desc">Ø Bewertung absteigend</option>
                                <option value="score_asc">Ø Bewertung aufsteigend</option>
                                <option value="ratings_desc">Anzahl Bewertungen absteigend</option>
                                <option value="ratings_asc">Anzahl Bewertungen aufsteigend</option>
                            </select>
                        </div>

                        <div class="p-2">
                            <x-buttons.button-basic wire:click="resetFilters" class="mt-4 text-sm text-blue-600 w-full">
                                Filter zurücksetzen
                            </x-buttons.button-basic>
                        </div>
                    </div>
                    
                </div>
                <div class="filter-sidebar" :class="showFilters ? 'col-span-10' : 'col-span-12'" x-transition>

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
                    @if($insurances->count() >= $perPage * $pages)
                        <div class="mt-6 text-center">
                            <x-buttons.button-basic wire:click="loadMore">
                                Mehr laden
                            </x-buttons.button-basic>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
</div>
