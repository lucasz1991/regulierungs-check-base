<div>
    <section class="pb-12">
        <div class="container mx-auto px-4">

            <div class="
                rounded-2xl
                bg-white/70
                border border-slate-200
                shadow-xl
                p-6 md:p-8
            ">

                {{-- Header --}}
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        <div class="h-12 w-12 rounded-xl
                                    bg-primary/10 text-primary  shrink-0 
                                    flex items-center justify-center">
                            <i class="fal fa-layer-group text-xl"></i>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl md:text-2xl font-semibold text-slate-900">
                            Abonnements – in Vorbereitung
                        </h2>
                        <p class="mt-2 text-slate-600 leading-relaxed max-w-3xl">
                            Regulierungs-CHECK befindet sich aktuell noch in der Aufbau- und Testphase.
                            Professionelle Abonnements folgen, sobald eine solide Datenbasis vorhanden ist.
                        </p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="mt-6 space-y-4 text-slate-700 leading-relaxed">

                    <p>
                        Mit zunehmender Anzahl echter Bewertungen werden erweiterte Funktionen verfügbar:
                        <strong>vertiefte Auswertungen</strong>, <strong>professionelle Filter</strong> und
                        <strong>branchenweite Vergleichsdaten</strong>.
                    </p>

                    {{-- Zielgruppen --}}
                    <div class="rounded-xl bg-slate-50 border border-slate-200 p-5">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <i class="fal fa-briefcase"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">
                                    Für professionelle Anwender
                                </p>
                                <p class="mt-1 text-slate-600">
                                    Versicherungsmakler, Anwälte und Gutachter erhalten später Zugriff auf
                                    spezialisierte Analyse- & Sichtbarkeitsfunktionen.
                                </p>
                            </div>
                        </div>
                    </div>

                    <p>
                        Wir bitten noch um etwas Geduld – jede abgegebene Bewertung hilft,
                        diese Funktionen schneller freizuschalten.
                    </p>

                    {{-- Actions --}}
                    <div class="pt-4 flex flex-wrap gap-3">
                        <a href="/"
                           class="inline-flex items-center gap-2
                                  rounded-xl bg-primary px-5 py-2.5
                                  text-sm font-semibold text-white shadow
                                  hover:opacity-90 transition">
                            <i class="fal fa-star"></i>
                            Bewertung abgeben
                        </a>

                        <a href="/insurances"
                           class="inline-flex items-center gap-2
                                  rounded-xl border border-slate-300
                                  px-5 py-2.5 text-sm font-semibold bg-slate-50
                                  text-slate-700 hover:bg-slate-100 transition">
                            <i class="fal fa-shield-alt"></i>
                            Versicherungen ansehen
                        </a>
                    </div>
                </div>

                {{-- Voranmeldung --}}
                <div class="mt-10 pt-8 border-t border-slate-200">

                    <div class="flex items-start gap-4 mb-6">
                        <div class="h-11 w-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <i class="fal fa-envelope-open-text text-lg"></i>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">
                                Unverbindlich voranmelden
                            </h3>
                            <p class="mt-1 text-sm text-slate-600 max-w-2xl">
                                Lass dich benachrichtigen, sobald professionelle Abonnements verfügbar sind.
                                Keine Verpflichtung, kein Spam.
                            </p>
                        </div>
                    </div>

                    <form class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        {{-- E-Mail --}}
                        <div class="md:col-span-5">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                E-Mail-Adresse
                            </label>
                            <input
                                type="email"
                                placeholder="name@firma.de"
                                class="w-full rounded-xl
                                       border border-slate-300
                                       bg-white/80
                                       px-4 py-2.5
                                       text-sm text-slate-800
                                       focus:outline-none
                                       focus:ring-2 focus:ring-primary/40"
                            />
                        </div>

                        {{-- Rolle --}}
                        <div class="md:col-span-4">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">
                                Berufsgruppe (optional)
                            </label>
                            <select
                                class="w-full rounded-xl
                                       border border-slate-300
                                       bg-white/80
                                       px-4 py-2.5
                                       text-sm text-slate-800
                                       focus:outline-none
                                       focus:ring-2 focus:ring-primary/40"
                            >
                                <option value="">Bitte auswählen</option>
                                <option>Versicherungsmakler</option>
                                <option>Anwalt / Kanzlei</option>
                                <option>Gutachter</option>
                                <option>Unternehmen</option>
                                <option>Sonstiges</option>
                            </select>
                        </div>

                        {{-- Submit --}}
                        <div class="md:col-span-3 flex items-end">
                            <button
                                type="submit"
                                class="w-full inline-flex items-center justify-center gap-2
                                       rounded-xl bg-primary px-5 py-2.5
                                       text-sm font-semibold text-white
                                       shadow hover:opacity-90 transition"
                            >
                                <i class="fal fa-paper-plane"></i>
                                Voranmelden
                            </button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </section>
</div>
