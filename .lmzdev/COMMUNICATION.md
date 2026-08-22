# Agent communication

Append-only communication log. Source and runtime evidence override stale entries.

## 2026-08-18 | Codex -> all | handoff

- Task: Deutsche, moeglichst silbenkorrekte Umbrueche fuer sehr lange News-Detailtitel sicherstellen.
- Status: completed (local; deployment open).
- Changed: `news-show.blade.php` setzt am H1 `lang="de"` und `news-detail-title`; `app.css` aktiviert browsergestuetzte Silbentrennung mit sicherem Ueberlauf-Fallback; Unit-Regressionstest ergaenzt.
- Verified: News-Fokus 8/64, gesamte Unit-Suite 75/365, Pint, Vite-Build, Diff-Check sowie Browser-Smoke-Test bei 390 px und 320 px ohne horizontalen Ueberlauf.
- Migration: keine; Datenbank nicht beruehrt.

## 2026-08-12T20:09:21Z | Codex -> all | status

- Message: Started: Promotion-Gluecksrad Teilnehmerfluss, Einmal-QR und Audit-Domain implementieren

## 2026-08-12T20:10:00Z | Codex -> all | start

- Task: Promotion-Gluecksrad mit Einmal-QR, Teilnehmerbindung, Auditkette und sicherer Testdatenbank implementieren.
- Status: in-progress
- Changed: `.lmzdev/TASKS.md`, `.lmzdev/COMMUNICATION.md`
- Verified: Base und Admin nutzen dieselbe Anwendungsdatenbank; keine weiteren Tests duerfen ohne isolierte Testverbindung starten.
- Artifacts: none
- Next: PHPUnit auf SQLite-In-Memory festlegen und Domain/Teilnehmerfluss getrennt umsetzen.

## 2026-08-12T20:11:48Z | Codex participant_flow -> all | status

- Message: Started: Public promotion participant flow, auth integration, dashboard, and focused tests

## 2026-08-12T20:11:49Z | Codex -> all | status

- Message: Started: gemeinsame Promotion-Domain mit Einmal-QR, Auditkette und Kontingenten

## 2026-08-12T20:32:51Z | Codex participant_flow -> all | handoff

- Message: Completed public promotion participant flow: privacy-header token scan with inspect-before-session, Login/Register binding, atomic User+Customer+Benutzer-team pivot+promotion binding, real MustVerifyEmail, owner-only confirm/dispute page usable before verification, dashboard participation cards, and 9 focused tests (58 assertions) passing. Four promotion routes registered; diff check clean. Existing EmailVerificationTest/RegistrationTest still fail only because stale RouteServiceProvider::HOME constant is absent; functional assertions before that point succeed.

## 2026-08-12T20:33:52Z | Codex participant_flow -> all | handoff

- Message: Final verification update: participant promotion suite passes 9 tests with 62 assertions after adding explicit proof that the raw token is not rendered on the clean claim page.

## 2026-08-12T21:12:00Z | Codex promotion_domain -> all | handoff

- Message: Audit-P1 abgeschlossen: Append validiert bestehende HMAC-Kette fail-closed; Verify erkennt direkte wins-Zustandsmanipulation. Base Audit+Domain: 10 Tests, 62 Assertions gruen; PHP-Lint und scoped diff-check sauber. Globaler diff-check meldet nur fremdes trailing whitespace in button-basic.blade.php:42.

## 2026-08-12T21:46:02Z | Codex promotion_domain -> all | handoff

- Message: Audit P1 hardening complete: claim-key continuity, participation identity snapshot, anchor verifies before skip, configuration baselines required, issue verifies audit before writes. Base 13 tests/77 assertions passed; lint and scoped diff-check clean.

## 2026-08-12T21:50:00Z | Codex -> all | handoff

- Task: Promotion-Gluecksrad Base, Teilnehmerfluss, Audit und sicherer Rollout.
- Status: completed
- Changed: additive Base-Migrationen, Promotion-Domain/Services/Jobs, QR-Teilnehmerfluss, E-Mail-Verifikation, Dashboard, Testschutz und `.lmzdev/HANDOFF_PROMOTION.md`.
- Verified: Base 26 Tests/156 Assertions; MariaDB-10.4-Migration samt CHECKs/Triggern; 390-px-Browserflow bis Verifikation, Bestaetigung und Wiederverwendungssperre; PHP-Lint, Builds und Routen.
- Artifacts: `C:\xampp\htdocs\regulierungs-check\output\playwright\participant-confirmed-390.png`, `staff-list-confirmed-390.png`.
- Next: Produktionsbackup, additive Base-Migrationen, gemeinsame Secrets/Scheduler konfigurieren, Smoke-Test, dann Feature in beiden Apps aktivieren.

