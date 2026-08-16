<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <meta name="referrer" content="no-referrer">
    <title>Glücksrad | Regulierungs-CHECK</title>
    <link rel="icon" type="image/png" href="{{ asset('site-images/logo/logo-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#eef7f5] text-slate-950 antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
