<div  @if($hasActiveRating) wire:poll.3s @endif class="bg-gray-50">
    <div class="container mx-auto px-4 py-6 space-y-4"> 
        <div>
            <div class="flex justify-end">
                <x-dropdown>
                    <x-slot name="trigger">
                        <button class="bg-white text-gray-600 hover:text-gray-900 focus:outline-none flex justify-between items-center p-1 pl-2 rounded border  shadow">
                            Optionen
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v.01M12 12v.01M12 18v.01" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        @if($claimRating->status != 'pending')
@if(!$claimRating->is_public)
    @if(auth()->user()->email_verified_at)
        @if($canBePublished)
            {{-- ✅ Darf veröffentlicht werden --}}
            <x-dropdown-link wire:click="publish">
                Veröffentlichen
            </x-dropdown-link>
        @else
            {{-- 🚫 Noch nicht veröffentlichbar --}}
            <x-dropdown-link
                disabled
                class="opacity-50 cursor-not-allowed"
                title="Diese Bewertung kann aktuell nicht veröffentlicht werden. Bitte prüfe den Verifikationsstatus."
                onclick="return false;"
            >
                Veröffentlichen (nicht möglich)
            </x-dropdown-link>
        @endif
    @else
        <x-dropdown-link
            disabled
            class="opacity-50 cursor-not-allowed"
            title="Bitte bestätigen Sie Ihre E-Mail, um zu veröffentlichen."
            onclick="return alert('Bitte bestätigen Sie Ihre E-Mail, um zu veröffentlichen.')"
        >
            Veröffentlichen
        </x-dropdown-link>
    @endif
@else
    <x-dropdown-link wire:click="unpublish">
        Privat schalten
    </x-dropdown-link>
