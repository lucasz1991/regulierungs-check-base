# Current state

## Confirmed

- Live-Diagnose 2026-08-26: Alle 24 stichprobenartig aus der aktuellen Startseite extrahierten `/storage/...`-Medien liefern `404 text/html`. `/storage/` selbst ist fuer Apache ein vorhandenes Verzeichnis (`403` wegen deaktiviertem Listing), die erwarteten Unterordner `/storage/uploads/`, `/storage/uploads/files/` und `/storage/pagebuilder_images/` werden jedoch als nicht vorhandene Verzeichnisse behandelt und auf die slashlose URL umgeleitet. Damit zeigt der produktive `public/storage`-Pfad nicht auf den vorhandenen Medienbestand oder Apache darf dessen Unterordner nicht traversieren.
- Das Bewertungsformular kompiliert wieder zu gueltigem PHP. Die SafeIcon-Zuweisung verwendet einen explizit abgeschlossenen `@php`/`@endphp`-Block; ein Regressionstest kompiliert das vollstaendige Blade-Template und prueft es auf `ParseError`.
- Promotion-Kern ist auf direkten Webrequest-Betrieb reduziert: keine Promotion-Commands/Scheduler, keine Auditmail/Ankerfelder, kein separater Zugriffskontext. Einmal-QR, Transaktionen, Kontingent, RBAC und synchrone HMAC-Auditkette bleiben erhalten.
- Alle gefundenen Raw-Ausgaben von `posts.body` laufen nun durch eine enge DOM-Allowlist. Dadurch ist auch unsicherer Blog-/News-Altbestand ohne Datenmigration renderseitig passiv, waehrend legitime Rich-HTML-Formatierung erhalten bleibt.
- LMZ Dev workspace initialized.
- Der Promotion-Aktivschalter stoppt weder faellige Datenschutz-Loeschungen noch Verankerung und Verifikation vorhandener Auditereignisse. Fehlende Kontexttabellen sind ein sauberer No-op; korrupte signierte Settings liefern Failure ohne Mail.
- Oeffentliche Blogtitel werden in Karte und Detailseite escaped; WebPage-Titel werden ebenfalls escaped und historische WebPage-Icons vor jeder Raw-Ausgabe fail-closed sanitiziert.

## Verification

- Bewertungsformular: direkte Blade-Kompilierung erfolgreich; `RatingFormBladeCompilationTest`, `InsuranceTypeIconRenderSecurityTest` und `SafeIconMarkupTest` zusammen 20 Tests/44 Assertions bestanden.
- Vereinfachter Base-Promotion-Ordner: 35 Tests/212 Assertions bestanden, ausschliesslich ueber die in `phpunit.xml` hart erzwungene SQLite-In-Memory-Verbindung. Darin: Legacy-MAC-Konvertierung und fail-closed Abbruch bei manipuliertem Legacy-MAC.
- Admin-Auditkern fokussiert: 5 Tests/55 Assertions bestanden. PHP-Lint fuer gemeinsame Settings-/Audit-/Model-/Migrations-/Testdateien bestanden; Settings-Service und -Model sind zwischen Base/Admin byte-identisch.
- Blog-HTML-Sicherheit: enger Sanitizer-/Blog-Lauf 5 Tests/51 Assertions; kombinierter Lauf mit dem zweiten `posts.body`-Raw-Sink 12 Tests/111 Assertions bestanden. Echtes Blade-Rendering von schadhaftem Altbestand, erlaubte Formatierung, sichere Links und verschleierte URL-/Element-Payloads sind abgedeckt. PHP-Lint und Pint-Test der eng geaenderten PHP-Dateien bestanden.
- Wartungs-/Audit-Fokus: Base 17 Tests/98 Assertions bestanden; vier PHP-Lints, Pint fuer beide Commands und `git diff --check` bestanden.
- DB-gesteuerte Promotion-Einstellungen: gesamter Base-Promotion-Ordner 34 Tests/207 Assertions gruen; alle Promotion-Consumer lesen den MAC-geschuetzten Singleton fail-closed.
- Gesamter Promotion-Ordner plus Icon-Sanitizer-/Render-Regressionen: 48 Tests mit 220 Assertions bestanden. Der unveraenderte Promotion-Kern bleibt bei 29 Tests/178 Assertions; die 19 Icon-Tests liefern 42 Assertions.
- PHP-Lint fuer alle drei neuen Base-PHP-Dateien und scoped `git diff --check` bestanden.
- Legacy-P1-Fokus Base: 4 Tests mit 18 Assertions bestanden (echtes Blade-Rendering fuer Blogtitel und DB-gestuetztes Legacy-WebPage-Rendering); PHP-Lint, Pint fuer beide Tests und globaler Diff-Check bestanden.

## Risks and blockers

