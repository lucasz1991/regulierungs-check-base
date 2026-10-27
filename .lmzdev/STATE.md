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

## 2026-09-04 | Admin-Mitarbeiter-Onboarding

- Das Mitarbeiter-Onboarding wurde ausschließlich im Admin generalisiert; Base benötigt dafür keine Route, Queue, Job, Command, Migration oder Datenmodelländerung.
- Die vom Admin-Build synchronisierte Tailwind-Datei enthält die Klassen der neuen teamneutralen Mitarbeiterverwaltung und Passwortsetzansicht.

## 2026-10-24 | Lokales Startseiten Redesign

- Freigegebenes Homepage-Design in Base integriert; native Livewire-Daten, Suche, Auth und Bewertungseinstieg bleiben angebunden.
- Separate Preview-SQLite wegen leerer lokaler MySQL. Prozessisolierter Launcher prueft DB, Mailer und Sessions, inklusive separatem Configcachepfad.
- Server laufen auf 6666 und browserkompatibel 8186. Build und 38 fokussierte Tests / 167 Assertions erfolgreich; responsive Browser- und Morph-Pruefungen erfolgreich.
- Bericht und bekannte Grenzen: artifacts/reports/homepage-redesign-local-2026-10-24.md. Kein Deployment.

## 2026-10-24 | Startseite visuell optimiert

- Nutzerhinweisleiste entfernt; hochwertigere lokale Gestaltung auf 8186 geöffnet.
- Vollständige Navigation ab 1180 px, zweizeiliger Desktop-Hero, mobile Prozessschritte mit vollständigem Text sowie verfeinerte Karten, News, About, Newsletter und Footer. Escape im nativen Bewertungsdialog korrigiert.
- Build und 13 fokussierte Tests / 110 Assertions erfolgreich; Browser bei 320–1440 px geprüft. Report: artifacts/reports/homepage-polish-2026-10-24.md.

## 2026-10-25 | Newsstreifen wieder minimal

- Newsband auf Nutzerwunsch kompakt (154 px), einzeilige Titel und einfache Linkpfeile; Wiedergabe- und Slidercontrols samt Scrollbars entfernt.
- Bestehender 36-px/s-Endloslauf unveraendert; Fokus-/Reduced-Motion-/Livewire-Verhalten erhalten.
- 8 fokussierte Tests / 59 Assertions, Pint und Diffcheck bestanden. Nutzer-IAB sowie 320/390-px-Browser, Loopgrenze und Bewegungsreduktion bestaetigt.
- Report: artifacts/reports/news-minimal-2026-10-25.md. Lokal auf 8186 geoeffnet; 6666 laeuft weiterhin. Kein Deployment.

## 2026-10-25 | Native Integration und lokale Migration

- Separate Startseiten-CSS-/JS-Varianten sowie kopierte Bilder/Fonts und das Importskript entfernt. CSS in vorhandener app.css, Navigation/Carousel/Lifecycle in app.js, Ticker in der vorhandenen homepage-news-ticker.js; normale Original-Assets. Vorschau-Flag in config/app.php integriert.
- Produktion-Build erfolgreich: app-xQvcqIuV.js und app-WqAnX6aU.css. Fokustests 13/112 und Pint bestanden; Quellcode/HTML ohne Redesign-Datei-/Pfadbezeichnungen.
- Auf ausdruecklichen Folgeauftrag lokale MySQL 127.0.0.1:3306 / regulierungs-check normal migriert. Vorher nur migrations mit 0 Eintraegen; danach 76 angewendete Migrationen, 0 ausstehend, 57 Tabellen inkl. sessions. Kein Fresh-/Reset-/Seed-Kommando.
- Port 6565 liefert HTTP 200 und ist im Nutzer-IAB geoeffnet. Kein SQLSTATE, alle Bilder geladen, kein Seitenueberlauf bei 1093 px. Fachinhalte der frischen lokalen DB bleiben leer. 8186/6666 nutzen weiterhin ihre getrennte Beispiel-SQLite.
- Kein Deployment. Keine neuen Varianten oder Reportdateien; vorhandene Projektdokumentation fortgeschrieben.

## 2026-10-25 | Live-Dump lokal importiert

