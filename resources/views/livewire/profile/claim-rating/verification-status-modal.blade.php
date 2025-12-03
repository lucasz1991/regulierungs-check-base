@php
    $v = $claimRating->verification;
    $isPending   = $v['state'] === 'pending';
    $isApproved  = $v['state'] === 'approved';
    $isRejected  = $v['state'] === 'rejected';
    $locked      = $isPending && $v['casefileUploaded']; // solange pending + file(s) => gesperrt
@endphp
<div class="flex mr-3">
    <div
        x-data="{
            percentage: {{ $verificationScore }}, // 0 / 20 / 60
            strokeLength: 565.5,
            get offset() {
                return this.strokeLength - (this.percentage / 100) * this.strokeLength;
            }
        }"
        class="flex"
    >
        {{-- Shield-Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <g>
                <path stroke="currentColor" stroke-width="1.5" fill="#f3f4f6" d="M12 3l7 3v5c0 5.25-3.5 9.75-7 11-3.5-1.25-7-5.75-7-11V6l7-3z"/>
                <ellipse cx="12" cy="11" rx="3" ry="2" fill="#fff" stroke="currentColor" stroke-width="1"/>
                <circle cx="12" cy="11" r="0.8" fill="#1f2937"/>
                <path d="M9.5 11c.5-.7 1.5-1.2 2.5-1.2s2 .5 2.5 1.2" stroke="#6b7280" stroke-width="0.7" fill="none"/>
            </g>
        </svg>

        <div class="w-6 relative" x-data="{ show: false }">
            <div
                @mouseover="show = true"
                @mouseleave="show = false"
                @click="show = true; $wire.openModal()"
                @click.away="show = false"
                x-ref="anchor"
                style="width: 100%; max-width: 300px; cursor: pointer;"
            >
                <svg viewBox="0 0 200 200" style="width: 100%; height: auto;" preserveAspectRatio="xMidYMid meet">
                    <circle
                        cx="100"
                        cy="100"
                        r="80"
                        stroke="#ddd"
                        stroke-width="30"
                        fill="none"
                    />
                    <circle
                        cx="100"
                        cy="100"
                        r="80"
                        stroke="#4CAF50"
                        stroke-width="30"
                        fill="none"
                        stroke-linecap="round"
                        stroke-dasharray="565.5"
                        :stroke-dashoffset="offset"
                        transform="rotate(-90 100 100)"
                    />
                </svg>
            </div>

            <div
                x-show="show"
                x-cloak
                x-anchor.offset.10="$refs.anchor"
                class="z-50 text-sm text-white bg-gray-800 rounded-md shadow-lg p-3"
            >
                Verifizierungsstatus:<br>
                <span class="font-semibold">{{ $statusLabel }}</span><br>
                <span class="text-xs opacity-75">{{ $verificationScore }} %</span>
            </div>
        </div>
    </div>

    {{-- Modal für Verifikationsdetails + Formular --}}
    <x-dialog-modal wire:model="showModal" :maxWidth="'2xl'">
        <x-slot name="title">
            <h2 class="text-lg font-semibold">Fall-Verifikation</h2>
        </x-slot>

        <x-slot name="content">
            @php $v = $claimRating->verification; @endphp

            {{-- Status-Alert oben je nach State --}}
            @if($v['state'] === 'pending')
                <x-alert class="mb-4" :mode="'warning'">
                    <p class="text-sm" >
                        Deine Falldaten wurden eingereicht und befinden sich aktuell in der Prüfung.
                        Solange die Prüfung läuft, sind Fallnummer und Dateien gesperrt.
                    </p>
                </x-alert>
            @elseif($v['state'] === 'approved')
                <x-alert class="mb-4" :mode="'success'">
                    <p class="text-sm ">
                        Die Verifikation wurde erfolgreich abgeschlossen. Deine Angaben wurden bestätigt.
                    </p>
                </x-alert>
            @elseif($v['state'] === 'rejected')
                <x-alert class="mb-4"  :mode="'danger'">
                    <p class="text-sm ">
                        Die Verifikation wurde abgelehnt. Bitte überprüfe deine Fallnummer und Dokumente und reiche sie ggf. erneut ein.
                    </p>
                </x-alert>
            @else
                <x-alert class="mb-4"  :mode="'info'">
                    <p class="text-sm ">
                        Für diese Bewertung liegt noch keine Verifikation vor. Bitte trage deine Fallnummer ein
                        und lade mindestens ein Falldokument hoch, damit wir deine Angaben prüfen können.
                    </p>
                </x-alert>
            @endif

            {{-- Statusanzeige (Badges) --}}
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-1">
                    Aktueller Verifikationsstatus:
                </p>
                @if($v['state'] === 'pending')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                        In Prüfung
                    </span>
                @elseif($v['state'] === 'approved')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        Verifiziert
                    </span>
                @elseif($v['state'] === 'rejected')
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                        Abgelehnt
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                        Keine Verifikation hinterlegt
                    </span>
                @endif
            </div>

            {{-- Fallnummer --}}
            <div class="mb-4">
                <label for="caseNumber" class="block text-sm font-medium text-gray-700">
                    Fallnummer <span class="text-red-500">*</span>
                </label>
                <input
                    id="caseNumber"
                    type="text"
                    wire:model.defer="caseNumber"
                    class="mt-1 block w-full rounded-md shadow-sm text-sm
                        @if($locked)
                            bg-gray-100 border-amber-400 text-gray-500 cursor-not-allowed
                        @else
                            border-gray-300
                        @endif"
                    placeholder="z. B. Schaden-Nr. 12345678"
                    @if($locked) disabled @endif
                >
                @error('caseNumber')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @if($locked)
                    <p class="mt-1 text-xs text-amber-700">
                        Die Fallnummer ist während der laufenden Prüfung gesperrt.
                    </p>
                @endif
            </div>

            {{-- bereits hochgeladene Falldokumente --}}
            @if($verificationFiles->count() > 0)
                <div class="mb-4 border border-gray-200 rounded-md p-3 bg-gray-50">
                    <p class="text-xs font-medium text-gray-600 mb-2">
                        Bereits hochgeladene Falldokumente:
                    </p>
                    <ul class="space-y-1 text-sm text-gray-700">
                        @foreach($verificationFiles as $file)
                            <li class="flex items-center justify-between">
                                <span>{{ $file->name_with_extension }}</span>
                                <span class="text-xs text-gray-500">{{ $file->size_formatted }}</span>

                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @if(!$locked)
            {{-- neue Datei(en) hochladen --}}
            <div class="mb-2">
                <label for="newFiles" class="block text-sm font-medium text-gray-700">
                    Falldokument(e) hochladen
                </label>
                <input
                    id="newFiles"
                    type="file"
                    multiple
                    wire:model="newFiles"
                    class="mt-1 block w-full text-sm
                        @if($locked)
                            bg-gray-100 text-gray-500 cursor-not-allowed
                        @else
                            text-gray-700
                        @endif"
                    @if($locked) disabled @endif
                >
                <p class="mt-1 text-xs text-gray-500">
                    Erlaubte Formate: PDF, Bilddateien · max. 10&nbsp;MB pro Datei
                </p>
                @error('newFiles.*')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror

                <div wire:loading wire:target="newFiles" class="mt-2 text-xs text-gray-500">
                    Dateien werden hochgeladen …
                </div>

            </div>
        @endif
        </x-slot>

<x-slot name="footer">
    <div class="flex justify-end gap-2">
        <x-button
            type="button"
            wire:click="closeModal"
            class="text-sm"
        >
            Schließen
        </x-button>

                @if($locked)
                    {{-- Gesperrt: disabled Button --}}
                    <x-button
                        type="button"
                        class="text-sm opacity-60 cursor-not-allowed"
                        disabled
                    >
                        Falldaten speichern
                    </x-button>
                @else
                    {{-- Normal klickbar --}}
                    <x-button
                        type="button"
                        wire:click="submit"
                        wire:loading.attr="disabled"
                        wire:target="submit,newFiles"
                        class="text-sm"
                    >
                        Falldaten speichern
                    </x-button>
                @endif
    </div>
</x-slot>

    </x-dialog-modal>
</div>
