# Startseite visuell verfeinert

## Auftrag und Richtung

Die lokale Startseite auf Port 8186 wurde nach Nutzerkommentar direkt im
Base-Repository verfeinert. Die sichtbare Hinweisleiste wurde aus dem Layout
entfernt. Die bestehende isolierte Laufzeitkonfiguration bleibt aktiv.

Der ausdrücklich gewünschte `high-end-visual-design`-Skill wurde angewendet:
ruhige räumliche Hierarchie, präzisere Typografie, zurückhaltende Tray/Core-Flächen,
verschachtelte Aktionspfeile und gezielte Transform-/Opacity-Bewegungen.
Die vorher ausdrücklich gewünschte bündige Navigation sowie Montserrat,
Originalillustrationen, Originalicons und Petrol/Gold bleiben die Produktrichtung.

## Änderungen

- Header: vollständige Desktopnavigation ab 1180 px, 86 px Leistenhöhe,
  präzisere Werkzeugflächen und Dropdowns. Fokus wird beim Wechsel über den
  Breakpoint sinnvoll auf Burger bzw. Marke gesetzt. Mobile Menüs unterstützen
  Tastatur, Fokusführung und Reduced Motion.
- Hero: Überschrift bei 980–1440 px zweizeilig, kein isoliertes „mal“;
  stärkere Text-/Bildbalance, ruhigere Kennzahlfläche und präzisere Hauptaktion.
- News: kompakteres Banner mit lesbaren zweizeiligen Titeln; kontinuierlicher
  Lauf, Pause und alle Steuerungen bleiben erhalten.
- Versicherer und Bewertungen: vorhandene Tray/Core-Elemente verfeinert,
  dunkler Logo-Hinweis wieder kontrastreich, ruhige unbekannte Dauer,
  klare Aktionen und zurückhaltender Hover. Bewertungstexte bleiben vollständig.
- Ablauf: großzügige drei Karten auf Desktop; auf Mobilgeräten einspaltige,
  kompakte Schritte mit sämtlichen Erklärungen. Das ältere innere 50-px-Grid
  wird explizit zurückgesetzt.
- Plattform, Newsletter und Footer: abgestufte Abstände, besser ausgerichtete
  Illustration und Textspalten, lesbarere Links und stärkere Überschriften.
  Der Newsletter-Markenname bleibt zusammenhängend.
- Bewegung: einmalige, dezente Eintritte via IntersectionObserver/Web Animations.
  Ohne JavaScript bleiben Inhalte sichtbar. Reduced Motion deaktiviert die
  Eintritte und Interaktionsbewegungen.
- Nativer Bewertungsdialog: Escape schließt jetzt; Fokus kehrt zum Auslöser zurück.

## Dateien

- `resources/views/layouts/app.blade.php`
- `public/homepage-redesign/polish.css`
- `public/homepage-redesign/components-refinement.css`
- `public/homepage-redesign/navigation-integration.css`
- `public/homepage-redesign/integration.css`
- `resources/js/homepage-navigation.js`
- `resources/js/homepage-motion.js`
- `resources/js/homepage-redesign.js`
- `resources/views/components/homepage/header.blade.php`
- `resources/views/components/homepage/newsletter.blade.php`
- `resources/views/livewire/banner/top-insurances-by-type-banner.blade.php`
- `resources/views/livewire/customer/rating/rating-form.blade.php`

## Verifikation

- Produktionsbuild erfolgreich: `app-RwVPZZje.js`.
- Fokussierter PHPUnit-Lauf: 13 Tests, 110 Assertions erfolgreich
  (`HomepageRedesignBannerDataTest`, `HomepageNewsTickerSpacingTest`,
  `HomepageNavigationTest`, `RatingFormBladeCompilationTest`).
- Header zusätzlich: 4 Tests/51 Assertions; Inhalte: 8 Tests/57 Assertions.
  Diese Prüfungen überschneiden sich mit dem Gesamt-Fokuslauf und werden nicht addiert.
- Kein horizontaler Seitenüberlauf bei 320, 390, 768, 980, 1024, 1237 und 1440 px.
- H1 bei 980, 1024, 1237 und 1440 px genau zweizeilig, ohne Glyphenüberlauf.
- Mobile Prozessbeschreibungen vollständig sichtbar, kein schmaler Restgrid.
- Native Suche/Filter, Konto, Menü, Tastatur und Breakpoint-Fokus geprüft.
- News laufen weiter; Pause hält an. Suche, Leerzustand, Reset, serverseitiger
  Kategorienwechsel, Pfeile, Range-Ende und No-JS-Scrollflächen geprüft.
- Escape/Fokusrückgabe des Bewertungsdialogs geprüft; keine Bewertung abgesendet.
- Reduced Motion: Eintritte deaktiviert, News still. Dunkler Fließtextkontrast
  in den geprüften Bereichen 7,92–8,8:1; H1 15,96:1.
- Direkte Sichtprüfung im vorhandenen In-App-Browser auf Port 8186:
  Hinweisleiste verschwunden, vollständiger Header und neuer Hero sichtbar.

Das Browserfenster bleibt auf der lokalen Startseite geöffnet. Der Nachweis liegt
unter `artifacts/screenshots/homepage-polish-2026-10-24/homepage-polish-desktop.png`.
Weitere Detailbilder liegen im selben Ordner und unter `output/playwright/`.
Kein Deployment, keine Änderungen an produktiven oder lokalen Geschäftsdaten.