- Nutzerarchiv E:/projekte/regulierungscheck/regulierungs-check_2026-10-25_05-43-49.sql.zip CRC/SHA256 geprueft, unveraendert direkt in lokale MySQL 127.0.0.1:3306 / regulierungs-check importiert. Die MariaDB-10.11-Sandbox-Kopfzeile wurde ausschliesslich im Speicher fuer den lokalen 10.4-Client ausgelassen.
- Vorherige lokale DB samt .env privat gesichert: E:/projekte/regulierungscheck/regulierungs-check_lokal_vor_import_2026-10-25_04-48-58Z.sql.zip. Keine Rohdumps, Geheimnisse oder personenbezogenen Inhalte in .lmzdev.
- Import ohne Fehler/Warnungen; unmittelbare Anfangspruefung deckungsgleich: 57 Tabellen, 125680 Datensaetze in 46 nichtleeren Tabellen, 3629 Benutzer, 632 Versicherer, 4325 Bewertungen, 39 Beitraege, 40 Sessions, 76 Migrationen und 2 Schutz-Trigger. Keine ausstehenden Migrationen.
- Nur vorhandene lokale .env angepasst: APP_URL=127.0.0.1:6565 und MAIL_MAILER=array. Queue bleibt database; keine Worker/Scheduler gestartet oder vorhanden. Eingebaute LogActivity-Jobs aus lokalen HTTP-Pruefungen bleiben unausgefuehrt; kein Mail-/AI-Job ausgefuehrt.
- Cache geleert, Wartungsmodus aufgehoben. Native Seite auf 6565 HTTP 200 und im vorhandenen IAB geoeffnet. Echte Kategorien, Aggregate (192 oeffentlich bewertete Versicherer), Filter/Reset und weiterlaufende News nach Livewire-Wechsel bestaetigt; kein Seitenueberlauf, keine geladenen Bildfehler. Alle 20 lokalen Homepage-Storage-Bilder liegen vor.
- Keine neuen Varianten, Importskripte oder Berichtdateien; notwendige Sicherung ausserhalb des Repositories. Screenshot local-homepage.png wurde aktualisiert. Produktivsystem und getrennte SQLite-Vorschauen auf 6666/8186 unveraendert.

## 2026-10-25 | Ruhiger Hero und kompakte Sparten

- Drei Browserkommentare umgesetzt: sichtbare News-Titelzeile inkl. Alle-News-Link entfernt, Newsregion bleibt per aria-label benannt; Endloslauf und Artikellinks erhalten.
- Hero mit schlichter Eyebrow, klarer zweizeiliger Desktop-Headline, begrenzter Illustration auf einer dezenten Flaeche und Kennzahl als schlanker Bildunterschrift. Inhalte, echte192-Aggregation und native Bewertungsaktion erhalten.
- Spartenintro linksbuendig und kompakt: 29px Desktopheading, 44px Tabs statt grosser Karten; weitere Sparten und Reset nativ/Livewire. Mobil scrollt die erste Tabzeile innerhalb ihrer eigenen Breite; Fokusrahmen bleibt sichtbar.
- Direkt in vorhandenen app.css/Welcome-/Banner-Blades umgesetzt, bestehender News-Test angepasst. Keine neuen Varianten, Assets oder Hilfsdateien.
- Build app-BRBRi0gH.css erfolgreich; 9 fokussierte Tests / 63 Assertions, Pint und Diffcheck bestanden. HTTP200 auf6565.
- Browser 1387/1024/980/390/320 px: keine Seiten-/Headlineueberlaeufe, Bild passt in seine Flaeche, Kennzahl liegt darunter, Tabs44px. News110px statt154px; Desktopintro ca102px statt363px.
- Dunkle und helle Mobilansicht, Tastaturoeffnung der zusaetzlichen Sparten, Auswahl Gewerbe & Betrieb und Reset erfolgreich; keine duplizierten News-Klone. ReducedMotion stoppt News. Temporaerer Prueftab samt Emulation geschlossen, Nutzeransicht wieder dunkel und Desktop.
- Native6565 bleibt geoeffnet; vorhandenes local-homepage.png aktualisiert. Keine Geschaeftsdaten- oder Produktionsaenderungen.

## 2026-10-25 | Untere Startseite und echte CMS-Inhalte vereinheitlicht

