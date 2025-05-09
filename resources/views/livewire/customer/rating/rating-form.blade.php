<div class=""
    x-data="{ 
     step: @entangle('step'),
     showFormModal: @entangle('showFormModal'),
    }">



    @if (session('message'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
        {{ session('message') }}
    </div>
    @endif
    <div>
        <div class="flex space-x-4 items-center justify-center mb-4 w-full">
            <a href="#" wire:click="$set('showFormModal', true)" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                Jetzt bewerten
            </a>
            <a href="/insurances"  class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">
                Vergleichen 
            </a>
        </div>
    </div>
    <x-rating-form-modal wire:model="showFormModal">
        <x-slot name="content">

            {{-- Step 0: Versicherungs typ --}}
            <div x-show="step == 0"  x-cloak x-collapse.duration.1000ms>
                <h2 class="text-lg font-bold mb-4">Jetzt Fall melden</h2>
                <h2 class="text-lg mb-12">Versicherungskategorie auswählen</h2>
                <style>
                    .swiper {
                        padding: 0px 0px 60px 0px;
                    }
                    .swiper-pagination.swiper-pagination-clickable.swiper-pagination-bullets {
                        
                    }
                </style>
                <div x-data="{
                            insuranceTypeId: null
                        }">
                    
                    <div x-data="{
                            swiper: null,
                            initSwiper() {
                                this.swiper = new Swiper(this.$refs.swiper, {
                                    effect: 'coverflow',
                                    freeMode: {
                                        enabled: true,
                                        sticky: true,
                                    },
                                    autoplay: {
                                        delay: 1800,
                                    },
                                    disableOnInteraction: true,
                                    speed: 1000,
                                    loop: true,
                                    centeredSlides: true,
                                    slidesPerView: '2',
                                    breakpoints: {
                                        640: {
                                            slidesPerView: 2,
                                            spaceBetween: 20,
                                        },
                                        768: {
                                            slidesPerView: 3,
                                            spaceBetween: 40,
                                        },
                                    },
                                    coverflowEffect: {
                                        rotate: 50,
                                        stretch: 0,
                                        depth: 100,
                                        modifier: 1,
                                        slideShadows: false,
                                    },
                                    pagination: {
                                        el: '.swiper-pagination',
                                        clickable: true,
                                    },
                                });
                                this.swiper.slideNext();
                            },
                            stopSwiper() {
                                this.swiper.autoplay.stop();
                            }
                        }"
                        x-init="initSwiper() "
                            x-on:click="swiper.autoplay.stop()"
                        class="max-w-full relative w-full"
                        wire:ignore
                    >
                        {{-- Navigation links/rechts außerhalb --}}
                        
                        <div class="swiper w-full" x-ref="swiper" >
                            <div class="swiper-wrapper pointer-events-none">
                                @foreach ($types as $type)
                                    <div class="swiper-slide h-full !z-10" wire:key="type-{{ $type->id }}"
                                            wire:click="$set('insuranceTypeId', {{ $type->id }})"
                                            @click="insuranceTypeId = {{ $type->id }}">
                                        <div 
                                            :class="insuranceTypeId == {{ $type->id }} 
                                                ? 'bg-blue-100 border-blue-500' 
                                                : 'hover:bg-gray-100 bg-slate-100'"
                                            class="border rounded p-2 text-center cursor-pointer w-[95%] h-24 flex justify-center items-center shadow-md transition duration-300 ease-in-out pointer-events-auto">
                                            <h3 class="font-bold max-w-full overflow-hidden text-xs md:text-base h-auto break-words">{{ $type->name }}</h3>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                              <!-- If we need pagination -->
                            <div class="swiper-pagination"></div>
        
        
                        </div>
                    </div>
                    @error('insuranceTypeId')
                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-center mt-12">
                        <div x-show="insuranceTypeId != null"  x-cloak  x-collapse.duration.1000ms>
                            <x-furtherbutton wire:click="nextStep" />
                        </div>
                    </div>
                </div>
            </div>
            {{-- Step 1: Versicherungs SubType --}}
            <div x-show="step == 1"  x-cloak  x-collapse.duration.1000ms>
                <div x-data="{ insuranceSubTypeId: @entangle('insuranceSubTypeId') }">
                    <h2 class="text-lg mb-12">Versicherungsart auswählen</h2>
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
                                        searchPlaceholderValue: 'Suchen...',
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
                <div class="flex justify-center space-x-4 mt-12">
                    <x-backbutton wire:click="previousStep" />
                    @if ($insuranceSubTypeId)
                        <x-furtherbutton wire:click="nextStep" />
                    @endif
                </div>
            </div>
            {{-- Step 2: Konkrete Versicherung auswählen --}}
            <div x-show="step == 2"  x-data="{ insuranceId: @entangle('insuranceId') }" x-cloak  x-collapse.duration.1000ms >
                <h2 class="text-lg font-bold mb-12">Welche Versicherungsgesellschaft?</h2>
                <div class="max-w-md mx-auto ">
                    <select wire:model.live="insuranceId" class="border rounded px-4 py-2"
                            x-init="
                                    let choices = new Choices($el, {
                                        removeItemButton: false, // ✅ EINZELAUSWAHL
                                        shouldSort: false,
                                        searchEnabled: true,
                                        placeholder: true,
                                        searchChoices: true,
                                        position: 'bottom',
                                        itemSelectText: '',
                                        searchPlaceholderValue: 'Suchen...',
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
                <div class="flex justify-center space-x-4 mt-12">
                    <x-backbutton wire:click="previousStep" />
                    @if ($insuranceId)
                    <x-furtherbutton wire:click="nextStep" />

                    @endif
                </div>
            </div>
            {{-- Step 3: Fallstatus --}}
            <div x-show="step == 3"  x-cloak  x-collapse.duration.1000ms>
                <h2 class="text-lg font-bold mb-12">Wie wurde der Schaden reguliert?</h2>
                <div class="flex justify-center items-center">
                    <div x-data="{ regulationType: @entangle('regulationType') }" class="grid grid-cols-2 gap-4 w-fit justify-center">
                        {{-- Karte: Vollzahlung --}}
                        <div 
                            wire:click="$set('regulationType', 'vollzahlung')"
                            @click="regulationType = 'vollzahlung'"
                            :class="{ 'bg-blue-100 border-blue-500': regulationType === 'vollzahlung', 'hover:bg-gray-100 bg-slate-100': regulationType !== 'vollzahlung' }"
                            class="w-32 h-12 border rounded-lg flex items-center justify-center text-center cursor-pointer transition duration-300 ease-in-out">
                                <span class="text-lg font-semibold">Vollzahlung</span>
                        </div>
                        {{-- Karte: Teilzahlung --}}
                        <div 
                            wire:click="$set('regulationType', 'teilzahlung')"
                            @click="regulationType = 'teilzahlung'"
                            :class="{ 'bg-blue-100 border-blue-500': regulationType === 'teilzahlung', 'hover:bg-gray-100 bg-slate-100': regulationType !== 'teilzahlung' }"
                            class="w-32 h-12 border rounded-lg flex items-center justify-center text-center cursor-pointer transition duration-300 ease-in-out">
                                <span class="text-lg font-semibold">Teilzahlung</span>
                        </div>
                        {{-- Karte: Ablehnung --}}
                        <div 
                            wire:click="$set('regulationType', 'ablehnung')"
                            @click="regulationType = 'ablehnung'"
                            :class="{ 'bg-blue-100 border-blue-500': regulationType === 'ablehnung', 'hover:bg-gray-100 bg-slate-100': regulationType !== 'ablehnung' }"
                            class="w-32 h-12 border rounded-lg flex items-center justify-center text-center cursor-pointer transition duration-300 ease-in-out">
                                <span class="text-lg font-semibold">Ablehnung</span>
                        </div>   
                        {{-- Karte: Austehend --}} 
                        <div 
                            wire:click="$set('regulationType', 'austehend')"
                            @click="regulationType = 'austehend'"
                            :class="{ 'bg-blue-100 border-blue-500': regulationType === 'austehend', 'hover:bg-gray-100 bg-slate-100': regulationType !== 'austehend' }"
                            class="w-32 h-12 border rounded-lg flex items-center justify-center text-center cursor-pointer transition duration-300 ease-in-out">
                                <span class="text-lg font-semibold">Austehend</span>
                        </div>
                    </div>
                </div>
                @error('regulationType')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
                <div class="flex justify-center space-x-4 mt-12">
                    <x-backbutton wire:click="previousStep" />
                    @if (!is_null($regulationType))
                        <x-furtherbutton wire:click="nextStep" />
                    @endif
                </div>
            </div>
            {{-- Step 3: Fallstatus Details --}}
            <div x-show="step == 3"  x-cloak  x-collapse.duration.1000ms>
                <h2 class="text-lg font-bold mb-12">
                    @switch($regulationType)
                        @case('vollzahlung')
                            Grund für Bewertung des Falls
                            @break
                        @case('teilzahlung')
                            Was war deiner Meinung nach der Grund für die Teilzahlung?
                            @break
                        @case('ablehnung')
                            Wie wurde die Ablehnung deiner Versicherung begründet?
                            @break
                        @case('austehend')
                            Was ist der Grund für die ausstehende Regulierung?
                            @break
                        @default
                            Keine gültige Auswahl getroffen.
                    @endswitch
                </h2>
                <div class="flex justify-center items-center">
                    <div>
                        <div>
                            <div class="mt-4 space-y-2">
                            @switch($regulationType)
                                @case('vollzahlung')
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Schnelle und unkomplizierte Abwicklung" class="mr-2">
                                        Schnelle und unkomplizierte Abwicklung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Gute Kommunikation und Transparenz" class="mr-2">
                                        Gute Kommunikation und Transparenz
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Faire und angemessene Regulierung" class="mr-2">
                                        Faire und angemessene Regulierung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Hervorragender Kundenservice" class="mr-2">
                                        Hervorragender Kundenservice
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Erwartungen vollständig erfüllt" class="mr-2">
                                        Erwartungen vollständig erfüllt
                                    </label>
                                    @break
                                @case('teilzahlung')
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Nur ein Teil des Schadens wurde anerkannt" class="mr-2">
                                        Nur ein Teil des Schadens wurde anerkannt
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Es gab eine Selbstbeteiligung" class="mr-2">
                                        Es gab eine Selbstbeteiligung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Die Versicherung hat die Summe nach Gutachten gekürzt" class="mr-2">
                                        Die Versicherung hat die Summe nach Gutachten gekürzt
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Kulanzzahlung statt voller Erstattung" class="mr-2">
                                        Kulanzzahlung statt voller Erstattung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Unklare Kommunikation / keine nachvollziehbare Begründung" class="mr-2">
                                        Unklare Kommunikation / keine nachvollziehbare Begründung
                                    </label>
                                    @break
                                @case('ablehnung')
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Schaden wurde als nicht versichert eingestuft" class="mr-2">
                                        Schaden wurde als nicht versichert eingestuft
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Fehlende oder unzureichende Dokumentation" class="mr-2">
                                        Fehlende oder unzureichende Dokumentation
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Verstoß gegen Vertragsbedingungen" class="mr-2">
                                        Verstoß gegen Vertragsbedingungen
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Schaden wurde als Bagatelle abgelehnt" class="mr-2">
                                        Schaden wurde als Bagatelle abgelehnt
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Unklare oder widersprüchliche Begründung" class="mr-2">
                                        Unklare oder widersprüchliche Begründung
                                    </label>
                                    @break
                                @case('austehend')
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Warte auf Rückmeldung der Versicherung" class="mr-2">
                                        Warte auf Rückmeldung der Versicherung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Benötigte Unterlagen wurden noch nicht eingereicht" class="mr-2">
                                        Benötigte Unterlagen wurden noch nicht eingereicht
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Versicherung benötigt mehr Zeit zur Bearbeitung" class="mr-2">
                                        Versicherung benötigt mehr Zeit zur Bearbeitung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Unklare Kommunikation seitens der Versicherung" class="mr-2">
                                        Unklare Kommunikation seitens der Versicherung
                                    </label>
                                    <label class="block">
                                        <input type="radio" wire:model.defer="{{ $fieldName }}" value="Andere Gründe" class="mr-2">
                                        Andere Gründe
                                    </label>
                                    @break
                                @default
                                    Keine gültige Auswahl getroffen.
                            @endswitch
                            </div>
                        </div>
                        <div x-data="{ charCount: 0 }">
                            <textarea wire:model.defer="{{ $fieldName }}"
                                    class="mt-2 w-full mx-auto border px-3 py-2 rounded" rows="4"
                                    x-on:input="charCount = $event.target.value.length"
                                    maxlength="255"></textarea>
                            <span x-text="`${charCount}/255 Zeichen`" class="text-sm " :class="charCount >= 255 ? 'text-red-600' : 'text-gray-500 '"></span>
                        </div>
                    </div>
                </div>
                @error('regulationDetail')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
                <div class="flex justify-center space-x-4 mt-12">
                    <x-backbutton wire:click="previousStep" />
                    @if (!is_null($regulationDetail))
                        <x-furtherbutton wire:click="nextStep" />
                    @endif
                </div>
            </div>
            {{-- Step 4: Zeitraum --}}
            <div x-show="step == 4"  x-cloak  x-collapse.duration.1000ms>
                <div>
                <div x-data="{
                        started_at: @entangle('started_at'),
                        ended_at: @entangle('ended_at'),
                        is_closed: @entangle('is_closed'),
                        initDatepicker(refName, bindTo) {
                            flatpickr(refName, {
                                dateFormat: 'd.m.Y',
                                defaultDate: bindTo || null,
                                locale: 'de',
                                inline: true,
                                allowInput: true,
                                disableMobile: true,
                                onChange: function(selectedDates, dateStr) {
                                    bindTo = dateStr;
                                },
                                onReady: function(selectedDates, dateStr, instance) {
                                    if (!bindTo) {
                                        const targetDate = new Date();
                                        targetDate.setMonth(targetDate.getMonth() - 6);
                                        instance.changeMonth(targetDate.getMonth(), false);
                                        instance.changeYear(targetDate.getFullYear());
                                    }
                                }
                            });
                        }
                    }" x-init="initDatepicker($refs.started, started_at); 
                        $nextTick(() => { if ({{ $is_closed ? 'true' : 'false' }}) initDatepicker($refs.ended, ended_at); })" 
                        class="flex flex-col items-center">
                        @if ($is_closed)
                            <div class="inline-flex mb-5">
                                <button type="button" wire:click="resetDates"
                                    :class="{ 'opacity-50 cursor-not-allowed': !started_at }"
                                    :disabled="!started_at"
                                    class="px-2 py-1 text-sm font-medium bg-white border border-l btn rounded-r-none rounded-l border-r-0  text-blue-500 border-blue-500 hover:bg-blue-500 hover:text-white focus:bg-blue-500 focus:z-10 focus:ring-2 focus:ring-blue-500/30 focus:text-white ">
                                    {{ $started_at ?? 'Start' }}
                                </button>
                                <button type="button"
                                    :class="{ 'opacity-50 cursor-not-allowed': !started_at }"
                                    :disabled="!started_at"
                                    wire:click="$set('ended_at', null)"
                                    class="px-2 py-1 text-sm font-medium bg-white border rounded-r btn rounded-l-none text-blue-500 border-blue-500 hover:bg-blue-500 hover:text-white focus:z-10 focus:ring-2 focus:bg-blue-500 focus:ring-blue-500/30 focus:text-white">
                                    {{ $ended_at ?? 'Ende' }}
                                </button>
                            </div>
                        @endif
                        <div x-show="!started_at || !is_closed"  x-cloak  x-collapse.duration.1000ms>    
                            <h2 class="text-lg font-bold mb-12">Wann hat der Fall begonnen?</h2>
                            {{-- Startdatum --}}
                            <label  class="block text-sm font-medium text-gray-700" wire:ignore>
                                <input type="text" data-input data-id="inline" readonly="readonly" x-ref="started" wire:model.live="started_at"  class="hidden max-w-md border rounded px-4 py-2 text-center" />
                            </label>
                        </div> 
                        @if ($is_closed)
                            <div x-show="started_at"  x-cloak  x-collapse.duration.1000ms class="text-center">
                                {{-- Startdatum (optional) --}}
                                {{-- Enddatum (optional) --}}
                                <h2 class="text-lg font-bold mb-12 ">Wann wurde der Fall abgeschlossen?</h2>
                                {{-- Enddatum --}}
                                <label class="block text-sm font-medium text-gray-700 mt-4" wire:ignore>
                                    <input type="text" data-input data-id="inline" readonly="readonly"  x-ref="ended" wire:model.live="ended_at"  class="hidden max-w-md border rounded px-4 py-2 text-center flatpickr flatpickr-input" />
                                </label>
                            </div>
                        @endif
                    </div>
                    @error('started_at')
                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                    @if ($is_closed)
                        @error('ended_at')
                            <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                        @enderror
                    @endif
                </div>
                <div class="flex justify-center space-x-4 mt-12">
                <x-backbutton wire:click="previousStep" />
                @if ($started_at && (!$is_closed || ($is_closed && $ended_at)))
                    <x-furtherbutton wire:click="nextStep" />

                    @endif
                </div>
            </div>
            {{-- Step 5: Fragen durchgehen --}}
                @foreach ($variableQuestions as $index => $q)
                    @php
                        $currentStep = $standardSteps + $index;
                        $fieldName = "answers." . $q->title;
                    @endphp
                    <div x-show="step === {{ $currentStep }}" x-collapse.duration.1000ms x-cloak >
                        <h2 class="text-lg font-bold mb-2">Frage {{ $currentStep + 1 }} von {{ $totalSteps  }}</h2>
                        <p class="text-md text-gray-800 mb-12 font-semibold">{{ $q->question_text }}</p>
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
                                <div x-data="{ charCount: 0 }">
                                    <textarea wire:model.defer="{{ $fieldName }}"
                                            class="mt-2 w-full mx-auto border px-3 py-2 rounded" rows="4"
                                            x-on:input="charCount = $event.target.value.length"
                                            maxlength="255"></textarea>
                                    <span x-text="`${charCount}/255 Zeichen`" class="text-sm " :class="charCount >= 255 ? 'text-red-600' : 'text-gray-500 '"></span>
                                </div>
                                @break
                            @case('boolean')
                                <div class="mt-2 space-x-4">
                                    <label><input type="radio" wire:model="{{ $fieldName }}" value="1"> Ja</label>
                                    <label><input type="radio" wire:model="{{ $fieldName }}" value="0"> Nein</label>
                                </div>
                                @break
                                @case('rating')
                                    <div class="flex justify-center space-x-1 mt-6 rating-group"     
                                        x-data="{ hovered: 0 }"
                                        >
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer relative" 
                                                @mouseover="hovered = {{ $i }}"
                                                @mouseleave="hovered = 0"
                                                >
                                                <input 
                                                    type="radio" 
                                                    wire:model.live="{{ $fieldName }}" 
                                                    value="{{ $i }}" 
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                >
                                                <span class="text-xl transition-colors duration-150 text-gray-300"
                                                    >
                                                    <svg
                                                            class="w-12 h-12 transition-colors duration-150 "
                                                                :class="{
                                                                    'text-yellow-400': hovered >= {{ $i }} || {{ data_get($this, $fieldName, 0) ?? 0 }} >= {{ $i }},
                                                                    'text-gray-300': hovered < {{ $i }} || {{ data_get($this, $fieldName, 0) ?? 0 }} < {{ $i }}
                                                                }"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.204 3.698a1 1 0 00.95.69h3.894c.969 0 1.371 1.24.588 1.81l-3.15 2.286a1 1 0 00-.364 1.118l1.204 3.698c.3.921-.755 1.688-1.54 1.118l-3.15-2.286a1 1 0 00-1.176 0l-3.15 2.286c-.784.57-1.838-.197-1.539-1.118l1.203-3.698a1 1 0 00-.364-1.118L2.414 9.125c-.783-.57-.38-1.81.588-1.81h3.894a1 1 0 00.951-.69l1.202-3.698z"/>
                                                        </svg>
                                                </span>
                                            </label>
                                        @endfor
                                    </div>
                                    @error($fieldName)
                                        <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                                    @enderror
                                    @break
                            @default
                                <p class="text-sm text-red-500">Unbekannter Fragetyp: {{ $q->type }}</p>
                        @endswitch
                        @error($fieldName)
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                        {{-- Navigation --}}
                        <div class="flex justify-center space-x-4 mt-12">
                            @if ($step > 0)
                            <x-backbutton wire:click="previousStep" />
                            @endif
                            <x-furtherbutton wire:click="{{ ($currentStep + 1) === $totalSteps ? 'submit' : 'nextStep' }}" />

                        </div>
                    </div>
                @endforeach

        </x-slot>
    </x-rating-form-modal>
    

</div>
