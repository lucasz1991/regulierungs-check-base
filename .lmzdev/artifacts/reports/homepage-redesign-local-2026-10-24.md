# Lokale Integration des Startseiten Redesigns

Stand: 24. Oktober 2026. Anwendung: `regulierungs-check-base`.

## Ergebnis

Die öffentliche Laravel-Startseite verwendet das freigegebene Design aus
`C:/Users/L. Zacharias/Documents/Regulierungs-CHECK-Redesign/project-design`.
Hero, bündiger Header, Newsbanner, Versicherervergleich, Bewertungen, Ablauf,
Plattformbereich, Newsletter und Footer sind integriert. Die bestehenden
Laravel-Routen, Livewire-Suche, Account-/Logout-Verträge, Bewertungseinstieg,
Veröffentlichungsfilter, Privatsphäre und CMS-Positionen bleiben angebunden.

Die Startseite lädt separat begrenzte Styles. Auch nach `wire:navigate` gelten
sie nur für `body.rc-home` und den markierten HTML-Root. Die ursprüngliche
Hintergrund-Pseudofläche ist auf der Startseite deaktiviert. Der native
Bewertungsdialog behält seine eigene lesbare Fläche.

Die Slider besitzen eine untere Range-Steuerung und native Scroll-Fallbacks.
Bei Livewire-Morphs werden nur betroffene Controller erneuert. Eine pausierte
Newsleiste und die Position des Bewertungssliders bleiben bei Änderungen am
Versicherervergleich erhalten. Suchfilter richten ihr Ereignis gezielt an
`insurance-track`.

## Laufende lokale Vorschau

- Angeforderter Terminalserver: <http://127.0.0.1:6666/>
- Direkt im normalen Browser nutzbar: <http://127.0.0.1:8186/>

Chrome sperrt Port 6666 standardmäßig als IRC-Port. Für die Browserprüfung
wurde nur in der eigenen QA-Sitzung `--explicitly-allowed-ports=6666` verwendet.
Port 8186 stellt dieselbe Anwendung mit einer passenden lokalen Asset-URL dar.

Erneut starten, jeweils in einem eigenen PowerShell-Terminal:

```powershell
Set-Location 'C:\xampp\htdocs\regulierungs-check\regulierungs-check-base'
& .\.lmzdev\scripts\Start-HomepagePreview.ps1 -Port 6666
```

Für den Browserzugang den gleichen Befehl mit `-Port 8186` ausführen. Stoppen
mit Strg+C. Der Launcher verändert keine `.env`-Datei. Er verwendet einen
separaten, nicht vorhandenen Konfigurationscache-Pfad und prüft vor dem Start
die tatsächlich wirksame SQLite-, Session- und Mailer-Konfiguration.

## Datenbasis

Die konfigurierte lokale MySQL-Datenbank enthält nur die leere Migrationstabelle.
Deshalb läuft die Vorschau mit einer neu angelegten, getrennten SQLite-Datei:
`.lmzdev/artifacts/temp/homepage-redesign-2026-10-24.sqlite`.

Die Vorlage enthält 22 Versicherer, 8 Hauptkategorien, 82 Unterarten,
15 öffentliche Bewertungszusammenfassungen und 6 Newsteaser aus dem belegten
Snapshot vom 19. Oktober 2026. Die Anzeigen zählen und aggregieren tatsächlich
vorhandene Datensätze; fehlende Regulierungsdauern erscheinen als Gedankenstrich.
Die Snapshot-Gesamtsummen werden nicht mit erfundenen Bewertungszeilen simuliert.

Vollständige produktive Fragebögen und Artikel fehlen. Die Fragebogenversionen
sind ausdrücklich leere Vorschaugerüste, Artikel enthalten den belegten Teaser
und einen Hinweis mit Original-Link. Bestehende importierte Benutzerkonten sind
deaktiviert. Die Original-MySQL-Datenbank und die Quell-Fixture wurden nicht
verändert. Die zwei fehlenden lokalen Versichererlogos wurden aus den vorhandenen
Originalassets des Entwurfs ergänzt. Sechs Uploadbilder auf der separaten
Newsübersichtsseite fehlen weiterhin in dieser begrenzten Vorschaudatenbasis.

