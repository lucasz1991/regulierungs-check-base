@php
    if ($greeting === 'default') {
        $greeting = $level === 'error' ? __('Whoops!') : __('Hello!');
    }
    if ($salutation === 'Mit freundlichen Grüßen, dein Regulierungs-CHECK Team') {
        $salutation = 'Viele Grüße';
    }
    $signoffLines = preg_split('/<br\s*\/?>|\R/iu', $salutation ?: 'Viele Grüße');
@endphp
{{-- Laravel resolves this view for the HTML and plain-text parts of the same notification. --}}
@include('mail::notification')
