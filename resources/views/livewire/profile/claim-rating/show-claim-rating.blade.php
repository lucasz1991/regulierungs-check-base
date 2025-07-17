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
                                    <x-dropdown-link wire:click="publish">
                                        Veröffentlichen
                                    </x-dropdown-link>
                                @else
                                    <x-dropdown-link disabled class="opacity-50 cursor-not-allowed" title="Bitte bestätigen Sie Ihre E-Mail, um zu veröffentlichen." onclick="return alert('Bitte bestätigen Sie Ihre E-Mail, um zu veröffentlichen.')">
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
        <div class="bg-white rounded shadow p-6">
                    <div class="flex justify-between ">
            <div class="">
                <div class="text-sm text-gray-500">
                    Erstellt am  - {{ \Carbon\Carbon::parse($claimRating->created_at)->format('d.m.Y') }}
                </div>
            </div>

            <div class="flex justify-between items-start">
                <div class="flex items-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-1 opacity-40 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <g>
                        <!-- Schild (Shield) -->
                        <path stroke="currentColor" stroke-width="1.5" fill="#f3f4f6" d="M12 3l7 3v5c0 5.25-3.5 9.75-7 11-3.5-1.25-7-5.75-7-11V6l7-3z"/>
                        <!-- Auge (Eye) -->
                        <ellipse cx="12" cy="11" rx="3" ry="2" fill="#fff" stroke="currentColor" stroke-width="1"/>
                        <circle cx="12" cy="11" r="0.8" fill="#1f2937"/>
                        <path d="M9.5 11c.5-.7 1.5-1.2 2.5-1.2s2 .5 2.5 1.2" stroke="#6b7280" stroke-width="0.7" fill="none"/>
                    </g>
                </svg>
                <div class="w-8">
    
                    <x-profile.claim-rating.claim-rating-circleprogress/>
                </div>
            </div>
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
            <div class="bg-white rounded shadow p-6">
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
            <div class="bg-white rounded shadow p-6">
                <div><span class="mr-4">Regulierungsart:</span>{{ $claimRating->answers['regulationType'] ?? '–' }}</div>
                <div><span class="mr-4">Abgeschlossen:</span>{{ $claimRating->answers['is_closed'] ? 'Ja' : 'Nein' }}</div>
                <div><span class="mr-4">Beginn:</span>{{ $claimRating->answers['selectedDates']['started_at'] ?? '–' }}</div>
                @if($claimRating->answers['is_closed'])
                    <div><span class="mr-4">Beendet:</span>{{ $claimRating->answers['selectedDates']['ended_at'] ?? '–' }}</div>
                @endif
                <hr class="my-4">
                <div><span class="mr-4">Details:</span>{{ $claimRating->answers['regulationDetail']['selected_value'] ?? '–' }}</div>
            </div>
        </div>
        @if($claimRating->status != 'pending')
        <div class="bg-gray-200 rounded shadow p-6">
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
        @endif
    </div>
</div>