Sessions liegen in Dateien; Mails verwenden ausschließlich den Array-Mailer.
Keine Bewertung wurde während der Prüfung gespeichert, kein KI-/Chatbot-Aufruf
und keine externe Übermittlung wurden ausgelöst.

Das Aufbauscript `.lmzdev/scripts/build-homepage-preview.py` verweigert ein bereits
vorhandenes Ziel. Die temporäre Datenbank ist Git-ignoriert.

## Verifikation

- `npm run build`: erfolgreich. Bestehende Hinweise zu drei root-relativen
  Font-/Bildpfaden bleiben; die geprüften Homepage-Ressourcen laden fehlerfrei.
- Fokuslauf `HomepageRedesignBannerDataTest`, `HomepageNewsTickerSpacingTest`,
  `HomepageNavigationTest`, `RatingFormBladeCompilationTest`,
  `InsuranceTypeIconRenderSecurityTest`, `SafeIconMarkupTest`, `BlogVisibilityTest`:
  **38 Tests, 167 Assertions erfolgreich**, hart auf SQLite `:memory:` begrenzt.
- PHP-/JavaScript-Syntax und `git diff --check`: erfolgreich.
- HTTP 200 auf beiden Ports; Previewkennzeichnung und neue Startseite vorhanden.
- Browser: keine horizontalen Seitenüberläufe bei 320/390/768/1440 px;
  Navigation zusätzlich bei 360/736/1024/1280 px geprüft.
- Menü, Untermenüs, Theme, Konto, Escape/Fokusrückgabe, echte Livewire-Suche
  samt Suchkategorie und Morph, Home → News → Home erfolgreich.
- Versicherersuche, Leerzustand/Reset, serverseitiger Kategorienwechsel,
  Range-Ende, Neuinitialisierung nach Morph und genau ein Announcer pro Slider
  geprüft. News-Pause und Bewertungsposition bleiben erhalten; keine doppelten IDs.
- Nativen Bewertungsdialog geöffnet, acht Kategorien dargestellt und geschlossen.
  Keine vollständige Bewertungsabgabe geprüft.
- Finale Homepage-Browserläufe: keine JavaScript-Fehler und keine HTTP-Ressourcenfehler.

Weitere bestehende Prüfungen wurden getrennt erfasst: Die zwei HTTP-Fälle in
`HomepageAosTest` besitzen keine Datenbank-Fixture und scheitern bereits an der
fehlenden Tabelle `activity_log` in ihrer leeren In-Memory-Datenbank. Ein Versuch
mit allen Migrationen wurde wegen einer vorhandenen Doctrine-DBAL-Anforderung
bei `promotion_tickets` verworfen; die Testdatei bleibt unverändert. Globales
`php artisan view:cache` scheitert an einer bestehenden unaufgelösten Komponente
`nav-link` außerhalb des geänderten Startseitenpfades. Die tatsächlichen
Startseiten-Views werden erfolgreich per HTTP gerendert.

## Pflege

Styles, Originalassets und Sliderquellen lassen sich reproduzierbar importieren:

```powershell
node scripts/import-homepage-redesign.mjs 'C:\Users\L. Zacharias\Documents\Regulierungs-CHECK-Redesign\project-design'
npm run build
```

`public/homepage-redesign/integration.css` und `navigation-integration.css`
enthalten die gezielten Anpassungen an die native Anwendung. Der Importer
überschreibt diese Dateien nicht. Screenshots liegen unter
`.lmzdev/artifacts/screenshots/homepage-redesign-2026-10-24/`.

Livewire-Lifecycle-Verträge wurden gegen die offizielle
[Livewire 3 Dokumentation](https://livewire.laravel.com/docs/3.x/javascript)
geprüft. Es erfolgte kein Deployment.
