@php
    // 1. Positivity 0–1 → Hue 0° (rot) bis 120° (grün)
    $positivity = $tag->positivity ?? 0.5;
    $hue = (int) round(120 * $positivity);

    // 2. Farbtöne
    $saturation = 90;

    // 3. Hintergrund hell, Text dunkel
    $lightnessBg = 88; // sanfte Farbe
    $lightnessText = 20; // kräftiger Kontrast

    // 4. CSS-Stile
    $bgColor = "hsl($hue, {$saturation}%, {$lightnessBg}%)";
    $textColor = "hsl($hue, {$saturation}%, {$lightnessText}%)";

    $style = "background-color: $bgColor; color: $textColor;";
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
    style="{{ $style }}">
    {{ $tag->name }}
</span>
