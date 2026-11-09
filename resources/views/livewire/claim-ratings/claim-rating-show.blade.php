<div class="app-page app-review-detail">
    <div class="app-shell space-y-6 pb-10">

        {{-- TOP BAR --}}
        <div class="rounded-3xl bg-[var(--surface)] border border-[var(--line)]  shadow-none p-5 md:p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-user.public-info :user="$claimRating->user" context="ratings" />
                        <span class="text-sm text-[var(--ink)]">
                            · {{ \Carbon\Carbon::parse($claimRating->created_at)->format('d.m.Y') }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    @auth
                        <livewire:claim-ratings.report-claim-rating-form :claimRatingId="$claimRating->id" />
                        <button wire:click="$dispatch('showReportClaimForm')" title="Melden"
                            class="inline-flex items-center justify-center rounded-xl px-3 py-2
                                   bg-[var(--surface)] border border-[var(--line)] shadow
                                   text-[var(--ink)] hover:bg-white transition">
                            <i class="fal fa-flag"></i>
                        </button>
                    @else
                        <a href="{{ route('login') }}" title="Melden"
                           class="inline-flex items-center justify-center rounded-xl px-3 py-2
                                  bg-[var(--surface)] border border-[var(--line)] shadow
                                  text-[var(--ink)] hover:bg-white transition">
                            <i class="fal fa-flag"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- INFO GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-2xl bg-[var(--surface)] border border-[var(--line)] shadow p-6">
                <p class="text-xs text-[var(--muted)] mb-2 flex items-center gap-2">
                    <i class="fal fa-shield-check text-[var(--muted)]"></i>
                    <span>Versicherung</span>
                </p>
                <x-insurance.insurance-name-button :insurance="$claimRating->insurance" />
                <p class="text-sm text-[var(--muted)] mt-3">
                    Versicherungs-Art:
                    <strong>{{ $claimRating->insuranceSubtype->name ?? 'Keine Angabe' }}</strong>
                </p>
            </div>

<div class="rounded-2xl bg-[var(--surface)] border border-[var(--line)] shadow p-6 space-y-5">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

        {{-- Regulierungsart --}}
        <div class="flex items-center justify-between gap-3">
            <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                <i class="fal fa-file-invoice text-[var(--muted)]"></i>
                <span>Regulierungsart</span>
            </span>

            <span class="inline-flex items-center rounded-full bg-[var(--surface-soft)] text-[var(--accent)] px-3 py-1 text-xs font-semibold">
                {{ $claimRating->answers['regulationType'] ?? '–' }}
            </span>
        </div>

        {{-- Abgeschlossen --}}
        <div class="flex items-center justify-between gap-3">
            <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                <i class="fal fa-check-circle text-[var(--muted)]"></i>
                <span>Abgeschlossen</span>
            </span>

            <span class="inline-flex items-center rounded-full
                {{ $claimRating->answers['is_closed']
                    ? 'bg-emerald-50 text-emerald-700'
                    : 'bg-amber-50 text-amber-700'
                }}
                px-3 py-1 text-xs font-semibold">
                {{ $claimRating->answers['is_closed'] ? 'Ja' : 'Nein' }}
            </span>
        </div>

        @php
            $start = $claimRating->answers['selectedDates']['started_at'] ?? null;
            $end   = $claimRating->answers['selectedDates']['ended_at'] ?? null;
            $startYear = $start ? \Carbon\Carbon::parse($start)->format('Y') : '–';
            $endYear   = $end   ? \Carbon\Carbon::parse($end)->format('Y') : '–';
        @endphp

        {{-- Jahr --}}
        <div class="flex items-center justify-between gap-3">
            <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                <i class="fal fa-calendar-alt text-[var(--muted)]"></i>
                <span>Jahr</span>
            </span>

            <span class="inline-flex items-center rounded-full bg-[var(--surface-soft)] text-[var(--accent)] px-3 py-1 text-xs font-semibold">
                {{ $claimRating->answers['is_closed'] ? $endYear : $startYear }}
            </span>
        </div>

        {{-- Dauer --}}
        <div class="flex items-center justify-between gap-3">
            <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                <i class="fal fa-clock text-[var(--muted)]"></i>
                <span>Dauer</span>
            </span>

            <span class="inline-flex items-center rounded-full bg-[var(--surface-soft)] text-[var(--accent)] px-3 py-1 text-xs font-semibold">
                {{ $claimRating->ratingDuration() }}
                {{ $claimRating->ratingDuration() === 1 ? 'Tag' : 'Tage' }}
            </span>
        </div>

    </div>

    {{-- Details --}}
    <div class="border-t border-[var(--line)] pt-4">
        <div class="flex items-start justify-between gap-4 text-sm">
            <span class="inline-flex items-center gap-2 font-medium text-[var(--muted)] shrink-0">
                <i class="fal fa-info-circle text-[var(--muted)] mt-0.5"></i>
                <span>Details</span>
            </span>

            <span class="text-[var(--ink)] text-right leading-relaxed">
                {{ $claimRating->answers['regulationDetail']['selected_values'] ?? '–' }}
            </span>
        </div>
    </div>

</div>

        </div>

        {{-- AUSWERTUNG --}}
        <div class="">
            <div class=" py-4  flex items-center justify-between">
                <h2 class="app-heading text-lg md:text-xl font-semibold text-[var(--ink)] flex items-center gap-2">
                    <i class="fal fa-chart-line text-[var(--accent)]"></i>
                    <span>Auswertung</span>
                </h2>
            </div>

            <div class=" space-y-4">

                {{-- Variable Fragen --}}
                <div class="">
                    @forelse($claimRating->attachments['scorings']['questions'] as $question)
                        <div class="rounded-xl border border-[var(--line)] bg-[var(--surface)] p-4 mb-3">
                            <p class="text-sm text-[var(--muted)]"><strong>Frage:</strong> {{ $question['question_title'] }}</p>
                            <p class="text-sm text-[var(--muted)]"><strong>Beschreibung:</strong> {{ $question['question_text'] }}</p>

                            @if($question['type'] == 'calc')
                                <div class="mt-2">
                                    <x-insurance.insurance-rating-stars-review-overview :score="$question['score']" />
                                </div>
                            @endif

                            @if($question['type'] == 'ai')
                                <div class="mt-2">
                                    <x-insurance.insurance-rating-stars-review-overview :score="$question['ai_score']" />
                                </div>
                                <p class="text-sm text-[var(--ink)] mt-2"><strong>Kommentar:</strong> {{ $question['ai_comment'] }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-[var(--muted)]">Keine variablen Fragen gefunden.</div>
                    @endforelse
                    <div class="rounded-xl border border-[var(--line)] bg-[var(--surface)] p-4 mb-3">
                    <div class="  text-sm text-[var(--ink)]">
                        <strong>Scoring variable Fragen:</strong>
                        @if($claimRating->attachments['scorings']['variable_questions'] != null)
                            <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->attachments['scorings']['variable_questions']" />
                        @endif
                    </div>
                    </div>
                </div>

                {{-- Referenz-Layout: Kommentar links / Scorings rechts (mobil reverse) --}}
                <div class="rounded-2xl border-[var(--line)] bg-[var(--surface)] p-4 md:p-6">
                    <div class="mb-4 text-sm text-[var(--ink)]">
                        <strong class="mr-2">Gesamt Scoring:</strong>
                        <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->score()" />
                    </div>

                    <div class="max-lg:flex-col flex gap-4">
                        <div class="relative w-full lg:w-2/3 order-2 lg:order-1 lg:pr-8">
                            <div class="prose max-w-full">
                                <h2 class="text-lg font-semibold mb-2 flex items-center gap-2">
                                    <i class="fal fa-comment-alt text-[var(--accent)]"></i>
                                    <span>Zusammenfassung</span>
                                </h2>
                                <p class="text-[var(--ink)] leading-relaxed">
                                    {{ $claimRating->comment() ?: 'Kein Kommentar vorhanden.' }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-[var(--surface)] border border-[var(--line)] shadow rounded-2xl w-full lg:w-1/3 p-5 lg:mt-4 order-1 lg:order-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-semibold text-[var(--ink)] flex items-center gap-2">
                                    <i class="fal fa-chart-bar text-[var(--accent)]"></i>
                                    <span>Scorings</span>
                                </h3>
                                <span class="text-xs text-[var(--muted)] rounded-full bg-[var(--surface-soft)] px-2 py-1">Ø / 5</span>
                            </div>

                            {{-- statt &nbsp;: grid für saubere Alignments --}}
                            <div class="space-y-3 text-sm">
                                <div class="grid grid-cols-[1fr_auto] items-center gap-3">
                                    <span class="text-[var(--ink)] inline-flex items-center gap-2">
                                        <i class="fal fa-clock text-[var(--muted)]"></i><span>Dauer</span>
                                    </span>
                                    <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->attachments['scorings']['regulation_speed']" />
                                </div>

                                <div class="grid grid-cols-[1fr_auto] items-center gap-3">
                                    <span class="text-[var(--ink)] inline-flex items-center gap-2">
                                        <i class="fal fa-headset text-[var(--muted)]"></i><span>Kundenservice</span>
                                    </span>
                                    <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->attachments['scorings']['customer_service']" />
                                </div>

                                <div class="grid grid-cols-[1fr_auto] items-center gap-3">
                                    <span class="text-[var(--ink)] inline-flex items-center gap-2">
                                        <i class="fal fa-balance-scale text-[var(--muted)]"></i><span>Fairness</span>
                                    </span>
                                    <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->attachments['scorings']['fairness']" />
                                </div>

                                <div class="grid grid-cols-[1fr_auto] items-center gap-3">
                                    <span class="text-[var(--ink)] inline-flex items-center gap-2">
                                        <i class="fal fa-eye text-[var(--muted)]"></i><span>Transparenz</span>
                                    </span>
                                    <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->attachments['scorings']['transparency']" />
                                </div>

                                <div class="pt-3 border-t border-[var(--line)]">
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

    </div>
</div>
