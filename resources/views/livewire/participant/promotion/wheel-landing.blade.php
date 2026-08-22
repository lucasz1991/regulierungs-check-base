@php
    $ticketStatus = $ticket?->status instanceof \BackedEnum ? $ticket->status->value : (string) ($ticket?->status ?? '');
    $result = $ticket?->effectiveResult;
    $activeResult = $ticket?->activeTurn?->results?->sortByDesc('sequence')?->first();
    $activeOutcome = $activeResult?->outcome_type_snapshot instanceof \BackedEnum
        ? $activeResult->outcome_type_snapshot->value
        : (string) ($activeResult?->outcome_type_snapshot ?? '');
    $finalOutcome = $result?->outcome_type_snapshot instanceof \BackedEnum
        ? $result->outcome_type_snapshot->value
        : (string) ($result?->outcome_type_snapshot ?? '');
    $mailStatus = $result?->mail_status instanceof \BackedEnum
        ? $result->mail_status->value
        : (string) ($result?->mail_status ?? '');
    $pollForLiveTurn = $ticketStatus === 'active'
        || ($ticketStatus === 'ready' && auth()->user()?->hasVerifiedEmail());
    $pollForCorrection = $ticketStatus === 'completed'
        && $ticket?->latestTurn?->completed_at?->greaterThan(
            now()->subMinutes(\App\Services\Promotion\PromotionTurnService::CORRECTION_WINDOW_MINUTES),
        );
@endphp