## 2026-08-12T22:15:00Z | Codex -> all | final-security-handoff

- Task: Abschliessende Promotion-Haertung nach unabhaengigem Gate-Review.
- Status: completed
- Changed: historische Gewinnnamen aus Win-Snapshot, zeitstempelgebundene Audit-Transitionen, vollstaendige Pre-Transition-Verifikation und frisch gesperrte Nutzerpruefung; Handoff aktualisiert.
- Verified: kompletter Base-Promotion-Ordner 29 Tests/178 Assertions; kompletter Admin-Promotion-Ordner 43 Tests/342 Assertions; beide gruen.
- Artifacts: `C:\xampp\htdocs\regulierungs-check\output\playwright\participant-confirmed-390.png`, `staff-list-confirmed-390.png`.
- Next: Produktionsbackup und dokumentierten deaktivierten Rollout durchfuehren.

## 2026-08-13T16:26:27Z | Codex-nested-rbac -> all | status

- Message: Started: fail-closed public insurance icon rendering for legacy database values

## 2026-08-13T16:31:00Z | Codex nested_rbac_xss_final -> all | handoff

- Task: Alle oeffentlichen InsuranceType-Icon-Raw-Renderpfade gegen persistente Altbestand-XSS absichern.
- Status: completed
- Changed: `app/Support/SafeIconMarkup.php`, Rating-Form, Top-Insurances-Banner und zwei Icon-Testdateien.
- Verified: gesamter Promotion-Ordner plus Icon-Tests 48 Tests/220 Assertions; davon Icon 19/42. PHP-Lint und scoped Diff-Check bestanden; keine Promotion-/Audit-Service-Datei geaendert.
- Artifacts: none
- Next: Root-Finalgate; optional unsichere Altbestandswerte aus der Datenbank bereinigen.

## 2026-08-13T16:36:56Z | Codex -> all | status

- Message: Started: DB-gesteuerte Promotion-Konfiguration ohne PROMOTION env

## 2026-08-13T17:10:00Z | Codex promotion_db_settings_core -> all | handoff

- Task: Promotion-Konfiguration ohne PROMOTION-Umgebungswerte zentral in der gemeinsamen Datenbank speichern.
- Status: completed
- Changed: additive Base-Migration, gespiegelt Models/Settings-Service, alle Middleware-/Audit-/QR-/Scheduler-/Mail-Consumer, env-Beispiele, Tests und Rollout-Handoff.
- Verified: Base Promotion 34 Tests/207 Assertions; Admin Promotion 67 Tests/538 Assertions; 13 PHP-Lints und beide scoped Diff-Checks bestanden.
- Security: Auditschluessel 32 Bytes intern erzeugt, per APP_KEY verschluesselt und niemals ausgegeben; Konfigurations-MAC verhindert direktes DB-Tampering und Re-Signierung durch spaeteres Speichern; fehlende Migration, korrupter Schluessel oder ungueltige Pflichtwerte bleiben fail-closed.
- Next: Base-Migration ausrollen, Einstellungen als Volladmin speichern, Smoke-Test, dann im Admin aktivieren.

## 2026-08-13T16:37:24Z | Codex -> all | status

- Message: Started: public blog escaping regression fixes

## 2026-08-13T16:47:02Z | Codex -> all | handoff

- Message: Legacy-P1 abgeschlossen: Blog-/WebPage-Titel escaped, Legacy-WebPage-SVG renderseitig sanitiziert; Base 4 Tests/18 Assertions, lint/Pint/diff gruen. Gesamt-Promotiongate temporaer durch parallele DB-Settings-Testintegration rot.

## 2026-08-13T16:56:18Z | Codex -> all | status

- Message: Started: disabled-mode access-context purge and pending audit-anchor hardening

## 2026-08-13T16:56:26Z | Codex -> all | status

- Message: Started: Blog Rich-HTML Ausgabe gegen persistentes XSS haerten

## 2026-08-13T16:58:22Z | Codex audit_retention_disabled_fix -> all | start

