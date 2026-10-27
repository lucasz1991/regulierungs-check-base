# Minimaler Newsstreifen - lokal, 2026-10-25

Auf Nutzerwunsch ist der Startseitenbereich `#wissen` wieder ein kompakter, automatisch durchlaufender Newsstreifen. Wiedergabe-Button, Range-Scrollbar, Seitenzaehler und Slider-Pfeile wurden aus dem Blade entfernt. Einzeilige Titel, kleine Metadaten und einfache Linkpfeile ersetzen die groessere Zweizeilenansicht. `Alle News` und alle echten Artikellinks bleiben erhalten.

Geaendert: `resources/views/livewire/banner/homepage-news-teaser-banner.blade.php`, News-Regeln in `public/homepage-redesign/components-refinement.css`, vorhandener `tests/Unit/HomepageNewsTickerSpacingTest.php`.

Der bestehende JS-Endloslauf mit 36 px/s und Livewire-Lifecycle wurde unveraendert beibehalten. Native Scrollbars bleiben auch ohne JS ausgeblendet; Touch- und Tastaturscrollen sind weiterhin moeglich. Hover/Fokus, reduzierte Bewegung und Tab-Sichtbarkeit werden weiterhin beachtet.

Pruefungen:

- PHPUnit-News-/Bannerdaten-Fokus: 8 Tests / 59 Assertions erfolgreich.
- Pint-Test fuer geaenderte Testdatei und `git diff --check` erfolgreich.
- HTTP 200 auf 8186, ausgeliefertes HTML ohne News-Toggle/Range/Pfeilcontrols.
- Direkte Pruefung im vorhandenen Nutzer-IAB: 1130 px breit, Newsbereich 154 px, Track 90 px, einzeiliger Titel 19.59 px, Controls 0, Scrollbar none, kein horizontaler Seitenueberlauf.
- Automatik aktiv; scrollLeft 1457 -> 1885 ueber zwei Messungen.
- Screenshot: `artifacts/screenshots/news-minimal-2026-10-25/news-minimal-desktop.png`.

Nur Blade und direkt ausgelieferte CSS wurden geaendert; kein neuer JS-Build erforderlich. Laufende Server auf 6666 und 8186 bestaetigt. Kein Deployment, keine Geschaeftsdaten-/Konfigurationsaenderung.

Unabhaengige Mobilpruefung:

- 320/390 px: kein Seitenueberlauf, Hoehe 154 px, Controls 0, native Scrollbars verborgen, Titel einzeilig.
- 29 px Bewegung / 800 ms; nahtloser Loop-Uebergang 1699 -> 1703 -> 3 -> 7 bestaetigt.
- Fokus stoppt den Lauf (816 -> 816 / 500 ms); Reduced Motion deaktiviert ihn (43 -> 43 / 800 ms).
- Messwerte: `artifacts/reports/news-minimal-audit-2026-10-25.json`; Mobilbilder unter `output/playwright/news-minimal-{390,320}.png`. Separate Pruefsitzung geschlossen.