<div class="relative min-h-screen overflow-hidden"
    @if ($pollForLiveTurn) wire:poll.1000ms.visible="refreshState"
    @elseif ($pollForCorrection) wire:poll.2000ms.visible="refreshState"
    @endif>
    <div aria-hidden="true" class="pointer-events-none absolute -left-28 top-24 h-72 w-72 rounded-full bg-[#0d9187]/15 blur-3xl"></div>
    <div aria-hidden="true" class="pointer-events-none absolute -right-24 top-0 h-80 w-80 rounded-full bg-[#f4c95d]/20 blur-3xl"></div>

    <header class="relative z-10 mx-auto flex max-w-5xl items-center justify-between px-5 py-5 sm:px-8">
        <a href="{{ route('home') }}" aria-label="Zur Regulierungs-CHECK Startseite" class="inline-flex rounded-xl bg-[#0b3038] px-4 py-3 shadow-sm focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#0d9187]/30">
            <img src="{{ asset('site-images/logo/logo-white.png') }}" alt="Regulierungs-CHECK" class="h-8 w-auto object-contain">
        </a>
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-full border border-[#0b3038]/15 bg-white/80 px-4 py-2 text-sm font-semibold text-[#0b3038] backdrop-blur transition hover:bg-white focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#0d9187]/25">Abmelden</button>
            </form>
        @endauth
    </header>

    <main class="relative z-10 mx-auto grid max-w-5xl gap-6 px-4 pb-14 sm:px-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
        <section class="pt-4 sm:pt-10">
            <p class="inline-flex items-center gap-2 rounded-full border border-[#0d9187]/20 bg-white/80 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.17em] text-[#08776f] shadow-sm">
                <span class="h-2 w-2 rounded-full bg-[#f4c95d]"></span>
                Promotion-Glücksrad
            </p>
            <h1 class="mt-5 max-w-xl text-4xl font-black leading-[1.02] tracking-[-0.04em] text-[#0b3038] sm:text-6xl">
                {{ $campaign?->landing_headline ?: 'Dein Dreh. Dein Moment.' }}
            </h1>
            <p class="mt-5 max-w-xl text-base leading-7 text-slate-600 sm:text-lg">
                {{ $campaign?->landing_text ?: 'Melde dich an, zeige dein persönliches Ticket am Glücksrad und erfahre deinen Gewinn direkt.' }}
            </p>

            <ol class="mt-8 grid gap-3" aria-label="Ablauf">
                @foreach ([['01', 'Anmelden', 'Ein Konto genügt für deine Teilnahme.'], ['02', 'Ticket zeigen', 'Der Mitarbeiter scannt deinen persönlichen QR-Code.'], ['03', 'Drehen', 'Du siehst das Ergebnis direkt auf deinem Handy.']] as [$number, $title, $copy])
                    <li class="flex gap-4 rounded-2xl border border-white/80 bg-white/65 p-4 backdrop-blur">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#0b3038] text-xs font-black text-white">{{ $number }}</span>
                        <span><strong class="block text-sm text-[#0b3038]">{{ $title }}</strong><span class="mt-0.5 block text-sm text-slate-600">{{ $copy }}</span></span>
                    </li>
                @endforeach
            </ol>
        </section>

        <section class="rounded-[2rem] border border-white/90 bg-white/90 p-5 shadow-[0_24px_80px_-30px_rgba(11,48,56,0.35)] backdrop-blur sm:p-8">
            @if ((! $promotionEnabled || ! $campaign) && ! in_array($ticketStatus, ['active', 'completed'], true))
                <div class="py-10 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">⌛</div>
                    <h2 class="mt-5 text-2xl font-black tracking-tight text-[#0b3038]">Aktuell keine Aktion</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Sobald das nächste Glücksrad startet, kannst du dich hier anmelden.</p>
                </div>
            @elseif (auth()->guest())
                <div x-data="{ mode: @entangle('mode').live }">
                    <h2 class="text-2xl font-black tracking-tight text-[#0b3038]">Hol dir dein Dreh-Ticket</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Kostenlos anmelden oder ein neues Konto erstellen.</p>

                    @if ($socialProviders !== [])
                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            @foreach ($socialProviders as $provider)
                                <a href="{{ route('social.redirect', ['provider' => $provider, 'return_to' => '/gluecksrad']) }}" class="inline-flex min-h-12 items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-800 transition hover:border-[#0d9187]/50 hover:bg-[#f5fbfa] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#0d9187]/20">
                                    <x-social-provider-logo :provider="$provider" />
                                    Mit {{ ucfirst($provider) }}
                                </a>
                            @endforeach
                        </div>
                        <div class="my-5 flex items-center gap-3 text-xs font-semibold uppercase tracking-widest text-slate-400"><span class="h-px flex-1 bg-slate-200"></span>oder<span class="h-px flex-1 bg-slate-200"></span></div>
                    @endif

                    <div class="grid grid-cols-2 rounded-xl bg-slate-100 p-1" role="tablist">
                        <button type="button" @click="mode = 'login'" :class="mode === 'login' ? 'bg-white text-[#0b3038] shadow-sm' : 'text-slate-500'" class="rounded-lg px-3 py-2.5 text-sm font-bold transition">Anmelden</button>
                        <button type="button" @click="mode = 'register'" :class="mode === 'register' ? 'bg-white text-[#0b3038] shadow-sm' : 'text-slate-500'" class="rounded-lg px-3 py-2.5 text-sm font-bold transition">Registrieren</button>
                    </div>

                    @if (session('promotion_auth_error'))
                        <p class="mt-4 rounded-xl bg-rose-50 p-3 text-sm font-semibold text-rose-800">{{ session('promotion_auth_error') }}</p>
                    @endif
                    <x-input-error for="social" class="mt-4" />
                    <x-input-error for="registration" class="mt-4" />

                    <form x-show="mode === 'login'" x-cloak wire:submit="login" class="mt-5 space-y-4">
                        <div><label for="wheel-login-email" class="text-sm font-bold text-slate-700">E-Mail</label><input id="wheel-login-email" type="email" wire:model="email" autocomplete="email" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-3 focus:border-[#0d9187] focus:ring-[#0d9187]" required><x-input-error for="email" class="mt-1" /></div>
                        <div><label for="wheel-login-password" class="text-sm font-bold text-slate-700">Passwort</label><input id="wheel-login-password" type="password" wire:model="password" autocomplete="current-password" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-3 focus:border-[#0d9187] focus:ring-[#0d9187]" required><x-input-error for="password" class="mt-1" /></div>
                        <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-[#0d9187] focus:ring-[#0d9187]"> Angemeldet bleiben</label>
                        <button type="submit" wire:loading.attr="disabled" class="flex min-h-12 w-full items-center justify-center rounded-xl bg-[#0d9187] px-5 py-3 font-black text-white shadow-lg shadow-[#0d9187]/20 transition hover:bg-[#08776f] disabled:opacity-60">Anmelden & Ticket öffnen</button>
                    </form>

                    <form x-show="mode === 'register'" x-cloak wire:submit="register" class="mt-5 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2"><div><label for="wheel-register-name" class="text-sm font-bold text-slate-700">Benutzername</label><input id="wheel-register-name" type="text" wire:model="username" autocomplete="nickname" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-3 focus:border-[#0d9187] focus:ring-[#0d9187]" required><x-input-error for="username" class="mt-1" /></div><div><label for="wheel-register-email" class="text-sm font-bold text-slate-700">E-Mail</label><input id="wheel-register-email" type="email" wire:model="email" autocomplete="email" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-3 focus:border-[#0d9187] focus:ring-[#0d9187]" required><x-input-error for="email" class="mt-1" /></div></div>
                        <div><label for="wheel-register-password" class="text-sm font-bold text-slate-700">Passwort</label><input id="wheel-register-password" type="password" wire:model="password" autocomplete="new-password" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-3 focus:border-[#0d9187] focus:ring-[#0d9187]" required><x-input-error for="password" class="mt-1" /></div>
                        <div><label for="wheel-register-confirm" class="text-sm font-bold text-slate-700">Passwort wiederholen</label><input id="wheel-register-confirm" type="password" wire:model="password_confirmation" autocomplete="new-password" class="mt-1 w-full rounded-xl border-slate-200 px-4 py-3 focus:border-[#0d9187] focus:ring-[#0d9187]" required></div>
                        <label class="flex items-start gap-3 text-sm leading-5 text-slate-600"><input type="checkbox" wire:model="terms" class="mt-0.5 rounded border-slate-300 text-[#0d9187] focus:ring-[#0d9187]"> <span>Ich akzeptiere die <a href="{{ route('terms') }}" class="font-semibold underline">Bedingungen</a> und <a href="{{ route('privacypolicy') }}" class="font-semibold underline">Datenschutzerklärung</a>.</span></label><x-input-error for="terms" />
                        <button type="submit" wire:loading.attr="disabled" class="flex min-h-12 w-full items-center justify-center rounded-xl bg-[#0d9187] px-5 py-3 font-black text-white shadow-lg shadow-[#0d9187]/20 transition hover:bg-[#08776f] disabled:opacity-60">Konto erstellen</button>
                    </form>
                </div>
            @else
                @if (! auth()->user()->hasVerifiedEmail() && ! in_array($ticketStatus, ['active', 'completed'], true))
                    <div class="py-4 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-3xl">✉</div>
                        <p class="mt-5 text-xs font-bold uppercase tracking-[0.16em] text-amber-700">Ein letzter Schritt</p>
                        <h2 class="mt-2 text-2xl font-black tracking-tight text-[#0b3038]">E-Mail bestätigen</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Wir haben den Bestätigungslink an <strong>{{ auth()->user()->email }}</strong> gesendet. Dein Ticket entsteht direkt nach der Bestätigung.</p>
                        @if (session('promotion_message'))<p class="mt-4 rounded-xl bg-emerald-50 p-3 text-sm font-semibold text-emerald-800">{{ session('promotion_message') }}</p>@endif
                        <x-input-error for="verification" class="mt-4" />
                        <button wire:click="resendVerification" wire:loading.attr="disabled" class="mt-6 min-h-12 w-full rounded-xl bg-[#0d9187] px-5 py-3 font-black text-white disabled:opacity-60">Bestätigungs-E-Mail erneut senden</button>
                    </div>
                @elseif ($ticketStatus === 'ready')
                    <div class="text-center">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#08776f]">Ticket bereit</p>
                        <h2 class="mt-2 text-2xl font-black tracking-tight text-[#0b3038]">Zeige diesen Code am Rad</h2>
                        <div class="mx-auto mt-5 aspect-square w-full max-w-[290px] rounded-[1.75rem] border border-[#0d9187]/20 bg-white p-4 shadow-inner">
                            <img src="{{ route('promotion.ticket.qr', ['participation' => $ticket->participation->public_id]) }}" alt="Persönlicher QR-Code für dein Glücksrad-Ticket" class="h-full w-full">
                        </div>
                        <p class="mt-4 break-all font-mono text-xs font-bold tracking-wide text-slate-500">{{ $ticket->participation->public_id }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-600">Der Code verschwindet automatisch, sobald der Mitarbeiter ihn gescannt hat.</p>
                    </div>
                @elseif ($ticketStatus === 'active')
                    <div class="py-8 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#f4c95d] text-4xl shadow-[0_0_0_12px_rgba(244,201,93,0.18)]">↻</div>
                        <p class="mt-7 text-xs font-bold uppercase tracking-[0.16em] text-[#08776f]">{{ $activeOutcome === 'retry' ? 'Zusatzdreh' : ($activeOutcome === 'quota_reroll' ? 'Bitte erneut drehen' : 'Scan erfolgreich') }}</p>
                        <h2 class="mt-2 text-4xl font-black leading-tight tracking-[-0.04em] text-[#0b3038]">Du bist dran.<br>Jetzt darfst du drehen!</h2>
                        <p class="mt-4 text-sm leading-6 text-slate-600">Der Mitarbeiter beobachtet das Ergebnis und trägt es direkt ein. Bitte halte diese Seite geöffnet.</p>
                    </div>
                @elseif ($ticketStatus === 'completed')
                    <div class="py-6 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full {{ $finalOutcome === 'no_win' ? 'bg-slate-100' : 'bg-[#f4c95d]' }} text-4xl">{{ $finalOutcome === 'no_win' ? '♡' : '★' }}</div>
                        <p class="mt-6 text-xs font-bold uppercase tracking-[0.16em] text-[#08776f]">Dein Ergebnis</p>
                        <h2 class="mt-2 text-3xl font-black tracking-tight text-[#0b3038]">{{ $finalOutcome === 'no_win' ? 'Diesmal leider kein Gewinn' : ($result?->label_snapshot ?: 'Glückwunsch!') }}</h2>
                        @if ($mailStatus === 'sent')
                            <p class="mt-3 text-sm leading-6 text-slate-600">Das Ergebnis wurde deinem Konto zugeordnet und per E-Mail versendet.</p>
                        @elseif ($mailStatus === 'failed')
                            <p class="mt-3 rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold leading-6 text-amber-900">Dein Ergebnis ist sicher gespeichert. Die E-Mail konnte nicht zugestellt werden; du findest das Ergebnis jederzeit in deinem Profil.</p>
                        @elseif ($mailStatus === 'pending')
                            <p class="mt-3 text-sm leading-6 text-slate-600">Das Ergebnis wurde deinem Konto zugeordnet und wird zusätzlich per E-Mail versendet.</p>
                        @else
                            <p class="mt-3 text-sm leading-6 text-slate-600">Das Ergebnis wurde deinem Konto zugeordnet und ist jederzeit in deinem Profil verfügbar.</p>
                        @endif
                        <a href="{{ route('dashboard') }}" class="mt-6 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-[#0b3038] px-5 py-3 font-black text-white">Im Profil ansehen</a>
                    </div>
                @else
                    <div class="py-8 text-center"><h2 class="text-2xl font-black text-[#0b3038]">Ticket nicht verfügbar</h2><p class="mt-3 text-sm text-slate-600">Bitte wende dich an das Promotion-Team vor Ort.</p></div>
                @endif
            @endif
        </section>

        @if ($campaign?->rules_text)
            <details class="rounded-2xl border border-[#0b3038]/10 bg-white/65 p-4 text-sm text-slate-600 lg:col-span-2">
                <summary class="cursor-pointer font-bold text-[#0b3038]">Teilnahmebedingungen</summary>
                <div class="mt-3 whitespace-pre-line leading-6">{{ $campaign->rules_text }}</div>
            </details>
        @endif
    </main>
</div>
