<div wire:loading.class="cursor-wait" class="py-12 px-4 sm:px-6 lg:px-8">


    <div class="container px-3 mx-auto space-y-10">
        <div>
            {{ $refferer}}
        </div>
        <!-- Hero -->
        <section class="grid gap-8 md:grid-cols-2 items-center">
        <div class="space-y-4">
            <p class="inline-flex items-center gap-2 text-xs uppercase tracking-wide text-gray-500">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Fair. Transparent. Nachvollziehbar.
            </p>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight">
            RewardQuest – Das Gewinnspiel von Regulierungs-Check
            </h1>
            <p class="text-gray-700">
            Gib jetzt deine <strong>Bewertung</strong> bei Regulierungs-Check ab und sichere dir die Chance auf
            <strong>500&nbsp;€</strong>. Teilnahme in wenigen Minuten – fair dokumentiert und zufällig ausgelost.
            </p>

            <div class="flex flex-wrap items-center gap-6 pt-2">
            <a
                x-on:click="Livewire.dispatch('showFormModal'); isClicked = true; setTimeout(() => isClicked = false, 600)"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold border border-current hover:opacity-90 cursor-pointer"
                href="#"
                aria-label="Jetzt Bewertung abgeben und teilnehmen"
            >
                <span>Jetzt Bewertung abgeben &amp; teilnehmen</span>
            </a>
            <span class="text-xs text-gray-500">*Mit Klick auf den Button „Jetzt Bewertung abgeben &amp; teilnehmen“ akzeptiere ich die <a href="#" class="text-blue-500 hover:underline hover:text-blue-800"  wire:click="$set('showTermsModal', true)">Teilnahmebedingungen</a>. Mehrfachteilnahmen sind nicht erlaubt.</span>
            </div>
        </div>

        <!-- Hero Bild-Platzhalter -->
        <div class="relative">
            <!-- Ersetze den src-Pfad durch dein Bild -->
            <img
            src="{{ asset('images/giveaway/hero.jpg') }}"
            alt="RewardQuest Gewinnspiel – Hero"
            class="w-full h-auto rounded-2xl shadow-lg ring-1 ring-black/5 object-cover"
            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 800 450%22%3E%3Crect width=%22800%22 height=%22450%22 fill=%22%23f3f4f6%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%239ca3af%22 font-family=%22Arial%22 font-size=%2220%22%3EHero Bild-Platzhalter 16:9%3C/text%3E%3C/svg%3E';"
            />
        </div>
        </section>

        <!-- So funktioniert’s -->
        <section class="space-y-6">
        <h2 class="text-2xl font-semibold">So nimmst du teil</h2>

        <div class="grid gap-6 md:grid-cols-3">
            <!-- Step 1 -->
            <article class="space-y-3">
            <!-- Step Icon / Bild-Platzhalter -->
            <img
                src="{{ asset('images/giveaway/step1.jpg') }}"
                alt="Schritt 1 – Instagram-Post kommentieren"
                class="w-full aspect-video object-cover rounded-xl ring-1 ring-black/5"
                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22%3E%3Crect width=%22400%22 height=%22225%22 fill=%22%23f3f4f6%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%239ca3af%22 font-family=%22Arial%22 font-size=%2216%22%3EStep 1 Bild (16:9)%3C/text%3E%3C/svg%3E';"
            />
            <div class="space-y-1">
                <h3 class="font-semibold">1) Instagram-Post kommentieren</h3>
                <p class="text-gray-700">
                Kommentiere unseren offiziellen Gewinnspiel-Post.
                <span class="block text-sm text-gray-500">Tipp: @regulierungscheck auf Instagram.</span>
                </p>
            </div>
            </article>

            <!-- Step 2 -->
            <article class="space-y-3">
            <img
                src="{{ asset('images/giveaway/step2.jpg') }}"
                alt="Schritt 2 – Folgen & Liken"
                class="w-full aspect-video object-cover rounded-xl ring-1 ring-black/5"
                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22%3E%3Crect width=%22400%22 height=%22225%22 fill=%22%23f3f4f6%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%239ca3af%22 font-family=%22Arial%22 font-size=%2216%22%3EStep 2 Bild (16:9)%3C/text%3E%3C/svg%3E';"
            />
            <div class="space-y-1">
                <h3 class="font-semibold">2) Folgen &amp; Liken</h3>
                <p class="text-gray-700">
                Folge <em>@regulierungscheck</em> und like den Gewinnspiel-Post.
                </p>
            </div>
            </article>

            <!-- Step 3 -->
            <article class="space-y-3">
            <img
                src="{{ asset('images/giveaway/step3.jpg') }}"
                alt="Schritt 3 – Bewertung abgeben & verifizieren"
                class="w-full aspect-video object-cover rounded-xl ring-1 ring-black/5"
                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 225%22%3E%3Crect width=%22400%22 height=%22225%22 fill=%22%23f3f4f6%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%239ca3af%22 font-family=%22Arial%22 font-size=%2216%22%3EStep 3 Bild (16:9)%3C/text%3E%3C/svg%3E';"
            />
            <div class="space-y-1">
                <h3 class="font-semibold">3) Bewertung abgeben &amp; bestätigen</h3>
                <p class="text-gray-700">
                Klicke auf „Jetzt Bewertung abgeben“ und fülle das Formular aus. E-Mail zuerst, danach deine Bewertung –
                so können wir dich im Gewinnfall sicher erreichen.
                </p>
            </div>
            </article>
        </div>
        </section>

        <!-- Gewinnerziehung -->
        <section class="space-y-4">
        <h2 class="text-2xl font-semibold">Ablauf der Gewinnerziehung</h2>

        <div class="grid gap-6 md:grid-cols-2">
            <ul class="list-disc pl-6 space-y-2 text-gray-700">
            <li>
                Der Gewinner wird nach Ende des Gewinnspiels <strong>per Zufall</strong> aus allen gültigen Kommentaren ermittelt
                (unabhängiges Tool, z.&nbsp;B. commentpicker.com).
            </li>
            <li>
                Danach wird geprüft, ob die Bedingungen erfüllt sind <span class="text-gray-600">(Folgen, Liken, Kommentar)</span>.
                Falls nicht, wird automatisch neu ausgelost.
            </li>
            <li>
                Die Ziehung wird <strong>durchgehend per Screen-Recording</strong> dokumentiert und intern gespeichert – für maximale Transparenz.
            </li>
            </ul>

            <!-- Doku/Sicherheit Bild-Platzhalter -->
            <div>
            <img
                src="{{ asset('images/giveaway/drawing-proof.jpg') }}"
                alt="Dokumentation der Ziehung – Screenrecording"
                class="w-full h-auto rounded-2xl ring-1 ring-black/5 object-cover"
                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 600 400%22%3E%3Crect width=%22600%22 height=%22400%22 fill=%22%23f3f4f6%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 fill=%22%239ca3af%22 font-family=%22Arial%22 font-size=%2216%22%3EDokumentation Bild-Platzhalter 3:2%3C/text%3E%3C/svg%3E';"
            />
            </div>
        </div>
        </section>

        <!-- Bekanntgabe -->
        <section class="space-y-3">
        <h2 class="text-2xl font-semibold">Bekanntgabe des Gewinners</h2>
        <ul class="list-disc pl-6 space-y-2 text-gray-700">
            <li>Benachrichtigung per <strong>Instagram-Direktnachricht</strong>.</li>
            <li>Zusätzlich öffentliche Nennung im <strong>Feed</strong> oder in der <strong>Story</strong>.</li>
            <li>Auszahlung: <strong>500&nbsp;€</strong> per Banküberweisung an den Gewinner.</li>
        </ul>
        </section>

    </div>

    <x-dialog-modal wire:model="showTermsModal" :maxWidth="'4xl'">
        <x-slot name="title">
            Teilnahmebedingungen
        </x-slot>

        <x-slot name="content" class="">
            <div class="space-y-6 text-gray-700">
                <p>
                    Veranstalter des Gewinnspiels ist die <strong>Regulierungs-Check RCQ GmbH</strong>, Neuer Wall 80, 20354 Hamburg, 
                    <a href="mailto:info@regulierungs-check.de" class="text-primary underline">info@regulierungs-check.de</a>
                </p>

                <h3 class="text-lg font-semibold">Teilnahmeberechtigung</h3>
                <p>
                    Teilnahmeberechtigt sind alle natürlichen Personen ab 18 Jahren mit Wohnsitz in Deutschland. 
                    Von der Teilnahme ausgeschlossen sind Mitarbeiter/innen des Veranstalters sowie deren Angehörige.
                </p>

                <h3 class="text-lg font-semibold">Teilnahmevoraussetzungen</h3>
                <p>Die Teilnahme ist kostenlos und an folgende Bedingungen geknüpft:</p>
                <ul class="list-disc pl-6 space-y-1">
                    <li>Folgen des Instagram-Accounts <strong>@regulierungs_check</strong></li>
                    <li>Liken des offiziellen Gewinnspiel-Posts</li>
                    <li>Markieren mindestens einer Person in den Kommentaren unter dem Gewinnspiel-Post</li>
                    <li>Abgabe einer kostenlosen, anonymen Bewertung der eigenen Versicherung auf 
                        <a href="https://www.regulierungs-check.de" target="_blank" class="text-primary underline">www.regulierungs-check.de</a>
                    </li>
                </ul>
                <p>
                    Die Teilnahme ist nur im Teilnahmezeitraum möglich. Nach Teilnahmeschluss eingehende Beiträge 
                    werden nicht berücksichtigt.
                </p>

                <h3 class="text-lg font-semibold">Teilnahmezeitraum</h3>
                <p>Das Gewinnspiel startet am <strong>21.09.2025</strong> und endet am <strong>02.11.2025 um 23:59 Uhr</strong>.</p>

                <h3 class="text-lg font-semibold">Gewinn</h3>
                <p>
                    Es werden <strong>2 Geldpreise zu je 1.000 €</strong> verlost.  
                    Die Auszahlung erfolgt per Überweisung auf ein vom Gewinner angegebenes Konto.  
                    Eine Übertragung oder Auszahlung in anderer Form ist ausgeschlossen.
                </p>

                <h3 class="text-lg font-semibold">Gewinnerermittlung und -benachrichtigung</h3>
                <p>
                    Die Gewinner/innen werden nach Teilnahmeschluss per Zufallsprinzip ermittelt.  
                    Die Benachrichtigung erfolgt bis spätestens <strong>07.11.2025</strong> per Direktnachricht auf Instagram 
                    sowie per Kommentarfunktion unter dem Gewinnspiel-Post.
                </p>
                <p>
                    Reagiert ein Gewinner nicht innerhalb von 7 Tagen, verfällt der Gewinnanspruch, 
                    und es wird ein Ersatzgewinner ausgelost.
                </p>

                <h3 class="text-lg font-semibold">Ausschluss vom Gewinnspiel</h3>
                <p>
                    Der Veranstalter behält sich das Recht vor, Teilnehmer/innen bei Verstößen gegen die Teilnahmebedingungen, 
                    bei falschen Angaben oder Manipulationsversuchen vom Gewinnspiel auszuschließen.
                </p>

                <h3 class="text-lg font-semibold">Vorzeitige Beendigung</h3>
                <p>
                    Der Veranstalter behält sich das Recht vor, das Gewinnspiel jederzeit aus wichtigem Grund ohne Vorankündigung 
                    zu beenden oder zu ändern.
                </p>

                <h3 class="text-lg font-semibold">Haftung</h3>
                <p>Ansprüche gegenüber dem Veranstalter, die über den ausgelobten Gewinn hinausgehen, sind ausgeschlossen.</p>

                <h3 class="text-lg font-semibold">Rechtsweg</h3>
                <p>Der Rechtsweg ist ausgeschlossen.</p>

                <h3 class="text-lg font-semibold">Instagram/TikTok-Hinweis</h3>
                <p>
                    Dieses Gewinnspiel steht in keiner Verbindung zu Instagram oder TikTok und wird in keiner Weise von diesen 
                    gesponsert, unterstützt oder organisiert. Ansprechpartner und Verantwortlicher ist ausschließlich der Veranstalter.
                </p>

                <h3 class="text-lg font-semibold">Datenschutz</h3>
                <p>Im Rahmen des Gewinnspiels werden folgende Daten verarbeitet:</p>
                <ul class="list-disc pl-6 space-y-1">
                    <li>Instagram-Nutzername (zur Überprüfung der Teilnahmebedingungen)</li>
                    <li>Teilnahmezeitraum (Nachweis, dass die Bewertung im Gewinnspielzeitraum abgegeben wurde)</li>
                    <li>Im Gewinnfall: Name und Bankverbindung (für die Auszahlung)</li>
                </ul>
                <p>
                    Die Daten werden ausschließlich für die Durchführung und Abwicklung des Gewinnspiels genutzt. 
                    Rechtsgrundlage ist Art. 6 Abs. 1 lit. b DSGVO.
                </p>
                <ul class="list-disc pl-6 space-y-1">
                    <li>Teilnahmevermerke und sonstige Gewinnspiel-Daten werden spätestens 3 Monate nach Ende des Gewinnspiels gelöscht.</li>
                    <li>Gewinnerdaten, die für die Auszahlung erforderlich sind, werden gemäß handels- und steuerrechtlichen Vorschriften 10 Jahre gespeichert.</li>
                    <li>Plattform-Account-Daten bleiben bestehen, solange der Nutzer sein Konto nicht löscht.</li>
                </ul>
                <p>
                    Weitere Informationen zum Datenschutz finden Sie in unserer 
                    <a href="https://www.regulierungs-check.de" target="_blank" class="text-primary underline">allgemeinen Datenschutzerklärung</a>.
                </p>
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-buttons.button-basic wire:click="$set('showTermsModal', false)" class="">
                Schließen
            </x-buttons.button-basic>
        </x-slot>
    </x-dialog-modal>

    <livewire:customer.rating.rating-form />
</div>
