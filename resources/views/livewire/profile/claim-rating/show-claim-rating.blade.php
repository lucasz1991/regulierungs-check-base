<div @if($hasActiveRating) wire:poll.3s @endif class="app-account app-page min-h-screen">
    <div class="app-shell pb-10 space-y-6">

        {{-- FLASH / ALERTS --}}
        @if(session()->has('message'))
            <x-alert class="mb-2">
                {{ session('message') }}
            </x-alert>
        @endif

        {{-- TOP BAR (wie neues Layout) --}}
        <div class="rounded-3xl bg-[var(--surface)] border border-[var(--line)]  p-5 md:p-6">
            <div class="flex items-start justify-between gap-4">

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-user.public-info :user="$claimRating->user" context="ratings" />

                        <span class="text-sm text-[var(--ink)]">
                            · Erstellt am {{ \Carbon\Carbon::parse($claimRating->created_at)->format('d.m.Y') }}
                        </span>

                        {{-- Public/Private Badge --}}
                        <span class="ml-1 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                            {{ $claimRating->is_public ? 'bg-emerald-50 text-emerald-700' : 'bg-[var(--surface-soft)] text-[var(--ink)]' }}">
                            <i class="fal {{ $claimRating->is_public ? 'fa-globe-europe' : 'fa-lock' }} mr-1.5"></i>
                            {{ $claimRating->is_public ? 'Öffentlich' : 'Privat' }}
                        </span>
                    </div>
                </div>

                {{-- RIGHT ACTIONS --}}
                <div class="flex items-center gap-2 shrink-0">

                    @if($showVerificationSection)
                        {{-- Verification modal button (dein bestehendes Livewire) --}}
                        <livewire:profile.claim-rating.verification-status-modal
                            :claim-rating="$claimRating"
                            wire:key="verification-status-{{ $claimRating->id }}"
                            lazy
                        />
                    @endif

                    {{-- Optionen Dropdown --}}
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 rounded-xl px-3 py-2
                                       bg-[var(--surface)] border border-[var(--line)] shadow
                                       text-[var(--ink)] hover:bg-[var(--surface)] transition"
                                type="button"
                            >
                                <i class="fal fa-ellipsis-v"></i>
                                <span class="text-sm font-semibold">Optionen</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if($claimRating->status != 'pending')
                                @if(!$claimRating->is_public)
                                    @if(auth()->user()->email_verified_at)
                                        @if($canBePublished)
                                            <x-dropdown-link wire:click="publish">
                                                <i class="fal fa-globe-europe mr-2"></i> Veröffentlichen
                                            </x-dropdown-link>
                                        @else
                                            <x-dropdown-link
                                                disabled
                                                class="opacity-50 cursor-not-allowed"
                                                title="Diese Bewertung kann aktuell nicht veröffentlicht werden. Bitte prüfe den Verifikationsstatus."
                                                onclick="return false;"
                                            >
                                                <i class="fal fa-globe-europe mr-2"></i> Veröffentlichen (nicht möglich)
                                            </x-dropdown-link>
                                        @endif
                                    @else
                                        <x-dropdown-link
                                            disabled
                                            class="opacity-50 cursor-not-allowed"
                                            title="Bitte bestätigen Sie Ihre E-Mail, um zu veröffentlichen."
                                            onclick="return alert('Bitte bestätigen Sie Ihre E-Mail, um zu veröffentlichen.')"
                                        >
                                            <i class="fal fa-envelope mr-2"></i> Veröffentlichen
                                        </x-dropdown-link>
                                    @endif
                                @else
                                    <x-dropdown-link wire:click="unpublish">
                                        <i class="fal fa-lock mr-2"></i> Privat schalten
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link wire:click="reanalyze">
                                    <i class="fal fa-user-robot mr-2"></i> Neu analysieren
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link wire:click="delete" wire:confirm="Möchten Sie diesen Eintrag wirklich löschen?">
                                <i class="fal fa-trash mr-2"></i> Löschen
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>

                </div>
            </div>
        </div>

        {{-- VERIFIKATIONS HINWEISE (optisch angepasst) --}}
        @if($requiresVerification && ! $canBePublished)
            @if($verification['state'] === 'pending')
                <div class="rounded-2xl bg-white/85 border border-[var(--line)] shadow p-5">
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                            <i class="fal fa-hourglass-half"></i>
                        </div>
                        <div class="text-sm text-[var(--ink)] leading-relaxed">
                            Diese Bewertung ist eine Mehrfachbewertung. Deine Falldaten wurden eingereicht und befinden sich aktuell in der Prüfung.
                            Solange die Verifikation läuft, kann die Bewertung nicht veröffentlicht werden.
                            Sobald die Prüfung abgeschlossen ist, informieren wir dich automatisch und die Bewertung wird, falls alles korrekt ist, direkt veröffentlicht.
                        </div>
                    </div>
                </div>
            @elseif($verification['state'] === 'rejected')
                <div class="rounded-2xl bg-white/85 border border-[var(--line)] shadow p-5">
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                            <i class="fal fa-times-circle"></i>
                        </div>
                        <div class="text-sm text-[var(--ink)] leading-relaxed">
                            Die Verifikation dieser Bewertung wurde abgelehnt. Bitte überprüfe deine Fallnummer und Falldokumente
                            und reiche sie über den Verifikationsbereich (Kreis-Symbol neben dem Status) erneut ein.
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-2xl bg-white/85 border border-[var(--line)] shadow p-5">
                    <div class="flex items-start gap-3">
                        <div class="h-11 w-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                            <i class="fal fa-exclamation-triangle"></i>
                        </div>
                        <div class="text-sm text-[var(--ink)] leading-relaxed">
                            Für diese Mehrfachbewertung ist eine Fall-Verifikation erforderlich.
                            Bitte hinterlege eine gültige Fallnummer und lade mindestens ein Falldokument über den Verifikationsbereich
                            (Kreis-Symbol neben dem Status) hoch, damit die Bewertung veröffentlicht werden kann.
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- INFO GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Versicherung --}}
            <div class="rounded-2xl bg-[var(--surface)] border border-[var(--line)] shadow p-6 w-full">
                <p class="text-xs text-[var(--muted)] mb-2 flex items-center gap-2">
                    <i class="fal fa-shield-check text-[var(--muted)]"></i>
                    <span>Versicherung</span>
                </p>

                <x-insurance.insurance-name-button :insurance="$claimRating->insurance" />

                <div class="mt-4 space-y-1 text-sm text-[var(--ink)]">
                    <p class="text-[var(--muted)]">
                        Versicherungs-art:
                        <strong>{{ $claimRating->insuranceType->name ?? 'Keine Angabe' }}</strong>
                    </p>
                    <p class="text-[var(--muted)]">
                        Versicherungs-typ:
                        <strong>{{ $claimRating->insuranceSubtype->name ?? 'Keine Angabe' }}</strong>
                    </p>
                </div>
            </div>

            {{-- Meta / Details (dein neuer Style mit Icons) --}}
            <div class="rounded-2xl bg-[var(--surface)] border border-[var(--line)] shadow p-6 w-full space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 xl:grid-cols-2 gap-x-6 gap-y-4 text-sm">

                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                            <i class="fal fa-file-invoice text-[var(--muted)]"></i>
                            <span>Regulierungsart</span>
                        </span>
                        <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                            {{ $claimRating->answers['regulationType'] ?? '–' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                            <i class="fal fa-check-circle text-[var(--muted)]"></i>
                            <span>Abgeschlossen</span>
                        </span>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            {{ $claimRating->answers['is_closed'] ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                            {{ $claimRating->answers['is_closed'] ? 'Ja' : 'Nein' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                            <i class="fal fa-calendar-alt text-[var(--muted)]"></i>
                            <span>Beginn</span>
                        </span>
                        <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                            {{ $claimRating->answers['selectedDates']['started_at'] ?? '–' }}
                        </span>
                    </div>

                    @if($claimRating->answers['is_closed'])
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex items-center gap-2 text-[var(--muted)] font-medium">
                                <i class="fal fa-calendar-check text-[var(--muted)]"></i>
                                <span>Beendet</span>
                            </span>
                            <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                                {{ $claimRating->answers['selectedDates']['ended_at'] ?? '–' }}
                            </span>
                        </div>
                    @endif

                </div>

                <div class="border-t border-[var(--line)] pt-4">
                    <div class="flex items-start justify-between gap-4 text-sm text-[var(--ink)]">
                        <span class="inline-flex items-center gap-2 font-medium text-[var(--muted)] shrink-0">
                            <i class="fal fa-info-circle text-[var(--muted)] mt-0.5"></i>
                            <span>Details</span>
                        </span>

                        <div class="text-right space-y-1">
                            @foreach((array) data_get($claimRating->answers, 'regulationDetail.selected_values', []) as $value)
                                <div>{{ $value }}</div>
                            @endforeach
                        </div>
                    </div>

                    @if(data_get($claimRating->answers, 'regulationDetail.textarea_value'))
                        <p class="text-sm text-[var(--ink)] mt-3">
                            <strong>Kommentar:</strong> {{ $claimRating->answers['regulationDetail']['textarea_value'] ?? '' }}
                        </p>
                    @endif
                </div>
 
            </div>
        </div>

        {{-- AUSWERTUNG --}}
        @if($claimRating->status != 'pending' && $claimRating->status != 'rating')

            <div class="mt-2">
                <div class="py-2 flex items-center justify-between">
                    <h2 class="text-lg md:text-xl font-semibold text-white flex items-center gap-2">
                        <i class="fal fa-chart-line text-[var(--accent)]"></i>
                        <span>Auswertung</span>
                    </h2>
                </div>

                <div class="space-y-4">

                    {{-- Variable Fragen --}}
                    <div class="space-y-3">
                        @forelse($claimRating->attachments['scorings']['questions'] as $question)
                            <div class="rounded-xl border border-white/80 bg-[var(--surface)] p-4">
                                <p class="text-sm text-[var(--muted)]">
                                    <strong>Frage:</strong> {{ $question['question_title'] }}
                                </p>
                                <p class="text-sm text-[var(--muted)] mt-1">
                                    <strong>Beschreibung:</strong> {{ $question['question_text'] }}
                                </p>

                                <p class="text-sm text-[var(--ink)] mt-2">
                                    <strong>Antwort:</strong> {{ $question['answer'] }}
                                </p>

                                @if($question['type'] == 'calc')
                                    <div class="mt-2">
                                        <x-insurance.insurance-rating-stars-review-overview :score="$question['score']" />
                                    </div>
                                @endif

                                @if($question['type'] == 'ai')
                                    <div class="mt-2">
                                        <x-insurance.insurance-rating-stars-review-overview :score="$question['ai_score']" />
                                    </div>
                                    <p class="text-sm text-[var(--ink)] mt-2">
                                        <strong>Kommentar:</strong> {{ $question['ai_comment'] }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <div class="text-sm text-[var(--muted)]">Keine variablen Fragen gefunden.</div>
                        @endforelse

                        <div class="rounded-xl border border-white/80 bg-[var(--surface)] p-4">
                            <p class="text-sm text-[var(--ink)]">
                                <strong>Scoring variable Fragen:</strong>
                                @if($claimRating->attachments['scorings']['variable_questions'] != null)
                                    <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->attachments['scorings']['variable_questions']" />
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- KOMMENTAR + SCORINGS (dein Referenz-Layout) --}}
                    <div class="rounded-2xl border-white/80 bg-[var(--surface)] p-4 md:p-6">
                        <div class="mb-4 text-sm text-[var(--ink)]">
                            <strong class="mr-2">Gesamt Scoring:</strong>
                            <x-insurance.insurance-rating-stars-review-overview :score="$claimRating->score()" />
                        </div>

                        <div class="max-lg:flex-col flex gap-4">
                            {{-- Kommentar --}}
                            <div class="relative w-full lg:w-2/3 order-2 lg:order-1 lg:pr-8">
                                <div class="prose max-w-full">
                                    <h2 class="text-lg font-semibold mb-2 flex items-center gap-2">
                                        <i class="fal fa-comment-alt text-blue-600"></i>
                                        <span>Zusammenfassung</span>
                                    </h2>

                                    <p class="text-[var(--ink)] leading-relaxed">
                                        {{ $claimRating->comment() ?: 'Kein Kommentar vorhanden.' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Scorings --}}
                            <div class="bg-[var(--surface)] border border-[var(--line)] shadow rounded-2xl w-full lg:w-1/3 p-5 lg:mt-4 order-1 lg:order-2">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm font-semibold text-[var(--ink)] flex items-center gap-2">
                                        <i class="fal fa-chart-bar text-blue-600"></i>
                                        <span>Scorings</span>
                                    </h3>
                                    <span class="text-xs text-[var(--muted)] rounded-full bg-[var(--surface-soft)] px-2 py-1">Ø / 5</span>
                                </div>

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

                                    {{-- Tags --}}
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

        @else
            <div class="rounded-2xl bg-[var(--surface)] border border-[var(--line)] shadow p-6">
                <h2 class="text-lg md:text-xl font-semibold text-[var(--ink)] mb-3 flex items-center gap-2">
                    <i class="fal fa-chart-line text-blue-600"></i>
                    <span>Auswertung</span>
                </h2>
                <p class="text-sm text-[var(--ink)] mb-2">
                    Die Auswertung wird durchgeführt, sobald die Prüfung abgeschlossen ist.
                </p>
                <p class="text-sm text-[var(--ink)]">
                    Derzeit befindet sich die Regulierung in der Prüfung. Bitte warten Sie, bis die Prüfung abgeschlossen ist, um die Auswertung zu sehen.
                </p>
            </div>
        @endif

    </div>
</div>