- Der Produktionsserver wurde nur per HTTP untersucht; ohne Server-/Plesk-Dateisystemzugriff ist noch offen, ob `public/storage` ein leeres echtes Verzeichnis, ein Link auf den falschen Release-Pfad oder ein korrektes Ziel mit fehlerhaften Traversal-/Besitzrechten ist. Anwendungsdateien und Produktion wurden nicht veraendert.
- Lokale Entwicklungsdatenbank `regulierungs-check` wurde am 2026-08-13 gegen 18:35 UTC durch einen irrtuemlich mit `--env=testing` gestarteten Artisan-`migrate:fresh` geleert; mangels `.env.testing` nutzte Artisan die Standard-MySQL-Verbindung. Der Lauf brach nach dem Erstellen der Migrationstabelle vor den Anwendungs-Migrationen ab. Kein Restore wurde durch den verursachenden Agenten gestartet; Root-Agent ist informiert.
- Kein offener Promotion-P0/P1 im geprueften Kernstand; Root-Agent uebernimmt abschliessende Admin-Integration und Testguard-Haertung.
- Bekannte P2: Domaincode ist zwischen Base/Admin gespiegelt; Audit-Verify ist bewusst vollstaendig und damit mit wachsender Eventzahl linear; Schluesselrotation ist fail-closed und benoetigt einen geplanten Wartungspfad.
- Bestehende schadhafte InsuranceType-Iconwerte bleiben in der Datenbank erhalten, werden aber in allen gefundenen oeffentlichen Raw-Renderpfaden verworfen. Eine spaetere Datenbereinigung ist optional und nicht fuer die XSS-Abwehr erforderlich.

## 2026-08-17 | Domainweite Kampagnen-Veroeffentlichung

- Der gemeinsame PromotionTicketService veroeffentlicht nur aktive, persistierte Kampagnen mit vollstaendigen Landingtexten und mindestens einem aktiven echten Gewinn mit positiver Menge.
- Der Service ist mit dem Admin byte-identisch; die Teilnehmer-Testfixture enthaelt nun einen echten Gewinn und bildet die Produktionsinvariante ab.
- Kompletter Base-Promotion-Ordner: 76 Tests/602 Assertions; Pint und `git diff --check` bestanden.

## 2026-08-18 | Deutsche Silbentrennung im News-Detailtitel

- Der native News-Hero kennzeichnet den Titel explizit mit `lang="de"` und einer eigenen CSS-Regel fuer automatische deutsche Silbentrennung.
- `hyphens: auto` trennt gemaess Browser-Woerterbuch; `word-break: normal` verhindert willkuerliche Zeichenumbrueche. `overflow-wrap: break-word` verhindert nur bei unbekannten Extremwoertern einen horizontalen Ueberlauf.
- Verifiziert: fokussierter News-Layout-Test 8 Tests/64 Assertions, komplette Unit-Suite 75 Tests/365 Assertions, Pint, Vite-Produktionsbuild und `git diff --check` bestanden.
- Browser-Smoke-Test bei 390 px und 320 px: `lang=de`, `hyphens=auto`, `word-break=normal`; Titel- und Seitenbreite ohne horizontalen Ueberlauf.
- Restrisiko: Die exakte sichtbare Silbentrennung richtet sich nach dem deutschen Trennwoerterbuch der jeweiligen Browser-Engine. Unabhaengig davon verhindert der Notfallumbruch ein Abschneiden des Titels.

## 2026-08-19 | Glücksrad-V2-Handbücher

- Zwei getrennte, druckfertige A4-PDFs erklären den aktuellen V2-Ablauf für Volladmins und Promotion-Mitarbeiter.
- Das Admin-Handbuch umfasst 10 Seiten; die Mitarbeiter-Anleitung 7 Seiten inklusive Prozessdiagrammen, Teilnehmeransichten, Kontingent-/Stickerlogik, Sonderfällen und Kurzreferenz.
- Veraltete V1- und alte Admin-Screenshots wurden nicht als aktuelle Bedienoberfläche verwendet. Die Darstellung bildet die vereinfachte Pflege über Gewinnbezeichnung und Menge ab.
- Beide PDFs wurden vollständig mit Poppler gerendert und visuell auf Layout, Überlagerungen, Seitenumbrüche und Lesbarkeit geprüft.

## 2026-08-21 | Apple-ID Betreiber-Kurzanleitung

- Eine einseitige, druckfertige A4-Anleitung erklärt dem Betreiber die vollständige Einrichtung von Sign in with Apple.
- Die Anleitung ordnet Services ID, Team ID, Key ID, Rücksprungadresse und `.p8` exakt den aktuellen Adminfeldern zu.
- Die Produktions-Rücksprungadresse, der einmalige `.p8`-Download, die 150-Tage-Erneuerung und der Test mit verborgener Apple-E-Mail sind hervorgehoben.
- Die PDF wurde mit pypdf und Poppler geprüft, vollständig gerendert und visuell auf Lesbarkeit, Überlagerungen und Umbrüche kontrolliert.
