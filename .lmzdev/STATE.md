# Current state

## Confirmed

- Promotion-Kern ist auf direkten Webrequest-Betrieb reduziert: keine Promotion-Commands/Scheduler, keine Auditmail/Ankerfelder, kein separater Zugriffskontext. Einmal-QR, Transaktionen, Kontingent, RBAC und synchrone HMAC-Auditkette bleiben erhalten.
- Alle gefundenen Raw-Ausgaben von `posts.body` laufen nun durch eine enge DOM-Allowlist. Dadurch ist auch unsicherer Blog-/News-Altbestand ohne Datenmigration renderseitig passiv, waehrend legitime Rich-HTML-Formatierung erhalten bleibt.
- LMZ Dev workspace initialized.
- Der Promotion-Aktivschalter stoppt weder faellige Datenschutz-Loeschungen noch Verankerung und Verifikation vorhandener Auditereignisse. Fehlende Kontexttabellen sind ein sauberer No-op; korrupte signierte Settings liefern Failure ohne Mail.
- Oeffentliche Blogtitel werden in Karte und Detailseite escaped; WebPage-Titel werden ebenfalls escaped und historische WebPage-Icons vor jeder Raw-Ausgabe fail-closed sanitiziert.

## Verification

- Vereinfachter Base-Promotion-Ordner: 35 Tests/212 Assertions bestanden, ausschliesslich ueber die in `phpunit.xml` hart erzwungene SQLite-In-Memory-Verbindung. Darin: Legacy-MAC-Konvertierung und fail-closed Abbruch bei manipuliertem Legacy-MAC.
- Admin-Auditkern fokussiert: 5 Tests/55 Assertions bestanden. PHP-Lint fuer gemeinsame Settings-/Audit-/Model-/Migrations-/Testdateien bestanden; Settings-Service und -Model sind zwischen Base/Admin byte-identisch.
- Blog-HTML-Sicherheit: enger Sanitizer-/Blog-Lauf 5 Tests/51 Assertions; kombinierter Lauf mit dem zweiten `posts.body`-Raw-Sink 12 Tests/111 Assertions bestanden. Echtes Blade-Rendering von schadhaftem Altbestand, erlaubte Formatierung, sichere Links und verschleierte URL-/Element-Payloads sind abgedeckt. PHP-Lint und Pint-Test der eng geaenderten PHP-Dateien bestanden.
- Wartungs-/Audit-Fokus: Base 17 Tests/98 Assertions bestanden; vier PHP-Lints, Pint fuer beide Commands und `git diff --check` bestanden.
- DB-gesteuerte Promotion-Einstellungen: gesamter Base-Promotion-Ordner 34 Tests/207 Assertions gruen; alle Promotion-Consumer lesen den MAC-geschuetzten Singleton fail-closed.
- Gesamter Promotion-Ordner plus Icon-Sanitizer-/Render-Regressionen: 48 Tests mit 220 Assertions bestanden. Der unveraenderte Promotion-Kern bleibt bei 29 Tests/178 Assertions; die 19 Icon-Tests liefern 42 Assertions.
- PHP-Lint fuer alle drei neuen Base-PHP-Dateien und scoped `git diff --check` bestanden.
- Legacy-P1-Fokus Base: 4 Tests mit 18 Assertions bestanden (echtes Blade-Rendering fuer Blogtitel und DB-gestuetztes Legacy-WebPage-Rendering); PHP-Lint, Pint fuer beide Tests und globaler Diff-Check bestanden.

## Risks and blockers

- Lokale Entwicklungsdatenbank `regulierungs-check` wurde am 2026-08-13 gegen 18:35 UTC durch einen irrtuemlich mit `--env=testing` gestarteten Artisan-`migrate:fresh` geleert; mangels `.env.testing` nutzte Artisan die Standard-MySQL-Verbindung. Der Lauf brach nach dem Erstellen der Migrationstabelle vor den Anwendungs-Migrationen ab. Kein Restore wurde durch den verursachenden Agenten gestartet; Root-Agent ist informiert.
- Kein offener Promotion-P0/P1 im geprueften Kernstand; Root-Agent uebernimmt abschliessende Admin-Integration und Testguard-Haertung.
- Bekannte P2: Domaincode ist zwischen Base/Admin gespiegelt; Audit-Verify ist bewusst vollstaendig und damit mit wachsender Eventzahl linear; Schluesselrotation ist fail-closed und benoetigt einen geplanten Wartungspfad.
- Bestehende schadhafte InsuranceType-Iconwerte bleiben in der Datenbank erhalten, werden aber in allen gefundenen oeffentlichen Raw-Renderpfaden verworfen. Eine spaetere Datenbereinigung ist optional und nicht fuer die XSS-Abwehr erforderlich.
