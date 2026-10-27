# Homepage content refinement — 24.10.2026

Scope: presentation in `top-insurances-by-type-banner.blade.php` and the new `public/homepage-redesign/components-refinement.css`. No query, privacy, route, controller or source-data changes.

The existing news ribbon is 220 px high instead of 240 px at 1237 px. Titles use two readable lines at 15 px; the original continuous autoplay remains. Existing insurer and review tray/core elements receive a 6 px frame, subtle depth, restrained hover lift and clearer link actions. Unknown durations retain the dash with quiet styling and an accessible unavailable label. The existing logo notice now has a visible dark-mode icon and a 44 px target.

## Verification

- Own browser session `rc-cards-refinement`, local isolated preview `http://127.0.0.1:8186/`.
- 1237×1018, 390×844 and 320×844: document width equals viewport; no clipped review paragraphs. All 15 complete reviews remain in the rail.
- News autoplay advanced 29 px in 800 ms; pause produced zero movement; next arrow worked.
- Local insurance search, empty state and reset worked. Livewire category filter selected the real category and retained the refined card padding after morph.
- Insurer/review arrows moved the tracks. Native range keyboard End reaches the end (1000 and 999/1000; fractional pixel rounding).
- No JavaScript: 6 news, 11 insurers and 15 reviews remain visible with native overflow scrolling; no horizontal document overflow at 320 px.
- Reduced motion: no transition and no card transform. Normal pointer hover: restrained 3 px lift.
- Dark mode: visible logo notice, keyboard Enter opens it (`aria-expanded=true`), Escape closes it.
- No browser page errors during interaction pass.
- `php vendor/bin/phpunit tests/Unit/HomepageRedesignBannerDataTest.php tests/Unit/HomepageNewsTickerSpacingTest.php`: 8 tests / 57 assertions passed.
- Blade syntax and scoped diff whitespace check passed.

Screenshots: `output/playwright/cards-refined-{news,insurance,reviews}-{1237,390,320}.png`, plus `cards-refined-insurance-dark.png`.

This is local browser and rendering verification, not deployment acceptance.
