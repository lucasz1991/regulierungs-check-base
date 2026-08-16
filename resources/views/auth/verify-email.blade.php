<x-app-layout>
    @php($promotionVerification = str_ends_with((string) session('url.intended'), '/gluecksrad'))
    <div class="mx-auto flex min-h-[68vh] max-w-3xl items-center px-4 py-12 sm:px-6">
        <section class="w-full overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-[0_24px_80px_-36px_rgba(11,48,56,0.38)]">
            <div class="bg-[#0b3038] px-6 py-7 text-white sm:px-10">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#0d9187] text-2xl" aria-hidden="true">✉</span>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#8dd7d1]">Regulierungs-CHECK</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight">E-Mail-Adresse bestätigen</h1>
                    </div>
                </div>
            </div>

            <div class="px-6 py-8 sm:px-10">
                <p class="text-base leading-7 text-slate-600">
                    Wir haben einen persönlichen Bestätigungslink an <strong class="text-slate-900">{{ auth()->user()->email }}</strong> gesendet.
                    @if($promotionVerification)
                        Danach wird dein Glücksrad-Ticket automatisch bereitgestellt.
                    @else
                        Danach kannst du alle Funktionen deines Kontos nutzen.
                    @endif
                </p>

                @if (session('status') === 'verification-link-sent')
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800" role="status">
                        Eine neue Bestätigungs-E-Mail wurde versendet.
                    </div>
                @endif

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <form method="POST" action="{{ route('verification.send') }}" class="sm:flex-1">
                        @csrf
                        <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-[#0d9187] px-5 py-3 font-bold text-white transition hover:bg-[#08776f] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#0d9187]/25">
                            Bestätigungs-E-Mail erneut senden
                        </button>
                    </form>
                    @if($promotionVerification)
                        <a href="{{ route('promotion.wheel') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-200 px-5 py-3 font-bold text-[#0b3038] hover:bg-slate-50">Zurück zum Glücksrad</a>
                    @else
                        <a href="{{ route('profile.show') }}" class="inline-flex min-h-12 items-center justify-center rounded-xl border border-slate-200 px-5 py-3 font-bold text-[#0b3038] hover:bg-slate-50">E-Mail im Profil ändern</a>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-5 text-center">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-slate-500 underline underline-offset-4 hover:text-slate-900">Mit einem anderen Konto anmelden</button>
                </form>
            </div>
        </section>
    </div>
</x-app-layout>
