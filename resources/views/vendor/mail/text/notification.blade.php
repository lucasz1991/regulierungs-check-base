@if (filled($greeting))
{{ $greeting }}

@endif
@foreach ($introLines as $line)
{{ $line instanceof \Illuminate\Contracts\Support\Htmlable ? strip_tags($line->toHtml()) : $line }}

@endforeach
@if (filled($actionText) && filled($actionUrl))
{{ $actionText }}
{{ $actionUrl }}

@endif
@foreach ($outroLines as $line)
{{ $line instanceof \Illuminate\Contracts\Support\Htmlable ? strip_tags($line->toHtml()) : $line }}

@endforeach
Du hast Fragen? Schreib uns einfach.
info@regulierungs-check.de

@foreach ($signoffLines as $signoffLine)
{{ $signoffLine }}
@endforeach
Regulierungs-Check RCQ GmbH
Oldesloer Str. 63 · 22457 Hamburg
Geschäftsführer Birkan Özülkü
Amtsgericht Hamburg · HRB 193808 · USt-ID DE458707066
https://www.regulierungs-check.de/
Impressum: https://www.regulierungs-check.de/imprint
Datenschutz: https://www.regulierungs-check.de/privacypolicy