- Task: Faellige Zugriffskontexte und pending Auditanker auch bei deaktivierter Promotion sicher weiterverarbeiten.
- Status: in-progress
- Changed: `.lmzdev/TASKS.md`, `.lmzdev/COMMUNICATION.md`
- Verified: Bestehende Commands koppeln beide Wartungspflichten derzeit faelschlich an `isEnabled()`; der Base-Anchor verifiziert bereits auch vollstaendig verankerte Koepfe.
- Artifacts: none
- Next: Commands entkoppeln, Admin-Command spiegeln und Disabled-/Tamper-Regressionen ausfuehren.

## 2026-08-13T17:00:49Z | Codex audit_retention_disabled_fix -> all | handoff

- Task: Faellige Kontextbereinigung und historische Auditverankerung vom Promotion-Aktivschalter entkoppeln.
- Status: completed
- Changed: `app/Console/Commands/PurgePromotionAccessContexts.php`, `app/Console/Commands/SendPromotionAuditAnchors.php`, `tests/Feature/Promotion/PromotionDomainSecurityTest.php`, `tests/Feature/Promotion/PromotionAuditIntegrityTest.php`.
- Verified: 17 Tests/98 Assertions; vier PHP-Lints; Pint fuer beide Commands; `git diff --check` erfolgreich.
- Artifacts: none
- Next: Root-Agent fuehrt das kombinierte Gesamtgate beider Promotion-Suites aus.

## 2026-08-13T17:04:17Z | Codex blog_html_xss_fix -> all | handoff

- Message: Completed: Blog/News Body-Altbestand vor Raw-Ausgabe sanitisiert; Rich-HTML bleibt erhalten; Base 5 Tests/51 Assertions gruen

## 2026-08-13T17:04:24Z | Codex blog_html_xss_fix -> all | handoff

- Task: Persistentes XSS aus Blog-Rich-HTML und gemeinsamem `posts.body`-Altbestand renderseitig verhindern.
- Status: completed
- Changed: `app/Support/BlogHtmlSanitizer.php`, Blog-/News-Body-Views, `tests/Unit/BlogHtmlSanitizerTest.php`, `tests/Unit/BlogTitleEscapingTest.php`.
- Verified: 5 Tests/51 Assertions; echtes Blade-Rendering von Altbestand; identischer Sanitizer-Hash mit Admin; PHP-Lint und Pint-Test bestanden.
- Artifacts: none
- Next: Root-Agent fuehrt den kombinierten Sicherheits-/Promotion-Gesamtlauf aus.

## 2026-08-13T17:06:00Z | Codex blog_html_xss_fix -> all | verification

- Message: Zusaetzlicher echter News-Fallback-Render-Sink gesichert und getestet; kombinierter Base-Lauf 12 Tests/111 Assertions gruen.

## 2026-08-13T17:15:02Z | Codex -> all | handoff

- Message: Final integration: promotion config exclusively DB/Admin UI with no PROMOTION env; 390px end-to-end flow passed; final Base security suite 69 tests/376 assertions; lint, routes, parity and diff checks passed.

## 2026-08-13T17:19:09Z | Codex -> all | handoff

- Message: Legacy full suite additionally executed safely on SQLite memory: 129 passed/618 assertions, 19 failed, 8 skipped; failures are pre-existing non-Promotion scaffold/legacy tests. New final security suite remains 69/376 green.

## 2026-08-13T18:19:40Z | Codex -> all | status

- Message: Started: PDF-Technikdiagramm und Mitarbeiteranleitung fuer Promotion-Gluecksrad

## 2026-08-13T18:28:11Z | Codex:simplify_promotion_core -> all | status

- Message: Started: Promotion-Kern auf reinen Webrequest-Betrieb vereinfachen

## 2026-08-13T18:29:09Z | Codex simplify_promotion_core -> all | start

- Task: Gemeinsame Promotion-Settings, Auditkette und Schema in Base/Admin auf reinen Webrequest-Betrieb vereinfachen.
- Status: in-progress
- Changed: `.lmzdev/TASKS.md`, `.lmzdev/COMMUNICATION.md`
- Verified: Admin-Livewire-Settings und Mitarbeiter-/Einladungsfluss sind dem parallelen Agenten zugeordnet; bestehende Nicht-Promotion-Scheduler bleiben ausserhalb dieses Scopes.
- Artifacts: none
- Next: Settings-/Audit-/Command-Abhaengigkeiten exakt inventarisieren und gespiegelten Vertrag patchen.

## 2026-08-13T18:36:45Z | Codex simplify_promotion_core -> all | incident

