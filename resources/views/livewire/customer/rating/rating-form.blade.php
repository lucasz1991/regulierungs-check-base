<div class="max-w-xl mx-auto px-4 py-6">
    @if (session('message'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
        {{ session('message') }}
    </div>
    @endif
    
    {{-- Step 0: Versicherungstyp --}}
    @if ($step === 0)
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
    @endif

    {{-- Step 1: Konkrete Versicherung auswählen --}}
    @if ($step === 1)
        <div>
            <h2 class="text-lg font-bold mb-4">Welche Versicherungsgesellschaft?</h2>

            <select wire:model.live="insuranceId" class="w-full border rounded px-4 py-2">
                <option value="">Bitte auswählen</option>
                @foreach ($insuranceType->insurances ?? [] as $insurance)
                    <option value="{{ $insurance->id }}">{{ $insurance->name }}</option>
                @endforeach
            </select>

            @error('insuranceId')
                <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-between mt-6">
            <x-button wire:click="previousStep">Zurück</x-button>
            @if ($insuranceId)
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    @endif

    {{-- Step 2: Fallstatus --}}
    @if ($step === 2)
        <div>
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
        </div>

        <div class="flex justify-between mt-6">
            <x-button wire:click="previousStep">Zurück</x-button>
            @if (!is_null($is_closed))
                <x-button wire:click="nextStep">Weiter</x-button>
            @endif
        </div>
    @endif

    {{-- Step 3: Zeitraum --}}
    @if ($step === 3)
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
    @endif

    {{-- Step 1+: Fragen durchgehen --}}
    @if ($step >= $standardSteps && isset($questions[$step - $standardSteps]))
        <form wire:submit.prevent="{{ $step === $totalSteps ? 'submit' : 'nextStep' }}">
            <div class="">
                @php
                    $q = $questions[$step - $standardSteps];
                    $fieldName = "answers." . $q->id;
                @endphp
                <h2 class="text-lg font-bold mb-2">Frage {{ $step+1 }} von {{ $totalSteps+1 }}</h2>
                <p class="text-md text-gray-800 mb-2 font-semibold">{{ $questions[$step - $standardSteps]->question_text }}</p>


                {{-- Eingabefeld je nach Typ --}}
                @switch($questions[$step - $standardSteps]->type)
                    @case('text')
                    @case('number')
                    @case('date')
                        <input type="{{ $questions[$step - $standardSteps]->type }}"
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
                        <p class="text-sm text-red-500">Unbekannter Fragetyp: {{ $questions[$step - $standardSteps]->type }}</p>
                @endswitch

                @error($fieldName)
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror

                {{-- Navigation --}}
                <div class="flex justify-between mt-6">
                    @if ($step > 0)
                        <x-button type="button" wire:click="previousStep">Zurück</x-button>
                    @endif

                    <x-button type="submit">
                        {{ $step === $totalSteps ? 'Absenden' : 'Weiter' }}
                    </x-button>
                </div>
            </div>
        </form>
    @endif
</div>
