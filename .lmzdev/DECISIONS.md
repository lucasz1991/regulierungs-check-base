# Decisions

Record durable decisions with date, context, decision, and consequences.

## 2026-08-13 | Promotion laeuft ausschliesslich in direkten Webrequests

- Gewinn-QR, Bindung, Teilnehmerbestaetigung, Ausgabe und Korrektur bleiben transaktionale Webaktionen; es gibt keine Promotion-Commands, Jobs oder Scheduler-Eintraege.
- Die HMAC-Auditkette wird synchron in derselben Fachtransaktion geschrieben und verifiziert. Externe Auditmails, Ankerstatus und getrennte IP-/User-Agent-Zugriffskontexte entfallen.
- Admin-Einstellungen enthalten nur Aktivierung, Einloese-Basis-URL und QR-Gueltigkeit; Auditschluessel und Konfigurations-MAC bleiben intern erzeugt und verschluesselt.
- Eine Base-Upgrade-Migration prueft den alten MAC fail-closed, signiert den reduzierten Vertrag neu und entfernt erst danach die entfallenen Wartungsfelder/-tabellen.

## 2026-08-13 | Wartungspflichten gelten unabhaengig vom Aktivschalter

- `enabled` steuert den oeffentlichen Promotionbetrieb, aber niemals die faellige Loeschung separater Zugriffskontexte oder die externe Verankerung vorhandener Auditereignisse.
- Auditanker verwenden auch deaktiviert nur den MAC-geschuetzten DB-Schluessel und die MAC-geschuetzte Kontrolladresse; ungueltige Settings brechen fail-closed ohne Mail ab.
- Vollstaendig verankerte Auditkoepfe werden weiter verifiziert, damit nachtraegliche Manipulation nicht durch einen Pending-Filter verborgen bleibt.

## 2026-08-13 | Escape public CMS titles and revalidate legacy SVG

- Blog and WebPage titles are plain text and therefore always use escaped Blade output.
- WebPage icons remain the only intentional raw markup in the header, but are accepted only after `SafeIconMarkup::svg()` validation on every render so historical shared-database values fail closed.

## 2026-08-13 | Blog Rich-HTML bleibt formatiert, aber passiv

- Die Toast-UI-Formatierung bleibt ueber eine enge DOM-Allowlist erhalten: Ueberschriften, Textauszeichnung, Zitate, Listen samt deaktivierten Aufgaben-Checkboxen, Tabellen und Links.
- Bilder, Styles, beliebige Klassen, Formulare sowie aktive/einbettende Elemente werden entfernt; Links erlauben nur interne Ziele sowie HTTP(S), Mail und Telefon, externe HTTP(S)-Links erhalten `noopener noreferrer`.
- Admin und Base verwenden byte-identischen Sanitizer-Code; Base prueft Altbestand unmittelbar vor jeder Raw-Ausgabe erneut.

## 2026-08-16 | Glücksrad V2 verwendet ein stateless Ticket und einen gesperrten Kampagnenzustand

- Der Poster-QR verweist dauerhaft auf `/gluecksrad`; erst das verifizierte Teilnehmerkonto erhält je Kampagne ein persistiertes Ticket. Der persönliche QR enthält nur Version, öffentliche Teilnahme-ID und HMAC, wird als SVG gestreamt und weder als Datei noch als Roh-Token gespeichert.
- `promotion_campaign_states` ist ab dem ersten V2-Laufzeitobjekt verpflichtend. Zeilensperren, eindeutige Constraints und Cross-Invarianten halten Kampagnenzustand, aktiven Aufruf, aktives Ticket und Participation-Verknüpfung konsistent.
- Jede V2-Fachmutation schreibt synchron in derselben Transaktion einen HMAC-verketteten Snapshot. Die Verifikation indexiert letzte Snapshots linear; nachträgliche Änderungen an Participation, Ticket, Aufruf, Ergebnis, Mailstatus oder Kampagnenzustand verhindern den nächsten legitimen Übergang.
- Nur neue Tickets und Scans benötigen eine aktuell öffentliche Kampagne. Bereits aktive Aufrufe bleiben nach Deaktivierung oder Kampagnenende freigebbar und abschließbar.
- Der Betrieb benötigt keine Promotion-Commands, Jobs, Scheduler, Queue-Worker oder ENV-Konfiguration. Einstellungen und Social-Provider werden verschlüsselt und MAC-geschützt im Admin gepflegt.