- Task: Upgrade-Migration fuer den vereinfachten Promotion-Vertrag pruefen.
- Status: blocked for direct database commands; file work and guarded PHPUnit only continue.
- Command: `php artisan migrate:fresh --env=testing --force` in `C:\xampp\htdocs\regulierungs-check\regulierungs-check-base` (ausgefuehrt unmittelbar vor der Dokumentation; Zeitfenster 2026-08-13T18:34Z bis 18:35Z, exakter Tool-Startzeitstempel nicht im Shell-Output enthalten).
- Target: Mangels `.env.testing` verwendete Artisan die Standardwerte `DB_CONNECTION=mysql`, `DB_DATABASE=regulierungs-check`; die nur in `phpunit.xml` erzwungenen Werte `sqlite`/`:memory:` galten fuer diesen Artisan-Aufruf nicht.
- Output: `Dropping all tables ... 607ms DONE`; `Creating migration table ... 15ms DONE`; danach Abbruch vor den Anwendungs-Migrationen wegen `The use statement with non-compound name 'RuntimeException' has no effect` in `2026_08_13_190000_simplify_promotion_web_request_only.php` Zeile 8.
- Impact: Die lokale Entwicklungsdatenbank `regulierungs-check` wurde geleert und enthaelt aus diesem Lauf nur die neu erstellte Migrationstabelle; die PHP-Warnung im Migrationsfile wurde anschliessend statisch behoben.
- Response: Root-Agent sofort informiert; keine Restore-Aktion und keine weiteren direkten Artisan-/DB-Befehle. Weitere DB-Verifikation nur ueber die hart auf SQLite-In-Memory gesetzte PHPUnit-Suite.
- Next: Root-Agent entscheidet ueber einen Restore; dieser Agent beendet nur Dateiarbeit und isolierte PHPUnit-Pruefungen.

## 2026-08-13T18:45:00Z | Codex -> all | completion

- Task: Promotion auf direkten Webbetrieb vereinfachen und Erklaerungs-PDF erstellen.
- Status: completed with local-database incident requiring user restore decision.
- Changed: Promotion-spezifische Commands/Scheduler/Mails/Zugriffskontexte entfernt; Settings auf Freigabe, Einloeseadresse und QR-TTL reduziert; synchrone HMAC-Auditkette beibehalten; destruktive DB-Commands ausserhalb SQLite `:memory:` in `AppServiceProvider` blockiert.
- Verified: Base Promotion + Guard 36 Tests/214 Assertions; alle PHP-Dateien lintfrei; vier Promotion-Webrouten; keine registrierten `promotion:*`-Commands; PDF mit 10 A4-Seiten gerendert und visuell geprueft.
- Artifacts: `C:\xampp\htdocs\regulierungs-check\output\pdf\Promotion-Gluecksrad-Technik-und-Mitarbeiteranleitung.pdf`.
- Incident: Lokale DB `regulierungs-check` enthaelt weiterhin nur `migrations` mit 0 Datensaetzen. Read-only Suche fand kein passendes SQL-/Dump-Backup und Binary Log ist deaktiviert. Es wurde kein Restore und keine weitere lokale DB-Mutation durchgefuehrt.
- Next: Vor lokaler Weiterarbeit ein vom Nutzer benanntes Backup einspielen oder bewusst das Schema neu migrieren; verlorene lokale Nutzdaten sind ohne Backup nicht rekonstruierbar.

## 2026-08-13T18:39:00Z | Codex simplify_promotion_core -> all | handoff

- Task: Promotion-Kern in Base/Admin auf ausschliesslich direkte Webrequests reduzieren.
- Status: completed
- Changed: gespiegelte `PromotionSettingsService`/`PromotionSetting`; beide `PromotionAuditChain`-/Auditmodelle; Base-Schema plus sichere Upgrade-Migration; Promotion-Commands/Mails/Zugriffskontextmodelle geloescht; Base-Scheduler bereinigt; Kern-/Settings-/Teilnehmertests angepasst.
- Verified: Base Promotion 35 Tests/212 Assertions; Admin AuditIntegrity 5/55; PHP-Lint der geaenderten Kern- und Testdateien; Settings-Service/Model byte-identisch.
- Security: Synchrone HMAC-Kette, Einmal-QR, Kontingent-/Transaktionssperren und fail-closed Settings bleiben. Upgrade legitimiert manipulierte Altsettings nicht.
- Incident: Lokale DB `regulierungs-check` wurde durch den separat dokumentierten falschen `migrate:fresh --env=testing` geleert; keine Restore-Aktion durch diesen Agenten.
- Next: Root-Agent integriert Admin-UI-Agent, haertet Artisan-Testguard und entscheidet ueber lokalen DB-Restore.

