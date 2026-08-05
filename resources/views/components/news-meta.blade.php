@props(['meta'])

<title>{{ $meta['title'] }} | {{ config('app.name', 'Regulierungs-Check') }}</title>
<meta name="description" content="{{ $meta['description'] }}" data-news-meta="description">
<meta name="robots" content="{{ $meta['robots'] }}" data-news-meta="robots">
<link rel="canonical" href="{{ $meta['canonical'] }}" data-news-meta="canonical">

<meta property="og:locale" content="de_DE">
<meta property="og:site_name" content="{{ config('app.name', 'Regulierungs-Check') }}">
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $meta['title'] }}">
<meta property="og:description" content="{{ $meta['description'] }}">
<meta property="og:url" content="{{ $meta['canonical'] }}">
<meta property="og:image" content="{{ $meta['image'] }}">
<meta property="og:image:alt" content="{{ $meta['imageAlt'] }}">

@if($meta['publishedTime'])
    <meta property="article:published_time" content="{{ $meta['publishedTime'] }}">
@endif
@if($meta['modifiedTime'])
    <meta property="article:modified_time" content="{{ $meta['modifiedTime'] }}">
@endif
@if($meta['section'])
    <meta property="article:section" content="{{ $meta['section'] }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $meta['title'] }}">
<meta name="twitter:description" content="{{ $meta['description'] }}">
<meta name="twitter:url" content="{{ $meta['canonical'] }}">
<meta name="twitter:image" content="{{ $meta['image'] }}">
<meta name="twitter:image:alt" content="{{ $meta['imageAlt'] }}">