- Konkrete Importfolge behoben: About-CMS liegt in content_between_2, daher beide Mittelslots jetzt generisch einmal rendern und About-Fallback nur bei vollstaendig leeren Mittelslots. Echter Newsletter bleibt im Footer-Slot mit originalem Formular/CSRF/JS. Andere Seiten behalten ihr bisheriges CMS-Rendering.
- Home-CMS hat semantische, themenfaehige Wrapper. H1-Tokens werden nur in diesen Home-Modulen zu H2; Attribute/Raw-Text geschuetzt. Newsletter-Markenname in sichtbarem Text ungetrennt, NBSP danach als normale Leerzeichen dargestellt. Gespeicherter CMS-Inhalt bleibt unveraendert.
- Einheitliche untere Inhaltsachsen, ruhigere Headinggroessen und Review-Karten, kompaktere Prozessschritte/Insurerhoehen, lesbare native CMS-About-CTA und Newsletterfarben, groessere Footer-Linkziele/korrekte Beschriftungen. Mobiler Hero bei320px/900px vollstaendig mit Kennzahl sichtbar.
- Nur vorhandene Dateien genutzt; keine neuen Varianten/Assets/Hilfs- oder Reportdateien. Bestehendes local-homepage.png mit unterem Seitenbereich aktualisiert.
- Build app-Drtb7ryW.css erfolgreich; 17 fokussierte Tests / 164 Assertions, Pint und Diffcheck bestanden. Drei CMS-Regressionsfaelle plus Markennamenfall im bestehenden HomepageBannerDataTest.
- Browser 1387/1024/768/390/320 px ohne Seitenueberlauf, genau1H1 und1About; echtes Formular/CSRF erhalten. Markenname390:246.19px innerhalb312px,320:216.64px innerhalb242px, jeweils eine Zeile. Darkmode-Input/Newsletterfarben lesbar, Fokusrahmen2px, required/email erhalten; kein Submit ausgefuehrt.
- Seite Desktop ca4084px statt ca4857px (Bewertungsvolltexte variieren). Temporaerer Prueftab geschlossen, Emulation entfernt, gewaehlter heller Modus wiederhergestellt; native6565 lokal geoeffnet. Kein Deployment und keine Geschaeftsdatenaenderung.

## 2026-10-25 | Product Design installiert, visuelle Auswahl offen

- Aktuelle Plugin-Recherche mit offiziellen Quellen zu Product Design, Figma MCP und MagicPath. Product Design als passendster direkter Workflow fuer die bestehende Laravel-Seite ausgewaehlt und per Codex CLI installiert; Version 0.1.56, installed/enabled sowohl CLI als auch Plugin-Katalog bestaetigt.
- Originalseite und Markenassets visuell geprueft. Drei eigenstaendige ImageGen-Konzepte auf Basis echter Screenshots/Logos und vorhandener Daten erstellt und im Chat ausgegeben. Richtungen: Verbraucher-Journal, Vergleich im Fokus, Starke Marke. Die sichtbare Chat-Reihenfolge ist massgeblich fuer eine numerische Auswahl; aus der Aufrufreihenfolge darf keine Nummerierung abgeleitet werden.
- Product-Design-ideate verlangt vor der Umsetzung eine Nutzerauswahl. Diese steht aus; in diesem Schritt keine UI-Quelldateien oder Geschaeftsdaten geaendert. Bestehende lokale Seite auf 6565 bleibt verfuegbar.
- Bei Umsetzung den gewaehlten Entwurf in vorhandene Blade/CSS/JS-Dateien integrieren, konkurrierende CSS-Regeln ersetzen statt neue Override-Lagen anzuhaengen. Kennzahl korrekt als Anzahl bewerteter Versicherer bezeichnen. Marke, Funktionen, vollstaendige Bewertungstexte, native Formulare und News-Endloslauf erhalten; keine Variantenordner oder unnoetigen Dateien.

## 2026-10-25 | Erste Konzepte verworfen, drei behutsame Varianten mit Content-Lock

- Nutzer gefaellt keiner der ersten drei Entwuerfe und verlangt bestehenden Content. Diese ersten Konzepte sind verworfen; aktuell gilt die neue Auswahlrunde.
- Aktuelle native Seite auf6565 erneut visuell und vorhandene Blades read-only geprueft. Bestehende local-homepage.png aktualisiert. Originalcopy, Illustration, Logos, Daten, Funktionen und Abschnittsreihenfolge fuer alle drei neuen Generierungen verbindlich vorgegeben.
- Drei ueberarbeitete Bilder im Chat vollstaendig ausgegeben. Sichtbare Reihenfolge dieser neuen Runde: 1 Klarer Weissraum, 2 Ruhige Markenflaeche, 3 Kompakt und praezise. Exakte Bilddateien stehen im folgenden Handoff in COMMUNICATION.md.
- Keine UI-Quelldateien oder Geschaeftsdaten in dieser Runde geaendert; keine neuen Repo-Varianten oder Hilfsdateien erstellt. Visuelle Auswahl vor Umsetzung gemaess Product-Design-ideate weiterhin offen. Der neue Content-Lock in DECISIONS.md ersetzt die vorherige Absicht einer beilaeufigen Kennzahl-Umbeschriftung.

