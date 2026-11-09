<div @if($hasActiveRating) wire:poll.3s @endif class="app-account space-y-8">

    {{-- Flash Message --}}
    @if(session()->has('message'))
        <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/30 p-4 text-[var(--accent)] flex items-start gap-3">
            <i class="fal fa-check-circle mt-0.5"></i>
            <div class="text-sm font-medium">
                {{ session('message') }}
            </div>
        </div>
    @endif

    {{-- Email Verification Hinweis --}}
    @if(!auth()->user()->email_verified_at && $claimRatings->count() > 0)
        <div class="rounded-2xl bg-amber-500/10 border border-amber-400/30 p-5 text-[var(--ink)]">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow">
                    <i class="fal fa-envelope-open-text"></i>
                </div>

                <div class="min-w-0">
                    <h6 class="text-lg font-semibold mb-1 text-[var(--ink)]">
                        E-Mail Verifizierung erforderlich
                    </h6>
                    <p class="text-sm text-[var(--muted)] leading-relaxed">
                        Um deine Bewertungen öffentlich sichtbar zu machen, musst du zuerst deine E-Mail-Adresse verifizieren.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h2 class="text-2xl font-semibold text-[var(--ink)] flex items-center gap-2">
                <i class="fal fa-star text-rcgold"></i>
                <span>Meine Bewertungen</span>
            </h2>
            <p class="mt-1 text-sm text-[var(--muted)]">
                Verwalte deine Einreichungen und behalte den Status im Blick.
            </p>
        </div>

        {{-- Count Badge --}}
        <div class="shrink-0">
            <span class="inline-flex items-center gap-2 rounded-full
                         bg-[var(--surface-soft)] text-[var(--ink)] px-3 py-1 text-xs font-semibold ">
                <i class="fal fa-list-ul text-[var(--accent)]"></i>
                {{ $claimRatings->total() ?? $claimRatings->count() }}
                {{ ($claimRatings->total() ?? $claimRatings->count()) === 1 ? 'Bewertung' : 'Bewertungen' }}
            </span>
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($claimRatings as $rating)
            {{-- deine Card ist hell -> passt perfekt --}}
            <x-profile.claim-rating.claim-rating-card :rating="$rating" />
        @empty
            <div class="rounded-2xl bg-[var(--surface-soft)] border border-[var(--line)] p-5 text-[var(--muted)] flex items-start gap-3">
                <i class="fal fa-info-circle mt-0.5 text-[var(--accent)]"></i>
                <div class="text-sm">
                    Du hast noch keine Bewertungen abgegeben.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($claimRatings->hasPages())
        <div class="pt-4">
            <div class="rounded-2xl bg-[var(--surface-soft)] border border-[var(--line)]  p-3">
                {{ $claimRatings->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif

</div>
