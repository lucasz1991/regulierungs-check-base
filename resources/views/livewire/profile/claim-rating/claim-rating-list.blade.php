<div @if($hasActiveRating) wire:poll.3s @endif class="space-y-8">

    {{-- Flash Message --}}
    @if(session()->has('message'))
        <div class="rounded-2xl bg-emerald-500/10 border border-emerald-400/30 p-4 text-emerald-200 flex items-start gap-3">
            <i class="fal fa-check-circle mt-0.5"></i>
            <div class="text-sm font-medium">
                {{ session('message') }}
            </div>
        </div>
    @endif

    {{-- Email Verification Hinweis --}}
    @if(!auth()->user()->email_verified_at && $claimRatings->count() > 0)
        <div class="rounded-2xl bg-amber-500/10 border border-amber-400/30 p-5 text-amber-100">
            <div class="flex items-start gap-4">
                <div class="shrink-0 w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow">
                    <i class="fal fa-envelope-open-text"></i>
                </div>

                <div class="min-w-0">
                    <h6 class="text-lg font-semibold mb-1 text-white">
                        E-Mail Verifizierung erforderlich
                    </h6>
                    <p class="text-sm text-amber-100/90 leading-relaxed">
                        Um deine Bewertungen öffentlich sichtbar zu machen, musst du zuerst deine E-Mail-Adresse verifizieren.
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <h2 class="text-2xl font-semibold text-white flex items-center gap-2">
                <i class="fal fa-star text-rcgold"></i>
                <span>Meine Bewertungen</span>
            </h2>
            <p class="mt-1 text-sm text-blue-100/80">
                Verwalte deine Einreichungen und behalte den Status im Blick.
            </p>
        </div>

        {{-- Count Badge --}}
        <div class="shrink-0">
            <span class="inline-flex items-center gap-2 rounded-full
                         bg-white/10 text-white px-3 py-1 text-xs font-semibold backdrop-blur">
                <i class="fal fa-list-ul text-white/70"></i>
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
            <div class="rounded-2xl bg-white/10 border border-white/20 p-5 text-white/80 flex items-start gap-3">
                <i class="fal fa-info-circle mt-0.5 text-white/70"></i>
                <div class="text-sm">
                    Du hast noch keine Bewertungen abgegeben.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($claimRatings->hasPages())
        <div class="pt-4">
            <div class="rounded-2xl bg-white/10 border border-white/20 backdrop-blur p-3">
                {{ $claimRatings->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif

</div>