## 2026-10-25 | Gewaehlte neue Variante 1 lokal umgesetzt

- Nutzer waehlt explizit Nr1 der neuen Runde: exec-2d37d551-2aa1-4318-8448-ed2e37194792.png. Direkt in vorhandener resources/css/app.css umgesetzt; keine Blade-/JS-/Backend-/Datenbankaenderungen in diesem Umsetzungsschritt und keine neuen Varianten/Assets/Test-/Berichtdateien.
- Weisser heller Grund, gemeinsame Inhaltsachsen, offene zweispaltige Hero-Komposition ohne Hintergrundoval, originale Illustration und flache Kennzahlunterschrift. Bestehende Texte, Labels, Originalbilder, dynamische Werte und Funktionen bleiben erhalten. Titel66→62px im visuellen Abgleich, groessere Lesetexte/CTAs, Header80px.
- 354 ueberholte Hero-/Versichererkarten-Selektoren aus alten CSS-Lagen entfernt, gemeinsame Review-Selektoren dabei erhalten; nun je eine kanonische Root-Regel fuer Hero/Grid/Card/Core samt gezielten Mediaqueries. CSS insgesamt3794 statt4331Zeilen. Native Rail-/Spaltenberechnung und alle Carousel-Hooks erhalten.
- Versicherer sind einfache weisse Karten mit12pxRadius/einerLinie, originalem Logo/44pxHinweisziel, Name, Dauer und Sternen/Bewertungszahl nebeneinander und44pxLinkziel. Kein verschachtelter Tray/Core-Hintergrund. News bleibt durchlaufend ohne Titel/Controls/Scrollbar, mit besser lesbarem Teaser.
- Design QA final result: passed. Quelle und echte Implementierung beide1490x1055px beiDPR1 in EINEM2980x1055Vergleich geprueft, nach erster Korrekturrunde erneut verglichen; unabhaengige selected_layout_review bestaetigt keineP0/P1/P2. Fuenf Flaechen: Montserrat/Hierarchie, Abstaende, Weiss/Petrol/Teal/Gold, scharfe Originalassets und vollstaendige Originalinhalte geprueft. Heroendet541.39px, News668.19px, Kartenstart846.03px, Kartenhoehe211.72px. Bewusste Abweichungen: echte ADAC-Logodatei statt generierter Form, originale Nav-/CTA-Pfeile und44pxZiele statt kleiner Mock-Linkflaechen.
- Browserbreiten1490/1280/1180/1101/1024/980/979/768/640/390/320 ohne Seiten-/Titel-/Kennzahlueberlauf; genau1H1 und1echtesNewsletterformular. Keine fehlgeschlagenen geladenen Bilder;13Lazy-Bilder ausserhalb des sichtbaren Bereichs noch ungeladen. Dunkle390pxAnsicht geprueft und heller Ausgangsmodus wiederhergestellt.
- NativeSparten perTastatur, Gewerbe&Betrieb/Reset, SucheAllianz→2echteTreffer/Reset→20, Bewertungsdialog/Escape/Fokusrueckkehr und Versichererslider2/21→1/21 geprueft. Ein NewsTrack/sechsKlone, sichtbarer Endloslauf11→934px; ReducedMotion bleibt934→934. Kein Formular abgeschickt, keineWorker/Scheduler/externenMails gestartet.
- Build app-BZk7m2lF.css erfolgreich;17fokussierteTests/164Assertions und finalerDiffcheck bestanden. Bekannte Buildhinweise fuer absolute vorhandeneFont-/Iconpfade; Montserrat imBrowser geladen. Browserkonsole meldet ausschliesslich vorhandeneUsercentrics-CMP-Freigabe fuer127.0.0.1; kein neuerAppfehler festgestellt.
- Vorhandene local-homepage.png abschliessend mit echtem1490x1120Render ueberschrieben. TemporaererPrueftab geschlossen, Emulation entfernt; bestehenderIAB-Tab1 auf6565 aktualisiert/hell/geoeffnet und neueCSSimDOM bestaetigt. KeinCommit/Push/Deployment, Produktivsystem unberuehrt.

