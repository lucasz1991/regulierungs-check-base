<!doctype html>
<html lang="de"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="referrer" content="no-referrer"><title>Newsletter-Einstellungen · Regulierungs-CHECK</title>
<style>body{font:1rem/1.55 system-ui,sans-serif;color:#153c45;background:#f1f6f4;margin:0;padding:clamp(1rem,7vw,5rem)}main{background:#fff;max-width:34rem;margin:auto;padding:clamp(1.25rem,5vw,3rem);border:1px solid #d8e4e0;border-radius:1rem}h1{font-size:clamp(1.4rem,5vw,2rem);line-height:1.2}button{font:inherit;font-weight:650;background:#126b60;color:white;border:0;border-radius:.45rem;padding:.55rem 1rem;cursor:pointer}p{overflow-wrap:anywhere}details{border-top:1px solid #dae6e2;margin-top:1.2rem;padding-top:1rem}summary{cursor:pointer}button:focus-visible,summary:focus-visible{outline:3px solid #d89000;outline-offset:3px}</style></head>
<body><main><img src="{{ asset('site-images/logo-xs.png') }}" width="147" alt="Regulierungs-CHECK">
@if($confirmation ?? false)
<h1>{{ $subscription->consented_at ? 'Du bist angemeldet' : 'Newsletter-Anmeldung bestätigen' }}</h1>
@if($subscription->consented_at)
    <p>Du erhältst den Regulierungs-CHECK Newsletter an {{ $subscription->email }}. Deine Einstellungen findest du in jeder Ausgabe.</p>
@else
    <p>Ich möchte den Regulierungs-CHECK Newsletter an <strong>{{ $subscription->email }}</strong> erhalten.</p>
    <form method="post" action="{{ request()->fullUrl() }}">@csrf<button type="submit" name="confirm" value="1">Newsletter abonnieren</button></form>
@endif
@else
<h1>{{ $unsubscribe ? 'Newsletter abbestellen' : 'Newsletter-Einstellungen' }}</h1>
<p>{{ $subscription->email }}</p>
@if(session('newsletter_notice'))<p role="status">{{ session('newsletter_notice') }}</p>@endif
@foreach($errors->all() as $error)<p role="alert">{{ $error }}</p>@endforeach
<form method="post" action="{{ request()->fullUrl() }}">@csrf
<input type="hidden" name="version" value="{{ $subscription->version }}">
@if($unsubscribe)
    @if($subscription->enabled)<button>Newsletter abbestellen</button>@else<p>Dein Newsletter-Abo ist ausgeschaltet.</p>@endif
@else
    <p>Newsletter abonnieren · <strong>{{ $subscription->enabled ? 'Ein' : 'Aus' }}</strong></p>
    <button type="submit" role="switch" aria-label="Newsletter abonnieren" aria-checked="{{ $subscription->enabled ? 'true' : 'false' }}" name="action" value="{{ $subscription->enabled ? 'disable' : 'enable' }}">{{ $subscription->enabled ? 'Ausschalten' : 'Einschalten' }}</button>
    @if(! $subscription->enabled || ! $subscription->consented_at)<p>{{ \App\Services\NewsletterSubscriptions::AGREEMENT }}</p>@endif
    @if($subscription->enabled && ! $subscription->consented_at)<button name="action" value="enable">Versand an diese Adresse erlauben</button>@endif
    <details><summary>Begrüßung</summary><p><label><input type="checkbox" value="1" name="neutral" @checked($subscription->neutral_greeting)> Neutral mit „Hallo,“ begrüßen</label></p><button name="action" value="greeting">Speichern</button></details>
@endif
</form>
@endif
</main></body></html>