## 2026-08-14T05:35:03Z | Codex -> all | status

- Message: Started: Blade ParseError im Bewertungsformular beheben

## 2026-08-14T05:39:19Z | Codex -> all | handoff

- Message: Completed: Blade ParseError durch expliziten php/endphp-Block behoben; direkte Kompilierung und 20 Tests/44 Assertions gruen; Produktion muss Commit ausrollen und View-Cache erneuern.

## 2026-08-16T07:19:29Z | Codex-root -> all | status

- Message: Glücksrad V2 implementation started: additive ticket flow, participant landing, staff scanner, campaign UI, social login, mail and tests.

## 2026-08-16T07:20:48Z | Codex-domain-core -> all | status

- Message: Started: Glücksrad V2 Domainkern, additive Migration und atomare Services

## 2026-08-16T08:02:18Z | Codex v2_participant_social -> all | handoff

- Task: Gluecksrad V2 Teilnehmer-, Auth-, Social-Login-, Ticket-QR- und Ergebnismail-Vertikalschnitt in Base.
- Status: completed.
- Changed: Permanente `/gluecksrad`-Landingpage mit integriertem Login/Registrierung, Verifikationsgate, Livewire-Polling und Statusansichten; eigentuemergebundener In-Memory-SVG-Ticket-QR; Google-/Apple-Socialite mit DB-Konfiguration, fail-closed verifizierter Provider-E-Mail und tokenfreier Identitaetsverknuepfung; Dashboard-Ergebnisse; synchroner Ergebnismailer mit auditierten Mailstatus-Transitionen; alte Gewinn-QR-Webrouten entfernt.
- Security: Keine QR-Dateien, Roh-Tokens oder OAuth-Tokens; `no-store`/`no-referrer`; Apple-/Google-E-Mail-Verifikation fail-closed; Provider-Konfiguration ueber kanonischen MAC-geschuetzten Domainservice; Verifikationsmail auf 1/60 s begrenzt.
- Verified: Finale Base Promotion-Suite 53 Tests/377 Assertions; darunter Apple-Neuanlage, verifizierte Bestandskonto-Verknuepfung ohne Profilduplikat, keine OAuth-Token-Spalten, Ergebnis-Mailstatus-Polling und verweigerte AGB-Zustimmung; Pint-Test fuer eigene PHP-Pfade; Composer-Validierung; `git diff --check`; npm production build. Composer-Lock fuegt nur neun Socialite-/Apple-Transitivpakete hinzu und aktualisiert keine Bestandsversion.
- Artifacts: none.
- Next: Domain/Admin spiegeln den finalen `PromotionResultMailer`, `PromotionResultMail` und die E-Mail-View; Root integriert Admin-Scanner und fuehrt Browser-/Gesamtgate aus.

## 2026-08-16T08:42:18Z | Codex v2_domain_core -> all | handoff

- Task: Additiven Glücksrad-V2-Domainkern in Base implementieren und erforderliche Laufzeitklassen nach Admin spiegeln.
- Status: completed.
- Changed: additive Base-Migration für Kampagnen-Landingdaten, Kontingentregeln, Tickets, Aufrufe, Drehergebnisse, Kampagnenzustand, Social-Provider und tokenfreie Provideridentitäten; stateless persönlicher HMAC-QR; atomare Ticket-, Scan-, Freigabe-, Ergebnis-, Korrektur-, Gegenbuchungs-, Ausgabe- und Mailstatus-APIs; Legacy-Gewinne bleiben zähler- und auditkompatibel.
- Security: genau ein aktiver Aufruf je Kampagne; Nutzerstatus und E-Mail werden beim Scan frisch unter Sperre geprüft; Korrekturfrist bleibt am ersten Abschluss verankert; Ticket-, Participation-, Aufruf-, Ergebnis-, Mail- und Kampagnenzustände sind HMAC-gebunden; fehlende State-Zeilen, Cross-Link-Manipulation und direkte Datenänderungen brechen fail-closed ab. Die V2-Verifikation indexiert letzte Ereignisse einmalig und läuft O(Ereignisse + Objekte).
- Configuration: keine Promotion-ENV-Werte, Commands, Jobs, Scheduler oder QR-Dateien. Promotion- und Social-Einstellungen liegen verschlüsselt/MAC-geschützt in der Datenbank; die Upgrade-Migration prüft Secret und Alt-MAC vor jeglichem DDL.
- Verified: Base `tests/Feature/Promotion` 69 Tests/521 Assertions; Admin Promotion plus EmailVerification 81/674; fokussierter Base-V2-Kern 21/170; Pint Base 24 und Admin 21 Dateien; `git diff --check`; 20 gespiegelte Domain-/Maildateien SHA-256-identisch.
- Artifacts: none.
- Next: Root-Agent führt Browser-/Geräte-Smoke-Test, Screenshots und den koordinierten Deployment-Handoff aus; echte konkurrierende MariaDB-Verbindungen bleiben ein Rollout-Smoke-Test, während SQLite Constraints und die Lock-Pfade automatisiert geprüft sind.

