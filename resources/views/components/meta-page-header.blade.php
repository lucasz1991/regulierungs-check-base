@once
    @if($robotsMeta)
        <meta name="robots" content="{{ $robotsMeta }}">
    @endif
    @if($metaTitle)
        <title>{{ $metaTitle }} | {{ config('app.name') }}</title>
    @endif
    @if($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if($metaKeywords)
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    @if($canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif
    @if($ogTitle)
        <meta property="og:title" content="{{ $ogTitle }}">
    @endif

    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">

    @if($ogDescription)
        <meta property="og:description" content="{{ $ogDescription }}">
    @else 
        @if($metaDescription)
            <meta property="og:description" content="{{ $metaDescription }}">
        @endif
    @endif
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @else 
        <meta property="og:image" content="{{ asset('site-images/logo/preview-1200x630.jpg') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    @if($canonicalUrl)
        <meta property="og:url" content="{{ $canonicalUrl }}">
    @endif
    <meta property="og:type" content="website">
    @if ($customCss)
        <style>{{ $customCss }}</style>
    @endif
    @if ($customJs)
        <script>{{ $customJs }}</script>
    @endif
@endonce
