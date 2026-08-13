# Promotion-Gluecksrad – technischer Handoff

Stand: 2026-08-13. Das Schema wird ausschliesslich aus diesem Base-Repository migriert; Base und Admin verwenden dieselbe Datenbank.

## Rollout

1. Produktionsdatenbank und Tabelle `migrations` sichern und gegen den erwarteten Stand pruefen.
2. Beide Anwendungen deployen; die additive Base-Migration legt die Promotion standardmaessig deaktiviert an.
3. Nur hier `php artisan migrate --force` ausfuehren. Die Migrationen `090000`, `100000`, `110000` und `120000` sind additiv.
4. Sicherstellen, dass beide Anwendungen dieselbe Datenbank und denselben `APP_KEY` verwenden. Die Migration erzeugt den internen Auditschluessel verschluesselt in der Datenbank; er wird nie angezeigt oder als Promotion-Umgebungswert konfiguriert.
5. Kontrolladresse, oeffentliche HTTPS-Einloese-URL, QR-Gueltigkeit und Aufbewahrung ausschliesslich im Admin-Einstellungsbereich speichern.
6. Im Admin `php artisan promotion:ensure-team --owner=<ADMIN-ID-ODER-EMAIL>` ausfuehren. Der Befehl weist niemanden automatisch zu.
7. Als Volladmin bei noch deaktiviertem oeffentlichen Flow Kampagne, die drei Gewinnarten, Surprise-Modus und Kontingente einrichten. Erst eine vollstaendig auditierte Kampagne aktivieren.
8. Base-Scheduler minuetlich ausfuehren (`php artisan schedule:run`). Nur Base steuert QR-Verfall, Auditanker und 24-Monats-Bereinigung.
9. Rechte-/QR-/Mail-/Audit-Smoke-Test durchfuehren und die Promotion danach ausschliesslich im Admin-Einstellungsbereich aktivieren.
10. Im vorgeschalteten Webserver/Proxy die Pfade `/promotion/einloesen/*` und `/mitarbeiter/einladung/*` in Access- und Fehlerlogs redigieren. Beide Pfade enthalten kurzlebige Bearer-Geheimnisse.

## Sicherheitsvertrag

- QR: 32 Zufallsbytes, in der Datenbank nur SHA-256; 30 Minuten; nach Bindung aus URL und Session entfernt.
- Teilnahme: genau ein aktiver Anspruch pro Konto/Kampagne; dauerhafte pruefzifferngeschuetzte Teilnahme-ID.
- Ausgabe: E-Mail-Verifikation erforderlich; `onsite_staff` nur mit Recht, `external_admin` nur Volladmin; Ausgabemodus wird bei QR-Ausgabe gesnapshotet.
- Kontingent: Transaktion, Zeilensperren, Reconciliation und DB-Checks. Abgelaufene Codes bleiben reserviert, bis ein Volladmin strukturiert gegenbucht.
- Audit: synchrone HMAC-Kette mit intern generiertem und per `APP_KEY` verschluesseltem DB-Schluessel, Konfigurations-MAC, Konfigurationsbaselines, Zustand-/Teilnahme-Snapshots, zeitstempelgebundene Transitionen und MariaDB-Trigger gegen UPDATE/DELETE. Vor jeder fachlichen Mutation wird der vollstaendige vorherige Audit-/Domainzustand unter Sperren verifiziert. Gutscheincodes, Roh-Tokens, Namen und E-Mails gehoeren nie ins Event-Ledger.
- RBAC: globale Admins haben Vollzugriff. Das kanonische Team `Promotion` erhaelt exakt `promotion.wins.record`, `promotion.wins.view_all`, `promotion.fulfillment.onsite`; jede Abweichung der Team-Matrix sperrt delegierte Zugriffe fail-closed.

## Verifikation

- Finale Base-Sicherheits-Suite (Promotion, DB-Settings, CMS-Sanitizer und Renderpfade): 69 Tests, 376 Assertions.
- Finale Admin-Sicherheits-Suite (Promotion, RBAC, Audit, DB-Settings und delegierte CMS-Pfade): 90 Tests, 634 Assertions.
- MariaDB 10.4.32: frische Migration, vier Promotion-CHECK-Constraints und beide Immutability-Trigger erfolgreich geprueft; die isolierte `_test`-Datenbank wurde danach entfernt.
- Browser 390 px: Konfiguration und Aktivierung ohne Promotion-Env, QR-Ausgabe, Registrierung, Teilnahme-ID, Bestaetigung vor Mailverifikation, E-Mail-Verifikation, Vor-Ort-Ausgabe, maskierte Mitarbeiterliste und QR-Wiederverwendungssperre erfolgreich.

Screenshots liegen unter `C:\xampp\htdocs\regulierungs-check\output\playwright\`.
