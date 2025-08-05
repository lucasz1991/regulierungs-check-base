<div @if($hasActiveRating) wire:poll.3s @endif>

    @if(session()->has('message'))
        <x-alert class="mb-6">
            {{ session('message') }}
        </x-alert>
    @endif
    @if(!auth()->user()->email_verified_at && $claimRatings->count() > 0)
        <x-alert class="mb-6 md:w-full">
            <h6 class="text-xl font-semibold  mb-1" >E-Mail Verifizierung</h6>
            <p>Um deine Bewertungen öffentlich sichtbar zu machen, musst du zuerst deine E-Mail-Adresse verifizieren.</p>
        </x-alert>
    @endif
    <h2 class="text-xl font-semibold text-gray-600 mb-4">Meine Bewertungen</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse ($claimRatings as $rating)
            <x-profile.claim-rating.claim-rating-card :rating="$rating" />
        @empty
            <x-alert>
                Du hast noch keine Bewertungen abgegeben.
            </x-alert>
        @endforelse
    </div>
    @if ($claimRatings->hasPages())
        <div class="mt-6">
            {{ $claimRatings->links('vendor.pagination.tailwind') }}
        </div>
    @endif

</div>
