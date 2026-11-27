<section class="mx-auto max-w-3xl rounded-xl border border-gray-200 p-5 sm:p-6" aria-label="Newsletter-Einstellungen">
    <div class="flex items-center justify-between gap-4">
        <div><h2 class="text-lg font-semibold">Newsletter abonnieren</h2><p class="break-all text-sm text-gray-600">{{ $subscription->email }}</p></div>
        <button type="button" wire:click="change('toggle')" wire:loading.attr="disabled" role="switch" aria-checked="{{ $subscription->enabled ? 'true' : 'false' }}" aria-label="Newsletter abonnieren"
            class="inline-flex items-center gap-2 rounded-full py-2 text-sm font-semibold"><span>{{ $subscription->enabled ? 'Ein' : 'Aus' }}</span>
            <span aria-hidden="true" style="display:inline-flex;width:2.75rem;padding:3px;border-radius:2rem;background:{{ $subscription->enabled ? '#146c60' : '#697b78' }};justify-content:{{ $subscription->enabled ? 'flex-end' : 'flex-start' }}"><span style="height:1.25rem;width:1.25rem;background:white;border-radius:50%"></span></span></button>
    </div>
    @if(! $subscription->enabled)<p class="mt-3 text-sm">Mit „Ein“ bestellst du den Newsletter für diese Adresse.</p>@endif
    <details class="mt-3 text-sm"><summary class="cursor-pointer">Details</summary>
        @if($subscription->enabled && ! $subscription->consented_at)
            <p class="mt-3">{{ \App\Services\NewsletterSubscriptions::AGREEMENT }}</p>
            <button type="button" wire:click="change('allow')" class="mt-1 underline">Versand an diese Adresse erlauben</button>
        @elseif($subscription->consented_at)<p class="mt-3">Versand für diese Adresse freigegeben.</p>@endif
        <p class="mt-3">Begrüßung: {{ $subscription->neutral_greeting ? 'Neutral' : 'Persönlich' }}
            <button type="button" class="ml-2 underline" wire:click="change('greeting')">{{ $subscription->neutral_greeting ? 'Persönlich begrüßen' : 'Neutral begrüßen' }}</button></p>
    </details>
    @error('newsletter')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
    @if($notice)<p class="mt-2 text-sm" role="status">{{ $notice }}</p>@endif
</section>
