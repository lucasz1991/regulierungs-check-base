<div class="bg-gray-50 wrap-break-word w-full overflow-hidden">
    <div class="container mx-auto px-4 py-6 space-y-4">    
        <div class="flex items-center justify-between mb-8">
            <div class="">
                <div class="flex flex-wrap items-center">
                    <x-user.public-info :user="$claimRating->user" context="ratings" />
                    <div class="text-sm text-gray-500 pl-1">
                        - {{ \Carbon\Carbon::parse($claimRating->created_at)->format('d.m.Y') }}
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                @auth
                    <livewire:claim-ratings.report-claim-rating-form :claimRatingId="$claimRating->id" />
                    <button wire:click="$dispatch('showReportClaimForm')" title="Melden"
                        class="flex items-center justify-center shadow p-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-100">
                        <svg viewBox="0 0 16 16" class="w-4 aspect-square"  xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" data-report-icon="true"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 .25V0H2v16h1V9.25h11.957l-4.5-4.5 4.5-4.5H3Zm0 1v7h9.543l-3.5-3.5 3.5-3.5H3Z"></path></svg>
                    </button>
                @else
                    <a href="{{ route('login') }}" title="Melden"
                        class="flex items-center justify-center shadow p-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-primary-700 focus:ring-4 focus:ring-gray-100">
                        <svg viewBox="0 0 16 16" class="w-4 aspect-square"  xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" data-report-icon="true"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 .25V0H2v16h1V9.25h11.957l-4.5-4.5 4.5-4.5H3Zm0 1v7h9.543l-3.5-3.5 3.5-3.5H3Z"></path></svg>
                    </a>
                @endauth
                <!-- <-claim-rating.share-dropdown claimRating="$claimRating" /> -->
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
            <div class="bg-white block rounded shadow p-6 w-full">
                <p class="text-sm text-gray-600 mb-1">
                    Versicherung: 
                </p>
                <x-insurance.insurance-name-button :insurance="$claimRating->insurance" />
                <p class="text-sm text-gray-600 mb-1">
                    Versicherungs-Art: <strong>{{ $claimRating->insuranceSubtype->name ?? 'Keine Angabe' }}</strong>
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
                        <span class="text-right">{{ $claimRating->answers['regulationDetail']['selected_values'] ?? '' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-200 rounded  p-2 md:p-6">
            <h2 class="text-xl md:text-xl  text-gray-800 mb-4">
                Auswertung:
            </h2>
            <div class="bg-white border border-gray-200 rounded-md p-4 mb-4">

                @forelse($claimRating->attachments['scorings']['questions'] as $question)
                    <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-4">
                        <p class="text-sm text-gray-600"><strong>Frage:</strong> {{ $question['question_title'] }}</p>
                        <p class="text-sm text-gray-600"><strong>Beschreibung:</strong> {{ $question['question_text'] }}</p>
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
    </div>
</div>