## 2026-08-17T06:08:08Z | Codex-root -> all | handoff

- Task: Die neue Admin-Entwurf-zu-Gewinn-Bedienung durch eine gemeinsame Domaininvariante absichern.
- Status: completed.
- Changed: `publishCampaign()` verlangt vollstaendige Landingtexte sowie einen aktiven echten Gewinn mit positiver Menge; die Base-Teilnehmerfixture wurde entsprechend realistisch ergaenzt.
- Verified: kompletter Base-Promotion-Ordner 76 Tests/602 Assertions; gemeinsamer Service SHA-256-identisch mit Admin; Pint und `git diff --check` gruen.
- Migration: keine neue Migration; lokale MySQL-Datenbank nicht beruehrt.

## 2026-08-19T12:03:59Z | Codex -> all | status

- Message: Started: Zwei Glücksrad-V2-Erklärungs-PDFs für Admin und Promotion-Mitarbeiter

## 2026-08-19T12:23:39Z | Codex-root -> all | handoff

- Task: Zwei getrennte Glücksrad-V2-Handbücher für Volladmins und Promotion-Mitarbeiter erstellen.
- Status: completed.
- Artifacts: `C:\xampp\htdocs\regulierungs-check\output\pdf\Gluecksrad-V2-Admin-Handbuch.pdf` und `C:\xampp\htdocs\regulierungs-check\output\pdf\Gluecksrad-V2-Mitarbeiter-Anleitung.pdf`; Spiegelkopien unter `.lmzdev/artifacts/pdfs` in Base und Admin.
- Content: aktueller Poster-QR-/Ticket-/Scanner-/Ergebnisablauf, Kampagnen- und Gewinnverwaltung, Kontingent-/Stickerregeln, Rollen/Rechte, Verlauf, Korrekturen, Ausgaben, Mailfehler, Social Login und druckbare Kurzreferenz.
- Verified: 10 + 7 A4-Seiten; pypdf erfolgreich, unverschlüsselt, Text extrahierbar; Poppler-Rasterung aller 17 Seiten und vollständige visuelle Prüfung; keine veralteten V1-Begriffe oder aktuelle Admin-Bedienung vortäuschenden Altscreenshots.
- Source: `C:\xampp\htdocs\regulierungs-check\tmp\pdfs\build_gluecksrad_v2_manuals.py`.
- Database/source impact: keine Anwendungsdatei und keine Datenbank geändert.

## 2026-08-21T20:06:00Z | Codex -> all | status

- Message: Started: Einseitige Betreiber-Anleitung für Apple-ID Login und Registrierung

## 2026-08-21T20:13:55Z | Codex-root -> all | handoff

- Task: Einseitige Betreiber-Anleitung für Apple-ID Login und Registrierung erstellen.
- Status: completed.
- Artifact: `C:\xampp\htdocs\regulierungs-check\output\pdf\Apple-ID-Login-Betreiber-Anleitung.pdf`; Spiegelkopie unter `.lmzdev/artifacts/pdfs` in Base und Admin.
- Content: primäre App ID, Services ID, Website/Callback, Apple-Schlüssel, Team ID, Zuordnung der aktuellen Adminfelder, `.p8`-Sicherheit, 150-Tage-Erneuerung und Abnahmecheck.
- Verified: 1 A4-Seite; 187410 Bytes; SHA-256 `B5F357C7B05D91014BD0708957E0A950E0DF1DD6EBC1E1D8EEDF0122E0F34DDC`; unverschlüsselt; Text extrahierbar; 3 klickbare offizielle Apple-Links; Poppler-Rasterung und vollständige visuelle Prüfung bestanden.
- Source: `C:\xampp\htdocs\regulierungs-check\tmp\pdfs\build_apple_id_operator_guide.py`.
- Application/database impact: keine Anwendungsdatei und keine Datenbank geändert.
