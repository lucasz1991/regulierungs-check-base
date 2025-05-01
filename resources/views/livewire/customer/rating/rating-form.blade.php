<div class=""
    x-data="{ 
     step: @entangle('step')
    }">
    @if (session('message'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
        {{ session('message') }}
    </div>
    @endif
    {{-- Step 0: Versicherungs typ --}}
    <div x-show="step == 0"  x-cloak  x-collapse.duration.1000ms>
        <h2 class="text-lg font-bold mb-4">Jetzt Fall melden</h2>
        <h2 class="text-lg mb-4">Wähle deine Versicherungskategorie</h2>
        <div x-data="{
                    insuranceTypeId: null
                }">
            <link rel="stylesheet" href="/adminresources/css/swiper-bundle.min.css">
            <script src="/adminresources/js/swiper-bundle.min.js"></script>
            <div 
                x-data="{
                    swiper: null,
                    initSwiper() {
                        this.swiper = new Swiper(this.$refs.swiper, {
                            effect: 'coverflow',
                            grabCursor: true,
                            centeredSlides: true,
                            slidesPerView: '3',
                            initialSlide: 1,
                            coverflowEffect: {
                                rotate: 30,
                                stretch: 0,
                                depth: 100,
                                modifier: 1,
                                slideShadows: true,
                            },
                            navigation: {
                                nextEl: this.$refs.next,
                                prevEl: this.$refs.prev,
                            },
                        });
                    }
                }"
                x-init="initSwiper()"
                class="relative w-full"
            >
                {{-- Navigation links/rechts außerhalb --}}
                <div class="absolute top-1/2 -left-8 transform -translate-y-1/2 z-10 mt-3">
                    <div class="swiper-button-prev !static" x-ref="prev"></div>
                </div>
                <div class="absolute top-1/2 -right-8 transform -translate-y-1/2 z-10 mt-3">
                    <div class="swiper-button-next !static" x-ref="next"></div>
                </div>
                <div class="swiper w-full" x-ref="swiper" wire:ignore>
                    <div class="swiper-wrapper">
                        @foreach ($types as $type)
                            <div class="swiper-slide h-full" wire:key="type-{{ $type->id }}">
                                <div 
                                    wire:click="$set('insuranceTypeId', {{ $type->id }})"
                                    @click="insuranceTypeId = {{ $type->id }}"
                                    :class="insuranceTypeId == {{ $type->id }} 
                                        ? 'bg-blue-100 border-blue-500' 
                                        : 'hover:bg-gray-100 bg-white'"
                                    class="border rounded p-4 text-center cursor-pointer w-64 h-20 flex justify-center items-center  shadow-md transition duration-300 ease-in-out ">
                                    <h3 class="font-bold text-lg h-auto">{{ $type->name }}</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            @error('insuranceTypeId')
                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
            @enderror
            <div class="flex justify-center mt-6">
                <div x-show="insuranceTypeId != null"  x-cloak  x-collapse.duration.1000ms>
                    <x-button wire:click="nextStep">Weiter</x-button>
                </div>
            </div>
        </div>

    </div>
    {{-- Step 1: Versicherungs SubType --}}
    <div x-show="step == 1"  x-cloak  x-collapse.duration.1000ms>
        <div x-data="{ insuranceSubTypeId: @entangle('insuranceSubTypeId') }">
            <h2 class="text-lg mb-4">Wähle deine Versicherungsart</h2>
            <div class="max-w-md mx-auto">

                <select wire:model.live="insuranceSubTypeId" class="w-full border rounded px-4 py-2" id="positionSelect" 
                    x-init="
                            let choices = new Choices($el, {
                                removeItemButton: false, // ✅ EINZELAUSWAHL
                                shouldSort: false,
                                searchEnabled: true,
                                placeholder: true,
                                searchChoices: true,
                                itemSelectText: '',
                            });
    
                            $el.addEventListener('change', (e) => {
                                insuranceSubTypeId = e.target.value;
                            });
    
                            $nextTick(() => {
                                if (insuranceSubTypeId > 0) {
                                    choices.setChoiceByValue(insuranceSubTypeId);
                                }
                            });
                        "
                    >
                    <option value="">Bitte auswählen</option>
                    @foreach ($insuranceSubTypes as $insuranceSubType)
                        <option value="{{ $insuranceSubType->id }}">{{ $insuranceSubType->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('insuranceSubTypeId')
                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-between mt-6">
            <x-button wire:click="previousStep">Zurück</x-button>
            @if ($insuranceSubTypeId)
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    </div>
    {{-- Step 2: Konkrete Versicherung auswählen --}}
    <div x-show="step == 2"  x-data="{ insuranceId: @entangle('insuranceId') }" x-cloak  x-collapse.duration.1000ms>
        <h2 class="text-lg font-bold mb-4">Welche Versicherungsgesellschaft?</h2>
        <div class="max-w-md mx-auto ">

            <select wire:model.live="insuranceId" class="border rounded px-4 py-2"
                    x-init="
                            let choices = new Choices($el, {
                                removeItemButton: false, // ✅ EINZELAUSWAHL
                                shouldSort: false,
                                searchEnabled: true,
                                placeholder: true,
                                searchChoices: true,
                                itemSelectText: '',
                            });
    
                            $el.addEventListener('change', (e) => {
                                insuranceId = e.target.value;
                            });
    
                            $nextTick(() => {
                                if (insuranceId > 0) {
                                    choices.setChoiceByValue(insuranceId);
                                }
                            });
                        "
                    >
                <option value="">Bitte auswählen</option>
                @foreach ($insurances ?? [] as $insurance)
                    <option value="{{ $insurance->id }}">{{ $insurance->name }}</option>
                @endforeach
            </select>
        </div>
        @error('insuranceId')
            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
        @enderror
        <div class="flex justify-between mt-6">
            <x-button wire:click="previousStep">Zurück</x-button>
            @if ($insuranceId)
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    </div>
    {{-- Step 3: Fallstatus --}}
    <div x-show="step == 3"  x-cloak  x-collapse.duration.1000ms>
        <h2 class="text-lg font-bold mb-4">Wurde der Fall bereits abgeschlossen?</h2>
        
        <div class="flex gap-4 justify-center">
            {{-- Karte: Ja --}}
            <div 
                wire:click="$set('is_closed', 1)"
                class="w-32 h-12 border rounded-lg flex items-center justify-center text-center cursor-pointer transition duration-300 ease-in-out 
                    {{ $is_closed === 1 ? 'bg-blue-100 border-blue-500' : 'hover:bg-gray-100 bg-white' }}">
                <span class="text-lg font-semibold">Ja</span>
            </div>

            {{-- Karte: Nein --}}
            <div 
                wire:click="$set('is_closed', 0)"
                class="w-32 h-12 border rounded-lg flex items-center justify-center text-center cursor-pointer transition duration-300 ease-in-out 
                    {{ $is_closed === 0 ? 'bg-blue-100 border-blue-500' : 'hover:bg-gray-100 bg-white' }}">
                <span class="text-lg font-semibold">Nein</span>
            </div>
        </div>

        @error('is_closed')
            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
        @enderror
        <div class="flex justify-between mt-6">
            <x-button wire:click="previousStep">Zurück</x-button>
            @if (!is_null($is_closed))
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    </div>
    {{-- Step 4: Zeitraum --}}
    <div x-show="step == 4"  x-cloak  x-collapse.duration.1000ms>
        <div>
            <h2 class="text-lg font-bold mb-4">Wann hat der Fall begonnen?</h2>
            <label class="block text-sm font-medium text-gray-700">Startdatum</label>
            <input type="date" wire:model.live="started_at" class="w-full border rounded px-4 py-2">
            @if ($is_closed)
                <label class="block text-sm font-medium text-gray-700 mt-4">Enddatum</label>
                <input type="date" wire:model.live="ended_at" class="w-full border rounded px-4 py-2">
            @endif
            @error('started_at')
                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
            @enderror
            @if ($is_closed)
                @error('ended_at')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            @endif
        </div>
        <div class="flex justify-between mt-6">
            <x-button wire:click="previousStep">Zurück</x-button>
            @if ($started_at && (!$is_closed || ($is_closed && $ended_at)))
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    </div>
    {{-- Step 5: Fragen durchgehen --}}
        @foreach ($questions as $index => $q)
            @php
                $currentStep = $standardSteps + $index;
                $fieldName = "answers." . $q->id;
            @endphp
            <div x-show="step === {{ $currentStep }}" x-collapse.duration.1000ms x-cloak >
                <h2 class="text-lg font-bold mb-2">Frage {{ $currentStep + 1 }} von {{ $totalSteps  }}</h2>
                <p class="text-md text-gray-800 mb-2 font-semibold">{{ $q->question_text }}</p>
                {{-- Eingabefeld je nach Typ --}}
                @switch($q->type)
                    @case('text')
                    @case('number')
                    @case('date')
                        <input type="{{ $q->type }}"
                            wire:model.defer="{{ $fieldName }}"
                            class="mt-2 w-full border px-3 py-2 rounded">
                        @break
                    @case('textarea')
                        <textarea wire:model.defer="{{ $fieldName }}"
                                class="mt-2 w-full border px-3 py-2 rounded" rows="4"></textarea>
                        @break
                    @case('boolean')
                        <div class="mt-2 space-x-4">
                            <label><input type="radio" wire:model="{{ $fieldName }}" value="1"> Ja</label>
                            <label><input type="radio" wire:model="{{ $fieldName }}" value="0"> Nein</label>
                        </div>
                        @break
                    @case('rating')
                        <div class="flex space-x-1 mt-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <label>
                                    <input type="radio" wire:model="{{ $fieldName }}" value="{{ $i }}">
                                    <span class="text-yellow-400 text-lg">★</span>
                                </label>
                            @endfor
                        </div>
                        @break
                    @default
                        <p class="text-sm text-red-500">Unbekannter Fragetyp: {{ $q->type }}</p>
                @endswitch
                @error($fieldName)
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
                {{-- Navigation --}}
                <div class="flex justify-between mt-6">
                    @if ($step > 0)
                        <x-button type="button" wire:click="previousStep">Zurück</x-button>
                    @endif
                    <x-button type="button" wire:click="{{ ($currentStep + 1) === $totalSteps ? 'submit' : 'nextStep' }}">
                        {{ $currentStep === $totalSteps ? 'Absenden' : 'Weiter' }}
                    </x-button>
                </div>
            </div>
        @endforeach
            <link rel="stylesheet" href="{{ URL::asset('adminresources/flatpickr/flatpickr/flatpickr.min.css') }}">
            <link href="{{ URL::asset('adminresources//choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
        
            <script src="{{ URL::asset('adminresources//choices.js/public/assets/scripts/choices.min.js') }}"></script>
        
</div>