## 2026-10-25 | Markierte Spartenwahl und Suchzeile verfeinert

- Browsermarkierungen gezielt in vorhandener resources/css/app.css umgesetzt. Konkrete Ursache der kleinen Spartenbeschriftungen: generische .category-label-Regel erzwang9px und7px unteren Rand. Im kompakten Spartenbereich jetzt vererbte Schrift/Zeilenhoehe, kein Rand, korrekte vertikale Ausrichtung;13px Desktop und12px Mobil.
- Gleichmaessige44px Sparten, etwas groessere Originalicons, lesbares Weitere-Sparten-Element, eindeutige aktive Auswahl und2px Tastaturfokus. Suchpfeil jetzt44x44px; Suche mit einer einfachen Kontur statt doppeltem Schatten. Dynamische Ergebniszahl13px/550 mit dezenter Trennlinie; mobil passend rechts ohne Trennlinie. Abstaende innerhalb des markierten Bereichs abgestimmt.
- Hero, News, Karten, Inhalte, Werte, Routes, Formulare und JS unveraendert. Keine neuen Dateien oder Varianten; vorhandene local-homepage.png mit1350x490Detailausschnitt aus echtem1490x1170Render aktualisiert.
- Build app-CACTgb6b.css und Diffcheck bestanden. Browser1490/1024/768/640/390/320 ohne Seiten-/Zahlueberlauf, gemessene13/12px Labels ohne7pxRand,44px Ziele. Tastaturfokus2px, echte Spartenwahl/Reset, SucheAllianz→2Treffer, Leerzustand→0, Reset→20; Dunkelmodus getestet und hell wiederhergestellt. Kein Formular abgeschickt oder Geschaeftsdaten geaendert.
- OriginalerIAB-Tab auf6565 mit neuerCSS aktualisiert/geoeffnet; Prueftab geschlossen und Emulation entfernt. Bestehende Buildhinweise zu absoluten Font-/Iconpfaden unveraendert. Keine neuen Tests fuer reine Darstellung, keine Produktionseingriffe.

## 2026-10-25 | Landingpage mit abgestimmten Animationen und mehr Tiefe

