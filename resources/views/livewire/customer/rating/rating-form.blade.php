<div class="max-w-xl mx-auto px-4 py-6"
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
        <div>
            <h2 class="text-lg mb-4">Wähle deine Versicherungskategorie</h2>

            <select wire:model.live="insuranceTypeId" class="w-full border rounded px-4 py-2">
                <option value="">Bitte auswählen</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>

            @error('insuranceTypeId')
                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end mt-6">
            @if ($insuranceTypeId)
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    </div>

    {{-- Step 1: Versicherungs SubType --}}
    <div x-show="step == 1"  x-cloak  x-collapse.duration.1000ms>
        <div>
            <h2 class="text-lg mb-4">Wähle deine Versicherungsart</h2>

            <select wire:model.live="insuranceSubTypeId" class="w-full border rounded px-4 py-2">
                <option value="">Bitte auswählen</option>
                @foreach ($insuranceSubTypes as $insuranceSubType)
                    <option value="{{ $insuranceSubType->id }}">{{ $insuranceSubType->name }}</option>
                @endforeach
            </select>

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
        <div x-show="step == 2"  x-cloak  x-collapse.duration.1000ms>
            <h2 class="text-lg font-bold mb-4">Welche Versicherungsgesellschaft?</h2>

            <select wire:model.live="insuranceId" class="w-full border rounded px-4 py-2">
                <option value="">Bitte auswählen</option>
                @foreach ($insurances ?? [] as $insurance)
                    <option value="{{ $insurance->id }}">{{ $insurance->name }}</option>
                @endforeach
            </select>

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

            <label class="inline-flex items-center mr-6">
                <input type="radio" wire:model.live="is_closed" value="1"> <span class="ml-2">Ja</span>
            </label>
            <label class="inline-flex items-center">
                <input type="radio" wire:model.live="is_closed" value="0"> <span class="ml-2">Nein</span>
            </label>

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

</div>