@endif

                            <x-dropdown-link wire:click="reanalyze">
                               Neu analysieren
                            </x-dropdown-link>
                        @endif
                        <x-dropdown-link wire:click="delete"  wire:confirm="Möchten Sie diesen Eintrag wirklich löschen?">
                            Löschen
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>   
        @if($requiresVerification && ! $canBePublished)
            @if($verification['state'] === 'pending')
                <x-alert class="mb-2" :mode="'warning'">
                    <p class="text-sm">
                        Diese Bewertung ist eine Mehrfachbewertung. Deine Falldaten wurden eingereicht und befinden sich aktuell in der Prüfung.
                        Solange die Verifikation läuft, kann die Bewertung nicht veröffentlicht werden.
                    </p>
                </x-alert>
            @elseif($verification['state'] === 'rejected')
                <x-alert class="mb-2" :mode="'danger'">
                    <p class="text-sm">
                        Die Verifikation dieser Bewertung wurde abgelehnt. Bitte überprüfe deine Fallnummer und Falldokumente
                        und reiche sie über den Verifikationsbereich (Kreis-Symbol neben dem Status) erneut ein.
                    </p>
                </x-alert>
            @else
                <x-alert class="mb-2"  :mode="'success'">
                    <p class="text-sm">
                        Für diese Mehrfachbewertung ist eine Fall-Verifikation erforderlich. 
                        Bitte hinterlege eine gültige Fallnummer und lade mindestens ein Falldokument über den Verifikationsbereich 
                        (Kreis-Symbol neben dem Status) hoch, damit die Bewertung veröffentlicht werden kann.
                    </p>
                </x-alert>
            @endif
        @endif
        <div class="bg-white rounded shadow p-6">
            <div class="flex justify-between ">
                <div class="">
                    <div class="text-sm text-gray-500">
                        Erstellt am  - {{ \Carbon\Carbon::parse($claimRating->created_at)->format('d.m.Y') }}
                    </div>
                </div>
                <div class="flex justify-between items-start">
                    <livewire:profile.claim-rating.verification-status-modal
                        :claim-rating="$claimRating"
                        wire:key="verification-status-{{ $claimRating->id }}"
                    />                
                    <div class="mr-3">
                        @if($claimRating->is_public)
                            <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded font-semibold">Öffentlich</span>
                        @else
                            <span class="ml-2 bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded font-semibold">Privat</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="bg-white  rounded shadow p-6 w-full">
                <p class="text-sm text-gray-600 mb-1">
                    Versicherung: 
                </p>
                <x-insurance.insurance-name-button :insurance="$claimRating->insurance" />
                <p class="text-sm text-gray-600 mb-1">
                    Versicherungs-art: <strong>{{ $claimRating->insuranceType->name ?? 'Keine Angabe' }}</strong>
                </p>
                <p class="text-sm text-gray-600 mb-1">
                    Versicherungs-typ: <strong>{{ $claimRating->insuranceSubtype->name ?? 'Keine Angabe' }}</strong>
                </p>
            </div>
            <div class="bg-white rounded shadow p-6 w-full space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-500">Regulierungsart:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-blue-700 bg-blue-200">{{ $claimRating->answers['regulationType'] ?? '–' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-500">Abgeschlossen:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-blue-700 bg-blue-200">{{ $claimRating->answers['is_closed'] ? 'Ja' : 'Nein' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-500">Beginn:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-blue-700 bg-blue-200">{{ $claimRating->answers['selectedDates']['started_at'] ?? '–' }}</span>
                    </div>
                    @if($claimRating->answers['is_closed'])
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-500">Beendet:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-blue-700 bg-blue-200">{{ $claimRating->answers['selectedDates']['ended_at'] ?? '–' }}</span>
                        </div>
                    @endif
                </div>
                <div class="border-t border-gray-200 pt-4">
                   <div class="flex justify-between text-sm text-gray-700">
                        <span class="font-medium text-gray-500">Details:</span>
                        <div class="text-right space-y-1">
                            @foreach((array) data_get($claimRating->answers, 'regulationDetail.selected_values', []) as $value)
                                <div>{{ $value }}</div>
                            @endforeach
                        </div>
                    </div>
                    @if($claimRating->answers['regulationDetail']['textarea_value'])
                        <p class="text-sm text-gray-600 mt-2">
                            <strong>Kommentar:</strong> {{ $claimRating->answers['regulationDetail']['textarea_value'] ?? '' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @if($claimRating->status != 'pending' && $claimRating->status != 'rating')
        <div class="bg-gray-200 rounded shadow p-2 md:p-6">
            <h2 class="text-xl md:text-xl  text-gray-800 mb-4">
                Auswertung:
            </h2>
            <div class="bg-white border border-gray-200 rounded-md p-4 mb-4">

                @forelse($claimRating->attachments['scorings']['questions'] as $question)
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-4">
                        <p class="text-sm text-gray-600"><strong>Frage:</strong> {{ $question['question_title'] }}</p>
                        <p class="text-sm text-gray-600"><strong>Beschreibung:</strong> {{ $question['question_text'] }}</p>
                        <p class="text-sm text-gray-700 mt-1"><strong>Antwort:</strong> {{ $question['answer'] }}</p>
                        @if($question['type'] == 'calc')
                            <p class="text-sm text-gray-700 mt-1"><x-insurance.insurance-rating-stars :score="$question['score']" /></p>
                        @endif
                        @if($question['type'] == 'ai')
                            <p class="text-sm text-gray-700 mt-1"><x-insurance.insurance-rating-stars :score="$question['ai_score']" /></p>
                            <p class="text-sm text-gray-700 mt-1"><strong>Kommentar:</strong> {{ $question['ai_comment'] }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-sm text-gray-500">Keine variablen Fragen gefunden.</div>
                @endforelse
                <div class=" mt-1 p-4">
                    <p class="text-sm text-gray-700">
                        <strong>Scoring variable Fragen:</strong> 
                        @if($claimRating->attachments['scorings']['variable_questions'] != null) 
                            <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['variable_questions']" /> 
                        @endif 
                    </p>
                </div>
            </div>
                    <hr class="my-4">
                    <div class="lg:flex gap-4">
                        <div class="bg-primary-50 p-4 rounded w-full lg:w-2/3">
                            <div class="text-sm text-gray-600 mb-4">
                                <strong class="my-4">Gesammt Scoring:</strong> 
                                <x-insurance.insurance-rating-stars :score="$claimRating->score()" />
                            </div>
                            <div class="prose max-w-full">
                                <h2 class="text-lg font-semibold mb-2">Kommentar</h2>
                                <p>{{ $claimRating->comment() ?: 'Kein Kommentar vorhanden.' }}</p>
                            </div>
                        </div>    
                        <div class="bg-white p-4 rounded w-full lg:w-1/3  items-center">
                            <strong class="mb-4">Scorings:</strong> 
                            <div label="Regulations Dauer">
                                <div class="flex items-center justify-between space-x-2">
                                    <span class="mr-4">Dauer:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['regulation_speed']" />
                                </div>
                            </div>
                            <div label="Kundenservice">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4">Kundenservice:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['customer_service']" />
                                </div>
                            </div>
                            <div label="Fairness">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4">Fairness:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['fairness']" />
                                </div>
                            </div>
                            <div label="Transparency">
                                <div class="flex items-center justify-between space-x-2">
                                <span class="mr-4">Transparenz:</span>
                                    <x-insurance.insurance-rating-stars :score="$claimRating->attachments['scorings']['transparency']" />
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($claimRating->tags() as $tag)
                                        <x-profile.claim-rating.claim-rating-tag-badge :tag="$tag" />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @else
            <div class="bg-gray-200 rounded shadow p-6">
                <h2 class="text-xl md:text-xl  text-gray-800 mb-4">
                    Auswertung:
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    Die Auswertung wird durchgeführt, sobald die Prüfung abgeschlossen ist.
                </p>
                <p class="text-sm text-gray-600 mb-4">
                    Derzeit befindet sich die Regulierung in der Prüfung. Bitte warten Sie, bis die Prüfung abgeschlossen ist, um die Auswertung zu sehen.
                </p>
            </div>
        @endif
    </div>
</div>