- Neue Browseranmerkung umgesetzt, direkt in vorhandenen resources/css/app.css und resources/js/app.js. Root gestaltet CSS; homepage_motion implementiert den integrierten Bewegungscontroller; controls_detail_review prueft die echte Desktopaufnahme read-only. Keine neuen Dateien, Bilder, Bibliotheken oder Varianten. Originaltexte, Logos, Illustration, Werte, Reihenfolge und native Funktionen erhalten.
- Ruhigere Hero-Abstaende, kleine Marken-Eyebrow, kraeftigere Textunterstreichung, weiche Kennzahlflaeche und dezenter CTA-Schatten. Klarere Hierarchie im Vergleichsbereich; einfache18pxKarten mit besser lesbaren Namen/Fakten/Links und sanftem Hover. Bei1280/1328px entdeckte Faktenueberlagerung behoben: Dauerlabel erhaelt eine eigene Gridzeile, Dauer/Sterne folgen darunter. DOM und Lesereihenfolge bleiben erhalten.
- Bestehende WAAPI-/IntersectionObserver-Integration erweitert: gestaffelter Einstieg, einmalige Abschnitts-/Kartenanimationen, Originalillustration schwebt um7px/6.4s. Mausneigung maximal3Grad nur bei feinem Zeiger. Bewegungen animieren Transform/Opacity; keine dauernden Scroll-Listener, keine Basisinhalte perCSS versteckt.
- Hero-Bewegung pausiert ausserhalb des Bildes oder bei verborgenem Dokument. ReducedMotion cancelt Bewegung sofort; Grundzustand voll sichtbar. Controller/Listener/RAF/Timer werden bei Navigation bereinigt, alte Observercallbacks abgesichert, getrennte Elemente entfernt. WeakSet plus wire:key-Historie verhindern wiederholte Einstiege derselben Karten nach Morphs. Tastaturfokus cancelt einen laufenden Einstieg des fokussierten Bereichs zugunsten voller Sichtbarkeit.
- Browserbelege: sichtbare Float-Matrizen veraendern sich; ausserhalb des Bildes exaktgleich(-6.27692px) vor/nach weiteren Pruefungen. Mausneigung1.2/0.84Grad beobachtet, beiVerlassen InlineTransform wiederleer. ReducedMotion: ArtTransform none, StageTransform leer, Hero/Titel/Karten jeweilsOpacity1; danach Fortsetzung bestaetigt.
- Zehn Breiten1490/1328/1280/1024/980/979/768/640/390/320: keine Seiten-, Titel-, Kennzahl- oder Scoreueberlaeufe; Dauerlabel/Sterne ueberlappen nach Korrektur nirgends. Mobiler Hero bei320px endet884px inklusive Kennzahl. Dunkle390pxAnsicht, echteSparten/Reset, SucheAllianz→2/Reset→20, Bewertungsdialog/Escape geprueft. Ein NewsTrack/sechsKlone auch nach Livewire-Wechsel und Versicherer-Navigation/zurueck. Genau1H1/1Newsletterformular; keine fehlgeschlagenen geladenen Bilder.
- Historientest stellte einen zuvor geoeffneten nativen Bewertungsdialog wieder her; ueber vorhandenes Abbrechen geschlossen. Kein Formular abgeschickt und kein Geschaeftsdatensatz geaendert. Dieser gecachte Dialogzustand wurde nicht als neue Regression des Bewegungscontrollers belegt; Dialogkomponente unveraendert.
- Finaler Build app-mqbN8rVf.css/app-CL5lDAqb.js erfolgreich,17fokussierteTests/164Assertions bestanden, Syntax-/Diffcheck bestanden. Agent meldet neun isolierte Lifecyclechecks ohne neue Testdatei. Bekannte absolute Font-/Icon-Buildhinweise sowie Usercentrics-Freigabe fuer127.0.0.1 bleiben getrennte lokale Hinweise; keine neuen App-JS-Fehler beobachtet.
- Vorhandene local-homepage.png mit neutralem echtem1490x1280Render aktualisiert. OriginalerIAB-Tab1 auf6565 neu geladen, aktuelleCSS/JS und heller Modus bestaetigt; reduzierte Bewegung nicht erzwungen. Prueftab geschlossen und Emulation bereinigt. KeinCommit/Push/Deployment oder Produktionseingriff.

## 2026-10-25 | Dezente geschwungene Linien als Scrollhintergrund

- Nutzerwuensch direkt in vorhandenen layouts/app.blade.php und app.css umgesetzt: zwei dekorative SVG-Ebenen mit insgesamt sechs geschwungenen Linien in Petrol und zurueckhaltendem Gold. Keine neuen Dateien, Assets, Bibliotheken oder JS-Scroll-Handler. Bestehende Inhalte und Gestaltung erhalten.
- Home-only Rendering, aria-hidden/focusable=false, pointer-events:none, feste negative Hintergrundebene unter isoliertem Body. Weiche seitliche Maskierung und reduzierte Deckkraft; vorhandene deckende News-/Prozess-/Footerflaechen bleiben erhalten. Header/Dialoge erhalten ihre bisherigen Ebenen und Positionen.
- Native CSS-Scroll-Timeline bewegt die Ebenen leicht gegeneinander. Progressive Enhancement ueber @supports; bei fehlender Unterstuetzung und bei ReducedMotion bleibt die Grafik statisch. Keine neue JS-Lifecycle-Verwaltung erforderlich, da die Ebene mit dem Seitenlayout entfernt wird.
- Browserbeleg Scroll0→2613: nahe Ebene von translate0/0 zu20/-140px, ferne Ebene von0/-45 zu-16/70px. ReducedMotion: animation-name none und beideTransforms none. Helle und dunkle1490pxAnsicht sowie390pxMobilansicht visuell geprueft; sechsBreiten1490/1024/768/640/390/320 ohne Seitenueberlauf, jeweils1H1/1Hintergrund/6Pfade. Deckkraft Desktop.18, Mobil.13, Dunkel.12/.10.
- Bewertungsschaltflaeche/Dialog/Abbrechen und Suche2→20 funktionieren vor der Hintergrundebene. Auf Versichererseite0Hintergruende, nach BrowserZurueck wiedergenau1 ohne Duplikate. Bestehender gecachter Dialogzustand beiHistorienrueckkehr wie vorher ueberAbbrechen geschlossen; keine Komponentenaenderung oder Formulareingabe.
- Build app-Bq_iUgwn.css erfolgreich, Diffcheck bestanden. Reine Darstellung: keine neuen Tests; echte Browserpruefungen bestaetigen Verhalten. Vorhandene lokale Font-/Icon-Buildhinweise unveraendert.
- Vorhandene local-homepage.png mit neutralem1490x1280Render aktualisiert. OriginalIAB6565 neu geladen, Startposition0, neueTimeline aktiv/hell/geoeffnet; Prueftab geschlossen und Emulation entfernt. Keine Produktionseingriffe, Commits, Pushes oder Geschaeftsdatenaenderungen.

