@php
    $statusLabels = [
        'bound' => 'Zugeordnet – Bestätigung ausstehend',
        'confirmed' => 'Bestätigt',
        'fulfilled' => 'Ausgegeben',
        'disputed' => 'Beanstandet',
        'cancelled' => 'Storniert',
    ];
@endphp

<div class="min-h-[70vh] bg-slate-50 py-10 sm:py-16">
    <div class="mx-auto w-full max-w-3xl px-4 sm:px-6">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60">
            <div class="bg-slate-950 px-6 py-8 text-white sm:px-10">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-300">Promotion-Glücksrad</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Dein Gewinn</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                    Prüfe die Angaben sorgfältig. Deine Teilnahme-ID bleibt dauerhaft deinem Konto zugeordnet.
                </p>
            </div>

            <div class="space-y-6 px-6 py-8 sm:px-10">
                @if (session('promotion_success'))
                    <div role="status" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
                        {{ session('promotion_success') }}
                    </div>
                @endif

                <x-input-error for="participation" />

                <dl class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Teilnahme-ID</dt>
                        <dd class="mt-2 break-all font-mono text-xl font-bold tracking-wide text-slate-950 sm:text-2xl">
                            {{ $participation->public_id }}
                        </dd>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kampagne</dt>
                        <dd class="mt-2 font-semibold text-slate-950">{{ $participation->campaign->name }}</dd>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Gewinn</dt>
                        <dd class="mt-2 font-semibold text-slate-950">{{ $participation->currentWin->prize_name_snapshot }}</dd>
                    </div>
                    <div class="rounded-2xl border border-slate-200 p-5 sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wider text-slate-500">Status</dt>
                        <dd class="mt-2 font-semibold text-slate-950">{{ $statusLabels[$status] ?? ucfirst($status) }}</dd>
                    </div>
                </dl>

                @if (! auth()->user()->hasVerifiedEmail())
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-950">
                        <p class="font-semibold">Bitte bestätige noch deine E-Mail-Adresse.</p>
                        <p>Du kannst den Gewinn bereits bestätigen. Er darf jedoch erst nach erfolgreicher E-Mail-Verifikation ausgegeben werden.</p>
                        <form method="POST" action="{{ route('verification.send') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="font-semibold text-amber-950 underline underline-offset-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-600">
                                Bestätigungs-E-Mail erneut senden
                            </button>
                        </form>
                    </div>
                @endif

                @if ($status === 'bound')
                    <div class="grid gap-3 sm:grid-cols-2">
                        <button
                            type="button"
                            wire:click="confirm"
                            wire:loading.attr="disabled"
                            wire:target="confirm,dispute"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:ring-offset-2 disabled:cursor-wait disabled:opacity-60"
                        >
                            Gewinn bestätigen
                        </button>
                        <button
                            type="button"
                            wire:click="dispute"
                            wire:confirm="Stimmt der angezeigte Gewinn nicht mit dem Ergebnis am Glücksrad überein?"
                            wire:loading.attr="disabled"
                            wire:target="confirm,dispute"
                            class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 font-semibold text-slate-800 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 disabled:cursor-wait disabled:opacity-60"
                        >
                            Stimmt nicht
                        </button>
                    </div>
                @elseif ($status === 'confirmed' && ! auth()->user()->hasVerifiedEmail())
                    <p class="rounded-xl bg-slate-100 p-4 text-sm text-slate-700">Deine Bestätigung ist gespeichert. Die Ausgabe bleibt bis zur E-Mail-Verifikation gesperrt.</p>
                @elseif ($status === 'confirmed')
                    <p class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-900">Deine Bestätigung und E-Mail-Verifikation liegen vor. Die Ausgabe kann nun durch den zuständigen Mitarbeiter erfolgen.</p>
                @elseif ($status === 'fulfilled')
                    <p class="rounded-xl bg-emerald-50 p-4 text-sm text-emerald-900">Dieser Gewinn wurde bereits ausgegeben.</p>
                @elseif ($status === 'disputed')
                    <p class="rounded-xl bg-amber-50 p-4 text-sm text-amber-900">Der Vorgang ist beanstandet und wird geprüft.</p>
                @endif

                <a href="{{ route('dashboard') }}" class="inline-flex font-semibold text-primary underline underline-offset-4 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    Zum Benutzerkonto
                </a>
            </div>
        </div>
    </div>
</div>