## 2026-10-25 | Hintergrund klarer und Lade-/Scrollanimationen deutlicher

- Nutzer wuenscht etwas deutlichere Linien und mehr Animation beim Laden/Scrollen. Nur vorhandene app.css/app.js angepasst; keine neuen Dateien, Bibliotheken, Assets oder Inhalte. Root erhoeht Sichtbarkeit/Parallax; stronger_page_motion erweitert den bestehenden Bewegungscontroller.
- Linien: Deckkraft hell .26 statt .18, mobil .19 statt .13, dunkel .18/.15; Strich1.35 statt1.2px. Vertikaler Ueberstand20Prozent verhindert Abschneiden bei laengerem Scrollweg. Bei1490x1280: nahe Ebene0/0→36/-250.88px, ferne0/-71.68→-28/179.2px. Zentrale Maskierung und statischer ReducedMotion-Fallback bleiben erhalten.
- Neue/staerkere einmalige Einstiege: Hintergrund blendet1500ms zu seiner berechneten CSS-Deckkraft ein, Header-Inhalt, getrennte blockartige Titelzeilen, Newsband als Ganzes, Sparten mit wire:key, Karten und CTA-Icon. Meist32px Weg/880–940ms plus100ms Kartenstaffelung. Keine Animation von Zahlen, keine News-Klonanimationen oder neuen Scroll-Handler.
- Desktop-/Mobil-Titel teilen ihre Verlaufshistorie, dadurch keine erneute Animation nach Resize/Morph. Illustration-Einstieg bleibt deckend, damit die eigene Kennzahlanimation nicht mit einer Eltern-Fade multipliziert wird. Fokus cancelt laufende Einstiege; bereits vor Observer-Callback fokussierte Bereiche werden nach Registrierung direkt sichtbar gelassen. ReducedMotion stellt sofort den nativen Grundzustand her.
- Browserbelege: Ladebeginn Header/Titel/CTA/News/Sparten .08, Hintergrund0 mit44pxVersatz und CTA-Icon gedreht/verkleinert; Endzustand Inhalte jeweils1, Hintergrund exakt.26/Transform none. Nach PageDown Kartenmatrix/Opacities noch in Bewegung, Sparten zeitversetzt .98/.97/.94/.92. Mobile H1 als Ganzes statt einzelner Inline-Spans animiert. Tastaturfokus macht CTA-Bereich sofort deckend.
- Sechs Breiten1490/1280/1024/768/390/320 ohne Seitenueberlauf, jeweils eine Hintergrundebene. ReducedMotion schaltet CSS-Timeline und alle Einstiege aus; Titel/Header1, Hintergrund.26/.19. Dunkel.18Desktop/.15Mobil geprueft. EchteSparten/Reset: aktiveSparte1 und alteKarten nachReset jeweils1 ohne Neustart; SucheAllianz2→20, einNewsTrack/sechsKlone. Keine neuen App-Konsolenfehler; bestehender lokalerCMP-Hinweis getrennt.
- Build app-CXLHywpa.css/app-BWoRyZ1V.js, Syntax-/Diffcheck bestanden. Agent:19 isolierte Lifecyclechecks plus separater Fokus-vor-Observer-Fall bestanden, ohne neue Testdatei. Vorhandene absolute Font-/Icon-Buildhinweise bleiben unveraendert.
- Bestehende local-homepage.png mit neutralem1490x1280Render aktualisiert. Prueftab geschlossen und Emulation entfernt; Originalbrowser wird zum Abschluss mit neuem Ladeablauf auf6565 aktualisiert. Keine Backend-/Daten-/Produktionsaenderung oder Deployment.
